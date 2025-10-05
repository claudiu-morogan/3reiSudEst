<?php
// CLI script to create an admin user
// Usage: php scripts/create_admin.php username password
if (PHP_SAPI !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}
$argv = $_SERVER['argv'];
if (count($argv) < 3) {
    echo "Usage: php scripts/create_admin.php username password\n";
    exit(1);
}
$user = $argv[1];
$pass = $argv[2];
require __DIR__ . '/../inc/config.php';
require __DIR__ . '/../inc/db.php';
$existing = findUserByUsername($user);
if ($existing) {
    echo "User already exists: $user\n";
    exit(1);
}
$hash = password_hash($pass, PASSWORD_DEFAULT);
if (createUser($user, $hash)) {
    echo "User created: $user\n";
    exit(0);
} else {
    echo "Failed to create user.\n";
    exit(1);
}
