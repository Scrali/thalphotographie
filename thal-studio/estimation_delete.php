<?php
require __DIR__ . '/includes/auth.php';
require_login();

$id = preg_replace('/[^a-zA-Z0-9_.-]/', '', $_POST['id'] ?? '');
if ($id !== '') {
    $file = __DIR__ . '/data/estimations/' . $id;
    if (!str_ends_with($file, '.json')) $file .= '.json';
    if (is_file($file)) unlink($file);
}
header('Location: estimations.php');
exit;
