# Docker Setup Guide

## Overview

This Docker setup provides a complete development environment with:
- **PHP 8.2** with Apache web server
- **MySQL 8.0** database
- **phpMyAdmin** for database management
- Automatic schema import
- Hot-reload for code changes
- Persistent data volumes

## Prerequisites

- Docker Desktop installed and running
- Docker Compose (included with Docker Desktop)
- 2GB free disk space
- Ports 8080, 8081, 3307 available

## Quick Start

### Windows
```bash
docker-start.bat
```

### Mac/Linux
```bash
chmod +x docker-start.sh
./docker-start.sh
```

### Manual Start
```bash
docker-compose up -d
```

## Service URLs

Once running, access:

- **Website**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/admin/login.php
- **phpMyAdmin**: http://localhost:8081

### Default Credentials

**Admin Panel**:
- Username: `admin`
- Password: `admin123`

**phpMyAdmin**:
- Server: `db`
- Username: `root`
- Password: `root_password_123`

**Database (from host machine)**:
- Host: `localhost`
- Port: `3307`
- Database: `3sudest`
- User: `3sudest_user`
- Password: `secure_password_123`

## Docker Commands

### Using Make (Recommended)

If you have `make` installed:

```bash
make up          # Start services
make down        # Stop and remove containers
make restart     # Restart services
make logs        # View logs
make shell       # Access web container
make db-shell    # Access MySQL shell
make clean       # Remove everything (including data!)
make db-backup   # Backup database
```

### Using Docker Compose

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose stop

# Restart services
docker-compose restart

# View logs
docker-compose logs -f

# Stop and remove containers
docker-compose down

# Rebuild containers
docker-compose build
docker-compose up -d
```

## Container Details

### Web Container (3sudest_web)

- **Base**: PHP 8.2 Apache
- **Extensions**: PDO, PDO_MySQL, MySQLi, GD, Zip, OPcache
- **Port**: 8080 → 80
- **Auto-reload**: Yes (volume mounted)

### Database Container (3sudest_db)

- **Base**: MySQL 8.0
- **Port**: 3307 → 3306
- **Character Set**: utf8mb4
- **Auto-import**: schema.sql runs on first start
- **Persistent**: Data stored in Docker volume

### phpMyAdmin Container (3sudest_phpmyadmin)

- **Base**: Latest phpMyAdmin
- **Port**: 8081 → 80
- **Upload Limit**: 10MB

## Persistent Data

Data is stored in named Docker volumes:

- `db_data` - MySQL database files
- `uploads_data` - User uploaded images
- `logs_data` - Application logs

### Backup Database
```bash
docker-compose exec db mysqldump -u3sudest_user -psecure_password_123 3sudest > backup.sql
```

### Restore Database
```bash
docker-compose exec -T db mysql -u3sudest_user -psecure_password_123 3sudest < backup.sql
```

## Development Workflow

### Live Code Editing

All code changes are immediately reflected:
1. Edit PHP files on your host machine
2. Refresh browser to see changes
3. No container restart needed

### Accessing Container Shell

```bash
# Web container
docker-compose exec web bash

# Database shell
docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest
```

## Troubleshooting

### Port Already in Use

Edit `docker-compose.yml` to change ports if needed.

### Database Connection Refused

Wait 10-15 seconds after startup for MySQL to initialize.

### Permission Errors

```bash
docker-compose exec web chown -R www-data:www-data public/uploads logs
docker-compose exec web chmod -R 755 public/uploads logs
```

### Reset Everything

```bash
docker-compose down -v  # WARNING: Deletes all data!
docker-compose up -d
```

## Security Notes

**IMPORTANT**: Change default passwords in production!

- Database root password
- Database user password
- Admin panel password

## FAQ

### Can I use this in production?

This setup is optimized for development. For production, use managed hosting with proper security.

### How do I connect from MySQL client?

- Host: `127.0.0.1`
- Port: `3307`
- User: `3sudest_user`
- Password: `secure_password_123`

### Is the database persisted?

Yes! Data survives container restarts. Only `docker-compose down -v` deletes it.

---

Built with developer experience in mind.
