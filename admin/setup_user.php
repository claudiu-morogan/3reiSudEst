<?php
// One-off script: accesează o singură dată pentru a crea un user admin
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/db.php';
// Schimbă user/parolă aici înainte de a rula
$user = 'admin';
$pass = 'Admin!2025';
if (findUserByUsername($user)) {
    echo 'User deja existent.'; exit;
}
$hash = password_hash($pass, PASSWORD_DEFAULT);
if (createUser($user, $hash)) {
    echo 'User creat: ' . htmlspecialchars($user) . '<br>Șterge acest fișier după utilizare.';
} else {
    echo 'Eroare la creare user.';
}
