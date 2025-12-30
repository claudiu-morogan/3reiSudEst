@echo off
echo =================================================
echo   3 Sud Est - Docker Setup (Windows)
echo =================================================
echo.

REM Check if Docker is running
docker info >nul 2>&1
if errorlevel 1 (
    echo ERROR: Docker is not running!
    echo Please start Docker Desktop and try again.
    pause
    exit /b 1
)

echo Building Docker containers...
docker-compose build

echo.
echo Starting services...
docker-compose up -d

echo.
echo Waiting for database to be ready...
timeout /t 10 /nobreak >nul

echo.
echo =================================================
echo   Setup Complete!
echo =================================================
echo.
echo Services are now running:
echo.
echo   Web Application:  http://localhost:8080
echo   Admin Panel:      http://localhost:8080/admin/login.php
echo   phpMyAdmin:       http://localhost:8081
echo.
echo Default Admin Login:
echo   Username: admin
echo   Password: admin123
echo.
echo Database Connection (from host):
echo   Host: localhost
echo   Port: 3307
echo   Database: 3sudest
echo   User: 3sudest_user
echo   Password: secure_password_123
echo.
echo Useful commands:
echo   Stop:     docker-compose stop
echo   Start:    docker-compose start
echo   Restart:  docker-compose restart
echo   Logs:     docker-compose logs -f
echo   Shell:    docker-compose exec web bash
echo   Down:     docker-compose down
echo.
pause
