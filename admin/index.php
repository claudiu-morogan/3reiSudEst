<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/csrf.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (isset($_SESSION['admin'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) { $error = 'CSRF token invalid.'; }
    else {
    $u = $_POST['user'] ?? '';
    $p = $_POST['pass'] ?? '';
    $user = findUserByUsername($u);
    if ($user && password_verify($p, $user['password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_user'] = $user['username'];
        header('Location: dashboard.php'); exit;
    } else {
        $error = 'Credențiale invalide';
    }
    }
}
require_once __DIR__ . '/../inc/header.php';
?>
<section class="card">
    <h2>Admin - Login</h2>
    <?php if ($error) echo '<p style="color:red">' . htmlspecialchars($error) . '</p>'; ?>
    <form method="post">
        <?php echo csrf_field(); ?>
        <label>Utilizator<br><input name="user" /></label><br>
        <label>Parolă<br><input name="pass" type="password" /></label><br>
        <button class="btn" type="submit">Autentificare</button>
    </form>
</section>
<?php require_once __DIR__ . '/../inc/footer.php';
