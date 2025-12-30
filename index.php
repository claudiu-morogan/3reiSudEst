<?php
/**
 * Public Site Entry Point
 * Routes all public page requests
 */

define('APP_ROOT', __DIR__);
require_once APP_ROOT . '/app/config.php';
require_once APP_ROOT . '/app/Database.php';
require_once APP_ROOT . '/app/Auth.php';
require_once APP_ROOT . '/app/models/Album.php';
require_once APP_ROOT . '/app/models/News.php';
require_once APP_ROOT . '/app/models/Gallery.php';
require_once APP_ROOT . '/app/models/Biography.php';
require_once APP_ROOT . '/app/models/Concert.php';

// Get requested page from URL
$page = $_GET['url'] ?? 'home';
$page = preg_replace('/[^a-z0-9\-\/]/', '', $page); // Sanitize

// Map routes to view files
$routes = [
    'home' => 'home.php',
    'biography' => 'biography.php',
    'discography' => 'discography.php',
    'concerts' => 'concerts.php',
    'news' => 'news.php',
    'gallery' => 'gallery.php',
];

// Default to home if route not found
$viewFile = $routes[$page] ?? $routes['home'];
$viewPath = APP_ROOT . '/views/' . $viewFile;

if (!file_exists($viewPath)) {
    http_response_code(404);
    die('Page not found');
}

// Load data for specific pages
$pageData = [];

switch ($page) {
    case 'discography':
        $pageData['albums'] = Album::getAll();
        break;
    case 'concerts':
        $pageData['upcoming'] = Concert::getUpcoming();
        $pageData['past'] = Concert::getPast(10);
        break;
    case 'news':
        $pageData['news'] = News::getAll();
        break;
    case 'gallery':
        $pageData['gallery'] = Gallery::getAll();
        break;
    case 'biography':
        $pageData['sections'] = Biography::getAll();
        break;
}

// Include layout
include APP_ROOT . '/views/layout/header.php';
include $viewPath;
include APP_ROOT . '/views/layout/footer.php';
