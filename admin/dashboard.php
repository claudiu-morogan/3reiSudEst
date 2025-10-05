<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
session_start();
if (!isset($_SESSION['admin'])) { header('Location: index.php'); exit; }
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
require_once __DIR__ . '/../inc/header.php';
?>
<section class="card">
    <h2>Dashboard Admin</h2>
    <?php if ($msg) echo '<p>' . htmlspecialchars($msg) . '</p>'; ?>
    <form method="post">
        <label>Titlu<br><input name="title" style="width:100%" /></label><br>
        <label>Conținut<br><textarea name="content" rows="6" style="width:100%"></textarea></label><br>
        <button class="btn" type="submit">Adaugă știre</button>
    </form>
    <h3>Știri existente</h3>
    <?php
    $existing = fetchLatestNews(100);
    if ($existing) {
        echo '<ul>';
        foreach ($existing as $e) {
            echo '<li>' . htmlspecialchars($e['title']) . ' - <a href="edit.php?id=' . $e['id'] . '">Edit</a> | <a href="delete.php?id=' . $e['id'] . '">Șterge</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Nu există știri.</p>';
    }
    ?>
    <p><a href="logout.php">Deconectare</a></p>
</section>
<?php require_once __DIR__ . '/../inc/footer.php';
