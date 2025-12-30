#!/bin/bash

echo "======================================"
echo "  Fix ALL Tables - Romanian Encoding"
echo "======================================"
echo ""
echo "Converting all database tables to UTF-8..."
echo ""

# Run the SQL fix script
docker exec -i 3sudest_db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_all_encoding.sql

if [ $? -eq 0 ]; then
    echo ""
    echo "======================================"
    echo "  SUCCESS!"
    echo "======================================"
    echo ""
    echo "All tables converted to UTF-8."
    echo ""
    echo "Test pages:"
    echo "  - Public: http://localhost:8080/concerts"
    echo "  - Admin: http://localhost:8080/admin/?page=concerts"
    echo ""
else
    echo ""
    echo "======================================"
    echo "  ERROR!"
    echo "======================================"
    echo ""
    echo "Failed to fix encoding."
    echo ""
fi
