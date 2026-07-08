<?php
require __DIR__ . '/includes/auth.php';
require_login();
header('Content-Type: application/json; charset=utf-8');

if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    http_response_code(403);
    echo json_encode(['ok'=>false,'error'=>'Jeton de sécurité invalide']);
    exit;
}

$data = json_decode((string)file_get_contents('php://input'), true);
if (!is_array($data)) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'JSON invalide']); exit; }
function thal_slug(string $value): string {
    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($converted !== false) $value = $converted;
    $value = preg_replace('/[^a-zA-Z0-9]+/', '_', $value);
    $value = trim((string)$value, '_');
    return substr($value ?: 'devis', 0, 90);
}
$quoteNumber = (string)($data['quoteNumber'] ?? 'DEV');
$clientName = (string)($data['clientName'] ?? 'client');
$existingId = !empty($data['_meta']['id']) ? thal_slug((string)$data['_meta']['id']) : '';
$id = $existingId ?: thal_slug($quoteNumber . '_' . $clientName);
$dir = __DIR__ . '/data/quotes';
if (!is_dir($dir)) mkdir($dir, 0755, true);
$data['_meta'] = ['id'=>$id,'quoteNumber'=>$quoteNumber,'clientName'=>$clientName,'eventDate'=>(string)($data['eventDate'] ?? ''),'updatedAt'=>date('c')];
$ok = file_put_contents($dir . '/' . $id . '.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
echo json_encode(['ok'=>$ok!==false,'id'=>$id,'name'=>$quoteNumber.' — '.$clientName]);
