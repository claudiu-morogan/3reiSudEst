# Production Deployment Checklist

## Files to Deploy

### ✅ Required Directories
```
app/              # All backend code
admin/            # Admin panel
public/           # Assets, CSS, JS
views/            # Public templates
sql/              # Database schemas
```

### ✅ Required Files (Root)
```
index.php         # Entry point
.htaccess         # URL rewriting (Apache)
```

### ✅ Create on Server
```
.env              # Environment config (don't copy from local!)
logs/             # Create empty directory
```

---

## ❌ Do NOT Deploy

```
.git/                      # Git repository
.claude/                   # AI metadata
.gitignore                 # Git config
.dockerignore              # Docker config
.env.example               # Template only
.env.docker                # Docker template

docker-compose.yml         # Docker only
Dockerfile                 # Docker only
Makefile                   # Build automation

install/                   # Development scripts
docs/                      # Documentation
*.md                       # Markdown docs (except README.md optional)
*.sh                       # Shell scripts
*.bat                      # Batch scripts

logs/*.log                 # Local logs
```

---

## Deployment Steps

### 1. Prepare Files

**On your local machine:**
```bash
# Create deployment archive (exclude dev files)
zip -r deploy.zip \
  app/ admin/ public/ views/ sql/ \
  index.php .htaccess README.md \
  -x "*.log" "*/.DS_Store" "*/Thumbs.db"
```

### 2. Upload to Server

**Via FTP/SFTP:**
- Upload `deploy.zip`
- Extract in web root
- Or upload directories directly

**Via Git (if available):**
```bash
git clone https://your-repo.git
cd your-repo
# Then manually remove install/, docs/, etc.
```

### 3. Configure Environment

**Create `.env` file on server:**
```env
DB_HOST=localhost
DB_NAME=production_database
DB_USER=production_user
DB_PASS=strong_password_here
DB_CHARSET=utf8mb4

APP_ENV=production
# APP_URL will auto-detect from server - comment out to use auto-detection
# APP_URL=https://yourdomain.com
BASE_PATH=
```

**Important:** The `APP_URL` setting is now optional. The system will automatically detect your domain (http/https) from the server. Only uncomment and set `APP_URL` if you need to force a specific URL.

### 4. Setup Database

**Import schema:**
```bash
mysql -u production_user -p production_database < sql/schema.sql
```

**Or via phpMyAdmin:**
1. Create database: `production_database`
2. Import: `sql/schema.sql`
3. Import (optional): `sql/setup_concerts_and_fix_encoding.sql`

### 5. Set Permissions

```bash
# Make uploads writable
chmod 755 public/uploads
chmod 755 public/uploads/albums
chmod 755 public/uploads/news
chmod 755 public/uploads/gallery

# Create logs directory
mkdir -p logs
chmod 755 logs
```

### 6. Security Tasks

**Change admin password:**
```php
# Generate new hash
php -r "echo password_hash('your_secure_password', PASSWORD_DEFAULT);"
```

```sql
# Update in database
UPDATE users
SET password_hash = 'hash_from_above'
WHERE username = 'admin';
```

**Verify `.htaccess`:**
```apache
# Should contain:
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

**Enable SSL:**
- Install SSL certificate (Let's Encrypt)
- Update `BASE_URL` in `.env` to https://
- Force HTTPS in `.htaccess`

### 7. Web Server Config

**Apache:**
- Ensure `mod_rewrite` is enabled
- Set document root to project directory
- Allow `.htaccess` overrides

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?url=$uri&$args;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
}
```

### 8. Verify Deployment

**Test these URLs:**
- ✅ Home: https://yourdomain.com
- ✅ Biography: https://yourdomain.com/biography
- ✅ Discography: https://yourdomain.com/discography
- ✅ Concerts: https://yourdomain.com/concerts
- ✅ News: https://yourdomain.com/news
- ✅ Gallery: https://yourdomain.com/gallery
- ✅ Admin: https://yourdomain.com/admin

**Check admin panel:**
- Login with new credentials
- Verify all CRUD operations work
- Test image uploads
- Check Romanian characters display correctly

---

## Post-Deployment

### Performance
- Enable OPcache in PHP
- Enable Gzip compression
- Optimize images (WebP format)
- Consider CDN for assets

### Monitoring
- Set up error logging
- Monitor disk space (uploads)
- Regular database backups
- Update admin password periodically

### Maintenance
- Keep PHP updated (8.2+)
- Regular MySQL backups
- Monitor security advisories
- Test backups regularly

---

## Quick Deploy Command

**One-liner for clean deployment:**
```bash
rsync -av --exclude-from='.deployignore' \
  ./ user@server:/path/to/webroot/
```

Uses `.deployignore` file to exclude dev files automatically.

---

## Rollback Plan

**If deployment fails:**

1. Keep previous version backed up
2. Restore files from backup
3. Restore database from backup
4. Verify `.env` settings
5. Check error logs: `tail -f logs/error.log`

---

## Support

See [SETUP.md](SETUP.md) for detailed setup instructions.

See [docs/TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md) for common issues.

---

**Deployment ready!** 🚀
