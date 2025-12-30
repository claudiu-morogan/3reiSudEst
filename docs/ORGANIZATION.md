# Installation Directory Organization

## Overview

The `install/` directory contains all materials needed to set up, configure, and troubleshoot the 3 Sud Est website.

## Directory Structure

```
install/
├── README.md              # Main installation guide - START HERE
├── MIGRATION_NOTES.md     # Details about file reorganization
├── ORGANIZATION.md        # This file - directory organization
│
├── scripts/               # Executable scripts for setup and maintenance
│   ├── docker-start.sh       # Start Docker containers (Linux/WSL)
│   ├── docker-start.bat      # Start Docker containers (Windows)
│   ├── docker-restart.bat    # Restart Docker containers
│   ├── docker-logs.bat       # View Docker logs
│   ├── setup.sh              # Initial database setup
│   ├── setup-concerts.sh     # Setup concerts module
│   ├── fix_login.sh          # Fix admin login (Linux/WSL)
│   ├── fix_login.bat         # Fix admin login (Windows)
│   ├── fix-encoding-wsl.sh   # Fix Romanian encoding (WSL)
│   ├── fix-all-encoding.bat  # Fix all table encodings (Windows)
│   ├── fix-encoding.bat      # Fix encoding via phpMyAdmin
│   ├── wsl-fix.sh            # General WSL fixes
│   ├── debug_login.php       # Debug admin login
│   └── test-bio.php          # Test biography functionality
│
├── docs/                  # Documentation and guides
│   ├── ADMIN_GUIDE.md           # How to use admin panel
│   ├── COMPLETION_GUIDE.md      # Project completion status
│   ├── CONCERTS_QUICK_START.md  # Quick concerts setup (3 steps)
│   ├── CONCERTS_SETUP.md        # Detailed concerts setup
│   ├── SETUP_CONCERTS_PHPMYADMIN.md  # Setup via phpMyAdmin
│   ├── ENCODING_FIX_PHPMYADMIN.md    # Fix encoding via phpMyAdmin
│   ├── FIX_ROMANIAN_ENCODING.md      # Romanian character fixes
│   ├── QUICK_FIX.md                  # Quick troubleshooting
│   ├── RESET_PASSWORD.md             # Reset admin password
│   └── TROUBLESHOOTING.md            # Comprehensive troubleshooting
│
└── sql/                   # Additional SQL scripts
    └── (reserved for future setup scripts)
```

## Quick Navigation

### 🚀 Getting Started
→ [README.md](README.md)

### 🔧 Setup Scripts
→ [scripts/](scripts/)

### 📖 Documentation
→ [docs/](docs/)

## Usage Patterns

### First Time Setup

1. Read [README.md](README.md)
2. Run `scripts/docker-start.sh` or `scripts/docker-start.bat`
3. Access http://localhost:8080

### Common Issues

**Login not working?**
→ [docs/RESET_PASSWORD.md](docs/RESET_PASSWORD.md)

**Weird Romanian characters?**
→ [docs/ENCODING_FIX_PHPMYADMIN.md](docs/ENCODING_FIX_PHPMYADMIN.md)

**Concerts not showing?**
→ [docs/CONCERTS_QUICK_START.md](docs/CONCERTS_QUICK_START.md)

**Something broken?**
→ [docs/TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md)

## File Organization Principles

### Scripts Directory (`scripts/`)

Contains executable files that **perform actions**:
- Setup automation
- Fix utilities
- Debug tools
- Database migrations

All scripts are organized by:
- **Platform**: `.sh` for Linux/WSL, `.bat` for Windows, `.php` for web
- **Purpose**: Prefix indicates function (`setup-`, `fix-`, `docker-`)

### Docs Directory (`docs/`)

Contains markdown files that **provide information**:
- How-to guides
- Troubleshooting
- Reference documentation
- Setup instructions

All docs are organized by:
- **Topic**: ADMIN, CONCERTS, ENCODING, etc.
- **Type**: GUIDE, SETUP, FIX, etc.

### SQL Directory (`sql/`)

Reserved for:
- Database setup scripts
- Migration scripts
- Seed data

Currently empty - main SQL files in project root `sql/` directory.

## Root Level Files

The project root contains only essential files:

**Operational:**
- `index.php` - Application entry point
- `.htaccess` - URL rewriting
- `docker-compose.yml` - Container orchestration
- `Dockerfile` - Image definition

**Configuration:**
- `.env`, `.env.example` - Environment config
- `Makefile` - Build automation

**Documentation (High Level):**
- `README.md` - Main project readme
- `INSTALL.md` - Quick install pointer to install/
- `DOCKER.md` - Docker documentation
- `ARCHITECTURE.txt` - System design
- `DEPLOYMENT.md` - Production deployment

**Detailed guides moved to `install/docs/`**

## Accessing Files

### From Project Root

```bash
# Run setup
./install/scripts/setup.sh

# View guide
cat install/docs/ADMIN_GUIDE.md

# Fix encoding
./install/scripts/fix-encoding-wsl.sh
```

### From Install Directory

```bash
cd install

# View main guide
cat README.md

# Run script
./scripts/docker-start.sh

# Read docs
cat docs/TROUBLESHOOTING.md
```

## Benefits of This Organization

✅ **Clean Root** - Only essential operational files in root
✅ **Logical Grouping** - Related files together
✅ **Easy Discovery** - Clear directory names
✅ **Better Maintenance** - Changes don't clutter root
✅ **Professional Structure** - Industry standard organization
✅ **Scalable** - Easy to add new scripts/docs

## Adding New Files

### New Script?
→ Place in `install/scripts/`
→ Update `install/README.md` if major feature

### New Documentation?
→ Place in `install/docs/`
→ Update `install/README.md` with link

### New SQL Script?
→ Place in `install/sql/` for setup/migration
→ Place in project `sql/` for schema/structure

## Related Documentation

- **Project Root**: [../README.md](../README.md)
- **Installation**: [README.md](README.md)
- **Migration Notes**: [MIGRATION_NOTES.md](MIGRATION_NOTES.md)
- **Admin Guide**: [docs/ADMIN_GUIDE.md](docs/ADMIN_GUIDE.md)

---

**Organized**: December 30, 2024
**Maintained By**: Development Team
**Purpose**: Clean, professional project structure
