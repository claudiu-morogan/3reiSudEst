<section class="card">
    <?php
    $id = $_GET['id'] ?? null;
    if ($id) {
        $item = getNewsById($id);
        if (!$item) {
            echo '<h2>Știre inexistentă</h2>';
        } else {
            echo '<h2>' . htmlspecialchars($item['title']) . '</h2>';
            echo '<p>' . nl2br(htmlspecialchars($item['content'])) . '</p>';
            echo '<small>Publicat: ' . htmlspecialchars($item['created_at']) . '</small>';
        }
        echo '<p><a href="/3reiSudEst/?page=news">Înapoi la listă</a></p>';
    } else {
        echo '<h2>Toate știrile</h2>';
        $page = max(1, (int)($_GET['p'] ?? 1));
        $per = 5;
        $total = countNews();
        $pages = (int)ceil($total / $per);
        $offset = ($page - 1) * $per;
        $items = fetchNewsPage($per, $offset);
        if (!$items) {
            echo '<p>Nu există știri.</p>';
        } else {
            foreach ($items as $n) {
                echo '<article class="news-item card">';
                echo '<h3><a href="/3reiSudEst/?page=news&id=' . $n['id'] . '">' . htmlspecialchars($n['title']) . '</a></h3>';
                echo '<p>' . nl2br(htmlspecialchars(substr($n['content'],0,300))) . '...</p>';
                echo '<small>Publicat: ' . htmlspecialchars($n['created_at']) . '</small>';
                echo '</article>';
            }
        }
        // pagination
        if ($pages > 1) {
            echo '<nav class="pagination">';
            for ($i=1;$i<=$pages;$i++) {
                if ($i === $page) {
                    echo ' <strong>' . $i . '</strong> ';
                } else {
                    echo ' <a href="/3reiSudEst/?page=news&p=' . $i . '">' . $i . '</a> ';
                }
            }
            echo '</nav>';
        }
    }
    ?>
</section>
