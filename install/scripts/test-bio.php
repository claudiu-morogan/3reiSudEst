<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', __DIR__);
require_once APP_ROOT . '/app/config.php';
require_once APP_ROOT . '/app/Database.php';

echo "Step 1: Config loaded<br>";

require_once APP_ROOT . '/app/models/Biography.php';
echo "Step 2: Biography model loaded<br>";

try {
    $sections = Biography::getAll();
    echo "Step 3: Data fetched. Count: " . count($sections) . "<br>";
    echo "<pre>";
    print_r($sections);
    echo "</pre>";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
