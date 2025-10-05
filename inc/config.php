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

?>
