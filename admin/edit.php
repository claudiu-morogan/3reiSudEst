<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/csrf.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['admin'])) { header('Location: index.php'); exit; }
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: dashboard.php'); exit; }
$news = getNewsById($id);
if (!$news) { echo 'Știre inexistentă.'; exit; }
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) { $msg = 'CSRF token invalid.'; }
    else {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        if ($title && $content) {
            updateNews($id, $title, $content);
            $msg = 'Știre actualizată.';
            $news = getNewsById($id);
        } else {
            $msg = 'Completați toate câmpurile.';
        }
    }
}
require_once __DIR__ . '/../inc/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <h3>Edit știre</h3>
            <?php if ($msg) echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>'; ?>
            <form method="post">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Titlu</label>
                    <input class="form-control" name="title" value="<?= htmlspecialchars($news['title']) ?>" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Conținut</label>
                    <textarea class="form-control" name="content" rows="6"><?= htmlspecialchars($news['content']) ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Salvează</button>
                <a class="btn btn-secondary ms-2" href="dashboard.php">Înapoi</a>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../inc/footer.php';
