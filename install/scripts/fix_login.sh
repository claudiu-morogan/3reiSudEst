#!/bin/bash

echo "=========================================="
echo "  3 Sud Est - Login Fix Script"
echo "=========================================="
echo ""

echo "Step 1: Checking if Docker is running..."
if ! docker ps > /dev/null 2>&1; then
    echo "ERROR: Docker is not running or not accessible"
    exit 1
fi
echo "✓ Docker is running"
echo ""

echo "Step 2: Updating admin password in database..."
docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -e "UPDATE users SET password_hash = '\$2y\$10\$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' WHERE username = 'admin';"

if [ $? -eq 0 ]; then
    echo "✓ Password updated successfully"
else
    echo "✗ Failed to update password"
    exit 1
fi
echo ""

echo "Step 3: Verifying password hash..."
result=$(docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -N -e "SELECT CASE WHEN password_hash = '\$2y\$10\$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' THEN 'CORRECT' ELSE 'WRONG' END FROM users WHERE username = 'admin';")

if [[ "$result" == *"CORRECT"* ]]; then
    echo "✓ Password hash verified as CORRECT"
else
    echo "✗ Password hash is WRONG: $result"
    exit 1
fi
echo ""

echo "Step 4: Checking if user exists..."
user_count=$(docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -N -e "SELECT COUNT(*) FROM users WHERE username = 'admin';")

if [[ "$user_count" -ge 1 ]]; then
    echo "✓ Admin user found"
else
    echo "✗ Admin user not found! Reimporting schema..."
    docker-compose exec -T db mysql -u3sudest_user -psecure_password_123 3sudest < sql/schema.sql
fi
echo ""

echo "=========================================="
echo "  Fix Complete!"
echo "=========================================="
echo ""
echo "Login credentials:"
echo "  URL:      http://localhost:8080/admin/login.php"
echo "  Username: admin"
echo "  Password: admin123"
echo ""
echo "Debug page (check this if still failing):"
echo "  http://localhost:8080/debug_login.php"
echo ""
