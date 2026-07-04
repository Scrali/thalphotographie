<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/thal-studio/includes/pricing.php';

function clean($v) { return trim((string)$v); }
function number_value($v) { return max(0, (float)$v); }

$settings = thal_pricing_settings(__DIR__ . '/thal-studio');

$type = clean($_POST['type'] ?? 'Événement');
$location = clean($_POST['location'] ?? '');
$eventDate = clean($_POST['event_date'] ?? '');
$onsiteHours = number_value($_POST['onsite_hours'] ?? 2);
$roundtripKm = number_value($_POST['roundtrip_km'] ?? 0);
$roundtripMinutes = number_value($_POST['roundtrip_minutes'] ?? 0);
$usage = clean($_POST['usage'] ?? 'private');
$name = clean($_POST['name'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$message = clean($_POST['message'] ?? '');

$pricing = thal_pricing_calculate([
    'onsite_hours' => $onsiteHours,
    'roundtrip_km' => $roundtripKm,
    'roundtrip_minutes' => $roundtripMinutes,
    'usage' => $usage,
], $settings);

$contactSummary =
"Demande d'estimation THAL Photographie\n\n" .
"Type : {$type}\n" .
"Date : {$eventDate}\n" .
"Lieu : {$location}\n" .
"Temps sur place : {$onsiteHours} h\n" .
"Kilomètres A/R : {$roundtripKm} km\n" .
"Usage : " . ($usage === 'commercial' ? 'Utilisation commerciale' : 'Cadre privé') . "\n" .
"Pack recommandé : {$pricing['pack_name']}\n" .
"Photos incluses : {$pricing['included_photos']}\n" .
"Prix conseillé interne : " . number_format($pricing['price_recommended'], 0, '.', "'") . " CHF\n" .
"Budget estimatif : entre " . number_format($pricing['price_min'], 0, '.', "'") . " CHF et " . number_format($pricing['price_max'], 0, '.', "'") . " CHF\n\n" .
"Message : {$message}";

$entry = [
    'createdAt' => date('c'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'type' => $type,
    'location' => $location,
    'event_date' => $eventDate,
    'onsite_hours' => $onsiteHours,
    'roundtrip_km' => $roundtripKm,
    'roundtrip_minutes' => $roundtripMinutes,
    'usage' => $usage,
    'pack_name' => $pricing['pack_name'],
    'included_photos' => $pricing['included_photos'],
    'price_recommended' => $pricing['price_recommended'],
    'price_min' => $pricing['price_min'],
    'price_max' => $pricing['price_max'],
    'pricing' => $pricing,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'message' => $message,
    'contact_summary' => $contactSummary,
    'status' => 'new',
];

$dir = __DIR__ . '/thal-studio/data/estimations';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$id = date('Ymd_His') . '_' . substr(md5(json_encode($entry)), 0, 8);
$entry['_id'] = $id . '.json';

$ok = file_put_contents($dir . '/' . $id . '.json', json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;

echo json_encode([
    'ok' => $ok,
    'id' => $id,
    'pack' => $pricing['pack_name'],
    'included_photos' => $pricing['included_photos'],
    'price_recommended' => $pricing['price_recommended'],
    'price_min' => $pricing['price_min'],
    'price_max' => $pricing['price_max'],
    'usage' => $usage,
    'contact_summary' => $contactSummary,
]);
