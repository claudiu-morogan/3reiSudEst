# Docker Quick Reference Card

## One-Command Setup

```bash
# Windows
docker-start.bat

# Mac/Linux
./docker-start.sh
```

## Access Points

| Service | URL |
|---------|-----|
| Website | http://localhost:8080 |
| Admin Panel | http://localhost:8080/admin/login.php |
| phpMyAdmin | http://localhost:8081 |

## Essential Commands

```bash
# Start everything
docker-compose up -d

# Stop everything
docker-compose down

# View logs
docker-compose logs -f

# Restart
docker-compose restart

# Access shell
docker-compose exec web bash

# Database backup
docker-compose exec db mysqldump -u3sudest_user -psecure_password_123 3sudest > backup.sql
```

## Shortcuts (with Make)

```bash
make up        # Start
make down      # Stop
make logs      # View logs
make shell     # Web shell
make db-backup # Backup database
```

## Troubleshooting

### Services won't start
```bash
docker-compose down
docker-compose up -d
```

### Database connection error
Wait 10-15 seconds after startup

### Port conflict
Edit `docker-compose.yml` and change port numbers

### Permission errors
```bash
docker-compose exec web chown -R www-data:www-data public/uploads logs
```

## Default Credentials

**Admin Panel**: admin / admin123  
**phpMyAdmin Root**: root / root_password_123  
**Database User**: 3sudest_user / secure_password_123

---

Full documentation: [DOCKER.md](DOCKER.md)
