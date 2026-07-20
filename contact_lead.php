<?php
header('Content-Type: application/json; charset=utf-8');

function clean($v) { return trim((string)$v); }

$name = clean($_POST['nom'] ?? $_POST['name'] ?? '');
$company = clean($_POST['entreprise'] ?? $_POST['company'] ?? '');
$email = clean($_POST['email'] ?? '');
$message = clean($_POST['message'] ?? '');

if ($name === '' && $email === '' && $message === '') {
    echo json_encode(['ok' => false, 'error' => 'empty']);
    exit;
}

$contactSummary = "Message reçu via le formulaire de contact du site\n\n" .
    "Nom : {$name}\n" .
    ($company !== '' ? "Entreprise : {$company}\n" : '') .
    "Email : {$email}\n\n" .
    "Message : {$message}";

$entry = [
    'createdAt' => date('c'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'name' => $company !== '' ? "{$name} ({$company})" : $name,
    'email' => $email,
    'phone' => '',
    'location' => '',
    'event_date' => '',
    'message' => $message,
    'contact_summary' => $contactSummary,
    'status' => 'new',
    'source' => 'contact_form',
];

$dir = __DIR__ . '/thal-studio/data/estimations';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$id = date('Ymd_His') . '_' . substr(md5(json_encode($entry) . microtime()), 0, 8);
$entry['_id'] = $id . '.json';
$ok = file_put_contents($dir . '/' . $id . '.json', json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;

echo json_encode(['ok' => $ok, 'id' => $id]);
