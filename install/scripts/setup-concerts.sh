#!/bin/bash

echo "======================================"
echo "  Setup Concerts + Fix Encoding"
echo "======================================"
echo ""
echo "This will:"
echo "  1. Create the concerts table"
echo "  2. Add sample concert data"
echo "  3. Fix encoding for all tables"
echo ""

# Run from WSL - navigate to project directory first
cd /mnt/c/Users/claud/Desktop/3reiSudEst

echo "Running setup..."
docker exec -i 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest < sql/setup_concerts_and_fix_encoding.sql

if [ $? -eq 0 ]; then
    echo ""
    echo "======================================"
    echo "  SUCCESS!"
    echo "======================================"
    echo ""
    echo "✓ Concerts table created"
    echo "✓ Sample data added"
    echo "✓ All tables converted to UTF-8"
    echo ""
    echo "Test the concerts page:"
    echo "  http://localhost:8080/concerts"
    echo ""
    echo "Admin panel:"
    echo "  http://localhost:8080/admin/?page=concerts"
    echo ""
else
    echo ""
    echo "======================================"
    echo "  ERROR!"
    echo "======================================"
    echo ""
    echo "Setup failed. Check error messages above."
    echo ""
fi
