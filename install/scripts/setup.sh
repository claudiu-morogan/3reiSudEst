#!/bin/bash

echo "================================================="
echo "  3 Sud Est - Website Setup Script"
echo "================================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env from template..."
    cp .env.example .env
    echo "✓ .env file created"
    echo ""
    echo "⚠️  IMPORTANT: Edit .env with your database credentials!"
    echo ""
else
    echo "✓ .env file already exists"
fi

# Create upload directories if missing
echo "Creating upload directories..."
mkdir -p public/uploads/albums
mkdir -p public/uploads/news
mkdir -p public/uploads/gallery
mkdir -p logs
echo "✓ Upload directories created"

# Set permissions
echo "Setting permissions..."
chmod 755 public/uploads/albums
chmod 755 public/uploads/news
chmod 755 public/uploads/gallery
chmod 755 logs
echo "✓ Permissions set"

echo ""
echo "================================================="
echo "  Setup Complete!"
echo "================================================="
echo ""
echo "Next steps:"
echo "1. Edit .env with your database credentials"
echo "2. Create database: CREATE DATABASE 3sudest;"
echo "3. Import schema: mysql -u root -p 3sudest < sql/schema.sql"
echo "4. Start server: php -S localhost:8000"
echo "5. Visit: http://localhost:8000"
echo ""
echo "Default admin login:"
echo "  Username: admin"
echo "  Password: admin123"
echo ""
echo "⚠️  CHANGE THE PASSWORD IN PRODUCTION!"
echo ""
