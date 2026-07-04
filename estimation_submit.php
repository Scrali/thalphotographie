<?php
header('Content-Type: application/json; charset=utf-8');

function clean($v) { return trim((string)$v); }
function number_value($v) { return max(0, (float)$v); }
function read_settings(): array {
    $defaults = [
        'home_address' => 'Sainte-Croix, VD, Suisse',
        'hourly_rate' => 120,
        'editing_rate' => 80,
        'editing_hours_per_onsite_hour' => 0.65,
        'km_rate' => 0.80,
        'base_fee' => 80,
        'commercial_fee' => 250,
        'private_photos_per_hour' => 18,
        'commercial_photos_per_hour' => 22,
        'min_photos_private' => 12,
        'min_photos_commercial' => 20,
        'range_min_multiplier' => 0.90,
        'range_max_multiplier' => 1.18,
    ];

    $file = __DIR__ . '/thal-studio/data/settings/estimation.json';
    if (!is_file($file)) {
        return $defaults;
    }

    $data = json_decode((string)file_get_contents($file), true);
    return is_array($data) ? array_merge($defaults, $data) : $defaults;
}

$settings = read_settings();

$type = clean($_POST['type'] ?? 'Événement');
$location = clean($_POST['location'] ?? '');
$eventDate = clean($_POST['event_date'] ?? '');
$onsiteHours = number_value($_POST['onsite_hours'] ?? 2);
$roundtripKm = number_value($_POST['roundtrip_km'] ?? 0);
$usage = clean($_POST['usage'] ?? 'private');
$name = clean($_POST['name'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$message = clean($_POST['message'] ?? '');

if ($onsiteHours <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Durée invalide.']);
    exit;
}

$hourlyRate = (float)$settings['hourly_rate'];
$editingRate = (float)$settings['editing_rate'];
$editingPerHour = (float)$settings['editing_hours_per_onsite_hour'];
$kmRate = (float)$settings['km_rate'];
$baseFee = (float)$settings['base_fee'];
$commercialFee = $usage === 'commercial' ? (float)$settings['commercial_fee'] : 0;

$includedPhotos = max((int)$settings['min_photos_private'], (int)round($onsiteHours * (float)$settings['private_photos_per_hour']));
if ($usage === 'commercial') {
    $includedPhotos = max((int)$settings['min_photos_commercial'], (int)round($onsiteHours * (float)$settings['commercial_photos_per_hour']));
}

$timeCost = $onsiteHours * $hourlyRate;
$editingCost = $onsiteHours * $editingPerHour * $editingRate;
$kmCost = $roundtripKm * $kmRate;
$raw = $timeCost + $editingCost + $kmCost + $baseFee + $commercialFee;

$min = round(($raw * (float)$settings['range_min_multiplier']) / 10) * 10;
$max = round(($raw * (float)$settings['range_max_multiplier']) / 10) * 10;

$packName = 'Pack ' . rtrim(rtrim(number_format($onsiteHours, 1, '.', ''), '0'), '.') . 'h';
$packName .= $usage === 'commercial' ? ' Commercial' : ' Privé';

$mapsUrl = 'https://www.google.com/maps/dir/?api=1&origin=' . rawurlencode((string)$settings['home_address']) . '&destination=' . rawurlencode($location);

$contactSummary =
"Demande d'estimation THAL Photographie\n\n" .
"Type : {$type}\n" .
"Date : {$eventDate}\n" .
"Lieu : {$location}\n" .
"Temps sur place : {$onsiteHours} h\n" .
"Kilomètres A/R indiqués : {$roundtripKm} km\n" .
"Usage : " . ($usage === 'commercial' ? 'Utilisation commerciale' : 'Cadre privé') . "\n" .
"Pack proposé : {$packName}\n" .
"Photos incluses : {$includedPhotos}\n" .
"Estimation indicative : entre " . number_format($min, 0, '.', "'") . " CHF et " . number_format($max, 0, '.', "'") . " CHF\n\n" .
"Message : {$message}";

$entry = [
    'createdAt' => date('c'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'type' => $type,
    'location' => $location,
    'event_date' => $eventDate,
    'onsite_hours' => $onsiteHours,
    'roundtrip_km' => $roundtripKm,
    'usage' => $usage,
    'pack_name' => $packName,
    'included_photos' => $includedPhotos,
    'price_min' => $min,
    'price_max' => $max,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'message' => $message,
    'maps_url' => $mapsUrl,
    'contact_summary' => $contactSummary,
];

$dir = __DIR__ . '/thal-studio/data/estimations';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$id = date('Ymd_His') . '_' . substr(md5(json_encode($entry)), 0, 8);
$ok = file_put_contents($dir . '/' . $id . '.json', json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;

echo json_encode([
    'ok' => $ok,
    'id' => $id,
    'pack' => $packName,
    'included_photos' => $includedPhotos,
    'price_min' => $min,
    'price_max' => $max,
    'usage' => $usage,
    'maps_url' => $mapsUrl,
    'contact_summary' => $contactSummary,
]);
