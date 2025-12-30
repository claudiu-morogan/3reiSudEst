# Troubleshooting Guide

## CSS/JS Files Not Loading (NS_ERROR_CONNECTION_REFUSED)

### Problem
Browser console shows:
```
NS_ERROR_CONNECTION_REFUSED for /public/css/main.css
NS_ERROR_CONNECTION_REFUSED for /public/js/app.js
```

### Cause
The `config.php` file was updated to support both Docker and manual installations by checking environment variables first.

### Solution

**If using Docker:**

1. Restart the container to apply config changes:
```bash
docker-compose restart web
```

2. Clear browser cache (Ctrl+Shift+R or Cmd+Shift+R)

3. Verify assets load at:
   - http://localhost:8080/public/css/main.css
   - http://localhost:8080/public/js/app.js

**If using manual setup:**

1. Ensure you have a `.env` file:
```bash
cp .env.example .env
```

2. Edit `.env` and set `BASE_PATH` correctly:
```env
# For root installation
BASE_PATH=

# For subfolder installation (e.g., localhost/3reiSudEst)
BASE_PATH=/3reiSudEst
```

3. Restart web server

### How It Works Now

The updated `config.php` checks environment variables first (Docker), then falls back to `.env` file (manual setup).

**Docker mode**: Uses environment variables from `docker-compose.yml`
**Manual mode**: Uses values from `.env` file

The `asset()` helper function correctly builds URLs based on `BASE_PATH`:
- Empty `BASE_PATH`: `/public/css/main.css`
- With `BASE_PATH=/3reiSudEst`: `/3reiSudEst/public/css/main.css`

### Verify It's Working

Check the page source (Ctrl+U) and look for:
```html
<link rel="stylesheet" href="http://localhost:8080/public/css/variables.css">
```

The URL should match your `APP_URL` setting.

## Other Common Issues

### Database Connection Failed

**Docker:**
```bash
# Wait for MySQL to initialize (first run takes 10-15 seconds)
docker-compose logs db | grep "ready for connections"

# If still failing, restart
docker-compose restart db
```

**Manual:**
```bash
# Check MySQL is running
mysql -u root -p -e "SELECT 1;"

# Verify credentials in .env match your MySQL setup
```

### Admin Login Not Working

**Username:** `admin`
**Password:** `admin123`

#### Quick Fix (Docker):
```bash
# Update password hash in database
docker-compose exec -T db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_admin_password.sql
```

#### Or use phpMyAdmin:
1. Visit http://localhost:8081
2. Login: root / root_password_123
3. Select `3sudest` database → `users` table
4. Edit admin row
5. Set `password_hash` to:
   ```
   $2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu
   ```

**See complete guide:** [RESET_PASSWORD.md](RESET_PASSWORD.md)

### Port Already in Use

**Docker:**
Edit `docker-compose.yml` and change ports:
```yaml
services:
  web:
    ports:
      - "9000:80"  # Change 8080 to 9000
```

### Permission Errors (Uploads/Logs)

**Docker:**
```bash
docker-compose exec web chown -R www-data:www-data public/uploads logs
docker-compose exec web chmod -R 755 public/uploads logs
```

**Manual:**
```bash
sudo chown -R www-data:www-data public/uploads logs
sudo chmod -R 755 public/uploads logs
```

### Page Not Found (404)

**Apache:**
Ensure `mod_rewrite` is enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Check `.htaccess` exists in project root.

**Nginx:**
Add rewrite rules to site config (see README.md).

## Debug Mode

Enable detailed error messages:

Edit `.env` (or set in docker-compose.yml):
```env
APP_ENV=development
```

Then check:
- Browser console for JavaScript errors
- Browser network tab for failed requests
- Server logs: `docker-compose logs -f web`

## Still Having Issues?

1. Check Docker logs: `docker-compose logs -f`
2. Check PHP errors: `tail -f logs/php_errors.log`
3. Check database errors: `tail -f logs/db_errors.log`
4. Verify file permissions
5. Clear browser cache
6. Try incognito/private window

## Quick Reset (Docker)

Nuclear option - start completely fresh:
```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

**WARNING**: This deletes all database data!
