<?php
// PDO helper
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo '<h2>Database connection error</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

function fetchLatestNews($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM news ORDER BY created_at DESC LIMIT ?');
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function fetchNewsPage($limit, $offset) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM news ORDER BY created_at DESC LIMIT ? OFFSET ?');
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function countNews() {
    global $pdo;
    $stmt = $pdo->query('SELECT COUNT(*) as c FROM news');
    $r = $stmt->fetch();
    return (int)$r['c'];
}

function getNewsById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
    $stmt->execute([(int)$id]);
    return $stmt->fetch();
}

function updateNews($id, $title, $content) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE news SET title = ?, content = ? WHERE id = ?');
    return $stmt->execute([$title, $content, (int)$id]);
}

function deleteNews($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
    return $stmt->execute([(int)$id]);
}

function findUserByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function createUser($username, $passwordHash) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO users (username, password, created_at) VALUES (?, ?, NOW())');
    return $stmt->execute([$username, $passwordHash]);
}
