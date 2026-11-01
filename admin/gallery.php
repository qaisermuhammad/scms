<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$photos = array_map('gallery_normalize_bundle', admin_load_gallery_photos());
$hasManualGalleryOrder = false;
foreach ($photos as $photo) {
    if (isset($photo['sort_order']) && (int) $photo['sort_order'] < PHP_INT_MAX) {
        $hasManualGalleryOrder = true;
        break;
    }
}
if (!$hasManualGalleryOrder) {
    usort($photos, function ($a, $b) {
        $aIsHera = strtolower(trim((string) ($a['title'] ?? ''))) === 'hera visit gallery';
        $bIsHera = strtolower(trim((string) ($b['title'] ?? ''))) === 'hera visit gallery';
        return $bIsHera <=> $aIsHera;
    });
}
$edit_item = null;
$edit_index = -1;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reorder_gallery'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $order = $_POST['order'] ?? [];
        $ordered = [];
        foreach ($order as $sortOrder => $index) {
            $idx = (int) $index;
            if (isset($photos[$idx])) {
                $photos[$idx]['sort_order'] = (int) $sortOrder;
                $ordered[] = $photos[$idx];
            }
        }
        if (count($ordered) === count($photos) && count(array_unique(array_map('intval', $order))) === count($photos)) {
            $photos = $ordered;
            admin_save_gallery_photos($photos);
            admin_flash('success', 'Gallery batches reordered successfully.');
        } else {
            admin_flash('error', 'Invalid gallery order; no changes were made.');
        }
    }
    header('Location: gallery.php');
    exit;
}

// Define where uploaded images will save locally
$upload_dir = __DIR__ . '/../assets/images/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle delete a single photo from a bundle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_photo'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $bundle_index = (int) ($_POST['bundle_index'] ?? -1);
        $photo_index = (int) ($_POST['photo_index'] ?? -1);
        if ($bundle_index >= 0 && isset($photos[$bundle_index])) {
            $images = $photos[$bundle_index]['image'] ?? [];
            $thumbs = $photos[$bundle_index]['thumb'] ?? [];
            if ($photo_index >= 0 && isset($images[$photo_index])) {
                unset($images[$photo_index]);
                unset($thumbs[$photo_index]);
                $photos[$bundle_index]['image'] = array_values($images);
                $photos[$bundle_index]['thumb'] = array_values($thumbs);
                $photos[$bundle_index]['updated_at'] = time();
                admin_save_gallery_photos($photos);
                admin_flash('success', 'Photo removed from the gallery batch.');
            }
        }
    }
    header('Location: gallery.php');
    exit;
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_index'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $idx = (int) $_POST['delete_index'];
        if (isset($photos[$idx])) {
            // Delete all physical files inside this bundle from the server assets
            $img_data = $photos[$idx]['image'] ?? '';
            $img_paths = is_array($img_data) ? $img_data : [$img_data];
            
            foreach ($img_paths as $img_path) {
                if (!empty($img_path)) {
                    site_delete_gallery_file($img_path);
                }
            }
            $thumb_data = $photos[$idx]['thumb'] ?? null;
            $thumb_paths = is_array($thumb_data) ? $thumb_data : (empty($thumb_data) ? [] : [$thumb_data]);
            foreach ($thumb_paths as $thumb_path) {
                if (!empty($thumb_path)) {
                    site_delete_gallery_file($thumb_path);
                }
            }
            array_splice($photos, $idx, 1);
            admin_save_gallery_photos($photos);
            admin_flash('success', 'Photo bundle removed from gallery.');
        }
    }
    header('Location: gallery.php');
    exit;
}

