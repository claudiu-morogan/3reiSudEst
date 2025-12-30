<?php
/**
 * Configuration Loader
 * Loads environment variables from .env file and defines application constants
 */

// Prevent direct access
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Load environment variables
// Priority: Docker environment variables > .env file > defaults
$env = [];

// Check if running in Docker (environment variables set by docker-compose)
if (getenv('DB_HOST') !== false) {
    // Running in Docker, use environment variables
    $env = [
        'DB_HOST' => getenv('DB_HOST'),
        'DB_NAME' => getenv('DB_NAME'),
        'DB_USER' => getenv('DB_USER'),
        'DB_PASS' => getenv('DB_PASS'),
        'DB_CHARSET' => getenv('DB_CHARSET'),
        'APP_ENV' => getenv('APP_ENV'),
        'APP_URL' => getenv('APP_URL'),
        'BASE_PATH' => getenv('BASE_PATH'),
        'SESSION_LIFETIME' => getenv('SESSION_LIFETIME'),
        'CSRF_TOKEN_NAME' => getenv('CSRF_TOKEN_NAME'),
        'MAX_UPLOAD_SIZE' => getenv('MAX_UPLOAD_SIZE'),
        'ALLOWED_IMAGE_TYPES' => getenv('ALLOWED_IMAGE_TYPES'),
    ];
} else {
    // Not in Docker, load from .env file
    $envFile = APP_ROOT . '/.env';
    if (!file_exists($envFile)) {
        die('Error: .env file not found. Copy .env.example to .env and configure your settings.');
    }

    $env = parse_ini_file($envFile);
    if ($env === false) {
        die('Error: Failed to parse .env file.');
    }
}

// Define constants from environment
define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
define('DB_NAME', $env['DB_NAME'] ?? '3sudest');
define('DB_USER', $env['DB_USER'] ?? 'root');
define('DB_PASS', $env['DB_PASS'] ?? '');
define('DB_CHARSET', $env['DB_CHARSET'] ?? 'utf8mb4');

define('APP_ENV', $env['APP_ENV'] ?? 'production');
define('APP_URL', rtrim($env['APP_URL'] ?? '', '/'));
define('BASE_PATH', trim($env['BASE_PATH'] ?? '', '/'));

define('SESSION_LIFETIME', (int)($env['SESSION_LIFETIME'] ?? 1800));
define('CSRF_TOKEN_NAME', $env['CSRF_TOKEN_NAME'] ?? 'csrf_token');

define('MAX_UPLOAD_SIZE', (int)($env['MAX_UPLOAD_SIZE'] ?? 5242880)); // 5MB
define('ALLOWED_IMAGE_TYPES', explode(',', $env['ALLOWED_IMAGE_TYPES'] ?? 'image/jpeg,image/png,image/webp'));

// Error reporting based on environment
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', APP_ROOT . '/logs/php_errors.log');
}

// Session configuration
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Strict');
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', '1'); // HTTPS only
}

// Helper function to build URLs
function base_url(string $path = ''): string {
    $base = APP_URL;
    if (BASE_PATH) {
        $base .= '/' . BASE_PATH;
    }

    if ($path) {
        return $base . '/' . ltrim($path, '/');
    }

    return $base;
}

// Helper function for asset URLs (CSS, JS, images)
function asset(string $path): string {
    $path = ltrim($path, '/');

    // In Docker, document root is /var/www/html, so public/ is at root level
    // In manual setup with BASE_PATH, we need to include it
    if (BASE_PATH) {
        return base_url('public/' . $path);
    }

    // For Docker or when BASE_PATH is empty
    return APP_URL . '/public/' . $path;
}
