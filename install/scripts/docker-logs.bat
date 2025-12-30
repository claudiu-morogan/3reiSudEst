@echo off
echo ======================================
echo   Docker Container Logs
echo ======================================
echo.
echo Showing last 50 lines of web container logs...
echo Press Ctrl+C to stop following logs
echo.

docker compose logs --tail=50 -f web
