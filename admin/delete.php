<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['admin'])) { header('Location: index.php'); exit; }
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: dashboard.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        deleteNews($id);
        header('Location: dashboard.php'); exit;
    } else {
        header('Location: dashboard.php'); exit;
    }
}
$news = getNewsById($id);
if (!$news) { header('Location: dashboard.php'); exit; }
require_once __DIR__ . '/../inc/csrf.php';
require_once __DIR__ . '/../inc/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3>Confirmare ștergere</h3>
            <p>Sunteți sigur că doriți să ștergeți: <strong><?= htmlspecialchars($news['title']) ?></strong>?</p>
            <form method="post">
                <?php echo csrf_field(); ?>
                <button class="btn btn-danger" name="confirm" value="1" type="submit">Da, șterge</button>
                <a class="btn btn-secondary ms-2" href="dashboard.php">Anulează</a>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../inc/footer.php';
