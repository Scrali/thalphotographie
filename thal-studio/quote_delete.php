<?php
require __DIR__ . '/includes/auth.php';
require_login();
if (!csrf_check($_POST['csrf'] ?? null)) {
    http_response_code(403);
    exit('Jeton de sécurité invalide.');
}
$id = preg_replace('/[^a-zA-Z0-9_.-]/', '', $_POST['id'] ?? '');
if ($id !== '') {
    $file = __DIR__ . '/data/quotes/' . $id . '.json';
    if (is_file($file)) unlink($file);
}
$redirect = ($_POST['redirect'] ?? '') === 'factures.php' ? 'factures.php' : 'quotes.php';
header('Location: ' . $redirect);
exit;
