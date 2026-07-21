<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
thal_json_api_guard();
require_login();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Jeton de sécurité invalide']);
    exit;
}

$raw = json_decode((string)file_get_contents('php://input'), true);
$category = thal_sanitize_category((string)($raw['category'] ?? ''));
$file = basename((string)($raw['file'] ?? ''));

if ($category === '' || $file === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Paramètres manquants']);
    exit;
}

$data = thal_gallery_scan(__DIR__);
$path = thal_photos_dir(__DIR__) . '/' . $category . '/' . $file;

if (is_file($path)) {
    unlink($path);
}

$thumbPath = thal_thumbs_dir(__DIR__) . '/' . $category . '/' . $file;
if (is_file($thumbPath)) {
    unlink($thumbPath);
}

if (isset($data[$category])) {
    $data[$category] = array_values(array_filter($data[$category], fn($f) => $f !== $file));
}

thal_gallery_save($data, __DIR__);
echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
