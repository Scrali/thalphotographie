<?php
header('Content-Type: application/json; charset=utf-8');

function fail_response(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

$settingsFile = __DIR__ . '/thal-studio/data/settings/estimation.json';
$settings = [
    'home_lat' => 46.8225,
    'home_lon' => 6.5019,
    'ors_api_key' => '',
];
if (is_file($settingsFile)) {
    $json = json_decode((string)file_get_contents($settingsFile), true);
    if (is_array($json)) $settings = array_merge($settings, $json);
}

$location = trim((string)($_GET['location'] ?? ''));
if ($location === '') fail_response('Lieu manquant.');

$key = trim((string)($settings['ors_api_key'] ?? ''));
if ($key === '') fail_response('Clé OpenRouteService manquante. Ajoute-la dans THAL Studio > Tarification.');

function http_json(string $url): array {
    $context = stream_context_create(['http' => ['method' => 'GET', 'timeout' => 12, 'ignore_errors' => true]]);
    $raw = @file_get_contents($url, false, $context);
    if ($raw === false) fail_response('Impossible de contacter OpenRouteService.', 502);
    $data = json_decode($raw, true);
    if (!is_array($data)) fail_response('Réponse OpenRouteService invalide.', 502);
    return $data;
}

$geo = http_json('https://api.openrouteservice.org/geocode/search?api_key=' . rawurlencode($key) . '&text=' . rawurlencode($location) . '&size=1');
if (empty($geo['features'][0]['geometry']['coordinates'])) fail_response('Lieu introuvable.');

$dest = $geo['features'][0]['geometry']['coordinates'];
$homeLon = (float)$settings['home_lon'];
$homeLat = (float)$settings['home_lat'];

$route = http_json('https://api.openrouteservice.org/v2/directions/driving-car?api_key=' . rawurlencode($key) . '&start=' . rawurlencode($homeLon . ',' . $homeLat) . '&end=' . rawurlencode(((float)$dest[0]) . ',' . ((float)$dest[1])));

$summary = $route['features'][0]['properties']['summary'] ?? null;
if (!$summary || !isset($summary['distance'])) fail_response('Distance introuvable.');

$onewayKm = (float)$summary['distance'] / 1000;
$onewayMinutes = isset($summary['duration']) ? ((float)$summary['duration'] / 60) : 0;

echo json_encode([
    'ok' => true,
    'oneway_km' => round($onewayKm, 1),
    'roundtrip_km' => round($onewayKm * 2, 1),
    'oneway_minutes' => round($onewayMinutes),
    'roundtrip_minutes' => round($onewayMinutes * 2),
    'destination_label' => $geo['features'][0]['properties']['label'] ?? $location,
], JSON_UNESCAPED_UNICODE);
