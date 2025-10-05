<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/header.php';

$page = $_GET['page'] ?? 'home';
$allowed = ['home','about','members','news'];
if (!in_array($page, $allowed)) {
    $page = 'home';
}

include __DIR__ . "/pages/{$page}.php";

require_once __DIR__ . '/inc/footer.php';

?>
