<?php
require __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/pricing.php';

if (!csrf_check($_POST['csrf'] ?? null)) {
    http_response_code(403);
    exit('Jeton de sécurité invalide.');
}

function thal_slug(string $value): string {
    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($converted !== false) $value = $converted;
    $value = preg_replace('/[^a-zA-Z0-9]+/', '_', $value);
    $value = trim((string)$value, '_');
    return substr($value ?: 'devis', 0, 90);
}

$id = preg_replace('/[^a-zA-Z0-9_.-]/', '', $_POST['id'] ?? '');
$file = __DIR__ . '/data/estimations/' . $id;
if ($id === '' || !is_file($file)) { header('Location: estimations.php'); exit; }

$estimate = json_decode((string)file_get_contents($file), true);
if (!is_array($estimate)) { header('Location: estimations.php'); exit; }

$estimate['_id'] = basename($file);
$settings = thal_pack_settings(__DIR__);
$pricing = $estimate['pricing'] ?? thal_pack_calculate([
    'onsite_hours'=>$estimate['onsite_hours'] ?? 1,
    'roundtrip_km'=>$estimate['roundtrip_km'] ?? 0,
    'usage'=>$estimate['usage'] ?? 'private',
], $settings);

$quote = thal_estimation_to_quote($estimate, $pricing, $settings);
$quoteId = thal_slug($quote['quoteNumber'] . '_' . $quote['clientName']);
$quote['_meta']['id'] = $quoteId;
$quote['_meta']['updatedAt'] = date('c');

$quoteDir = __DIR__ . '/data/quotes';
if (!is_dir($quoteDir)) mkdir($quoteDir, 0755, true);
file_put_contents($quoteDir . '/' . $quoteId . '.json', json_encode($quote, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

$estimate['status'] = 'converted';
$estimate['convertedQuoteId'] = $quoteId;
$estimate['convertedAt'] = date('c');
file_put_contents($file, json_encode($estimate, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

header('Location: devis.php?q=' . urlencode($quoteId));
exit;
