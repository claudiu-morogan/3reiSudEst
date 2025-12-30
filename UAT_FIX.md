# Fix UAT Server URL Issue

## Problem
The site on UAT server is showing localhost URLs instead of the actual domain.

## Quick Fix

### Option 1: Let it Auto-Detect (Recommended)

Edit your `.env` file on the UAT server and **comment out** or **remove** the `APP_URL` line:

```env
DB_HOST=localhost
DB_NAME=your_database
DB_USER=your_user
DB_PASS=your_password
DB_CHARSET=utf8mb4

APP_ENV=production
# APP_URL will auto-detect - leave this commented
BASE_PATH=
```

The system will now automatically detect your domain from the server (`$_SERVER['HTTP_HOST']`) and protocol (http/https).

### Option 2: Set Explicit URL

If auto-detection doesn't work, set the URL explicitly in `.env`:

```env
APP_ENV=production
APP_URL=https://uat.yourdomain.com
BASE_PATH=
```

---

## How It Works

The updated `app/config.php` now includes auto-detection:

```php
// Auto-detect APP_URL if not set in .env
if (!empty($env['APP_URL'])) {
    define('APP_URL', rtrim($env['APP_URL'], '/'));
} else {
    // Auto-detect from server variables
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('APP_URL', $protocol . '://' . $host);
}
```

---

## Verification

After updating `.env`:

1. Clear any cache (if applicable)
2. Visit your UAT site: `https://uat.yourdomain.com`
3. Check links in the navigation - they should now use the correct domain
4. Check admin panel links
5. Verify assets (CSS, JS, images) are loading correctly

---

## Template Files

### For UAT Server

Use `.env.uat` template:
```bash
cp .env.uat .env
# Then edit database credentials
```

### For Production Server

Use `.env.example` template:
```bash
cp .env.example .env
# Then edit database credentials and uncomment APP_URL if needed
```

---

## Troubleshooting

**Links still showing wrong domain?**
- Verify `.env` file has no `APP_URL` set, or it's commented out
- Check file permissions: `.env` should be readable by PHP
- Restart PHP-FPM if applicable: `sudo systemctl restart php8.2-fpm`

**Assets (CSS/JS) not loading?**
- Check `BASE_PATH` in `.env` - should be empty if site is in root
- Verify `public/` directory is accessible
- Check `.htaccess` rules are working

**Mixed content warnings (http/https)?**
- Auto-detection should handle this correctly
- If issues persist, explicitly set `APP_URL=https://yourdomain.com`

---

## What Changed

**Before:**
- `APP_URL` was required in `.env`
- Defaulted to empty string if not set
- Caused issues when moving between environments

**After:**
- `APP_URL` is now optional
- Auto-detects from `$_SERVER['HTTP_HOST']` and `$_SERVER['HTTPS']`
- Works seamlessly on any server without configuration

---

**Updated files:**
- `app/config.php` - Added auto-detection logic
- `.env.example` - Made APP_URL optional with comments
- `.env.uat` - New UAT template with best practices
