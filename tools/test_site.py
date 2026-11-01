import os, pathlib, tempfile, shutil, subprocess, time, re, json, socket, base64
import requests
import sqlite3
ROOT=pathlib.Path(__file__).resolve().parents[1]
PHP=os.environ.get('PHP_BINARY',r'C:\xampp\php\php.exe')
checks=[]
def check(label, condition):
    checks.append((label,bool(condition))); print(('PASS ' if condition else 'FAIL ')+label)
def token(response, name='csrf'):
    m=re.search(r'name="'+name+r'" value="([^"]+)',response.text)
    if not m: raise RuntimeError('Token missing: '+response.url)
    return m.group(1)
with tempfile.TemporaryDirectory(prefix='scms-test-') as td:
    tmp=pathlib.Path(td); site=tmp/'site'
    shutil.copytree(ROOT,site,ignore=shutil.ignore_patterns('.git','scms-laravel','scms-laravel-partial','_preview','graphify-out'))
    # Start from the previous schema to exercise the upgrade without losing existing rows.
    copied_db=site/'data'/'applications.sqlite'
    if copied_db.exists(): copied_db.unlink()
    old_db=sqlite3.connect(copied_db)
    old_db.execute('CREATE TABLE IF NOT EXISTS applications (id INTEGER PRIMARY KEY AUTOINCREMENT, reference TEXT NOT NULL UNIQUE, submitted_at TEXT NOT NULL, full_name TEXT NOT NULL, email TEXT NOT NULL, phone TEXT NOT NULL, program_id TEXT NOT NULL, program_title TEXT NOT NULL, education TEXT NOT NULL, notes TEXT NOT NULL)')
    old_db.commit(); old_db.close()
    h=subprocess.check_output([PHP,'-r','echo password_hash($argv[1], PASSWORD_DEFAULT);','test-password-123'],text=True)
    env=os.environ.copy(); env['SCMS_ADMIN_USERNAME']='testadmin'; env['SCMS_ADMIN_PASSWORD_HASH']=h; env['SCMS_ADMIN_CREDENTIAL_FILE']=str(tmp/'missing')
    sock=socket.socket(); sock.bind(('127.0.0.1',0)); port=sock.getsockname()[1]; sock.close()
    proc=subprocess.Popen([PHP,'-S','127.0.0.1:'+str(port),'-t',str(site)],env=env,stdout=subprocess.DEVNULL,stderr=subprocess.DEVNULL)
    try:
        url='http://127.0.0.1:'+str(port)+'/'; session=requests.Session()
        for _ in range(50):
            try: session.get(url,timeout=1); break
            except requests.ConnectionError: time.sleep(.1)
        def get(path): return session.get(url+path,timeout=15)
        def post(page,data,files=None): return session.post(url+'admin/'+page+'.php',data={'csrf':csrf,**data},files=files,timeout=20)
        def data(name): return json.loads((site/'data'/name).read_text(encoding='utf-8'))
        for page in ['index.php','about.php','admissions.php','contact.php','downloads.php','facilities.php','faculty.php','gallery.php','hera-inspection.php','news.php','programs.php','scholarships.php','program.php?id=bsn-generic','404.php']:
            r=get(page); check('public '+page, r.status_code in (200,404) and '</html>' in r.text.lower() and 'Fatal error' not in r.text)
        for page in ['dashboard','applications','messages','news','programs','facilities','faculty','gallery','downloads','scholarships','settings']:
            r=session.get(url+'admin/'+page+'.php',allow_redirects=False); check('protected '+page,r.status_code==302 and 'login.php' in r.headers.get('Location',''))
        r=get('admin/login.php'); csrf=token(r); old=session.cookies.get('PHPSESSID')
        r=post('login',{'username':'testadmin','password':'test-password-123'}); check('login and session rotation', 'Dashboard' in r.text and session.cookies.get('PHPSESSID')!=old)
        r=get('admin/dashboard.php'); csrf=token(get('admin/news.php'))
        for page in ['dashboard','applications','messages','news','programs','facilities','faculty','gallery','downloads','scholarships','settings']:
            r=get('admin/'+page+'.php'); check('admin '+page,r.status_code==200 and '</html>' in r.text.lower() and 'Fatal error' not in r.text)
        original=(site/'data/news.json').read_bytes(); post('news',{'csrf':'bad','save_news':'1','title':'blocked','date':'2026-01-01','summary':'x'}); check('CSRF rejects mutation',(site/'data/news.json').read_bytes()==original)
        post('news',{'save_news':'1','title':'TEST NEWS','date':'2026-01-01','summary':'test'}); item=next((x for x in data('news.json') if x['title']=='TEST NEWS'),None); check('news create',item is not None)
        if item:
            post('news',{'save_news':'1','edit_id':item['id'],'title':'TEST NEWS EDIT','date':'2026-01-01','summary':'test'}); check('news edit',any(x['title']=='TEST NEWS EDIT' for x in data('news.json'))); post('news',{'delete_id':item['id']}); check('news delete',not any(x.get('id')==item['id'] for x in data('news.json')))
        post('programs',{'save_program':'1','id':'test-program','title':'TEST PROGRAM','duration':'1 Year'}); check('program create',any(x['id']=='test-program' for x in data('programs.json'))); check('program public detail','TEST PROGRAM' in get('program.php?id=test-program').text)
        post('programs',{'save_program':'1','edit_id':'test-program','id':'test-program','title':'TEST PROGRAM EDIT','duration':'2 Years'}); check('program edit',any(x['title']=='TEST PROGRAM EDIT' for x in data('programs.json'))); post('programs',{'delete_id':'test-program'}); check('program delete',not any(x['id']=='test-program' for x in data('programs.json')))
        for page,action,file,key,fields in [('facilities','save_facility','facilities.json','title',{'title':'TEST FACILITY','description':'description'}),('faculty','save_faculty','faculty.json','name',{'name':'TEST FACULTY','designation':'teacher'}),('scholarships','save_scholarship','scholarships.json','title',{'title':'TEST SCHOLARSHIP','description':'description'})]:
            post(page,{action:'1',**fields}); rows=data(file); idx=next((i for i,x in enumerate(rows) if x.get(key)==fields[key]),None); check(page+' create',idx is not None)
            if idx is not None:
                post(page,{action:'1',**fields,key:fields[key]+' EDIT','edit_index':str(idx)}); rows=data(file); idx=next((i for i,x in enumerate(rows) if x.get(key)==fields[key]+' EDIT'),None); check(page+' edit',idx is not None)
                if idx is not None: post(page,{'delete_index':str(idx)}); check(page+' delete',not any(x.get(key)==fields[key]+' EDIT' for x in data(file)))
        post('scholarships',{'save_eligibility':'1','eligibility_text':'TEST ELIGIBILITY'}); rows=data('scholarship_eligibility.json'); check('eligibility create','TEST ELIGIBILITY' in rows); post('scholarships',{'delete_eligibility_index':str(rows.index('TEST ELIGIBILITY'))}); check('eligibility delete','TEST ELIGIBILITY' not in data('scholarship_eligibility.json'))
        post('downloads',{'save_group':'1','group_title':'TEST GROUP'}); rows=data('downloads.json'); idx=next((i for i,x in enumerate(rows) if x['title']=='TEST GROUP'),None); check('download group create',idx is not None)
        if idx is not None:
            post('downloads',{'save_group':'1','group_title':'TEST GROUP EDIT','edit_group_index':str(idx)}); rows=data('downloads.json'); idx=next(i for i,x in enumerate(rows) if x['title']=='TEST GROUP EDIT'); check('download group edit',True)
            post('downloads',{'save_item':'1','group_index':str(idx),'item_label':'TEST ITEM','item_href':'https://example.org','item_available':'1'}); rows=data('downloads.json'); idx=next(i for i,x in enumerate(rows) if x['title']=='TEST GROUP EDIT'); ii=next((i for i,x in enumerate(rows[idx]['items']) if x['label']=='TEST ITEM'),None); check('download item create',ii is not None)
            if ii is not None:
                post('downloads',{'save_item':'1','group_index':str(idx),'edit_item_index':str(ii),'item_label':'TEST ITEM EDIT','item_href':'https://example.org'}); rows=data('downloads.json'); idx=next(i for i,x in enumerate(rows) if x['title']=='TEST GROUP EDIT'); ii=next((i for i,x in enumerate(rows[idx]['items']) if x['label']=='TEST ITEM EDIT'),None); check('download item edit',ii is not None)
                if ii is not None: post('downloads',{'delete_item':'1','group_index':str(idx),'item_index':str(ii)}); check('download item delete',not any(x['label']=='TEST ITEM EDIT' for x in data('downloads.json')[idx]['items']))
            post('downloads',{'delete_group_index':str(idx)}); check('download group delete',not any(x['title']=='TEST GROUP EDIT' for x in data('downloads.json')))
        # Reordering requires every existing gallery index exactly once.
        before=(site/'data/gallery_photos.json').read_bytes()
        post('gallery',{'reorder_gallery':'1','order[]':'0'})
        check('gallery rejects incomplete reorder',(site/'data/gallery_photos.json').read_bytes()==before)
        r=get('admin/settings.php'); fields=dict(re.findall(r'<input[^>]+name="([^"]+)"[^>]+value="([^"]*)"',r.text)); fields.update({'name':'TEST COLLEGE','save_settings':'1'}); post('settings',fields); check('settings save',data('settings.json')['name']=='TEST COLLEGE'); check('settings public','TEST COLLEGE' in get('contact.php').text)
        r=get('contact.php'); ct=token(r,'csrf_token'); session.post(url+'contact.php',data={'csrf_token':ct,'name':'Test User','email':'test@example.org','subject':'Test','message':'Test message','country_code':'+92','phone':'3456453236'}); messages=data('contact_messages.json'); check('contact submission',any(x['name']=='Test User' and x['phone']=='+923456453236' for x in messages)); mid=messages[-1]['id']; post('messages',{'delete_id':mid}); check('message delete',not any(x['id']==mid for x in data('contact_messages.json')))
        # A valid, tiny PNG exercises GD conversion. Invalid content must not be accepted.
        png=base64.b64decode('iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAIAAAACDbGyAAAAEUlEQVR4nGP8z4ACmFC5JPMBUJEBCVdmRcsAAAAASUVORK5CYII=')
        post('gallery',{'save_photo':'1','title':'TEST UPLOAD'},files={'gallery_files[]':('test.png',png,'image/png')}); r=get('admin/gallery.php'); check('gallery image upload','TEST UPLOAD' in r.text)
        rows=data('gallery_photos.json') if (site/'data/gallery_photos.json').exists() else []; gi=next((i for i,x in enumerate(rows) if x.get('title')=='TEST UPLOAD'),None)
        if gi is not None:
            post('gallery',{'save_photo':'1','title':'TEST UPLOAD EDIT','edit_index':str(gi)}); rows=data('gallery_photos.json'); gi=next((i for i,x in enumerate(rows) if x.get('title')=='TEST UPLOAD EDIT'),None); check('gallery edit',gi is not None)
            if gi is not None: post('gallery',{'delete_index':str(gi)}); check('gallery delete',not any(x.get('title')=='TEST UPLOAD EDIT' for x in data('gallery_photos.json')))
        post('gallery',{'save_photo':'1','title':'BAD UPLOAD'},files={'gallery_files[]':('bad.php',b'<?php echo 1;', 'image/jpeg')}); check('gallery rejects PHP upload','BAD UPLOAD' not in get('admin/gallery.php').text)
        post('gallery',{'save_photo':'1','title':'DISGUISED UPLOAD'},files={'gallery_files[]':('bad.jpg',b'<?php echo 1;', 'image/jpeg')})
        check('gallery rejects disguised content','DISGUISED UPLOAD' not in get('admin/gallery.php').text)
        r=get('contact.php'); ct=token(r,'csrf_token'); before=(site/'data/contact_messages.json').read_bytes()
        session.post(url+'contact.php',data={'csrf_token':ct,'name':'Test User','email':'invalid','subject':'Test','message':'Test'})
        check('contact rejects invalid email',(site/'data/contact_messages.json').read_bytes()==before)
        response=session.post(url+'contact.php',data={'csrf_token':ct,'name':'Test User','email':'test@example.org','phone':'letters','subject':'Test','message':'Test'})
        check('contact rejects text phone',(site/'data/contact_messages.json').read_bytes()==before and 'valid phone number' in response.text)
        response=session.post(url+'contact.php',data={'csrf_token':ct,'name':'Test User','email':'test@example.org','country_code':'+92','phone':'03456453236','subject':'Test','message':'Test'})
        check('contact rejects leading zero',(site/'data/contact_messages.json').read_bytes()==before and 'valid phone number' in response.text)
        response=session.post(url+'contact.php',data={'csrf_token':ct,'name':'Test User','email':'test@example.org','subject':'Test','message':'word '*101})
        check('contact rejects oversized message',(site/'data/contact_messages.json').read_bytes()==before and 'no more than 100 words' in response.text)
        session.post(url+'contact.php',data={'csrf_token':'invalid','name':'Test User','email':'test@example.org','subject':'Test','message':'Test'})
        check('contact rejects invalid CSRF',(site/'data/contact_messages.json').read_bytes()==before)
        import sqlite3
        r=get('apply.php'); at=token(r,'csrf_token')
        application={'csrf_token':at,'full_name':'Application Test','email':'applicant@gmail.com','phone':'03001234567','program_id':'bsn-generic','education':'FSc pre-medical','notes':'test','father_name':'Guardian Test','date_of_birth':'2005-01-02','address':'Test street','city':'Charsadda','qualification':'Intermediate / Higher Secondary School Certificate','institution':'Test College','board':'BISE Peshawar','graduation_year':'2024','marks_obtained':'900','total_marks':'1100','guardian_phone':'03001234567','domicile':'Charsadda'}
        bad={**application,'csrf_token':'invalid'}
        session.post(url+'apply.php',data=bad)
        dbfile=site/'data/applications.sqlite'
        def application_count():
            if not dbfile.exists(): return 0
            db=sqlite3.connect(dbfile)
            try: return db.execute('select count(*) from applications').fetchone()[0]
            finally: db.close()
        check('application rejects invalid CSRF',application_count()==0)
        r=session.post(url+'apply.php',data={**application,'program_id':'not-a-program'})
        check('application rejects invalid program',application_count()==0 and 'Select an available program' in r.text)
        r=session.post(url+'apply.php',data={**application,'father_name':''})
        check('application rejects missing required detail',application_count()==0 and 'Parent or guardian name is required' in r.text)
        r=session.post(url+'apply.php',data={**application,'date_of_birth':'2099-01-01'})
        check('application rejects future date of birth',application_count()==0 and 'valid date of birth' in r.text)
        r=session.post(url+'apply.php',data={**application,'email':'invalid-email'})
        check('application rejects invalid email',application_count()==0 and 'valid email address' in r.text)
        for address in ['user@', '@example.com', 'user@@example.com', 'user name@example.com', 'user@example..com']:
            r=session.post(url+'apply.php',data={**application,'email':address})
            check('application rejects malformed email '+address,application_count()==0 and 'valid email address' in r.text)
        r=session.post(url+'apply.php',data={**application,'email':'user@example.org'})
        check('application rejects unsupported email domain',application_count()==0 and 'Use a Gmail, Yahoo, Hotmail, or Outlook' in r.text)
        r=session.post(url+'apply.php',data={**application,'marks_obtained':'1200'})
        check('application rejects marks above total',application_count()==0 and 'cannot exceed total marks' in r.text)
        r=session.post(url+'apply.php',data={**application,'total_marks':'0'})
        check('application rejects zero total',application_count()==0 and 'greater than zero' in r.text)
        r=session.post(url+'apply.php',data={**application,'guardian_phone':''})
        check('application requires guardian phone',application_count()==0 and 'Parent or guardian phone is required' in r.text)
        check('application city dropdown present','<select class="form-select" id="city"' in get('apply.php').text)
        r=session.post(url+'apply.php',data={**application,'institution':'12345'})
        check('application rejects numeric institution',application_count()==0 and 'including letters' in r.text)
        r=session.post(url+'apply.php',data={**application,'board':''})
        check('application requires board',application_count()==0 and 'Exam board is required' in r.text)
        r=session.post(url+'apply.php',data=application,allow_redirects=False)
        check('application submit redirects',r.status_code==303 and application_count()==1)
        confirmation=get('apply.php?submitted=1'); check('application confirmation','reference number' in confirmation.text and 'Application Test' in get('admin/applications.php').text); check('application details visible to admin','Guardian Test' in get('admin/applications.php').text and '81.82%' in get('admin/applications.php').text)
        get('apply.php?submitted=1'); check('confirmation refresh does not duplicate',application_count()==1)
        db=sqlite3.connect(dbfile); row=db.execute('select id, reference from applications where full_name=?',('Application Test',)).fetchone(); db.close()
        appid, ref=row
        edit=get('admin/application-edit.php?id='+str(appid)); version=token(edit,'version'); admin_token=token(edit)
        edit_data={k:v for k,v in application.items() if k!='csrf_token'}; edit_data.update({'csrf':admin_token,'version':version,'full_name':'Edited Applicant','marks_obtained':'770'})
        r=session.post(url+'admin/application-edit.php?id='+str(appid),data={**edit_data,'csrf':'bad'}); check('admin edit rejects invalid CSRF','Invalid request' in r.text and 'Application Test' in get('admin/applications.php').text)
        r=session.post(url+'admin/application-edit.php?id='+str(appid),data=edit_data,allow_redirects=False); listing=get('admin/applications.php'); check('admin application edit',r.status_code==303 and 'Edited Applicant' in listing.text and '70.00%' in listing.text and ref in listing.text)
        r=session.post(url+'admin/application-edit.php?id='+str(appid),data=edit_data); check('admin edit rejects stale version','changed while you were editing' in r.text)
        session.post(url+'admin/applications.php',data={'csrf':'bad','delete_id':appid,'reference':ref}); check('admin delete rejects invalid CSRF','Edited Applicant' in get('admin/applications.php').text)
        session.post(url+'admin/applications.php',data={'csrf':admin_token,'delete_id':appid,'reference':ref}); check('admin application delete','Edited Applicant' not in get('admin/applications.php').text)
        create_data={k:v for k,v in application.items() if k!='csrf_token'}; create_data.update({'csrf':admin_token,'full_name':'Admin Created'})
        r=session.post(url+'admin/application-edit.php',data=create_data,allow_redirects=False); check('admin application create',r.status_code==303 and 'Admin Created' in get('admin/applications.php').text)
        get('admin/logout.php'); r=session.get(url+'admin/dashboard.php',allow_redirects=False); check('logout',r.status_code==302)
    finally:
        proc.terminate(); proc.wait()
print('RESULT:',sum(ok for _,ok in checks),'passed,',sum(not ok for _,ok in checks),'failed')
raise SystemExit(any(not ok for _,ok in checks))
