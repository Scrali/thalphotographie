<?php
require __DIR__ . '/includes/auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$slot = isset($_GET['slot']) ? (int)$_GET['slot'] : (int)($_POST['slot'] ?? 0);

if ($slot < 1 || $slot > 3) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Slot invalide']);
    exit;
}

$path = __DIR__ . '/data/layouts/preset-' . $slot . '.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!is_file($path)) {
        echo json_encode(['ok' => false, 'empty' => true]);
        exit;
    }

    $json = file_get_contents($path);
    echo json_encode(['ok' => true, 'data' => json_decode($json, true)]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw, true);

    if (!is_array($payload)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'JSON invalide']);
        exit;
    }

    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['ok' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (is_file($path)) {
        unlink($path);
    }

    echo json_encode(['ok' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['ok' => false, 'error' => 'Méthode non autorisée']);
