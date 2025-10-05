<div class="row g-4">
    <div class="col-lg-8">
                <div class="card p-4 hero-gradient">
                    <h2 class="fw-bold rainbow-text">Bine ai venit pe fan site-ul 3 Sud Est</h2>
                <p class="mb-0">Acesta este un site colorat, dedicat fanilor — știri, membri și evenimente.</p>
            </div>

        <div class="card p-4 mt-4">
            <h3>Ultimele știri</h3>
            <?php
            $news = fetchLatestNews(5);
            if (!$news) {
                    echo '<p>Nu există știri de afișat.</p>';
            } else {
                    foreach ($news as $n) {
                            echo '<article class="mb-3">';
                            echo '<h4><a href="/3reiSudEst/?page=news&id=' . $n['id'] . '">' . htmlspecialchars($n['title']) . '</a></h4>';
                            echo '<p class="text-muted small mb-1">Publicat: ' . htmlspecialchars($n['created_at']) . '</p>';
                            echo '<p>' . nl2br(htmlspecialchars(substr($n['content'],0,250))) . '...</p>';
                            echo '</article>';
                    }
            }
            ?>
        </div>
    </div>
    <div class="col-lg-4">
        <aside class="card p-3">
            <h5>Despre</h5>
            <p class="small">Fanii pot găsi informații despre membri, știri și altele.</p>
        </aside>
    </div>
</div>
