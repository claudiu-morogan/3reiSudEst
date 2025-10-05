
<?php if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); } ?>
<!doctype html>
<html lang="ro">
<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>3 Sud Est - Fan site</title>
        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/3reiSudEst/css/style.css" />
</head>
<body>
<header class="py-3" style="background: linear-gradient(90deg, rgba(255,107,107,0.2), rgba(124,58,237,0.2));">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="d-flex align-items-center text-white text-decoration-none" href="/3reiSudEst/">
            <img src="/3reiSudEst/assets/3se.png" alt="3 Sud Est" style="height:40px;" />
            <span class="ms-3 fs-5 fw-semibold">Fan site</span>
        </a>
        <nav class="d-none d-md-block">
            <ul class="nav">
                <li class="nav-item"><a class="nav-link text-white" href="/3reiSudEst/">Acasă</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/3reiSudEst/?page=about">Despre</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/3reiSudEst/?page=members">Membri</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/3reiSudEst/?page=news">Știri</a></li>
                <?php if (!empty($_SESSION['admin'])): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="/admin/index.php">Actualizare</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="d-md-none">
            <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav">Meniu</button>
        </div>
    </div>
    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileNavLabel">Meniu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="/3reiSudEst/">Acasă</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/3reiSudEst/?page=about">Despre</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/3reiSudEst/?page=members">Membri</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/3reiSudEst/?page=news">Știri</a></li>
                <?php if (!empty($_SESSION['admin'])): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="/autentificare">Actualizare</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>
<main class="container my-4">
