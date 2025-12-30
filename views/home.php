<section class="hero-section">
    <div class="hero-background will-animate" id="hero-bg">
        <!-- Replace with actual band photo -->
        <img src="<?= asset('uploads/hero-bg.jpg') ?>" alt="3 Sud Est" onerror="this.style.display='none'">
    </div>
    
    <div class="hero-content stagger-children">
        <h1 class="hero-title">3 Sud Est</h1>
        <p class="hero-subtitle">Muzică din suflet pentru suflete</p>
        <div class="hero-actions">
            <a href="<?= base_url('discography') ?>" class="btn-primary btn-lift">Descoperă muzica</a>
        </div>
    </div>
</section>

<section class="about-preview">
    <div class="container">
        <div class="reveal reveal-up">
            <h2>Povestea noastră</h2>
            <p>De peste două decenii, 3 Sud Est aduce bucurie în inimile românilor cu melodii care au devenit imnuri generaționale.</p>
        </div>
    </div>
</section>

<style>
.btn-primary {
    display: inline-block;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, var(--color-electric-blue) 0%, var(--color-neon-purple) 100%);
    color: #fff;
    font-weight: var(--font-weight-bold);
    border-radius: var(--border-radius-md);
    font-size: var(--font-size-lg);
}

.about-preview {
    padding: var(--space-3xl) 0;
    text-align: center;
}

.about-preview h2 {
    font-family: var(--font-display);
    font-size: var(--font-size-2xl);
    margin-bottom: var(--space-md);
    color: var(--color-gold);
}

.about-preview p {
    max-width: 600px;
    margin: 0 auto;
    color: var(--color-text-secondary);
    font-size: var(--font-size-lg);
}
</style>

<script>
// Initialize parallax for hero background
if (window.animationController) {
    window.animationController.initParallax('#hero-bg');
}
</script>
