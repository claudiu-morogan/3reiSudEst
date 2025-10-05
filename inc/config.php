<?php
// Development config - update before production
// Load .env file if present (simple parser) so credentials are not committed
$envFile = __DIR__ . '/../.env';
$env = [];
if (file_exists($envFile)) {
	$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		$line = trim($line);
		if ($line === '' || strpos($line, '#') === 0) continue;
		if (strpos($line, '=') === false) continue;
		list($k,$v) = explode('=', $line, 2);
		$k = trim($k); $v = trim($v);
		$v = trim($v, "\"'");
		$env[$k] = $v;
	}
}

define('DB_HOST', $env['DB_HOST'] ?? '127.0.0.1');
define('DB_NAME', $env['DB_NAME'] ?? '3reisudest');
define('DB_USER', $env['DB_USER'] ?? 'root');
define('DB_PASS', $env['DB_PASS'] ?? '');

// Project root
define('ROOT', __DIR__ . '/..');

// Optional base URL from .env (e.g. http://localhost/3reiSudEst)
define('BASE_URL', rtrim($env['BASE_URL'] ?? '', '/'));

/**
 * Return the base URL for the application, e.g. "http://localhost/3reiSudEst"
 */
function base_url(): string
{
	// If BASE_URL is set in .env prefer it
	if (!empty(BASE_URL)) return BASE_URL;
	$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
	$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
	$scheme = $isHttps ? 'https' : 'http';
	// SCRIPT_NAME is something like /3reiSudEst/admin/upload.php -> dirname twice gives /3reiSudEst
	$script = $_SERVER['SCRIPT_NAME'] ?? '';
	$base = '';
	if ($script) {
		$base = dirname(dirname($script));
		if ($base === '\\' || $base === '.') $base = '';
	}
	$base = rtrim($base, '/\\');
	return $scheme . '://' . $host . ($base ? '/' . ltrim($base, '/') : '');
}

?>
