@echo off
echo ==========================================
echo   3 Sud Est - Login Fix Script
echo ==========================================
echo.

echo Step 1: Checking if Docker is running...
docker ps >nul 2>&1
if errorlevel 1 (
    echo ERROR: Docker is not running or not accessible
    pause
    exit /b 1
)
echo OK: Docker is running
echo.

echo Step 2: Updating admin password in database...
docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -e "UPDATE users SET password_hash = '$2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' WHERE username = 'admin';"
if errorlevel 1 (
    echo ERROR: Failed to update password
    pause
    exit /b 1
)
echo OK: Password updated successfully
echo.

echo Step 3: Verifying password hash...
docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -N -e "SELECT CASE WHEN password_hash = '$2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' THEN 'CORRECT' ELSE 'WRONG' END FROM users WHERE username = 'admin';"
echo.

echo ==========================================
echo   Fix Complete!
echo ==========================================
echo.
echo Login credentials:
echo   URL:      http://localhost:8080/admin/login.php
echo   Username: admin
echo   Password: admin123
echo.
echo Debug page (check this if still failing):
echo   http://localhost:8080/debug_login.php
echo.
pause
