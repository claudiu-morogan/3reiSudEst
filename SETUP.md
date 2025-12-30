# 3 Sud Est - Setup Instructions

## Quick Start (Development)

### 1. Start Docker

**Windows:**
```bash
install\scripts\docker-start.bat
```

**Linux/Mac/WSL:**
```bash
chmod +x install/scripts/docker-start.sh
./install/scripts/docker-start.sh
```

### 2. Access the Site

- **Website**: http://localhost:8080
- **Admin**: http://localhost:8080/admin
  - Username: `admin`
  - Password: `admin123`
- **phpMyAdmin**: http://localhost:8081
  - Username: `root`
  - Password: `root_password_123`

---

## Production Deployment

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Apache or Nginx
- Composer (optional)

### Deployment Steps

#### 1. Upload Files

Upload these directories to your server:
```
app/
admin/
public/
views/
sql/
index.php
.htaccess
```

**Do NOT upload:**
- `.git/`, `.claude/`, `logs/` (local only)
- `docker-compose.yml`, `Dockerfile` (Docker only)
- `install/`, `docs/` (development only)
- `.env.docker`, `.env.example` (templates only)

#### 2. Configure Environment

Create `.env` file on server:
```env
DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASS=your_database_password
APP_ENV=production
BASE_URL=https://yourdomain.com
```

#### 3. Setup Database

Import the schema:
```bash
mysql -u your_user -p your_database < sql/schema.sql
```

#### 4. Set Permissions

```bash
chmod 755 public/uploads/albums
chmod 755 public/uploads/news
chmod 755 public/uploads/gallery
chmod 755 logs
```

#### 5. Security

- Change admin password immediately
- Enable SSL/HTTPS
- Update `BASE_URL` in `.env`
- Set `APP_ENV=production`

---

## Common Issues

### Romanian Characters Look Weird?
Run this SQL in phpMyAdmin:
```sql
ALTER DATABASE your_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Admin Login Not Working?
Reset password with this SQL:
```sql
UPDATE users SET password_hash = '$2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' WHERE username = 'admin';
```
Password will be: `admin123`

### Concerts Not Showing?
Import concerts table:
```sql
-- Run the content from sql/setup_concerts_and_fix_encoding.sql in phpMyAdmin
```

---

## Need Help?

- **Development docs**: See `docs/` directory
- **Installation scripts**: See `install/scripts/` directory
- **Troubleshooting**: See `docs/TROUBLESHOOTING.md`

---

**That's it! The site should now be running.**
