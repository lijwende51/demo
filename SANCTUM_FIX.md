# Laravel Sanctum Installation Fix

## Issue
Error: "Call to undefined method App\Models\User::createToken()"

This happened because Laravel Sanctum wasn't installed in the project.

## What Was Fixed

### 1. Installed Laravel Sanctum
```bash
composer require laravel/sanctum
```

### 2. Published Sanctum Configuration
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Ran Sanctum Migrations
```bash
php artisan migrate
```
This created the `personal_access_tokens` table.

### 4. Added HasApiTokens Trait to User Model
Updated `backend/app/Models/User.php`:
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    // ...
}
```

### 5. Configured Middleware
Updated `backend/bootstrap/app.php` to enable stateful API:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->statefulApi();
})
```

### 6. Cleared All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## What to Do Now

### 1. Restart Laravel Server

**IMPORTANT:** You must restart your Laravel server for changes to take effect.

```bash
# Stop the current server (Ctrl+C in the terminal running it)
# Then restart:
cd backend
php artisan serve
```

### 2. Test Registration

1. Go to `http://localhost:5173/register`
2. Fill in the form:
   - Name: Your Name
   - Email: your@email.com
   - Password: password123
   - Confirm Password: password123
3. Click "Register"

### 3. Expected Result

✅ User should be created successfully
✅ Auto-login should work
✅ You should be redirected to `/products`
✅ Token should be stored in localStorage

## Verify Installation

Check if Sanctum is installed:
```bash
cd backend
composer show laravel/sanctum
```

Check if migration ran:
```bash
php artisan migrate:status
```

You should see `personal_access_tokens` table in the list.

## Test API Directly

You can test the registration endpoint directly:

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

Expected response:
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "created_at": "2026-04-27T...",
    "updated_at": "2026-04-27T..."
  }
}
```

## All Fixed! ✅

Your authentication system is now fully configured with Laravel Sanctum and ready to use!
