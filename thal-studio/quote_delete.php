<?php
require __DIR__ . '/includes/auth.php';
require_login();
$id = preg_replace('/[^a-zA-Z0-9_.-]/', '', $_POST['id'] ?? '');
if ($id !== '') {
    $file = __DIR__ . '/data/quotes/' . $id . '.json';
    if (is_file($file)) unlink($file);
}
header('Location: quotes.php');
exit;
