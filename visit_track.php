<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/thal-studio/includes/visits.php';

$cookieName = 'thal_visit';
$isNewVisit = !isset($_COOKIE[$cookieName]);

// Fenêtre glissante de 30 minutes : une navigation ou un rechargement pendant ce
// délai ne compte pas comme une nouvelle visite (même logique qu'une "session" Analytics).
setcookie($cookieName, '1', [
    'expires' => time() + 1800,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if ($isNewVisit) {
    $ip = (string)($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '');
    if (strpos($ip, ',') !== false) $ip = trim(explode(',', $ip)[0]);

    $country = thal_lookup_country($ip);
    thal_record_visit($country);
}

echo json_encode(['ok' => true, 'new' => $isNewVisit]);
