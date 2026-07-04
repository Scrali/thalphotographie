<?php
function thal_pack_settings(?string $baseDir = null): array {
    $baseDir = $baseDir ?: dirname(__DIR__);
    $file = $baseDir . '/data/settings/packs.json';
    $defaults = [
        'home_address'=>'Sainte-Croix, VD, Suisse',
        'home_lat'=>46.8225,
        'home_lon'=>6.5019,
        'ors_api_key'=>'',
        'km_included'=>30,
        'km_rate'=>0.60,
        'commercial_percent'=>30,
        'range_percent'=>10,
        'rounding'=>10,
        'packs'=>[
            ['id'=>'pack_1','name'=>'Essentiel 1 heure','duration_hours'=>1,'base_price'=>250,'photos'=>20,'features'=>['Galerie privée','Livraison HD','Sauvegarde 1 an']],
            ['id'=>'pack_2','name'=>'Essentiel 2 heures','duration_hours'=>2,'base_price'=>390,'photos'=>40,'features'=>['Galerie privée','Livraison HD','Sauvegarde 1 an']],
            ['id'=>'pack_3','name'=>'Premium 4 heures','duration_hours'=>4,'base_price'=>790,'photos'=>80,'features'=>['Galerie privée','Livraison HD','Sauvegarde 1 an']],
        ],
    ];
    if (!is_file($file)) return $defaults;
    $data = json_decode((string)file_get_contents($file), true);
    return is_array($data) ? array_merge($defaults, $data) : $defaults;
}

function thal_round_to(float $value, float $step): float {
    $step = max(1, $step);
    return round($value / $step) * $step;
}

function thal_select_pack(float $requestedHours, array $packs): array {
    usort($packs, fn($a, $b) => ((float)$a['duration_hours']) <=> ((float)$b['duration_hours']));
    foreach ($packs as $pack) {
        if ((float)$pack['duration_hours'] >= $requestedHours) return $pack;
    }
    return end($packs);
}

function thal_pack_calculate(array $input, array $settings): array {
    $requestedHours = max(0.5, (float)($input['onsite_hours'] ?? 2));
    $roundtripKm = max(0, (float)($input['roundtrip_km'] ?? 0));
    $usage = (string)($input['usage'] ?? 'private');

    $pack = thal_select_pack($requestedHours, $settings['packs']);
    $basePrice = (float)$pack['base_price'];

    $extraKm = max(0, $roundtripKm - (float)$settings['km_included']);
    $kmCost = $extraKm * (float)$settings['km_rate'];

    $commercialFee = 0;
    if ($usage === 'commercial') {
        $commercialFee = ($basePrice + $kmCost) * ((float)$settings['commercial_percent'] / 100);
    }

    $recommended = thal_round_to($basePrice + $kmCost + $commercialFee, (float)$settings['rounding']);
    $range = max(0, (float)$settings['range_percent']) / 100;

    return [
        'pack_id'=>(string)($pack['id'] ?? ''),
        'pack_name'=>(string)$pack['name'],
        'pack_duration'=>(float)$pack['duration_hours'],
        'requested_hours'=>$requestedHours,
        'included_photos'=>(int)$pack['photos'],
        'features'=>array_values($pack['features'] ?? []),
        'base_price'=>(int)$basePrice,
        'roundtrip_km'=>round($roundtripKm, 1),
        'km_included'=>(float)$settings['km_included'],
        'extra_km'=>round($extraKm, 1),
        'km_rate'=>(float)$settings['km_rate'],
        'km_cost'=>(int)thal_round_to($kmCost, (float)$settings['rounding']),
        'commercial_fee'=>(int)thal_round_to($commercialFee, (float)$settings['rounding']),
        'commercial_percent'=>(float)$settings['commercial_percent'],
        'price_recommended'=>(int)$recommended,
        'price_min'=>(int)thal_round_to($recommended * (1 - $range), (float)$settings['rounding']),
        'price_max'=>(int)thal_round_to($recommended * (1 + $range), (float)$settings['rounding']),
        'usage'=>$usage,
    ];
}

function thal_estimation_to_quote(array $estimate, array $pricing, array $settings): array {
    $start = '14:00';
    $end = date('H:i', strtotime($start) + ((float)$pricing['pack_duration'] * 3600));
    $clientName = trim((string)($estimate['name'] ?? 'Client'));
    if ($clientName === '') $clientName = 'Client';

    $features = array_merge([$pricing['included_photos'] . ' photos retouchées'], $pricing['features'] ?? []);
    $included = implode("\n", array_map(fn($f) => "- " . $f, $features));

    return [
        'quoteNumber'=>'DEV-' . date('Y') . '-' . date('His'),
        'quoteDate'=>date('Y-m-d'),
        'validUntil'=>date('Y-m-d', strtotime('+30 days')),
        'clientName'=>$clientName,
        'clientEmail'=>(string)($estimate['email'] ?? ''),
        'clientPhone'=>(string)($estimate['phone'] ?? ''),
        'clientAddress'=>'',
        'serviceType'=>(string)($estimate['type'] ?? 'Prestation photographique'),
        'eventDate'=>(string)($estimate['event_date'] ?? ''),
        'eventPlace'=>(string)($estimate['location'] ?? ''),
        'startTime'=>$start,
        'endTime'=>$end,
        'photosDelivered'=>$pricing['included_photos'] . ' photos retouchées incluses',
        'deliveryDelay'=>'1 semaine',
        'description'=>"Pack recommandé : {$pricing['pack_name']}\nUsage : " . ($pricing['usage'] === 'commercial' ? 'Utilisation commerciale' : 'Cadre privé') . "\nBudget estimatif initial : entre {$pricing['price_min']} CHF et {$pricing['price_max']} CHF.",
        'distanceKm'=>(string)$pricing['roundtrip_km'],
        'kmRate'=>(string)$pricing['km_rate'],
        'prepHours'=>'0.5',
        'travelHours'=>'0',
        'sortHours'=>'0',
        'editHours'=>'0',
        'deliveryHours'=>'0',
        'hourlyRate'=>'0',
        'gearCost'=>'0',
        'priceMode'=>'package',
        'packagePrice'=>(string)$pricing['price_recommended'],
        'discountPercent'=>'0',
        'rounding'=>(string)$settings['rounding'],
        'showCommercialRights'=>$pricing['usage'] === 'commercial',
        'commercialRightsFee'=>(string)$pricing['commercial_fee'],
        'commercialRightsLabel'=>'Utilisation commerciale',
        'showHourly'=>false,
        'included'=>$included,
        'terms'=>"Devis valable jusqu’à la date indiquée. Paiement à réception du devis validé ou au plus tard le jour de la prestation, sauf accord contraire.",
        '_meta'=>['source'=>'estimation','sourceId'=>(string)($estimate['_id'] ?? ''),'pricingEngine'=>'pack-v0.7.0'],
    ];
}

function thal_pricing_settings(?string $baseDir = null): array { return thal_pack_settings($baseDir); }
function thal_pricing_calculate(array $input, array $settings): array { return thal_pack_calculate($input, $settings); }
