<?php
/**
 * Login Debug Script
 * Visit: http://localhost:8080/debug_login.php
 * DO NOT USE IN PRODUCTION - DELETE AFTER TESTING
 */

define('APP_ROOT', __DIR__);
require_once APP_ROOT . '/app/config.php';
require_once APP_ROOT . '/app/Database.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Debug</title>
    <style>
        body {
            font-family: monospace;
            background: #1a1a1a;
            color: #00ff00;
            padding: 20px;
        }
        .success { color: #00ff00; }
        .error { color: #ff0000; }
        .warning { color: #ffaa00; }
        pre { background: #0a0a0a; padding: 10px; border: 1px solid #333; }
    </style>
</head>
<body>
    <h1>Login Debug Tool</h1>

    <?php
    // Test credentials
    $test_username = 'admin';
    $test_password = 'admin123';
    $correct_hash = '$2y$10$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu';

    echo "<h2>Step 1: Check Database Connection</h2>";
    try {
        $db = Database::getInstance();
        echo "<p class='success'>✓ Database connected successfully</p>";
    } catch (Exception $e) {
        echo "<p class='error'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }

    echo "<h2>Step 2: Check if users table exists</h2>";
    $tables = Database::query("SHOW TABLES LIKE 'users'");
    if (count($tables) > 0) {
        echo "<p class='success'>✓ users table exists</p>";
    } else {
        echo "<p class='error'>✗ users table NOT found!</p>";
        echo "<p>Run: docker-compose exec -T db mysql -u3sudest_user -psecure_password_123 3sudest < sql/schema.sql</p>";
        exit;
    }

    echo "<h2>Step 3: Check if admin user exists</h2>";
    $sql = "SELECT * FROM users WHERE username = ?";
    $user = Database::queryOne($sql, [$test_username]);
    
    if ($user) {
        echo "<p class='success'>✓ Admin user found</p>";
        echo "<pre>";
        echo "ID: " . htmlspecialchars($user['id']) . "\n";
        echo "Username: " . htmlspecialchars($user['username']) . "\n";
        echo "Email: " . htmlspecialchars($user['email']) . "\n";
        echo "Password Hash: " . htmlspecialchars($user['password_hash']) . "\n";
        echo "</pre>";
    } else {
        echo "<p class='error'>✗ Admin user NOT found!</p>";
        echo "<p>Run: docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest < sql/fix_admin_password.sql</p>";
        exit;
    }

    echo "<h2>Step 4: Verify Password Hash</h2>";
    if ($user['password_hash'] === $correct_hash) {
        echo "<p class='success'>✓ Password hash is CORRECT</p>";
    } else {
        echo "<p class='error'>✗ Password hash is WRONG!</p>";
        echo "<p>Expected: <code>$correct_hash</code></p>";
        echo "<p>Got: <code>" . htmlspecialchars($user['password_hash']) . "</code></p>";
        echo "<p class='warning'>FIX: Run this command:</p>";
        echo "<pre>docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -e \"UPDATE users SET password_hash = '\$2y\$10\$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' WHERE username = 'admin';\"</pre>";
    }

    echo "<h2>Step 5: Test password_verify()</h2>";
    $verify_result = password_verify($test_password, $user['password_hash']);
    if ($verify_result) {
        echo "<p class='success'>✓ password_verify('admin123', hash) = TRUE</p>";
    } else {
        echo "<p class='error'>✗ password_verify('admin123', hash) = FALSE</p>";
        echo "<p>This means the hash in database doesn't match 'admin123'</p>";
    }

    echo "<h2>Step 6: Generate New Hash for Comparison</h2>";
    $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
    echo "<p>Fresh hash for 'admin123':</p>";
    echo "<pre>$new_hash</pre>";
    echo "<p>Test new hash:</p>";
    if (password_verify($test_password, $new_hash)) {
        echo "<p class='success'>✓ New hash works!</p>";
    } else {
        echo "<p class='error'>✗ New hash failed (PHP issue?)</p>";
    }

    echo "<h2>Step 7: Test Login Flow</h2>";
    echo "<p>Simulating Auth::login() logic...</p>";
    
    // Simulate the login process
    if (!$user) {
        echo "<p class='error'>✗ User not found</p>";
    } elseif (!password_verify($test_password, $user['password_hash'])) {
        echo "<p class='error'>✗ Password verification FAILED</p>";
        echo "<p>This is why login fails!</p>";
    } else {
        echo "<p class='success'>✓ Login should succeed!</p>";
    }

    echo "<h2>Summary</h2>";
    if ($user && password_verify($test_password, $user['password_hash'])) {
        echo "<p class='success'>✓✓✓ Everything looks good! Login should work.</p>";
        echo "<p>If login still fails, check:</p>";
        echo "<ul>";
        echo "<li>Browser cookies/cache</li>";
        echo "<li>CSRF token in login form</li>";
        echo "<li>Session configuration</li>";
        echo "</ul>";
    } else {
        echo "<p class='error'>✗✗✗ Login will FAIL until password hash is fixed.</p>";
        echo "<h3>Quick Fix Command:</h3>";
        echo "<pre>docker-compose exec db mysql -u3sudest_user -psecure_password_123 3sudest -e \"UPDATE users SET password_hash = '\$2y\$10\$rL9gXLqj5pBkVlLmJH1uquLxKJ5q3fVX0xzQqLH5oj4xVIvFJQXZu' WHERE username = 'admin';\"</pre>";
    }
    ?>

    <hr>
    <p class='warning'>⚠️ DELETE THIS FILE AFTER DEBUGGING: debug_login.php</p>
</body>
</html>
