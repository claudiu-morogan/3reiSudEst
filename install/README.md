# Development Setup Scripts

This directory contains scripts for **local development only**.

## Quick Start

### Start Development Environment

**Windows:**
```bash
scripts\docker-start.bat
```

**Linux/Mac/WSL:**
```bash
chmod +x scripts/docker-start.sh
./scripts/docker-start.sh
```

**Access:**
- Website: http://localhost:8080
- Admin: http://localhost:8080/admin (admin/admin123)
- phpMyAdmin: http://localhost:8081 (root/root_password_123)

---

## Available Scripts

### Docker Management
- `docker-start.bat` / `docker-start.sh` - Start containers
- `docker-restart.bat` - Restart containers
- `docker-logs.bat` - View logs

### Database Setup
- `setup.sh` - Initial database setup
- `setup-concerts.sh` - Setup concerts module

### Fixes & Debug
- `fix_login.bat` / `fix_login.sh` - Reset admin password
- `fix-encoding-wsl.sh` - Fix Romanian characters
- `fix-all-encoding.bat` - Fix all table encodings
- `debug_login.php` - Debug login issues
- `test-bio.php` - Test biography

---

## Common Tasks

### Reset Admin Password
```bash
# WSL/Linux
./scripts/fix_login.sh

# Windows
scripts\fix_login.bat
```

### Fix Romanian Characters
```bash
# WSL/Linux
./scripts/fix-encoding-wsl.sh

# Windows - use phpMyAdmin at http://localhost:8081
```

### Setup Concerts
```bash
# WSL/Linux
./scripts/setup-concerts.sh

# Windows - see: ../docs/CONCERTS_QUICK_START.md
```

---

## Documentation

**All guides are in:** `../docs/`

- [SETUP.md](../SETUP.md) - Main setup guide
- [docs/TROUBLESHOOTING.md](../docs/TROUBLESHOOTING.md) - Troubleshooting
- [docs/ADMIN_GUIDE.md](../docs/ADMIN_GUIDE.md) - Admin panel usage
- [docs/CONCERTS_QUICK_START.md](../docs/CONCERTS_QUICK_START.md) - Concerts setup

---

## Production Deployment

**⚠️ Don't deploy this directory to production!**

Only deploy:
- `app/`, `admin/`, `public/`, `views/`, `sql/`
- `index.php`, `.htaccess`
- `.env` (create custom on server)

**See:** [SETUP.md](../SETUP.md) for deployment instructions.
