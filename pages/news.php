<div class="card p-4">
  <?php
  $id = $_GET['id'] ?? null;
  if ($id) {
      $item = getNewsById($id);
      if (!$item) {
          echo '<h2>Știre inexistentă</h2>';
      } else {
          echo '<h2 class="rainbow-text">' . htmlspecialchars($item['title']) . '</h2>';
          echo '<p>' . nl2br(htmlspecialchars($item['content'])) . '</p>';
          echo '<p class="text-muted small">Publicat: ' . htmlspecialchars($item['created_at']) . '</p>';
      }
      echo '<p><a class="btn btn-sm btn-outline-primary" href="/3reiSudEst/?page=news">Înapoi la listă</a></p>';
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
              echo '<article class="mb-3">';
              echo '<h4><a class="rainbow-text" style="text-decoration:none;" href="/3reiSudEst/?page=news&id=' . $n['id'] . '">' . htmlspecialchars($n['title']) . '</a></h4>';
              echo '<p class="text-muted small mb-1">Publicat: ' . htmlspecialchars($n['created_at']) . '</p>';
              echo '<p>' . nl2br(htmlspecialchars(substr($n['content'],0,300))) . '...</p>';
              echo '</article>';
          }
      }
      // pagination
      if ($pages > 1) {
          echo '<nav><ul class="pagination">';
          for ($i=1;$i<=$pages;$i++) {
              $active = $i === $page ? ' active' : '';
              echo '<li class="page-item' . $active . '"><a class="page-link" href="/3reiSudEst/?page=news&p=' . $i . '">' . $i . '</a></li>';
          }
          echo '</ul></nav>';
      }
  }
  ?>
</div>
