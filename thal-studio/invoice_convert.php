<?php
require __DIR__ . '/includes/auth.php';
require_login();
header('Content-Type: application/json; charset=utf-8');

if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Jeton de sécurité invalide']);
    exit;
}

function thal_invoice_slug(string $value): string {
    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($converted !== false) $value = $converted;
    $value = preg_replace('/[^a-zA-Z0-9]+/', '_', $value);
    $value = trim((string)$value, '_');
    return substr($value ?: 'facture', 0, 90);
}

$raw = json_decode((string)file_get_contents('php://input'), true);
$id = preg_replace('/[^a-zA-Z0-9_.-]/', '', (string)($raw['id'] ?? ''));
$source = __DIR__ . '/data/quotes/' . $id . '.json';

if ($id === '' || !is_file($source)) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Devis introuvable']);
    exit;
}

$data = json_decode((string)file_get_contents($source), true);
if (!is_array($data)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Devis illisible']);
    exit;
}

// Compteur de numérotation des factures, séparé de celui des devis.
$year = date('Y');
$counterFile = __DIR__ . '/data/settings/counters.json';
$counters = is_file($counterFile) ? json_decode((string)file_get_contents($counterFile), true) : [];
if (!is_array($counters)) {
    $counters = [];
}
$key = 'facture_' . $year;
$next = (int)($counters[$key] ?? 0) + 1;
$counters[$key] = $next;
if (!is_dir(dirname($counterFile))) {
    mkdir(dirname($counterFile), 0755, true);
}
file_put_contents($counterFile, json_encode($counters, JSON_PRETTY_PRINT), LOCK_EX);

$invoiceNumber = sprintf('FAC-%s-%03d', $year, $next);

$data['docType'] = 'facture';
$data['quoteNumber'] = $invoiceNumber;
$data['quoteDate'] = date('Y-m-d');
$data['serviceDoneDate'] = (string)($data['eventDate'] ?? '');
$data['depositAmount'] = $data['depositAmount'] ?? '0';
$data['depositDate'] = $data['depositDate'] ?? '';
$data['terms'] = 'Paiement à 30 jours. Coordonnées de paiement : voir bulletin QR joint. TVA non applicable.';

$clientName = (string)($data['clientName'] ?? 'client');
$newId = thal_invoice_slug($invoiceNumber . '_' . $clientName);

$data['_meta'] = [
    'id' => $newId,
    'quoteNumber' => $invoiceNumber,
    'clientName' => $clientName,
    'clientEmail' => (string)($data['clientEmail'] ?? ''),
    'clientPhone' => (string)($data['clientPhone'] ?? ''),
    'eventDate' => (string)($data['eventDate'] ?? ''),
    'createdAt' => date('c'),
    'updatedAt' => date('c'),
    'convertedFromId' => $id,
];

$dir = __DIR__ . '/data/quotes';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}
$ok = file_put_contents($dir . '/' . $newId . '.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;

echo json_encode(['ok' => $ok, 'id' => $newId, 'quoteNumber' => $invoiceNumber]);
