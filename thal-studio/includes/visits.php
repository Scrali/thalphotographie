<?php
function thal_visits_file(?string $baseDir = null): string {
    $baseDir = $baseDir ?: dirname(__DIR__);
    return $baseDir . '/data/stats/visits.json';
}

function thal_visits_summary(?string $baseDir = null): array {
    $file = thal_visits_file($baseDir);
    $defaults = ['total' => 0, 'countries' => [], 'daily' => []];
    if (!is_file($file)) return $defaults;
    $data = json_decode((string)file_get_contents($file), true);
    return is_array($data) ? array_merge($defaults, $data) : $defaults;
}

function thal_is_private_ip(string $ip): bool {
    if ($ip === '') return true;
    if (in_array($ip, ['127.0.0.1', '::1'], true)) return true;
    return (bool)preg_match('/^(10\.|192\.168\.|172\.(1[6-9]|2\d|3[0-1])\.)/', $ip);
}

// Interroge ip-api.com (gratuit, sans clé) pour ne conserver que le pays — jamais l'IP elle-même.
function thal_lookup_country(string $ip): array {
    if (thal_is_private_ip($ip)) {
        return ['code' => 'XX', 'name' => 'Local / inconnu'];
    }
    if (!function_exists('curl_init')) {
        return ['code' => 'XX', 'name' => 'Inconnu'];
    }
    $ch = curl_init('http://ip-api.com/json/' . urlencode($ip) . '?fields=status,country,countryCode');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    $response = @curl_exec($ch);
    curl_close($ch);

    if (!$response) return ['code' => 'XX', 'name' => 'Inconnu'];
    $data = json_decode($response, true);
    if (!is_array($data) || ($data['status'] ?? '') !== 'success') return ['code' => 'XX', 'name' => 'Inconnu'];

    return [
        'code' => (string)($data['countryCode'] ?? 'XX'),
        'name' => (string)($data['country'] ?? 'Inconnu'),
    ];
}

function thal_record_visit(array $country, ?string $baseDir = null): void {
    $file = thal_visits_file($baseDir);
    if (!is_dir(dirname($file))) mkdir(dirname($file), 0755, true);

    $fp = fopen($file, 'c+');
    if (!$fp) return;

    flock($fp, LOCK_EX);
    $raw = stream_get_contents($fp);
    $data = json_decode((string)$raw, true);
    if (!is_array($data)) $data = ['total' => 0, 'countries' => [], 'daily' => []];

    $data['total'] = (int)($data['total'] ?? 0) + 1;

    $code = (string)($country['code'] ?: 'XX');
    $name = (string)($country['name'] ?: 'Inconnu');
    if (!isset($data['countries'][$code]) || !is_array($data['countries'][$code])) {
        $data['countries'][$code] = ['name' => $name, 'count' => 0];
    }
    $data['countries'][$code]['name'] = $name;
    $data['countries'][$code]['count'] = (int)($data['countries'][$code]['count'] ?? 0) + 1;

    $today = date('Y-m-d');
    $data['daily'][$today] = (int)($data['daily'][$today] ?? 0) + 1;
    if (count($data['daily']) > 90) {
        ksort($data['daily']);
        $data['daily'] = array_slice($data['daily'], -90, null, true);
    }

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
}
