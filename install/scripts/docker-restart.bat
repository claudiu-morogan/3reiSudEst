@echo off
echo ======================================
echo   Restarting Docker Containers
echo ======================================
echo.

echo Stopping containers...
docker compose down

echo.
echo Starting containers...
docker compose up -d

echo.
echo Waiting for services to be ready...
timeout /t 5 /nobreak >nul

echo.
echo Checking container status...
docker compose ps

echo.
echo ======================================
echo   Containers restarted successfully!
echo ======================================
echo.
echo You can now access:
echo   - Website: http://localhost:8080
echo   - Admin: http://localhost:8080/admin/
echo   - phpMyAdmin: http://localhost:8081
echo.
echo To view logs, run:
echo   docker compose logs -f web
echo.
