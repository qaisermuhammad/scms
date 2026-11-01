<?php
require_once __DIR__ . '/../includes/admin_auth.php';

session_start();

$error = '';
// Limit failed attempts by the direct client address. Do not trust forwarded headers.
$rateDir = sys_get_temp_dir() . '/scms-login-limits';
if (!is_dir($rateDir)) @mkdir($rateDir, 0700, true);
$rateFile = $rateDir . '/' . hash('sha256', $_SERVER['REMOTE_ADDR'] ?? 'unknown') . '.json';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!is_string($username) || !is_string($password)) { http_response_code(400); exit; }

    $rateHandle = @fopen($rateFile, 'c+');
    if (!$rateHandle || !flock($rateHandle, LOCK_EX)) {
        http_response_code(503);
        exit('Login temporarily unavailable.');
    }
    $attempts = json_decode(stream_get_contents($rateHandle), true);
    $attempts = is_array($attempts) ? array_values(array_filter($attempts, fn($time) => is_int($time) && $time > time() - 900)) : [];
    if (count($attempts) >= 5) {
        $error = 'Too many attempts. Please try again later.';
        http_response_code(429);
    } elseif ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } elseif (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } elseif (admin_login($username, $password)) {
        ftruncate($rateHandle, 0);
        fflush($rateHandle);
        flock($rateHandle, LOCK_UN);
        fclose($rateHandle);
        header('Location: dashboard.php');
        exit;
    } else {
        $attempts[] = time();
        $error = 'Invalid username or password.';
    }
    rewind($rateHandle);
    ftruncate($rateHandle, 0);
    fwrite($rateHandle, json_encode($attempts));
    fflush($rateHandle);
    flock($rateHandle, LOCK_UN);
    fclose($rateHandle);
}

$csrf = admin_csrf_token();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | SCMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/site.css">
    <style>
        body {
            background: linear-gradient(135deg, #f7faf8 0%, #eef4ef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            padding: 2.5rem;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(15,23,42,0.1);
        }
        .login-logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        .login-title {
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            color: var(--secondary-color);
        }
        .login-subtitle {
            color: #888;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }
        .form-control-custom {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26,95,42,0.1);
        }
        .btn-login {
            width: 100%;
            padding: 0.95rem;
            border-radius: 12px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: #fff;
            border: none;
            transition: all 0.25s;
        }
        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center">
            <img src="../assets/images/logo.jpg" alt="SCMS" class="login-logo">
            <div class="login-title">SCMS Admin</div>
            <div class="login-subtitle">Sign in to manage your site</div>
        </div>
        <?php if ($error): ?>
            <div class="alert-error"><i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
            <div class="mb-3">
                <label class="form-label" style="font-weight:600;font-size:0.9rem;color:var(--secondary-color);">Username</label>
                <input type="text" name="username" class="form-control-custom" placeholder="Enter username" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label" style="font-weight:600;font-size:0.9rem;color:var(--secondary-color);">Password</label>
                <input type="password" name="password" class="form-control-custom" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt me-2"></i>Sign In</button>
        </form>
        <div class="text-center mt-3">
            <a href="../index.php" style="color:#888;font-size:0.85rem;text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Back to Website</a>
        </div>
    </div>
</body>
</html>
