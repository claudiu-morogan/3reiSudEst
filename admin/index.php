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
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3>Admin - Login</h3>
            <?php if ($error) echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>'; ?>
            <form method="post">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Utilizator</label>
                    <input class="form-control" name="user" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Parolă</label>
                    <input class="form-control" name="pass" type="password" />
                </div>
                <button class="btn btn-primary" type="submit">Autentificare</button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../inc/footer.php';
