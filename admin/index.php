<?php
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/app/config.php';
require_once APP_ROOT . '/app/Database.php';
require_once APP_ROOT . '/app/Auth.php';
require_once APP_ROOT . '/app/CSRF.php';

// Require authentication
Auth::require();

// Get requested page
$page = $_GET['page'] ?? 'dashboard';
$page = preg_replace('/[^a-z0-9\-]/', '', $page);

// Map routes to view files
$routes = [
    'dashboard' => 'dashboard.php',
    'biography' => 'biography.php',
    'discography' => 'discography.php',
    'concerts' => 'concerts.php',
    'news' => 'news.php',
    'gallery' => 'gallery.php',
];

$viewFile = $routes[$page] ?? $routes['dashboard'];
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - 3 Sud Est</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f7;
            color: #1d1d1f;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);
            color: #fff;
            padding: 2rem 0;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        .sidebar-header {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
        }

        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 1.5rem;
            right: 1.5rem;
            height: 1px;
            background: linear-gradient(90deg, #ff0064, transparent);
            opacity: 0.6;
        }

        .sidebar-header h1 {
            font-size: 1.375rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff 0%, #a0a0a0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-header p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .sidebar-nav {
            margin-top: 1.5rem;
            padding: 0 0.75rem;
        }

        .sidebar-nav a {
            display: block;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 10px;
            margin-bottom: 0.375rem;
            font-weight: 500;
            font-size: 0.9375rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 10px;
        }

        .sidebar-nav a::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, #ff0064 0%, #c9004d 100%);
            border-radius: 0 2px 2px 0;
            transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-nav a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(255, 0, 100, 0.15);
        }

        .sidebar-nav a:hover::before {
            opacity: 1;
        }

        .sidebar-nav a:hover::after {
            height: 60%;
        }

        .sidebar-nav a:active {
            transform: translateX(2px) scale(0.98);
        }

        .sidebar-nav a.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(255, 0, 100, 0.25) 0%, rgba(255, 0, 100, 0.15) 100%);
            box-shadow: 0 0 25px rgba(255, 0, 100, 0.4),
                        0 4px 15px rgba(255, 0, 100, 0.3),
                        inset 0 1px 0 rgba(255, 255, 255, 0.15),
                        inset 0 -1px 0 rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 0, 100, 0.3);
            font-weight: 600;
        }

        .sidebar-nav a.active::before {
            opacity: 1;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.08) 100%);
        }

        .sidebar-nav a.active::after {
            height: 100%;
            width: 4px;
            box-shadow: 0 0 10px rgba(255, 0, 100, 0.8);
        }
        
        .main-content {
            flex: 1;
            padding: 2rem;
        }
        
        .top-bar {
            background: #fff;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .logout-btn {
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: opacity 0.2s;
        }
        
        .logout-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>Admin Panel</h1>
                <p>Bine ai venit, <?= htmlspecialchars(Auth::username()) ?></p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="<?= base_url('admin/') ?>" class="<?= ($page === 'dashboard') ? 'active' : '' ?>">Dashboard</a>
                <a href="<?= base_url('admin/?page=biography') ?>" class="<?= ($page === 'biography') ? 'active' : '' ?>">Biografie</a>
                <a href="<?= base_url('admin/?page=discography') ?>" class="<?= ($page === 'discography') ? 'active' : '' ?>">Discografie</a>
                <a href="<?= base_url('admin/?page=concerts') ?>" class="<?= ($page === 'concerts') ? 'active' : '' ?>">Concerte</a>
                <a href="<?= base_url('admin/?page=news') ?>" class="<?= ($page === 'news') ? 'active' : '' ?>">Știri</a>
                <a href="<?= base_url('admin/?page=gallery') ?>" class="<?= ($page === 'gallery') ? 'active' : '' ?>">Galerie</a>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="top-bar">
                <h2>3 Sud Est - Content Management</h2>
                <a href="<?= base_url('admin/logout.php') ?>" class="logout-btn">Logout</a>
            </div>
            
            <div class="content-area">
                <?php 
                $viewPath = __DIR__ . '/views/' . $viewFile;
                if (file_exists($viewPath)) {
                    include $viewPath;
                } else {
                    echo '<p>View not found.</p>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>
