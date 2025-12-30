# 3 Sud Est - Docker Management Makefile

.PHONY: help build up down start stop restart logs shell clean db-backup db-restore

help:
	@echo "3 Sud Est - Docker Commands"
	@echo ""
	@echo "  make build        Build Docker images"
	@echo "  make up           Start all services"
	@echo "  make down         Stop and remove containers"
	@echo "  make start        Start existing containers"
	@echo "  make stop         Stop containers"
	@echo "  make restart      Restart all services"
	@echo "  make logs         Show container logs"
	@echo "  make shell        Access web container shell"
	@echo "  make db-shell     Access MySQL shell"
	@echo "  make clean        Remove all containers and volumes"
	@echo "  make db-backup    Backup database"
	@echo "  make db-restore   Restore database from backup"

build:
	docker-compose build

up:
	docker-compose up -d
	@echo ""
	@echo "Services running at:"
	@echo "  Web: http://localhost:8080"
	@echo "  Admin: http://localhost:8080/admin/login.php"
	@echo "  phpMyAdmin: http://localhost:8081"

down:
	docker-compose down

start:
	docker-compose start

stop:
	docker-compose stop

restart:
	docker-compose restart

logs:
	docker-compose logs -f

shell:
	docker-compose exec web bash

db-shell:
	docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest

clean:
	docker-compose down -v
	@echo "All containers and volumes removed"

db-backup:
	@mkdir -p backups
	docker-compose exec db mysqldump -u3sudest_user -psecure_password_123 3sudest > backups/backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "Database backed up to backups/"

db-restore:
	@read -p "Enter backup file path: " file; \
	docker-compose exec -T db mysql -u3sudest_user -psecure_password_123 3sudest < $$file
	@echo "Database restored"
