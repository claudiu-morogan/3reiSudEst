#!/bin/bash

echo "======================================"
echo "  WSL + Docker Debugging"
echo "======================================"
echo ""

# Check if we're in WSL
if grep -qi microsoft /proc/version; then
    echo "✓ Running in WSL"
else
    echo "✗ Not in WSL - script designed for WSL environment"
fi

echo ""
echo "Checking Docker containers..."
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"

echo ""
echo "Checking Apache error logs..."
docker compose logs --tail=50 web | grep -i "error\|fatal\|warning" | tail -20

echo ""
echo "Testing if files are accessible in container..."
docker compose exec web ls -la /var/www/html/ | head -10

echo ""
echo "Testing PHP syntax in container..."
docker compose exec web php -l /var/www/html/index.php

echo ""
echo "Checking file permissions..."
docker compose exec web stat /var/www/html/index.php

echo ""
echo "======================================"
echo "To view full logs, run:"
echo "  docker compose logs -f web"
echo "======================================"
