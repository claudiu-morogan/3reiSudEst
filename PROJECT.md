# 3 Sud Est - Project Overview

## Project Structure

```
3reiSudEst/
│
├── 📁 Production Files (Deploy These)
│   ├── app/              Backend code (models, auth, database)
│   ├── admin/            Admin panel
│   ├── public/           Assets (CSS, JS, images, uploads)
│   ├── views/            Public page templates
│   ├── sql/              Database schemas
│   ├── index.php         Application entry point
│   └── .htaccess         URL rewriting rules
│
├── 📁 Configuration
│   ├── .env              Environment config (create custom for each environment)
│   ├── .env.example      Template for environment file
│   ├── .env.docker       Docker environment template
│   ├── .gitignore        Git ignore rules
│   └── .deployignore     Deployment exclusion list
│
├── 📁 Development Only (Don't Deploy)
│   ├── install/          Setup scripts for local development
│   │   ├── scripts/      Docker, setup, and fix scripts
│   │   └── README.md     Script usage guide
│   │
│   ├── docs/             All documentation
│   │   ├── ADMIN_GUIDE.md
│   │   ├── TROUBLESHOOTING.md
│   │   ├── CONCERTS_QUICK_START.md
│   │   └── ... (15+ guides)
│   │
│   ├── docker-compose.yml   Docker orchestration
│   ├── Dockerfile           Docker image definition
│   ├── Makefile             Build automation
│   └── logs/                Local development logs
│
└── 📄 Documentation (Root)
    ├── README.md         Main project readme
    ├── SETUP.md          Setup & deployment guide
    ├── DEPLOY.md         Production deployment checklist
    └── PROJECT.md        This file
```

---

## Quick Reference

### Development
```bash
# Start local development
./install/scripts/docker-start.sh

# Access
http://localhost:8080       # Website
http://localhost:8080/admin # Admin (admin/admin123)
http://localhost:8081       # phpMyAdmin (root/root_password_123)
```

### Production Deploy
```bash
# What to upload
app/ admin/ public/ views/ sql/ index.php .htaccess

# What NOT to upload
install/ docs/ docker* Makefile *.md *.sh *.bat .git/
```

### Documentation
- **[README.md](README.md)** - Project overview & quick start
- **[SETUP.md](SETUP.md)** - Detailed setup instructions
- **[DEPLOY.md](DEPLOY.md)** - Production deployment checklist
- **[docs/](docs/)** - All guides (15+ documents)
- **[install/README.md](install/README.md)** - Development scripts

---

## File Count Summary

**Production Files:**
- Core directories: 5 (app, admin, public, views, sql)
- Core files: 2 (index.php, .htaccess)

**Configuration Files:**
- Environment: 3 (.env, .env.example, .env.docker)
- Config: 3 (.gitignore, .deployignore, .htaccess)

**Development Only:**
- Documentation: 18 files in docs/
- Scripts: 14 files in install/scripts/
- Docker: 3 files (docker-compose.yml, Dockerfile, Makefile)
- Guides: 4 root-level MD files

**Total Root Files:** 14 (minimal & clean)

---

## Technology Stack

- **Backend**: PHP 8.2
- **Database**: MySQL 8.0 / MariaDB 10.6+
- **Frontend**: Vanilla JavaScript, CSS3
- **Server**: Apache (mod_rewrite) or Nginx
- **Development**: Docker, Docker Compose

---

## Features

### Public Website
✅ Band biography with timeline
✅ Discography with album covers & track listings
✅ Concert schedule (upcoming & past)
✅ News & announcements
✅ Photo gallery
✅ Mobile-responsive design
✅ Smooth animations & parallax effects
✅ Romanian language support (UTF-8)

### Admin Panel
✅ Secure authentication (bcrypt)
✅ CRUD for all content types
✅ Image upload management
✅ CSRF protection
✅ Session security
✅ Clean, modern interface
✅ Active page highlighting

---

## Key Directories Explained

### `app/` - Backend Application
- `config.php` - Environment loader
- `Database.php` - PDO wrapper
- `Auth.php` - Authentication
- `CSRF.php` - CSRF protection
- `models/` - Data access layer (Album, Concert, News, etc.)

### `admin/` - Admin Panel
- `index.php` - Admin router
- `login.php` - Login page
- `logout.php` - Logout handler
- `views/` - Admin CRUD interfaces

### `public/` - Public Assets
- `css/main.css` - Styles
- `js/app.js` - JavaScript
- `uploads/` - User uploaded images

### `views/` - Public Templates
- `layout/` - Header, footer, navigation
- Individual page views (home, biography, discography, etc.)

### `sql/` - Database
- `schema.sql` - Main database structure
- `setup_concerts_and_fix_encoding.sql` - Concerts + UTF-8 fix

### `install/scripts/` - Development Tools
- Docker management scripts
- Database setup scripts
- Fix utilities (login, encoding)
- Debug tools

### `docs/` - Documentation
- Setup guides
- Troubleshooting
- Feature documentation
- Architecture notes

---

## Environment Variables

```env
# Database
DB_HOST=localhost
DB_NAME=3sudest
DB_USER=db_user
DB_PASS=db_password
DB_CHARSET=utf8mb4

# Application
APP_ENV=development|production
BASE_URL=http://localhost:8080
```

---

## Default Credentials

### Local Development
**Admin Panel:**
- Username: `admin`
- Password: `admin123`

**phpMyAdmin:**
- Username: `root`
- Password: `root_password_123`

**Database User:**
- Username: `3sudest_user`
- Password: `secure_password_123`

⚠️ **Change all passwords in production!**

---

## Support Resources

**Setup Issues?** → [SETUP.md](SETUP.md)
**Deployment?** → [DEPLOY.md](DEPLOY.md)
**Bugs/Errors?** → [docs/TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md)
**Admin Guide?** → [docs/ADMIN_GUIDE.md](docs/ADMIN_GUIDE.md)
**Concerts Setup?** → [docs/CONCERTS_QUICK_START.md](docs/CONCERTS_QUICK_START.md)

---

## Maintenance

### Regular Tasks
- Update admin password quarterly
- Backup database weekly
- Monitor upload directory size
- Check error logs
- Update dependencies

### Security
- Keep PHP updated (8.2+)
- Monitor security advisories
- Test backups regularly
- Review access logs
- Audit user accounts

---

**Project Status:** ✅ Production Ready

**Built for:** 3 Sud Est Official Website
**Architecture:** MVC-lite custom PHP
**Design:** Premium Romanian pop aesthetic
**Deployment:** Ready for shared hosting or VPS
