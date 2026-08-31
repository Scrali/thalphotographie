<?php
require __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/maintenance.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? null)) {
    http_response_code(403);
    exit('Jeton de sécurité invalide.');
}

$lock = thal_maintenance_lock_path(__DIR__);
$action = (string)($_POST['action'] ?? '');

if ($action === 'enable') {
    file_put_contents($lock, "Mode maintenance activé le " . date('c') . "\n", LOCK_EX);
} elseif ($action === 'disable') {
    if (is_file($lock)) unlink($lock);
}

header('Location: maintenance_settings.php');
exit;
