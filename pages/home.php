<section class="card">
    <h2>Bine ai venit pe fan site-ul 3 Sud Est</h2>
    <p>Acesta este un site simplu construit cu PHP + MySQL pentru a demonstra un scaffold local pe XAMPP.</p>
</section>

<section class="card">
    <h2>Ultimele știri</h2>
    <?php
    $news = fetchLatestNews(5);
    if (!$news) {
        echo '<p>Nu există știri de afișat.</p>';
    } else {
        foreach ($news as $n) {
            echo '<div class="news-item">';
            echo '<h3>' . htmlspecialchars($n['title']) . '</h3>';
            echo '<p>' . nl2br(htmlspecialchars(substr($n['content'],0,300))) . '...</p>';
            echo '<small>Publicat: ' . htmlspecialchars($n['created_at']) . '</small>';
            echo '</div>';
        }
    }
    ?>
</section>
