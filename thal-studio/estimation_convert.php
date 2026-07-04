<?php
require __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/pricing.php';

function thal_slug(string $value): string {
    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($converted !== false) {
        $value = $converted;
    }
    $value = preg_replace('/[^a-zA-Z0-9]+/', '_', $value);
    $value = trim((string)$value, '_');
    return substr($value ?: 'devis', 0, 90);
}

$id = preg_replace('/[^a-zA-Z0-9_.-]/', '', $_POST['id'] ?? '');
if ($id === '') {
    header('Location: estimations.php?error=missing_id');
    exit;
}

$file = __DIR__ . '/data/estimations/' . $id;
if (!str_ends_with($file, '.json')) {
    $file .= '.json';
}

if (!is_file($file)) {
    header('Location: estimations.php?error=estimation_not_found');
    exit;
}

$estimate = json_decode((string)file_get_contents($file), true);
if (!is_array($estimate)) {
    header('Location: estimations.php?error=invalid_estimation');
    exit;
}

$estimate['_id'] = basename($file);

$settings = thal_pack_settings(__DIR__);
$pricing = $estimate['pricing'] ?? thal_pack_calculate([
    'onsite_hours' => $estimate['onsite_hours'] ?? 2,
    'roundtrip_km' => $estimate['roundtrip_km'] ?? 0,
    'usage' => $estimate['usage'] ?? 'private',
], $settings);

$quote = thal_estimation_to_quote($estimate, $pricing, $settings);

// ID unique et stable
$quoteId = thal_slug($quote['quoteNumber'] . '_' . $quote['clientName']);

$quote['_meta'] = [
    'id' => $quoteId,
    'source' => 'estimation',
    'sourceId' => basename($file),
    'pricingEngine' => 'pack-v0.6.1',
    'updatedAt' => date('c'),
];

$quoteDir = __DIR__ . '/data/quotes';
if (!is_dir($quoteDir)) {
    mkdir($quoteDir, 0755, true);
}

$quotePath = $quoteDir . '/' . $quoteId . '.json';
$ok = file_put_contents($quotePath, json_encode($quote, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

if ($ok === false) {
    header('Location: estimations.php?error=quote_write_failed');
    exit;
}

// Marquer l'estimation comme convertie
$estimate['status'] = 'converted';
$estimate['convertedQuoteId'] = $quoteId;
$estimate['convertedAt'] = date('c');
file_put_contents($file, json_encode($estimate, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

header('Location: devis.php?q=' . urlencode($quoteId));
exit;
