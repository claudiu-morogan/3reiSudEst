<?php
session_start();
function csrf_token() {
    if (empty($_SESSION['__csrf_token'])) {
        $_SESSION['__csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['__csrf_token'];
}

function csrf_field() {
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="__csrf" value="' . $t . '" />';
}

function csrf_check() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sent = $_POST['__csrf'] ?? '';
        return hash_equals($_SESSION['__csrf_token'] ?? '', $sent);
    }
    return true;
}
