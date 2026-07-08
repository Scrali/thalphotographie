<?php
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function read_json_file(string $path, array $default = []): array {
    if (!is_file($path)) {
        return $default;
    }

    $json = file_get_contents($path);
    $data = json_decode((string)$json, true);
    return is_array($data) ? $data : $default;
}

function write_json_file(string $path, array $data): bool {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}

function thal_base_dir(?string $baseDir = null): string {
    return $baseDir ?: dirname(__DIR__);
}

function thal_lower(string $value): string {
    return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
}

function thal_money(float $amount): string {
    return number_format($amount, 0, '.', "'") . ' CHF';
}

function thal_date_label(?string $value, bool $withTime = false): string {
    if (!$value) {
        return '-';
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return $value;
    }

    return date($withTime ? 'd.m.Y H:i' : 'd.m.Y', $timestamp);
}

function thal_json_records(string $dir, bool $stripExtension = true): array {
    $items = [];
    if (!is_dir($dir)) {
        return $items;
    }

    foreach (glob($dir . '/*.json') ?: [] as $file) {
        $data = read_json_file($file);
        if (!$data) {
            continue;
        }

        $data['_file'] = basename($file);
        $data['_id'] = $stripExtension ? basename($file, '.json') : basename($file);
        $data['_filemtime'] = filemtime($file) ?: time();
        $items[] = $data;
    }

    return $items;
}

function thal_quote_items(?string $baseDir = null): array {
    $dir = thal_base_dir($baseDir) . '/data/quotes';
    $quotes = [];

    foreach (thal_json_records($dir) as $data) {
        $meta = is_array($data['_meta'] ?? null) ? $data['_meta'] : [];
        $amount = (float)($data['packagePrice'] ?? $data['price_recommended'] ?? 0);
        $updatedAt = (string)($meta['updatedAt'] ?? $data['updatedAt'] ?? date('c', (int)$data['_filemtime']));

        $quotes[] = [
            'id' => (string)$data['_id'],
            'quoteNumber' => (string)($data['quoteNumber'] ?? $meta['quoteNumber'] ?? 'DEV'),
            'quoteDate' => (string)($data['quoteDate'] ?? ''),
            'clientName' => (string)($data['clientName'] ?? $meta['clientName'] ?? 'Client'),
            'clientEmail' => (string)($data['clientEmail'] ?? ''),
            'clientPhone' => (string)($data['clientPhone'] ?? ''),
            'clientAddress' => (string)($data['clientAddress'] ?? ''),
            'serviceType' => (string)($data['serviceType'] ?? ''),
            'eventDate' => (string)($data['eventDate'] ?? $meta['eventDate'] ?? ''),
            'eventPlace' => (string)($data['eventPlace'] ?? ''),
            'amount' => $amount,
            'updatedAt' => $updatedAt,
            'createdAt' => (string)($meta['createdAt'] ?? $data['createdAt'] ?? $updatedAt),
            'raw' => $data,
        ];
    }

    usort($quotes, fn($a, $b) => strcmp($b['updatedAt'], $a['updatedAt']));
    return $quotes;
}

function thal_estimation_items(?string $baseDir = null): array {
    $dir = thal_base_dir($baseDir) . '/data/estimations';
    $items = [];

    foreach (thal_json_records($dir, false) as $data) {
        $items[] = [
            'id' => (string)$data['_id'],
            'createdAt' => (string)($data['createdAt'] ?? date('c', (int)$data['_filemtime'])),
            'name' => (string)($data['name'] ?? ''),
            'email' => (string)($data['email'] ?? ''),
            'phone' => (string)($data['phone'] ?? ''),
            'location' => (string)($data['location'] ?? ''),
            'eventDate' => (string)($data['event_date'] ?? ''),
            'packName' => (string)($data['pack_name'] ?? ''),
            'priceMin' => (float)($data['price_min'] ?? 0),
            'priceMax' => (float)($data['price_max'] ?? 0),
            'priceRecommended' => (float)($data['price_recommended'] ?? 0),
            'status' => (string)($data['status'] ?? 'new'),
            'convertedQuoteId' => (string)($data['convertedQuoteId'] ?? ''),
            'raw' => $data,
        ];
    }

    usort($items, fn($a, $b) => strcmp($b['createdAt'], $a['createdAt']));
    return $items;
}

