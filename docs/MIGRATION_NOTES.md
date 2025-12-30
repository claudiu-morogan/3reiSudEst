# Installation Directory Migration Notes

## What Changed?

All setup scripts, fix utilities, and documentation have been organized into the `install/` directory for better project organization.

## File Relocations

### Scripts Moved to `install/scripts/`

**Docker Scripts:**
- `docker-start.bat` → `install/scripts/docker-start.bat`
- `docker-start.sh` → `install/scripts/docker-start.sh`
- `docker-restart.bat` → `install/scripts/docker-restart.bat`
- `docker-logs.bat` → `install/scripts/docker-logs.bat`

**Setup Scripts:**
- `setup.sh` → `install/scripts/setup.sh`
- `setup-concerts.sh` → `install/scripts/setup-concerts.sh`

**Fix/Debug Scripts:**
- `fix_login.bat` → `install/scripts/fix_login.bat`
- `fix_login.sh` → `install/scripts/fix_login.sh`
- `fix-encoding-wsl.sh` → `install/scripts/fix-encoding-wsl.sh`
- `fix-all-encoding.bat` → `install/scripts/fix-all-encoding.bat`
- `fix-encoding.bat` → `install/scripts/fix-encoding.bat`
- `wsl-fix.sh` → `install/scripts/wsl-fix.sh`
- `debug_login.php` → `install/scripts/debug_login.php`
- `test-bio.php` → `install/scripts/test-bio.php`

### Documentation Moved to `install/docs/`

**Setup Guides:**
- `ADMIN_GUIDE.md` → `install/docs/ADMIN_GUIDE.md`
- `COMPLETION_GUIDE.md` → `install/docs/COMPLETION_GUIDE.md`
- `CONCERTS_QUICK_START.md` → `install/docs/CONCERTS_QUICK_START.md`
- `CONCERTS_SETUP.md` → `install/docs/CONCERTS_SETUP.md`
- `SETUP_CONCERTS_PHPMYADMIN.md` → `install/docs/SETUP_CONCERTS_PHPMYADMIN.md`

**Troubleshooting Guides:**
- `ENCODING_FIX_PHPMYADMIN.md` → `install/docs/ENCODING_FIX_PHPMYADMIN.md`
- `FIX_ROMANIAN_ENCODING.md` → `install/docs/FIX_ROMANIAN_ENCODING.md`
- `QUICK_FIX.md` → `install/docs/QUICK_FIX.md`
- `RESET_PASSWORD.md` → `install/docs/RESET_PASSWORD.md`
- `TROUBLESHOOTING.md` → `install/docs/TROUBLESHOOTING.md`

## Files Remaining in Root

### Essential Files
- `README.md` - Main project readme (updated with new paths)
- `index.php` - Public site entry point
- `.htaccess` - Apache rewrite rules
- `.env`, `.env.example`, `.env.docker` - Environment configuration
- `docker-compose.yml` - Docker orchestration
- `Dockerfile` - Docker image definition
- `Makefile` - Build automation

### Documentation (Root Level)
- `DOCKER.md` - Docker usage guide
- `DOCKER_QUICKREF.md` - Docker quick reference
- `DOCKER_SUMMARY.md` - Docker summary
- `ARCHITECTURE.txt` - System architecture
- `CHECKLIST.md` - Development checklist
- `DEPLOYMENT.md` - Deployment guide
- `PROJECT_SUMMARY.md` - Project overview

### Directories
- `app/` - Application code
- `admin/` - Admin panel
- `public/` - Public assets
- `views/` - Public templates
- `sql/` - Database schemas
- `logs/` - Application logs
- `.git/` - Git repository
- `.claude/` - Claude AI metadata

## New Structure Benefits

### ✅ Cleaner Root Directory
The project root is now much cleaner with only essential operational files.

### ✅ Better Organization
- All installation materials in one place
- Scripts separated from documentation
- Clear hierarchy for finding resources

### ✅ Easier Onboarding
New developers can find all setup information in the `install/` directory.

### ✅ Professional Structure
Follows industry standards for project organization.

## How to Use After Migration

### Starting the Project
```bash
# Old way (still works from root)
cd /path/to/3reiSudEst
./install/scripts/docker-start.sh

# Or navigate to scripts
cd install/scripts
./docker-start.sh
```

### Finding Documentation
All guides are now in `install/docs/`:
```bash
# View installation guide
cat install/README.md

# View admin guide
cat install/docs/ADMIN_GUIDE.md

# View troubleshooting
cat install/docs/TROUBLESHOOTING.md
```

### Running Scripts
All scripts are in `install/scripts/`:
```bash
# Make executable (first time only)
chmod +x install/scripts/*.sh

# Run setup
./install/scripts/setup.sh

# Fix encoding
./install/scripts/fix-encoding-wsl.sh
```

## Git Tracking

All moved files are tracked by Git. The migration preserves history:
```bash
# See file history after move
git log --follow install/scripts/docker-start.sh

# See what was moved
git log --stat
```

## Rollback (if needed)

If you need to restore the old structure:
```bash
# Move scripts back to root
mv install/scripts/*.sh .
mv install/scripts/*.bat .
mv install/scripts/*.php .

# Move docs back to root
mv install/docs/*.md .

# Remove install directory
rm -rf install/
```

## Questions?

See [install/README.md](README.md) for the comprehensive installation guide.

---

**Migration Date**: December 30, 2024
**Reason**: Project organization and maintainability
**Impact**: File paths updated in README.md, no code changes required
