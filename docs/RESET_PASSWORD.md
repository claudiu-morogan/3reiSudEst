# Admin Password Reset Guide

## Problem: Admin Login Not Working

Username: `admin`
Password: `admin123`

If this doesn't work, the password hash in the database needs to be updated.

---

## Quick Fix (Docker)

### Option 1: Run SQL Script (Recommended)

```bash
# Update password in database
docker-compose exec -T db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_admin_password.sql

# Verify it worked
docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -e "SELECT username, email FROM users WHERE username='admin';"
```

### Option 2: Direct SQL Command

```bash
docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -e "UPDATE users SET password_hash = '\$2y\$10\$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' WHERE username = 'admin';"
```

### Option 3: phpMyAdmin (Visual)

1. Go to http://localhost:8081
2. Login with root / root_password_123
3. Select database: `3sudest`
4. Click on `users` table
5. Edit the `admin` row
6. Replace `password_hash` with:
   ```
   $2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu
   ```
7. Save

### Option 4: Reimport Schema (Nuclear Option)

**WARNING**: This deletes all data and starts fresh!

```bash
docker-compose exec -T db mysql -u3sudest_user -psecure_password_123 3sudest < sql/schema.sql
```

---

## Quick Fix (Manual Setup)

### Option 1: Run SQL Script

```bash
mysql -u root -p 3sudest < sql/fix_admin_password.sql
```

### Option 2: MySQL Command Line

```bash
mysql -u root -p 3sudest
```

Then run:
```sql
UPDATE users 
SET password_hash = '$2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu'
WHERE username = 'admin';

SELECT username, email FROM users WHERE username = 'admin';
```

---

## Create Custom Admin Password

### Step 1: Generate Hash

Create a file `generate_hash.php`:

```php
<?php
$password = 'your_new_password_here';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: $password\n";
echo "Hash: $hash\n";
```

Run it:
```bash
# Docker
docker-compose exec web php /var/www/html/generate_hash.php

# Manual
php generate_hash.php
```

### Step 2: Update Database

Copy the generated hash and run:

```sql
UPDATE users 
SET password_hash = 'YOUR_GENERATED_HASH_HERE'
WHERE username = 'admin';
```

---

## Verify Password Works

### Test Login

1. Visit: http://localhost:8080/admin/login.php
2. Username: `admin`
3. Password: `admin123` (or your custom password)
4. Click "Autentificare"

### Check Session

If login succeeds, you should be redirected to:
```
http://localhost:8080/admin/
```

### Debug Login Issues

Add temporary debug to `admin/login.php`:

```php
// After the login attempt
if (Auth::login($username, $password)) {
    // Success
} else {
    // Add this debug
    error_log("Login failed for user: $username");
    
    // Check if user exists
    $user = Database::queryOne("SELECT * FROM users WHERE username = ?", [$username]);
    error_log("User found: " . ($user ? 'YES' : 'NO'));
    
    if ($user) {
        error_log("Hash in DB: " . $user['password_hash']);
        error_log("Password verify: " . (password_verify($password, $user['password_hash']) ? 'MATCH' : 'NO MATCH'));
    }
}
```

Then check logs:
```bash
# Docker
docker-compose logs -f web

# Manual
tail -f logs/php_errors.log
```

---

## Password Hash Reference

The correct hash for `admin123` is:
```
$2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu
```

This was generated with:
```php
password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 10])
```

---

## Common Issues

### 1. User Doesn't Exist

Check if user was created:
```sql
SELECT * FROM users;
```

If empty, run:
```sql
INSERT INTO users (username, password_hash, email) VALUES
('admin', '$2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu', 'admin@3sudest.ro');
```

### 2. Database Not Initialized

Check if tables exist:
```sql
SHOW TABLES;
```

If empty, reimport schema:
```bash
# Docker
docker-compose exec -T db mysql -u3sudest_user -psecure_password_123 3sudest < sql/schema.sql

# Manual
mysql -u root -p 3sudest < sql/schema.sql
```

### 3. Wrong Database Credentials

Verify connection in `test-config.php`:
```
http://localhost:8080/public/test-config.php
```

### 4. CSRF Token Issues

Clear browser cookies and try again.

---

## Create Additional Admin Users

```sql
-- Generate hash first with password_hash()
INSERT INTO users (username, password_hash, email) VALUES
('john', '$2y$10$...your_hash_here...', 'john@3sudest.ro');
```

---

## Security Best Practices

1. **Change default password immediately** after first login
2. **Use strong passwords** (12+ characters, mixed case, numbers, symbols)
3. **Don't commit** password hashes to version control
4. **Rotate passwords** every 3-6 months
5. **Delete test accounts** before production

---

## Quick Test Command

Test if password is correct:

```bash
# Docker
docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -e "SELECT username, CASE WHEN password_hash = '\$2y\$10\$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' THEN 'CORRECT' ELSE 'WRONG' END as password_status FROM users WHERE username='admin';"
```

Expected output:
```
+----------+-----------------+
| username | password_status |
+----------+-----------------+
| admin    | CORRECT         |
+----------+-----------------+
```

---

**After fixing, login should work with: admin / admin123**