// Handle add/edit (Supports multiple picture uploads bundled together)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_photo'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $edit_index_value = trim((string) ($_POST['edit_index'] ?? ''));
        $edit_index = $edit_index_value === '' ? -1 : (int) $edit_index_value;

        // Process uploads: accept large phone photos, then compress + make thumbs
        $uploaded_files = [];
        $uploaded_thumbs = [];
        $upload_errors = [];
        if (isset($_FILES['gallery_files']) && is_array($_FILES['gallery_files']['name'])) {
            $files = $_FILES['gallery_files'];
            $file_count = count($files['name']);
            
            for ($i = 0; $i < $file_count; $i++) {
                $err = $files['error'][$i];
                if ($err === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                if ($err !== UPLOAD_ERR_OK) {
                    if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                        $upload_errors[] = 'A photo was too large for the server upload limit.';
                    } else {
                        $upload_errors[] = 'One photo failed to upload (error ' . $err . ').';
                    }
                    continue;
                }

                $name = $files['name'][$i];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif'];
                
                if (!in_array($ext, $allowed, true)) {
                    $upload_errors[] = $name . ' is not a supported image type.';
                    continue;
                }

                // HEIC is not handled by GD — skip with a clear message
                if (in_array($ext, ['heic', 'heif'], true)) {
                    $upload_errors[] = $name . ' is HEIC; please export as JPG from the phone first.';
                    continue;
                }

                if (!extension_loaded('gd')) {
                    $upload_errors[] = 'Image optimizer (GD) is not enabled on this server.';
                    break;
                }

                $base = 'gallery_' . uniqid('', true) . '_' . time() . '_' . $i;
                $saved = site_save_gallery_upload($files['tmp_name'][$i], $upload_dir, $base);
                if ($saved) {
                    $uploaded_files[] = $saved['image'];
                    $uploaded_thumbs[] = $saved['thumb'];
                } else {
                    $upload_errors[] = 'Could not optimize ' . $name . '.';
                }
            }
        }

        $warn = !empty($upload_errors) ? (' ' . implode(' ', $upload_errors)) : '';

        if ($edit_index >= 0) {
            // EDIT MODE (Modify bundle details or append/replace bundle photos)
            if ($title === '') {
                admin_flash('error', 'Title is required.');
            } else {
                if (isset($photos[$edit_index])) {
                    $old_img_data = $photos[$edit_index]['image'] ?? '';
                    $old_thumb_data = $photos[$edit_index]['thumb'] ?? '';
                    $final_images = is_array($old_img_data) ? $old_img_data : (empty($old_img_data) ? [] : [$old_img_data]);
                    $final_thumbs = is_array($old_thumb_data) ? $old_thumb_data : (empty($old_thumb_data) ? [] : [$old_thumb_data]);

                    if (!empty($uploaded_files)) {
                        if (isset($_POST['keep_existing_photos']) && $_POST['keep_existing_photos'] == '1') {
                            $final_images = array_merge($final_images, $uploaded_files);
                            $final_thumbs = array_merge($final_thumbs, $uploaded_thumbs);
                        } else {
                            foreach ($final_images as $old_path) {
                                site_delete_gallery_file($old_path);
                            }
                            foreach ($final_thumbs as $old_thumb) {
                                site_delete_gallery_file($old_thumb);
                            }
                            $final_images = $uploaded_files;
                            $final_thumbs = $uploaded_thumbs;
                        }
                    }

                    $photos[$edit_index] = [
                        'title' => $title,
                        'description' => $description,
                        'admin_bundle' => $photos[$edit_index]['admin_bundle'] ?? true,
                        'image' => array_values(array_filter($final_images, fn($path) => $path !== '')),
                        'thumb' => array_values(array_filter($final_thumbs, fn($path) => $path !== '')),
                        'created_at' => (int) ($photos[$edit_index]['created_at'] ?? time()),
                        'updated_at' => time(),
                        'sort_order' => (int) ($photos[$edit_index]['sort_order'] ?? $edit_index),
                    ];
                    admin_flash(empty($uploaded_files) && $warn !== '' ? 'error' : 'success', 'Gallery bundle updated successfully.' . $warn);
                    admin_save_gallery_photos($photos);
                }
            }
        } else {
            // Every new upload is an independent batch. Existing bundles change only through edit mode.
            if (empty($uploaded_files)) {
                admin_flash('error', $warn !== '' ? trim($warn) : 'Please select at least one image file to upload.');
            } else {
                foreach ($photos as $existingIndex => $existingPhoto) {
                    $photos[$existingIndex]['sort_order'] = (int) ($existingPhoto['sort_order'] ?? $existingIndex) + 1;
                }
                array_unshift($photos, [
                    'title' => $title,
                    'description' => $description,
                    'admin_bundle' => true,
                    'image' => $uploaded_files,
                    'thumb' => $uploaded_thumbs,
                    'created_at' => time(),
                    'updated_at' => time(),
                    'sort_order' => 0,
                ]);
                admin_flash('success', 'New batch added with ' . count($uploaded_files) . ' photo(s).' . $warn);
                admin_save_gallery_photos($photos);
            }
        }
    }
    header('Location: gallery.php');
    exit;
}

