<?php
/**
 * Configuration Test Script
 * Visit: http://localhost:8080/public/test-config.php
 */

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/app/config.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Config Test</title>
    <style>
        body {
            font-family: monospace;
            background: #1a1a1a;
            color: #00ff00;
            padding: 20px;
        }
        .success { color: #00ff00; }
        .error { color: #ff0000; }
        .info { color: #00aaff; }
        table {
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background: #2a2a2a;
        }
    </style>
</head>
<body>
    <h1>3 Sud Est - Configuration Test</h1>

    <h2>Environment Detection</h2>
    <table>
        <tr>
            <th>Check</th>
            <th>Result</th>
        </tr>
        <tr>
            <td>Running in Docker?</td>
            <td class="<?= getenv('DB_HOST') !== false ? 'success' : 'error' ?>">
                <?= getenv('DB_HOST') !== false ? 'YES (using env vars)' : 'NO (using .env file)' ?>
            </td>
        </tr>
        <tr>
            <td>Environment</td>
            <td class="info"><?= APP_ENV ?></td>
        </tr>
    </table>

    <h2>URL Configuration</h2>
    <table>
        <tr>
            <th>Constant</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>APP_URL</td>
            <td class="info"><?= APP_URL ?></td>
        </tr>
        <tr>
            <td>BASE_PATH</td>
            <td class="info"><?= BASE_PATH ?: '(empty)' ?></td>
        </tr>
    </table>

    <h2>Helper Function Tests</h2>
    <table>
        <tr>
            <th>Function</th>
            <th>Input</th>
            <th>Output</th>
        </tr>
        <tr>
            <td>base_url()</td>
            <td>''</td>
            <td class="info"><?= base_url() ?></td>
        </tr>
        <tr>
            <td>base_url()</td>
            <td>'admin/login.php'</td>
            <td class="info"><?= base_url('admin/login.php') ?></td>
        </tr>
        <tr>
            <td>asset()</td>
            <td>'css/main.css'</td>
            <td class="info"><?= asset('css/main.css') ?></td>
        </tr>
        <tr>
            <td>asset()</td>
            <td>'js/app.js'</td>
            <td class="info"><?= asset('js/app.js') ?></td>
        </tr>
    </table>

    <h2>File Existence Check</h2>
    <table>
        <tr>
            <th>File</th>
            <th>Exists?</th>
        </tr>
        <?php
        $files = [
            'public/css/variables.css',
            'public/css/animations.css',
            'public/css/main.css',
            'public/js/animations.js',
            'public/js/app.js',
        ];
        foreach ($files as $file):
            $path = APP_ROOT . '/' . $file;
            $exists = file_exists($path);
        ?>
        <tr>
            <td><?= htmlspecialchars($file) ?></td>
            <td class="<?= $exists ? 'success' : 'error' ?>">
                <?= $exists ? 'YES' : 'NO' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Database Configuration</h2>
    <table>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>DB_HOST</td>
            <td class="info"><?= DB_HOST ?></td>
        </tr>
        <tr>
            <td>DB_NAME</td>
            <td class="info"><?= DB_NAME ?></td>
        </tr>
        <tr>
            <td>DB_USER</td>
            <td class="info"><?= DB_USER ?></td>
        </tr>
        <tr>
            <td>DB Connection</td>
            <td class="<?php
                try {
                    $pdo = Database::getInstance();
                    echo 'success">CONNECTED';
                } catch (Exception $e) {
                    echo 'error">FAILED: ' . htmlspecialchars($e->getMessage());
                }
            ?>
            </td>
        </tr>
    </table>

    <h2>Expected Asset URLs in HTML</h2>
    <pre class="info">
&lt;link rel="stylesheet" href="<?= asset('css/variables.css') ?>"&gt;
&lt;link rel="stylesheet" href="<?= asset('css/animations.css') ?>"&gt;
&lt;link rel="stylesheet" href="<?= asset('css/main.css') ?>"&gt;
&lt;script src="<?= asset('js/animations.js') ?>"&gt;&lt;/script&gt;
&lt;script src="<?= asset('js/app.js') ?>"&gt;&lt;/script&gt;
    </pre>

    <p class="success">If all checks pass, your configuration is correct!</p>
    <p class="info">After fixing issues, delete this file for security: public/test-config.php</p>
</body>
</html>
