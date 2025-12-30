<section class="biography-section">
    <div class="container">
        <h1 class="section-title reveal reveal-up">Povestea Noastră</h1>
        <p class="section-subtitle reveal reveal-up">Călătoria unei trupe legendare</p>

        <div class="biography-content">
            <?php if (!empty($pageData['sections'])): ?>
                <?php foreach ($pageData['sections'] as $index => $section): ?>
                    <article class="bio-section reveal reveal-up">
                        <h2 class="bio-title"><?= htmlspecialchars($section['title']) ?></h2>
                        <div class="bio-text">
                            <?= $section['content'] ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-state">Conținut în curs de actualizare...</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.biography-content {
    max-width: 800px;
    margin: var(--space-3xl) auto 0;
}

.bio-section {
    margin-bottom: var(--space-3xl);
}

.bio-title {
    font-family: var(--font-display);
    font-size: var(--font-size-2xl);
    color: var(--color-gold);
    margin-bottom: var(--space-md);
}

.bio-text {
    color: var(--color-text-secondary);
    font-size: var(--font-size-lg);
    line-height: 1.8;
}

.bio-text p {
    margin-bottom: var(--space-md);
}
</style>
