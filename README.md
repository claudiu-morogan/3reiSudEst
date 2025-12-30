# 3 Sud Est - Official Website

Premium presentation website for the Romanian band 3 Sud Est.

**Tech Stack**: PHP 8.2, MySQL 8.0, Vanilla JS

---

## Quick Start

### Development (Docker)

```bash
# Windows
install\scripts\docker-start.bat

# Linux/Mac/WSL
chmod +x install/scripts/docker-start.sh
./install/scripts/docker-start.sh
```

**Access:**
- Website: http://localhost:8080
- Admin: http://localhost:8080/admin (admin/admin123)
- phpMyAdmin: http://localhost:8081 (root/root_password_123)

---

## Production Deployment

### 1. Upload Files

Upload to your server:
- `app/` - Application code
- `admin/` - Admin panel
- `public/` - Assets (CSS, JS, uploads)
- `views/` - Templates
- `sql/` - Database schema
- `index.php` - Entry point
- `.htaccess` - URL rewriting

### 2. Configure

Create `.env` file:
```env
DB_HOST=localhost
DB_NAME=your_database
DB_USER=your_user
DB_PASS=your_password
APP_ENV=production
BASE_URL=https://yourdomain.com
```

### 3. Database

```bash
mysql -u user -p database < sql/schema.sql
```

### 4. Permissions

```bash
chmod 755 public/uploads/albums public/uploads/news public/uploads/gallery logs
```

### 5. Security

- Change admin password
- Enable SSL
- Set production environment

**📖 Full setup guide: [SETUP.md](SETUP.md)**

---

## Features

### Public Site
- Animated hero & parallax
- Discography with track listings
- News & events
- Photo gallery
- Band biography
- Mobile responsive

### Admin Panel
- Secure authentication
- Full CRUD for all content
- Image management
- CSRF protection

---

## Project Structure

```
3reiSudEst/
├── app/              # Backend (models, auth, database)
├── admin/            # Admin panel
├── public/           # Assets (CSS, JS, uploads)
├── views/            # Public templates
├── sql/              # Database schemas
├── install/          # Development setup scripts
├── docs/             # Documentation
├── index.php         # Entry point
└── .htaccess         # URL rewriting
```

---

## Admin Access

**Default credentials:**
- Username: `admin`
- Password: `admin123`

**⚠️ Change in production!**

Generate new password hash:
```php
php -r "echo password_hash('new_password', PASSWORD_DEFAULT);"
```

Update in database:
```sql
UPDATE users SET password_hash = 'hash_here' WHERE username = 'admin';
```

---

## Documentation

- **[SETUP.md](SETUP.md)** - Setup & deployment guide
- **[docs/](docs/)** - All documentation
- **[install/](install/)** - Development scripts

---

## Requirements

- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.6+
- Apache with mod_rewrite or Nginx
- UTF-8 support

---

## Support

**Common issues:**
- Romanian characters: Run UTF-8 conversion in phpMyAdmin
- Login issues: Reset admin password via SQL
- Concerts missing: Import `sql/setup_concerts_and_fix_encoding.sql`

**Full troubleshooting**: [docs/TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md)

---

## License

Proprietary - All rights reserved to 3 Sud Est

---

**Built with passion for Romanian pop culture** 🎵
