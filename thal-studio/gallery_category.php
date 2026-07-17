<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
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
$action = (string)($raw['action'] ?? 'create');
$data = thal_gallery_scan(__DIR__);
$photosDir = thal_photos_dir(__DIR__);

if ($action === 'create') {
    $name = thal_sanitize_category((string)($raw['name'] ?? ''));
    if ($name === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Nom de catégorie invalide']);
        exit;
    }

    $dir = $photosDir . '/' . $name;
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Impossible de créer le dossier']);
        exit;
    }

    if (!isset($data[$name])) {
        $data[$name] = [];
    }

    thal_gallery_save($data, __DIR__);
    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'delete') {
    $name = thal_sanitize_category((string)($raw['name'] ?? ''));
    if ($name === '' || !isset($data[$name])) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Catégorie introuvable']);
        exit;
    }

    if (!empty($data[$name])) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'La catégorie contient encore des photos']);
        exit;
    }

    $dir = $photosDir . '/' . $name;
    if (is_dir($dir)) {
        @rmdir($dir);
    }
    unset($data[$name]);

    thal_gallery_save($data, __DIR__);
    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Action inconnue']);