// Load edit item setup
if (isset($_GET['edit'])) {
    $edit_index = (int) $_GET['edit'];
    if (isset($photos[$edit_index])) {
        $edit_item = $photos[$edit_index];
    }
}

admin_render_header('Gallery');
admin_render_flash();
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <h5><?php echo $edit_item ? 'Edit Photo Bundle' : 'Upload Bundle (New Album)'; ?></h5>
            <form method="POST" action="gallery.php" enctype="multipart/form-data" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <input type="hidden" name="edit_index" value="<?php echo $edit_index >= 0 ? $edit_index : ''; ?>">
                
                <div class="mb-3">
                    <label class="admin-form-label">Bundle Title *</label>
                    <input type="text" name="title" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['title'] ?? ''); ?>" required placeholder="e.g. Meeting Yesterday / Sports Day">
                </div>
                
                <div class="mb-3">
                    <label class="admin-form-label">Select Image File(s) <?php echo $edit_item ? '(Optional)' : '*'; ?></label>
                    <input type="file" name="gallery_files[]" id="galleryFiles" class="admin-form-control" <?php echo $edit_item ? '' : 'required'; ?> multiple accept="image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif">
                    <div id="galleryFileStatus" class="small text-muted mt-2" aria-live="polite">No files selected.</div>
                    <div class="progress mt-2" id="galleryUploadProgress" style="height:8px;display:none;">
                        <div class="progress-bar bg-success" id="galleryUploadProgressBar" role="progressbar" style="width:0%"></div>
                    </div>
                    <div id="galleryFileList" class="small mt-2" style="max-height:120px;overflow:auto;"></div>
                    <small class="text-muted">Select one or several images. You can use Ctrl/Cmd or Shift in the file picker.</small>
                </div>

                <?php if ($edit_item): ?>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="keep_existing_photos" class="form-check-input" id="keepPhotos" value="1" checked>
                        <label class="form-check-label" for="keepPhotos" style="font-size:0.85rem;">Append new photos (Uncheck to replace all photos)</label>
                    </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <label class="admin-form-label">Description / Event Caption</label>
                    <textarea name="description" class="admin-form-control" placeholder="Optional description..."><?php echo htmlspecialchars($edit_item['description'] ?? ''); ?></textarea>
                </div>

                <?php if ($edit_item): 
                    $bundle_images = is_array($edit_item['image'] ?? null) ? $edit_item['image'] : (empty($edit_item['image'] ?? '') ? [] : [$edit_item['image']]);
                    if (!empty($bundle_images)):
                ?>
                    <div class="mb-3">
                        <label class="admin-form-label">Photos in this batch</label>
                        <div class="row g-2">
                            <?php foreach ($bundle_images as $photo_index => $img_path): ?>
                                <div class="col-4 position-relative">
                                    <img src="<?php echo htmlspecialchars(str_replace('./', '../', $img_path)); ?>" alt="" style="width:100%;height:80px;object-fit:cover;border-radius:8px;">
                                    <form method="POST" action="gallery.php" onsubmit="return confirm('Remove this photo from this batch?');" style="position:absolute;top:4px;right:4px;">
                                        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                                        <input type="hidden" name="bundle_index" value="<?php echo htmlspecialchars((string) $edit_index); ?>">
                                        <input type="hidden" name="photo_index" value="<?php echo htmlspecialchars((string) $photo_index); ?>">
                                        <input type="hidden" name="remove_photo" value="1">
                                        <button type="submit" class="btn btn-sm btn-danger" style="padding:2px 6px;line-height:1;">×</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endif; ?>
                
                <button type="submit" name="save_photo" class="admin-btn-sm admin-btn-primary">
                    <i class="fas <?php echo $edit_item ? 'fa-save' : 'fa-upload'; ?> me-1"></i><?php echo $edit_item ? 'Update Bundle' : 'Upload to Top'; ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="gallery.php" class="admin-btn-sm admin-btn-danger ms-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="margin:0;">Gallery Preview (Newest Bundles Listed First)</h5>
                <span class="admin-badge admin-badge-success"><?php echo count($photos); ?> Bundles Total</span>
            </div>
            <?php if (empty($photos)): ?>
                <p class="text-muted">No photo bundles in gallery yet.</p>
            <?php else: ?>
                <div class="row g-3" id="galleryBundleList">
                    <?php foreach ($photos as $i => $item): 
                        $img_data = $item['image'] ?? '';
                        $thumb_data = $item['thumb'] ?? '';
                        $images = is_array($img_data) ? $img_data : (empty($img_data) ? [] : [$img_data]);
                        $thumbs = is_array($thumb_data) ? $thumb_data : (empty($thumb_data) ? [] : [$thumb_data]);

                        $cover_image = !empty($thumbs[0]) ? $thumbs[0] : (!empty($images) ? $images[0] : '../assets/images/placeholder.jpg');

                        if (is_string($cover_image) && str_starts_with($cover_image, './')) {
                            $cover_image = '../' . ltrim(substr($cover_image, 2), '/');
                        } elseif (is_string($cover_image) && str_starts_with($cover_image, 'assets/')) {
                            $cover_image = '../' . $cover_image;
                        }

                        if (is_string($cover_image) && str_starts_with($cover_image, './assets/')) {
                            $cover_image = '../' . ltrim(substr($cover_image, 2), '/');
                        }

                        if (is_string($cover_image) && !preg_match('#^(https?:)?/#i', $cover_image) && !str_starts_with($cover_image, '../')) {
                            $cover_image = '../' . ltrim($cover_image, '/');
                        }

                        if (is_string($cover_image) && $cover_image !== '../assets/images/placeholder.jpg' && !str_contains($cover_image, '_thumb.')) {
                            $auto_thumb = site_gallery_thumb_path_for($cover_image);
                            if ($auto_thumb) {
                                $auto_thumb = str_replace('./', '../', $auto_thumb);
                                $cover_image = $auto_thumb;
                            }
                        }
                    ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="card gallery-bundle-card" data-index="<?php echo $i; ?>" draggable="true" style="border-radius:12px;overflow:hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); position:relative; cursor:move;">
                                <img src="<?php echo htmlspecialchars($cover_image); ?>" alt="" style="width:100%;height:140px;object-fit:cover;display:block;" onerror="this.src='../assets/images/placeholder.jpg'">
                                <span class="badge bg-dark" style="position:absolute; top:10px; right:10px; font-size:0.75rem; border-radius:4px; padding: 4px 8px; z-index: 10; color:#fff; background-color:#212529;">
                                    <i class="fas fa-images"></i> <?php echo count($images); ?> Photos
                                </span>
                                <div class="p-2">
                                    <h6 style="font-size:0.85rem;margin:0;" class="text-truncate"><?php echo htmlspecialchars($item['title'] ?? ''); ?></h6>
                                    <p style="font-size:0.75rem;margin:2px 0 8px 0;" class="text-muted text-truncate"><?php echo htmlspecialchars($item['description'] ?? 'No caption'); ?></p>
                                    <div class="d-flex gap-1">
                                        <a href="gallery.php?edit=<?php echo $i; ?>" class="admin-btn-sm admin-btn-primary" style="font-size:0.7rem;padding:0.25rem 0.5rem;"><i class="fas fa-edit"></i></a>
                                        <form method="POST" action="gallery.php" onsubmit="return confirm('Remove this entire photo bundle?');" style="display:inline;">
                                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                                            <input type="hidden" name="delete_index" value="<?php echo $i; ?>">
                                            <button type="submit" class="admin-btn-sm admin-btn-danger" style="font-size:0.7rem;padding:0.25rem 0.5rem;"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    (function () {
        const fileInput = document.getElementById('galleryFiles');
        const status = document.getElementById('galleryFileStatus');
        const fileList = document.getElementById('galleryFileList');
        const form = fileInput ? fileInput.form : null;
        const progress = document.getElementById('galleryUploadProgress');
        const progressBar = document.getElementById('galleryUploadProgressBar');
        let selectedFiles = [];

        if (!fileInput || !status || !fileList || !form) return;

        fileInput.addEventListener('change', function () {
            const incomingFiles = Array.from(fileInput.files || []);
            const existingKeys = new Set(selectedFiles.map((file) => file.name + ':' + file.size + ':' + file.lastModified));
            incomingFiles.forEach((file) => {
                const key = file.name + ':' + file.size + ':' + file.lastModified;
                if (!existingKeys.has(key)) {
                    selectedFiles.push(file);
                    existingKeys.add(key);
                }
            });

            const transfer = new DataTransfer();
            selectedFiles.forEach((file) => transfer.items.add(file));
            fileInput.files = transfer.files;

            const files = selectedFiles;
            const totalBytes = files.reduce((total, file) => total + file.size, 0);
            status.textContent = files.length
                ? files.length + ' file(s) selected - ' + (totalBytes / 1048576).toFixed(2) + ' MB total'
                : 'No files selected.';
            fileList.innerHTML = files.map((file) => {
                const size = (file.size / 1048576).toFixed(2);
                return '<div class="d-flex justify-content-between border-bottom py-1"><span class="text-truncate me-2">' + file.name.replace(/[&<>"']/g, '') + '</span><span class="text-muted flex-shrink-0">' + size + ' MB</span></div>';
            }).join('');
        });

        form.addEventListener('submit', function () {
            if (!fileInput.files.length || !progress || !progressBar) return;
            progress.style.display = 'block';
            progressBar.style.width = '35%';
            status.textContent += ' - Uploading...';
        });
    })();

    (function () {
        const cards = document.querySelectorAll('.gallery-bundle-card');
        let draggedIndex = null;

        const submitReorder = () => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'gallery.php';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = 'csrf';
            csrf.value = '<?php echo htmlspecialchars(admin_csrf_token()); ?>';
            form.appendChild(csrf);

            const reorder = document.createElement('input');
            reorder.type = 'hidden';
            reorder.name = 'reorder_gallery';
            reorder.value = '1';
            form.appendChild(reorder);

            Array.from(document.querySelectorAll('.gallery-bundle-card')).forEach((card) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order[]';
                input.value = card.dataset.index;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        };

        cards.forEach((card) => {
            card.addEventListener('dragstart', function (event) {
                draggedIndex = Number(this.dataset.index);
                this.style.opacity = '0.5';
                if (event.dataTransfer) event.dataTransfer.effectAllowed = 'move';
            });

            card.addEventListener('dragend', function () {
                this.style.opacity = '1';
                draggedIndex = null;
            });

            card.addEventListener('dragover', function (event) {
                event.preventDefault();
                this.style.border = '2px dashed #1a5f2a';
            });

            card.addEventListener('dragleave', function () {
                this.style.border = '';
            });

            card.addEventListener('drop', function (event) {
                event.preventDefault();
                this.style.border = '';

                const targetIndex = Number(this.dataset.index);
                if (draggedIndex === null || draggedIndex === targetIndex) return;

                const list = document.getElementById('galleryBundleList');
                const draggedCard = list.querySelector('.gallery-bundle-card[data-index="' + draggedIndex + '"]');
                const targetCard = list.querySelector('.gallery-bundle-card[data-index="' + targetIndex + '"]');

                if (!draggedCard || !targetCard) return;

                const draggedColumn = draggedCard.parentElement;
                const targetColumn = targetCard.parentElement;
                if (!draggedColumn || !targetColumn || draggedColumn === targetColumn) return;

                const cardsList = Array.from(list.querySelectorAll('.gallery-bundle-card'));
                const draggedPosition = cardsList.indexOf(draggedCard);
                const targetPosition = cardsList.indexOf(targetCard);
                if (draggedPosition < targetPosition) {
                    targetColumn.after(draggedColumn);
                } else {
                    targetColumn.before(draggedColumn);
                }

                submitReorder();
            });
        });
    })();
</script>

<?php admin_render_footer(); ?>
