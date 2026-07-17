<?php
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function read_json_file(string $path, array $default = []): array {
    if (!is_file($path)) {
        return $default;
    }

    $json = (string)file_get_contents($path);
    // Tolère un BOM UTF-8 en tête de fichier (json_decode le refuse sinon).
    if (str_starts_with($json, "\xEF\xBB\xBF")) {
        $json = substr($json, 3);
    }

    $data = json_decode($json, true);
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

// ===== Galerie photo =====

const THAL_GALLERY_IGNORE_DIRS = ['scripts', '.git', '.github', '__MACOSX'];
const THAL_GALLERY_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

// Alias de genres reconnus dans un nom de fichier pour le tri automatique
// (clé = fragment recherché dans le nom normalisé, valeur = catégorie visée).
const THAL_GALLERY_ALIASES = [
    'mariage' => 'Mariage',
    'wedding' => 'Mariage',
    'portrait' => 'Portraits',
    'evenement' => 'Evenements',
    'concert' => 'Evenements',
    'soiree' => 'Evenements',
    'projet' => 'Projets',
    'corporate' => 'Corporate',
    'entreprise' => 'Corporate',
];

function thal_photos_dir(?string $baseDir = null): string {
    return thal_base_dir($baseDir) . '/../photos';
}

function thal_gallery_json_path(?string $baseDir = null): string {
    return thal_photos_dir($baseDir) . '/gallery.auto.json';
}

function thal_is_allowed_image_ext(string $ext): bool {
    return in_array(thal_lower($ext), THAL_GALLERY_IMAGE_EXTENSIONS, true);
}

function thal_truncate(string $value, int $length): string {
    return function_exists('mb_substr') ? mb_substr($value, 0, $length) : substr($value, 0, $length);
}

function thal_sanitize_category(string $name): string {
    $name = trim($name);
    $name = preg_replace('/[\/\\\\]+/', ' ', $name) ?? '';
    $name = preg_replace('/[^\p{L}\p{N} _-]/u', '', $name) ?? '';
    $name = trim(preg_replace('/\s+/', ' ', $name) ?? '');
    return thal_truncate($name, 60);
}

function thal_sanitize_filename(string $name): string {
    $name = basename($name);
    $ext = thal_lower(preg_replace('/[^a-zA-Z0-9]/', '', pathinfo($name, PATHINFO_EXTENSION)) ?? '');
    $base = pathinfo($name, PATHINFO_FILENAME);
    $base = preg_replace('/[^\p{L}\p{N} _-]/u', '', $base) ?? '';
    $base = trim(preg_replace('/\s+/', '_', $base) ?? '');
    if ($base === '') {
        $base = 'photo';
    }
    return $ext !== '' ? $base . '.' . $ext : $base;
}

function thal_gallery_normalize(string $value): string {
    $value = thal_lower($value);
    $transliterations = ['é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','à'=>'a','â'=>'a','ä'=>'a','ù'=>'u','û'=>'u','ü'=>'u','ô'=>'o','ö'=>'o','î'=>'i','ï'=>'i','ç'=>'c'];
    return strtr($value, $transliterations);
}

// Devine la catégorie d'une photo à partir de son nom de fichier :
// correspondance directe avec une catégorie existante, puis alias connus (mariage, portrait...).
function thal_guess_category(string $filename, array $categories): ?string {
    $norm = thal_gallery_normalize(pathinfo($filename, PATHINFO_FILENAME));

    foreach ($categories as $cat) {
        $catNorm = thal_gallery_normalize($cat);
        if ($catNorm !== '' && str_contains($norm, $catNorm)) {
            return $cat;
        }
    }

    foreach (THAL_GALLERY_ALIASES as $needle => $target) {
        if (!str_contains($norm, $needle)) {
            continue;
        }
        foreach ($categories as $cat) {
            if (thal_gallery_normalize($cat) === thal_gallery_normalize($target)) {
                return $cat;
            }
        }
        return $target;
    }

    return null;
}

// Lit le dossier photos/ sur disque et le réconcilie avec gallery.auto.json :
// conserve l'ordre existant, ajoute les nouveaux fichiers trouvés, retire les entrées orphelines.
function thal_gallery_scan(?string $baseDir = null): array {
    $photosDir = thal_photos_dir($baseDir);
    if (!is_dir($photosDir)) {
        return [];
    }

    $stored = read_json_file(thal_gallery_json_path($baseDir));

    $onDisk = [];
    foreach (scandir($photosDir) ?: [] as $entry) {
        if ($entry === '.' || $entry === '..' || in_array($entry, THAL_GALLERY_IGNORE_DIRS, true)) {
            continue;
        }
        $full = $photosDir . '/' . $entry;
        if (!is_dir($full)) {
            continue;
        }
        $files = [];
        foreach (scandir($full) ?: [] as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (thal_is_allowed_image_ext(pathinfo($file, PATHINFO_EXTENSION))) {
                $files[] = $file;
            }
        }
        $onDisk[$entry] = $files;
    }

    $result = [];
    foreach ($stored as $cat => $files) {
        if (!is_string($cat) || !isset($onDisk[$cat])) {
            continue;
        }
        $files = is_array($files) ? $files : [];
        $kept = array_values(array_filter($files, fn($f) => in_array($f, $onDisk[$cat], true)));
        $new = array_values(array_diff($onDisk[$cat], $kept));
        sort($new);
        $result[$cat] = array_merge($kept, $new);
        unset($onDisk[$cat]);
    }

    foreach ($onDisk as $cat => $files) {
        sort($files);
        $result[$cat] = $files;
    }

    return $result;
}

function thal_gallery_save(array $data, ?string $baseDir = null): bool {
    return write_json_file(thal_gallery_json_path($baseDir), $data);
}
