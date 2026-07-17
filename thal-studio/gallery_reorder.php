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
$fromCategory = thal_sanitize_category((string)($raw['fromCategory'] ?? ''));
$toCategory = thal_sanitize_category((string)($raw['toCategory'] ?? ''));
$file = basename((string)($raw['file'] ?? ''));
$targetIndex = (int)($raw['targetIndex'] ?? 0);

if ($fromCategory === '' || $toCategory === '' || $file === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Paramètres manquants']);
    exit;
}

$data = thal_gallery_scan(__DIR__);

if (!isset($data[$fromCategory]) || !in_array($file, $data[$fromCategory], true)) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Photo introuvable']);
    exit;
}

$data[$fromCategory] = array_values(array_filter($data[$fromCategory], fn($f) => $f !== $file));

$finalName = $file;

if ($fromCategory !== $toCategory) {
    $photosDir = thal_photos_dir(__DIR__);
    $srcPath = $photosDir . '/' . $fromCategory . '/' . $file;
    $destDir = $photosDir . '/' . $toCategory;

    if (!is_dir($destDir) && !mkdir($destDir, 0755, true) && !is_dir($destDir)) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Impossible de créer la catégorie cible']);
        exit;
    }

    $base = pathinfo($file, PATHINFO_FILENAME);
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $suffix = 1;
    while (is_file($destDir . '/' . $finalName)) {
        $finalName = $base . '_' . $suffix . ($ext !== '' ? '.' . $ext : '');
        $suffix++;
    }

    if (is_file($srcPath)) {
        rename($srcPath, $destDir . '/' . $finalName);
    }
}

if (!isset($data[$toCategory])) {
    $data[$toCategory] = [];
}

$targetIndex = max(0, min($targetIndex, count($data[$toCategory])));
array_splice($data[$toCategory], $targetIndex, 0, [$finalName]);

thal_gallery_save($data, __DIR__);
echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
