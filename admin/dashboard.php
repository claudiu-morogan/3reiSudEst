<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['admin'])) { header('Location: index.php'); exit; }
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) { $msg = 'CSRF token invalid.'; }
    else {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        if ($title && $content) {
            $stmt = $pdo->prepare('INSERT INTO news (title, content, created_at) VALUES (?, ?, NOW())');
            $stmt->execute([$title, $content]);
            $msg = 'Știre adăugată.';
        } else {
            $msg = 'Completați toate câmpurile.';
        }
    }
}
require_once __DIR__ . '/../inc/csrf.php';
require_once __DIR__ . '/../inc/header.php';
?>
<div class="row">
    <div class="col-lg-8">
        <div class="card p-4">
            <h3>Dashboard Admin</h3>
            <?php if ($msg) echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>'; ?>
            <form method="post">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Titlu</label>
                    <input class="form-control" name="title" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Conținut</label>
                    <textarea class="form-control" name="content" rows="6"></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Adaugă știre</button>
            </form>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-4">
            <h5>Știri existente</h5>
            <?php
            $existing = fetchLatestNews(100);
            if ($existing) {
                    echo '<ul class="list-group">';
                    foreach ($existing as $e) {
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">' . htmlspecialchars($e['title']) . '<span><a class="btn btn-sm btn-outline-secondary me-2" href="edit.php?id=' . $e['id'] . '">Edit</a><a class="btn btn-sm btn-outline-danger" href="delete.php?id=' . $e['id'] . '">Șterge</a></span></li>';
                    }
                    echo '</ul>';
            } else {
                    echo '<p>Nu există știri.</p>';
            }
            ?>
            <p class="mt-3"><a class="btn btn-sm btn-outline-primary" href="logout.php">Deconectare</a></p>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../inc/footer.php';
