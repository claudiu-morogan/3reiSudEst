<?php
// Simple dashboard with stats
$albumCount = Database::queryOne("SELECT COUNT(*) as count FROM albums")['count'] ?? 0;
$newsCount = Database::queryOne("SELECT COUNT(*) as count FROM news")['count'] ?? 0;
$galleryCount = Database::queryOne("SELECT COUNT(*) as count FROM gallery_items")['count'] ?? 0;
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card h3 {
        font-size: 0.875rem;
        color: #6c6c6c;
        margin-bottom: 0.5rem;
    }
    
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1d1d1f;
    }
    
    .quick-links {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .quick-links h3 {
        margin-bottom: 1rem;
    }
    
    .quick-links a {
        display: inline-block;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem 1rem;
        background: #007aff;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.875rem;
    }
    
    .quick-links a:hover {
        background: #0051d5;
    }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Albume</h3>
        <div class="stat-value"><?= $albumCount ?></div>
    </div>
    
    <div class="stat-card">
        <h3>Știri</h3>
        <div class="stat-value"><?= $newsCount ?></div>
    </div>
    
    <div class="stat-card">
        <h3>Galerie</h3>
        <div class="stat-value"><?= $galleryCount ?></div>
    </div>
</div>

<div class="quick-links">
    <h3>Acțiuni rapide</h3>
    <a href="<?= base_url('admin/?page=discography') ?>">Adaugă album</a>
    <a href="<?= base_url('admin/?page=news') ?>">Adaugă știre</a>
    <a href="<?= base_url('admin/?page=gallery') ?>">Adaugă imagine</a>
    <a href="<?= base_url() ?>" target="_blank">Vezi site-ul public</a>
</div>
