<?php
/**
 * Authentication Handler
 * Manages user sessions, login, logout, and access control
 */

class Auth {
    /**
     * Start session if not already started
     */
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();

            // Check session timeout
            if (isset($_SESSION['LAST_ACTIVITY']) &&
                (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_LIFETIME)) {
                self::logout();
            }
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }

    /**
     * Attempt to log in user
     */
    public static function login(string $username, string $password): bool {
        self::init();

        // Fetch user from database
        $sql = "SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1";
        $user = Database::queryOne($sql, [$username]);

        if (!$user) {
            return false;
        }

        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        // Regenerate session ID to prevent fixation
        session_regenerate_id(true);

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['authenticated'] = true;

        // Update last login
        $updateSql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        Database::execute($updateSql, [$user['id']]);

        return true;
    }

    /**
     * Log out current user
     */
    public static function logout(): void {
        self::init();

        // Clear session data
        $_SESSION = [];

        // Destroy session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destroy session
        session_destroy();
    }

    /**
     * Check if user is authenticated
     */
    public static function check(): bool {
        self::init();
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
    }

    /**
     * Require authentication (redirect to login if not authenticated)
     */
    public static function require(): void {
        if (!self::check()) {
            header('Location: ' . base_url('admin/login.php'));
            exit;
        }
    }

    /**
     * Get current user ID
     */
    public static function id(): ?int {
        self::init();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current username
     */
    public static function username(): ?string {
        self::init();
        return $_SESSION['username'] ?? null;
    }
}
