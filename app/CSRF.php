<?php
/**
 * CSRF Protection
 * Generate and validate CSRF tokens for form submissions
 */

class CSRF {
    /**
     * Generate a CSRF token and store in session
     */
    public static function generateToken(): string {
        Auth::init(); // Ensure session is started

        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }

        return $_SESSION[CSRF_TOKEN_NAME];
    }

    /**
     * Validate submitted CSRF token
     */
    public static function validateToken(string $token): bool {
        Auth::init();

        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            return false;
        }

        return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    /**
     * Output hidden input field with CSRF token
     */
    public static function field(): string {
        $token = self::generateToken();
        return sprintf(
            '<input type="hidden" name="%s" value="%s">',
            htmlspecialchars(CSRF_TOKEN_NAME, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Verify token from POST request or die
     */
    public static function verify(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST[CSRF_TOKEN_NAME] ?? '';

            if (!self::validateToken($token)) {
                http_response_code(403);
                die('CSRF token validation failed.');
            }
        }
    }
}
