<?php
header('Content-Type: application/json; charset=utf-8');

function clean($v) { return trim((string)$v); }
function number_value($v) { return max(0, (float)$v); }

$type = clean($_POST['type'] ?? 'Événement');
$location = clean($_POST['location'] ?? '');
$eventDate = clean($_POST['event_date'] ?? '');
$onsiteHours = number_value($_POST['onsite_hours'] ?? 2);
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

$hourlyRate = 120;
$editingPerHour = 0.65;
$editingRate = 80;
$baseFee = 80;
$commercialFee = $usage === 'commercial' ? 250 : 0;

$includedPhotos = max(12, (int)round($onsiteHours * 18));
if ($usage === 'commercial') {
    $includedPhotos = max(20, (int)round($onsiteHours * 22));
}

$workCost = ($onsiteHours * $hourlyRate) + ($onsiteHours * $editingPerHour * $editingRate) + $baseFee + $commercialFee;

// Estimation volontairement sous forme de fourchette.
$min = round(($workCost * 0.90) / 10) * 10;
$max = round(($workCost * 1.18) / 10) * 10;

$packName = 'Pack ' . rtrim(rtrim(number_format($onsiteHours, 1, '.', ''), '0'), '.') . 'h';
if ($usage === 'commercial') {
    $packName .= ' Commercial';
} else {
    $packName .= ' Privé';
}

$entry = [
    'createdAt' => date('c'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'type' => $type,
    'location' => $location,
    'event_date' => $eventDate,
    'onsite_hours' => $onsiteHours,
    'usage' => $usage,
    'pack_name' => $packName,
    'included_photos' => $includedPhotos,
    'price_min' => $min,
    'price_max' => $max,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'message' => $message,
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
]);
