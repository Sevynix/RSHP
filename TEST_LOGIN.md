# Login Redirect Test

## Steps to Debug:

### 1. Try to login as admin user

### 2. After login attempt, run this command to see the redirect log:
```powershell
Get-Content "storage\logs\laravel.log" -Tail 20 | Select-String "Login redirect"
```

### 3. Check what session data was set:
```powershell
php artisan tinker --execute="dd(session()->all())"
```

### 4. Check if user has active role in database:
```powershell
php artisan tinker --execute="DB::table('user as u')->leftJoin('role_user as ru', function(\$query) { \$query->on('ru.iduser', '=', 'u.iduser')->where('ru.status', '=', '1'); })->leftJoin('role as r', 'r.idrole', '=', 'ru.idrole')->select('u.iduser', 'u.nama', 'u.email', 'ru.idrole', 'r.nama_role')->where('u.email', 'YOUR_EMAIL_HERE')->get()"
```

## Expected Results:

- Session should have `user_role` = 1 for admin
- Log should show "Login redirect - user_role: 1"
- Redirect should go to `/admin/dashboard`

## Common Issues:

1. **Session not set** - Check if `attemptThreeTierLogin()` is returning true
2. **Role not found** - Check if user has active role (status = '1') in role_user table  
3. **Redirect happening before session set** - Timing issue with session data
