<?php
require __DIR__ . '/includes/auth.php';
require_login();
$id = preg_replace('/[^a-zA-Z0-9_.-]/', '', $_POST['id'] ?? '');
$source = __DIR__ . '/data/quotes/' . $id . '.json';
if (!is_file($source)) { header('Location: quotes.php'); exit; }
$data = json_decode((string)file_get_contents($source), true);
if (!is_array($data)) { header('Location: quotes.php'); exit; }
function thal_slug(string $value): string {
    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($converted !== false) $value = $converted;
    $value = preg_replace('/[^a-zA-Z0-9]+/', '_', $value);
    $value = trim((string)$value, '_');
    return substr($value ?: 'devis', 0, 90);
}
$data['quoteNumber'] = ((string)($data['quoteNumber'] ?? 'DEV')) . '-COPIE';
$data['_meta']['id'] = thal_slug($data['quoteNumber'] . '_' . ($data['clientName'] ?? 'client'));
$data['_meta']['updatedAt'] = date('c');
file_put_contents(__DIR__ . '/data/quotes/' . $data['_meta']['id'] . '.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
header('Location: devis.php?q=' . urlencode($data['_meta']['id']));
exit;
