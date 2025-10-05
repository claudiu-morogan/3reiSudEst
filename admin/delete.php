<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
session_start();
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
require_once __DIR__ . '/../inc/header.php';
?>
<section class="card">
    <h2>Confirmare ștergere</h2>
    <p>Sunteți sigur că doriți să ștergeți: <strong><?= htmlspecialchars($news['title']) ?></strong>?</p>
    <form method="post">
        <button class="btn" name="confirm" value="1" type="submit">Da, șterge</button>
        <a href="dashboard.php">Anulează</a>
    </form>
</section>
<?php require_once __DIR__ . '/../inc/footer.php';
