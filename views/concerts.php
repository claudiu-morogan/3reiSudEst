<?php
/**
 * Public Concerts Page
 * Displays upcoming and past concerts
 */

$upcomingConcerts = $pageData['upcoming'] ?? [];
$pastConcerts = $pageData['past'] ?? [];
?>

<style>
    .concerts-hero {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        padding: var(--space-3xl) var(--space-lg);
        text-align: center;
        margin-bottom: var(--space-2xl);
    }

    .concerts-hero h1 {
        font-family: var(--font-display);
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        color: var(--color-text-inverse);
        margin-bottom: var(--space-md);
        text-transform: uppercase;
        letter-spacing: -0.02em;
    }

    .concerts-hero p {
        font-size: 1.25rem;
        color: var(--color-text-inverse);
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .concerts-section {
        margin-bottom: var(--space-3xl);
    }

    .section-title {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 700;
        color: var(--color-text-primary);
        margin-bottom: var(--space-xl);
        text-align: center;
    }

    .concerts-grid {
        display: grid;
        gap: var(--space-xl);
        max-width: 900px;
        margin: 0 auto;
    }

    .concert-card {
        background: var(--color-surface);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: transform var(--transition-base), box-shadow var(--transition-base);
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: var(--space-lg);
    }

    .concert-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }

    .concert-date-box {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        padding: var(--space-lg);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--color-text-inverse);
    }

    .concert-month {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        opacity: 0.9;
        margin-bottom: 0.25rem;
    }

    .concert-day {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .concert-year {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .concert-time {
        font-size: 1.125rem;
        font-weight: 600;
        margin-top: var(--space-sm);
        padding-top: var(--space-sm);
        border-top: 1px solid rgba(255, 255, 255, 0.3);
    }

    .concert-info {
        padding: var(--space-lg);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .concert-title {
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-text-primary);
        margin-bottom: var(--space-sm);
    }

    .concert-location {
        color: var(--color-text-secondary);
        margin-bottom: var(--space-xs);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .concert-location::before {
        content: "📍";
    }

    .concert-venue {
        color: var(--color-text-tertiary);
        font-size: 0.9375rem;
        margin-bottom: var(--space-md);
    }

    .concert-description {
        color: var(--color-text-secondary);
        line-height: 1.6;
        margin-bottom: var(--space-md);
    }

    .concert-meta {
        display: flex;
        gap: var(--space-md);
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: var(--space-md);
    }

    .concert-price {
        background: var(--color-accent);
        color: var(--color-text-inverse);
        padding: 0.375rem 0.875rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .concert-status {
        padding: 0.375rem 0.875rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .concert-status.sold-out {
        background: #fbbf24;
        color: #78350f;
    }

    .concert-status.cancelled {
        background: #fca5a5;
        color: #7f1d1d;
    }

    .concert-actions {
        display: flex;
        gap: var(--space-md);
        flex-wrap: wrap;
    }

    .concert-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        color: var(--color-text-inverse);
        text-decoration: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        transition: transform var(--transition-base), box-shadow var(--transition-base);
    }

    .concert-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .concert-btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .past-concert-card {
        opacity: 0.7;
    }

    .past-concert-card .concert-date-box {
        background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
    }

    .no-concerts {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--color-text-tertiary);
    }

    .no-concerts-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
    }

    @media (max-width: 768px) {
        .concert-card {
            grid-template-columns: 1fr;
        }

        .concert-date-box {
            padding: var(--space-md);
        }

        .concert-day {
            font-size: 2.5rem;
        }

        .concerts-hero h1 {
            font-size: 2rem;
        }

        .concert-actions {
            flex-direction: column;
        }

        .concert-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="concerts-hero">
    <h1 data-animate="fade-up">Concerte</h1>
    <p data-animate="fade-up" data-animate-delay="100">
        Urmărește calendarul nostru de evenimente și nu rata niciun concert!
    </p>
</div>

<div class="container">
    <!-- Upcoming Concerts -->
    <?php if (!empty($upcomingConcerts)): ?>
        <section class="concerts-section">
            <h2 class="section-title" data-animate="fade-up">Concerte Viitoare</h2>
            <div class="concerts-grid">
                <?php foreach ($upcomingConcerts as $concert): ?>
                    <?php
                    $dateObj = new DateTime($concert['event_date']);
                    $day = $dateObj->format('j');
                    $month = $dateObj->format('F');
                    $year = $dateObj->format('Y');

                    $romanianMonths = [
                        'January' => 'Ian', 'February' => 'Feb', 'March' => 'Mar',
                        'April' => 'Apr', 'May' => 'Mai', 'June' => 'Iun',
                        'July' => 'Iul', 'August' => 'Aug', 'September' => 'Sept',
                        'October' => 'Oct', 'November' => 'Nov', 'December' => 'Dec'
                    ];
                    $month = $romanianMonths[$month] ?? $month;
                    ?>
                    <div class="concert-card" data-animate="fade-up">
                        <div class="concert-date-box">
                            <div class="concert-month"><?= $month ?></div>
                            <div class="concert-day"><?= $day ?></div>
                            <div class="concert-year"><?= $year ?></div>
                            <?php if (!empty($concert['event_time'])): ?>
                                <div class="concert-time">
                                    <?= Concert::formatTime($concert['event_time']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="concert-info">
                            <div>
                                <h3 class="concert-title"><?= htmlspecialchars($concert['title']) ?></h3>

                                <div class="concert-location">
                                    <span>
                                        <?= htmlspecialchars($concert['city']) ?>
                                        <?php if ($concert['country'] !== 'România'): ?>
                                            , <?= htmlspecialchars($concert['country']) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="concert-venue">
                                    <?= htmlspecialchars($concert['venue']) ?>
                                </div>

                                <?php if (!empty($concert['description'])): ?>
                                    <p class="concert-description">
                                        <?= nl2br(htmlspecialchars($concert['description'])) ?>
                                    </p>
                                <?php endif; ?>

                                <div class="concert-meta">
                                    <?php if (!empty($concert['price_info'])): ?>
                                        <span class="concert-price">
                                            <?= htmlspecialchars($concert['price_info']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($concert['status'] === 'sold_out'): ?>
                                        <span class="concert-status sold-out">Sold Out</span>
                                    <?php elseif ($concert['status'] === 'cancelled'): ?>
                                        <span class="concert-status cancelled">Anulat</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="concert-actions">
                                <?php if (!empty($concert['ticket_url']) && $concert['status'] !== 'sold_out' && $concert['status'] !== 'cancelled'): ?>
                                    <a href="<?= htmlspecialchars($concert['ticket_url']) ?>"
                                       class="concert-btn"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        🎫 Cumpără Bilete
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($concert['venue_map_url'])): ?>
                                    <a href="<?= htmlspecialchars($concert['venue_map_url']) ?>"
                                       class="concert-btn concert-btn-secondary"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        📍 Vezi Locația
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php else: ?>
        <section class="concerts-section">
            <h2 class="section-title" data-animate="fade-up">Concerte Viitoare</h2>
            <div class="no-concerts" data-animate="fade-up">
                <div class="no-concerts-icon">🎸</div>
                <p>Nu există concerte programate momentan.</p>
                <p>Urmărește-ne pe rețelele sociale pentru a afla primii despre evenimentele viitoare!</p>
            </div>
        </section>
    <?php endif; ?>

    <!-- Past Concerts -->
    <?php if (!empty($pastConcerts)): ?>
        <section class="concerts-section">
            <h2 class="section-title" data-animate="fade-up">Concerte Trecute</h2>
            <div class="concerts-grid">
                <?php foreach ($pastConcerts as $concert): ?>
                    <?php
                    $dateObj = new DateTime($concert['event_date']);
                    $day = $dateObj->format('j');
                    $month = $dateObj->format('F');
                    $year = $dateObj->format('Y');

                    $romanianMonths = [
                        'January' => 'Ian', 'February' => 'Feb', 'March' => 'Mar',
                        'April' => 'Apr', 'May' => 'Mai', 'June' => 'Iun',
                        'July' => 'Iul', 'August' => 'Aug', 'September' => 'Sept',
                        'October' => 'Oct', 'November' => 'Nov', 'December' => 'Dec'
                    ];
                    $month = $romanianMonths[$month] ?? $month;
                    ?>
                    <div class="concert-card past-concert-card" data-animate="fade-up">
                        <div class="concert-date-box">
                            <div class="concert-month"><?= $month ?></div>
                            <div class="concert-day"><?= $day ?></div>
                            <div class="concert-year"><?= $year ?></div>
                        </div>

                        <div class="concert-info">
                            <div>
                                <h3 class="concert-title"><?= htmlspecialchars($concert['title']) ?></h3>

                                <div class="concert-location">
                                    <span>
                                        <?= htmlspecialchars($concert['city']) ?>
                                        <?php if ($concert['country'] !== 'România'): ?>
                                            , <?= htmlspecialchars($concert['country']) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="concert-venue">
                                    <?= htmlspecialchars($concert['venue']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>
