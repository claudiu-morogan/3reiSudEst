@echo off
echo ======================================
echo   Fix ALL Tables - Romanian Encoding
echo ======================================
echo.
echo This will convert ALL database tables to proper UTF-8 encoding.
echo This fixes Romanian characters: ă, â, î, ș, ț
echo.
echo This is SAFE - it only converts table encoding, doesn't delete data.
echo.
pause

echo.
echo Converting all tables to UTF-8 encoding...
docker exec -i 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_all_encoding.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ======================================
    echo   SUCCESS!
    echo ======================================
    echo.
    echo All tables converted to UTF-8.
    echo.
    echo IMPORTANT: If you still see weird characters, the data itself
    echo may have been inserted incorrectly. You'll need to re-enter it.
    echo.
    echo Test pages:
    echo   - Public: http://localhost:8080
    echo   - Admin: http://localhost:8080/admin/
    echo.
) else (
    echo.
    echo ======================================
    echo   ERROR!
    echo ======================================
    echo.
    echo Failed to fix encoding. Check if Docker is running.
    echo.
)

pause