function thal_client_key(string $name, string $email, string $phone): string {
    $email = trim($email);
    if ($email !== '') {
        return 'email:' . thal_lower($email);
    }

    $digits = preg_replace('/\D+/', '', $phone);
    if ($digits) {
        return 'phone:' . $digits;
    }

    return 'name:' . thal_lower(trim($name) ?: 'client');
}

function thal_empty_client(string $name, string $email = '', string $phone = '', string $address = ''): array {
    return [
        'name' => $name !== '' ? $name : 'Client',
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'quoteCount' => 0,
        'estimateCount' => 0,
        'quotedTotal' => 0.0,
        'potentialMin' => 0.0,
        'potentialMax' => 0.0,
        'lastActivity' => '',
        'latestQuoteId' => '',
        'latestQuoteNumber' => '',
        'latestEstimateId' => '',
        'latestLabel' => '',
    ];
}

function thal_collect_clients(array $quotes, array $estimations): array {
    $clients = [];

    foreach ($quotes as $quote) {
        $key = thal_client_key($quote['clientName'], $quote['clientEmail'], $quote['clientPhone']);
        if (!isset($clients[$key])) {
            $clients[$key] = thal_empty_client($quote['clientName'], $quote['clientEmail'], $quote['clientPhone'], $quote['clientAddress']);
        }

        $clients[$key]['quoteCount']++;
        $clients[$key]['quotedTotal'] += (float)$quote['amount'];
        if ($quote['clientEmail'] !== '') $clients[$key]['email'] = $quote['clientEmail'];
        if ($quote['clientPhone'] !== '') $clients[$key]['phone'] = $quote['clientPhone'];
        if ($quote['clientAddress'] !== '') $clients[$key]['address'] = $quote['clientAddress'];

        if ($quote['updatedAt'] > $clients[$key]['lastActivity']) {
            $clients[$key]['lastActivity'] = $quote['updatedAt'];
            $clients[$key]['latestQuoteId'] = $quote['id'];
            $clients[$key]['latestQuoteNumber'] = $quote['quoteNumber'];
            $clients[$key]['latestLabel'] = 'Devis ' . $quote['quoteNumber'];
        }
    }

    foreach ($estimations as $estimate) {
        $key = thal_client_key($estimate['name'], $estimate['email'], $estimate['phone']);
        if (!isset($clients[$key])) {
            $clients[$key] = thal_empty_client($estimate['name'], $estimate['email'], $estimate['phone']);
        }

        $clients[$key]['estimateCount']++;
        $clients[$key]['potentialMin'] += (float)$estimate['priceMin'];
        $clients[$key]['potentialMax'] += (float)$estimate['priceMax'];
        if ($estimate['email'] !== '') $clients[$key]['email'] = $estimate['email'];
        if ($estimate['phone'] !== '') $clients[$key]['phone'] = $estimate['phone'];

        if ($estimate['createdAt'] > $clients[$key]['lastActivity']) {
            $clients[$key]['lastActivity'] = $estimate['createdAt'];
            $clients[$key]['latestEstimateId'] = $estimate['id'];
            $clients[$key]['latestLabel'] = 'Estimation';
        }
    }

    $clients = array_values($clients);
    usort($clients, fn($a, $b) => strcmp($b['lastActivity'], $a['lastActivity']));
    return $clients;
}

function thal_prefill_query(array $client): string {
    return http_build_query([
        'clientName' => $client['name'] ?? '',
        'clientEmail' => $client['email'] ?? '',
        'clientPhone' => $client['phone'] ?? '',
        'clientAddress' => $client['address'] ?? '',
    ]);
}
