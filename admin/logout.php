<?php
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/app/config.php';
require_once APP_ROOT . '/app/Auth.php';

Auth::logout();

header('Location: ' . base_url('admin/login.php'));
exit;
