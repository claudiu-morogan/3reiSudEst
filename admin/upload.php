<?php
require_once __DIR__ . '/../inc/config.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
// Only allow authenticated admins to upload
if (empty($_SESSION['admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

// Basic upload handling
$maxSize = 2 * 1024 * 1024; // 2MB
$allowed = ['image/jpeg','image/png','image/gif'];
if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}
$file = $_FILES['image'];
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'File too large']);
    exit;
}
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
if (!in_array($mime, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type']);
    exit;
}

$ext = $mime === 'image/png' ? '.png' : ($mime === 'image/gif' ? '.gif' : '.jpg');
$name = bin2hex(random_bytes(12)) . $ext;
$dir = __DIR__ . '/../uploads';
if (!is_dir($dir)) mkdir($dir, 0755, true);
$dest = $dir . '/' . $name;
if (!move_uploaded_file($file['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save file']);
    exit;
}

// Build absolute URL to the uploads folder so Quill can load it from anywhere
require_once __DIR__ . '/../inc/config.php';
$appBase = base_url();
$url = rtrim($appBase, '/') . '/uploads/' . $name;
echo json_encode(['success' => true, 'url' => $url]);
