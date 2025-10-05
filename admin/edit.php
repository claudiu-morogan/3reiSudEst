<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/csrf.php';
session_start();
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
<section class="card">
    <h2>Edit știre</h2>
    <?php if ($msg) echo '<p>' . htmlspecialchars($msg) . '</p>'; ?>
    <form method="post">
        <?php echo csrf_field(); ?>
        <label>Titlu<br><input name="title" value="<?= htmlspecialchars($news['title']) ?>" style="width:100%" /></label><br>
        <label>Conținut<br><textarea name="content" rows="6" style="width:100%"><?= htmlspecialchars($news['content']) ?></textarea></label><br>
        <button class="btn" type="submit">Salvează</button>
    </form>
    <p><a href="dashboard.php">Înapoi</a></p>
</section>
<?php require_once __DIR__ . '/../inc/footer.php';
