<style>
    .tracklist {
        margin-top: var(--space-lg);
        padding-top: var(--space-lg);
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .tracklist-title {
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: var(--space-md);
    }

    .track {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.625rem 0;
        transition: all 0.2s ease;
        position: relative;
    }

    .track::before {
        content: '';
        position: absolute;
        left: -0.5rem;
        width: 2px;
        height: 0;
        background: var(--color-accent);
        transition: height 0.2s ease;
    }

    .track:hover::before {
        height: 100%;
    }

    .track:hover .track-title {
        color: #fff;
    }

    .track:hover .track-youtube a {
        opacity: 1;
    }

    .track-number {
        font-weight: 400;
        color: rgba(255, 255, 255, 0.4);
        min-width: 1.5rem;
        text-align: right;
        font-size: 0.875rem;
        font-variant-numeric: tabular-nums;
    }

    .track-info {
        flex: 1;
        min-width: 0;
    }

    .track-title {
        font-weight: 400;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.9375rem;
        line-height: 1.5;
        transition: color 0.2s ease;
    }

    .track-duration {
        color: rgba(255, 255, 255, 0.4);
        font-size: 0.875rem;
        font-variant-numeric: tabular-nums;
        font-weight: 400;
    }

    .track-youtube a {
        color: var(--color-accent);
        text-decoration: none;
        font-size: 0.875rem;
        opacity: 0.6;
        transition: opacity 0.2s ease;
        margin-left: 0.75rem;
    }

    .track-youtube a:hover {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .track {
            gap: 0.75rem;
            padding: 0.5rem 0;
        }

        .track-number {
            min-width: 1.25rem;
            font-size: 0.8125rem;
        }

        .track-title {
            font-size: 0.875rem;
        }

        .track-duration {
            font-size: 0.8125rem;
        }
    }
</style>

<section class="discography-section">
    <div class="container">
        <h1 class="section-title reveal reveal-up">Discografie</h1>
        <p class="section-subtitle reveal reveal-up">O călătorie prin sunetele care au marcat generații</p>

        <div class="albums-timeline">
            <?php if (!empty($pageData['albums'])): ?>
                <?php foreach ($pageData['albums'] as $index => $album): ?>
                    <?php
                    $side = $index % 2 === 0 ? 'left' : 'right';
                    $revealClass = $side === 'left' ? 'reveal-left' : 'reveal-right';

                    // Get album with tracks
                    $albumWithTracks = Album::getWithTracks($album['id']);
                    $tracks = $albumWithTracks['tracks'] ?? [];
                    ?>

                    <article class="album-card reveal <?= $revealClass ?>" data-year="<?= htmlspecialchars($album['release_year']) ?>">
                        <div class="album-year"><?= htmlspecialchars($album['release_year']) ?></div>

                        <div class="album-content card-hover image-zoom">
                            <?php if ($album['cover_image']): ?>
                                <div class="album-cover">
                                    <img src="<?= htmlspecialchars($album['cover_image']) ?>" alt="<?= htmlspecialchars($album['title']) ?>">
                                </div>
                            <?php endif; ?>

                            <div class="album-info">
                                <h2 class="album-title"><?= htmlspecialchars($album['title']) ?></h2>

                                <?php if ($album['description']): ?>
                                    <p class="album-description"><?= nl2br(htmlspecialchars($album['description'])) ?></p>
                                <?php endif; ?>

                                <!-- Track Listing -->
                                <?php if (!empty($tracks)): ?>
                                    <div class="tracklist">
                                        <div class="tracklist-title">Piese</div>
                                        <?php foreach ($tracks as $track): ?>
                                            <div class="track">
                                                <span class="track-number"><?= htmlspecialchars($track['track_number']) ?>.</span>
                                                <div class="track-info">
                                                    <div class="track-title"><?= htmlspecialchars($track['title']) ?></div>
                                                </div>
                                                <?php if ($track['duration']): ?>
                                                    <span class="track-duration"><?= htmlspecialchars($track['duration']) ?></span>
                                                <?php endif; ?>
                                                <?php if ($track['youtube_url']): ?>
                                                    <span class="track-youtube">
                                                        <a href="<?= htmlspecialchars($track['youtube_url']) ?>" target="_blank" rel="noopener noreferrer">▶</a>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="album-links">
                                    <?php if ($album['spotify_url']): ?>
                                        <a href="<?= htmlspecialchars($album['spotify_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn-lift">Spotify</a>
                                    <?php endif; ?>

                                    <?php if ($album['apple_music_url']): ?>
                                        <a href="<?= htmlspecialchars($album['apple_music_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn-lift">Apple Music</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-state">Nu există albume disponibile momentan.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
