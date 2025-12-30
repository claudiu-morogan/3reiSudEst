<section class="gallery-section">
    <div class="container">
        <h1 class="section-title reveal reveal-up">Galerie Foto</h1>
        <p class="section-subtitle reveal reveal-up">Momente memorabile</p>

        <div class="gallery-grid">
            <?php if (!empty($pageData['gallery'])): ?>
                <?php foreach ($pageData['gallery'] as $index => $item): ?>
                    <div class="gallery-item reveal reveal-scale image-zoom">
                        <img src="<?= base_url($item['image_path']) ?>" 
                             alt="<?= htmlspecialchars($item['title'] ?? 'Galerie 3 Sud Est') ?>"
                             loading="lazy">
                        
                        <?php if ($item['caption'] || $item['title']): ?>
                            <div class="gallery-caption">
                                <?= htmlspecialchars($item['caption'] ?: $item['title']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-state">Galeria va fi populată în curând...</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--space-md);
    margin-top: var(--space-2xl);
}

.gallery-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: var(--border-radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    cursor: pointer;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: var(--space-sm);
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: #fff;
    font-size: var(--font-size-sm);
    opacity: 0;
    transition: opacity var(--transition-base);
}

.gallery-item:hover .gallery-caption {
    opacity: 1;
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: var(--space-sm);
    }
}
</style>
