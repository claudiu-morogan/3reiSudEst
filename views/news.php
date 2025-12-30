<section class="news-section">
    <div class="container">
        <h1 class="section-title reveal reveal-up">Știri</h1>
        <p class="section-subtitle reveal reveal-up">Ultimele noutăți despre 3 Sud Est</p>

        <div class="news-grid">
            <?php if (!empty($pageData['news'])): ?>
                <?php foreach ($pageData['news'] as $index => $article): ?>
                    <article class="news-card reveal reveal-up card-hover image-zoom">
                        <?php if ($article['featured_image']): ?>
                            <div class="news-image">
                                <img src="<?= base_url($article['featured_image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="news-content">
                            <time class="news-date"><?= date('d F Y', strtotime($article['publish_date'])) ?></time>
                            <h2 class="news-title"><?= htmlspecialchars($article['title']) ?></h2>
                            
                            <?php if ($article['excerpt']): ?>
                                <p class="news-excerpt"><?= htmlspecialchars($article['excerpt']) ?></p>
                            <?php endif; ?>
                            
                            <a href="<?= base_url('news/' . $article['slug']) ?>" class="btn-read-more btn-lift">
                                Citește tot
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-state">Nu există știri disponibile momentan.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--space-xl);
    margin-top: var(--space-2xl);
}

.news-card {
    background: var(--color-deep-blue);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.news-image {
    aspect-ratio: 16 / 9;
    overflow: hidden;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-content {
    padding: var(--space-md);
}

.news-date {
    color: var(--color-gold);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
}

.news-title {
    font-family: var(--font-display);
    font-size: var(--font-size-xl);
    margin: var(--space-sm) 0;
    color: var(--color-text-primary);
}

.news-excerpt {
    color: var(--color-text-secondary);
    margin: var(--space-sm) 0 var(--space-md);
    line-height: 1.6;
}

.btn-read-more {
    display: inline-block;
    padding: var(--space-xs) var(--space-md);
    background: var(--color-electric-blue);
    border-radius: var(--border-radius-sm);
    color: #fff;
    text-decoration: none;
    font-weight: var(--font-weight-medium);
}

@media (max-width: 768px) {
    .news-grid {
        grid-template-columns: 1fr;
    }
}
</style>
