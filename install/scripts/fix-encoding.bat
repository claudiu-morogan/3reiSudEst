@echo off
echo ======================================
echo   Fix Romanian Character Encoding
echo ======================================
echo.
echo This will fix encoding issues for Romanian characters (ă, â, î, ș, ț)
echo.
echo WARNING: This will recreate the concerts table and remove any existing concerts.
echo Press Ctrl+C to cancel, or
pause

echo.
echo Fixing concerts table encoding...
docker exec -i 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_concerts_encoding.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ======================================
    echo   SUCCESS!
    echo ======================================
    echo.
    echo Romanian characters should now display correctly.
    echo Visit http://localhost:8080/concerts to verify.
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
