<?php
header('Content-Type: application/json; charset=utf-8');

function fail_response(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

$settingsFile = __DIR__ . '/thal-studio/data/settings/packs.json';
$settings = [
    'home_lat' => 46.8225,
    'home_lon' => 6.5019,
    'ors_api_key' => '',
];
if (is_file($settingsFile)) {
    $json = json_decode((string)file_get_contents($settingsFile), true);
    if (is_array($json)) $settings = array_merge($settings, $json);
}

$text = trim((string)($_GET['text'] ?? ''));
if (mb_strlen($text) < 3) {
    echo json_encode(['ok' => true, 'suggestions' => []], JSON_UNESCAPED_UNICODE);
    exit;
}

$key = trim((string)($settings['ors_api_key'] ?? ''));
if ($key === '') fail_response('Clé OpenRouteService manquante. Ajoute-la dans THAL Studio > Packs & tarifs.');

$context = stream_context_create(['http' => ['method' => 'GET', 'timeout' => 8, 'ignore_errors' => true]]);
$url = 'https://api.openrouteservice.org/geocode/autocomplete'
    . '?api_key=' . rawurlencode($key)
    . '&text=' . rawurlencode($text)
    . '&size=5'
    . '&focus.point.lon=' . rawurlencode((string)(float)$settings['home_lon'])
    . '&focus.point.lat=' . rawurlencode((string)(float)$settings['home_lat']);

$raw = @file_get_contents($url, false, $context);
if ($raw === false) fail_response('Impossible de contacter OpenRouteService.', 502);

$data = json_decode($raw, true);
if (!is_array($data)) fail_response('Réponse OpenRouteService invalide.', 502);

$suggestions = [];
foreach ((array)($data['features'] ?? []) as $feature) {
    $label = $feature['properties']['label'] ?? null;
    $coords = $feature['geometry']['coordinates'] ?? null;
    if (!$label || !is_array($coords) || count($coords) < 2) continue;
    $suggestions[] = [
        'label' => $label,
        'lon' => (float)$coords[0],
        'lat' => (float)$coords[1],
    ];
}

echo json_encode(['ok' => true, 'suggestions' => $suggestions], JSON_UNESCAPED_UNICODE);
