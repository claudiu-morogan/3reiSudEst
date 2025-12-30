<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3 Sud Est - Oficial</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700;900&family=Inter:wght@400;500;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= asset('css/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/animations.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body>
    <nav class="main-nav">
        <div class="container">
            <a href="<?= base_url() ?>" class="logo">3 Sud Est</a>

            <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="nav-links">
                <li><a href="<?= base_url() ?>">Acasă</a></li>
                <li><a href="<?= base_url('biography') ?>">Biografie</a></li>
                <li><a href="<?= base_url('discography') ?>">Discografie</a></li>
                <li><a href="<?= base_url('concerts') ?>">Concerte</a></li>
                <li><a href="<?= base_url('news') ?>">Știri</a></li>
                <li><a href="<?= base_url('gallery') ?>">Galerie</a></li>
            </ul>
        </div>
    </nav>
    
    <main class="main-content">
