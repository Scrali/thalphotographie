<?php
function thal_pricing_defaults(): array {
    return [
        'home_address' => 'Sainte-Croix, VD, Suisse',
        'home_lat' => 46.8225,
        'home_lon' => 6.5019,
        'ors_api_key' => '',
        'hourly_rate' => 30,
        'km_rate' => 0.60,
        'prep_hours' => 0.5,
        'sort_hours_per_onsite_hour' => 0.2,
        'retouch_minutes_per_photo' => 2.0,
        'delivery_hours' => 0.3,
        'photos_per_hour_private' => 18,
        'photos_per_hour_commercial' => 22,
        'min_photos_private' => 12,
        'min_photos_commercial' => 20,
        'gear_cost' => 25,
        'backup_cost' => 15,
        'commercial_fee' => 150,
        'range_percent' => 8,
        'rounding' => 10,
    ];
}

function thal_pricing_settings(?string $baseDir = null): array {
    $defaults = thal_pricing_defaults();
    $baseDir = $baseDir ?: dirname(__DIR__);
    $file = $baseDir . '/data/settings/estimation.json';

    if (!is_file($file)) {
        return $defaults;
    }

    $data = json_decode((string)file_get_contents($file), true);
    return is_array($data) ? array_merge($defaults, $data) : $defaults;
}

function thal_round_to(float $value, float $step): float {
    $step = max(1, $step);
    return round($value / $step) * $step;
}

function thal_pricing_calculate(array $input, array $settings): array {
    $onsiteHours = max(0.25, (float)($input['onsite_hours'] ?? 1));
    $roundtripKm = max(0, (float)($input['roundtrip_km'] ?? 0));
    $usage = (string)($input['usage'] ?? 'private');

    $photosPerHour = $usage === 'commercial'
        ? (float)$settings['photos_per_hour_commercial']
        : (float)$settings['photos_per_hour_private'];

    $minPhotos = $usage === 'commercial'
        ? (int)$settings['min_photos_commercial']
        : (int)$settings['min_photos_private'];

    $includedPhotos = max($minPhotos, (int)round($onsiteHours * $photosPerHour));

    $prepHours = (float)$settings['prep_hours'];
    $travelHours = max(0, (float)($input['roundtrip_minutes'] ?? 0)) / 60;
    $sortHours = $onsiteHours * (float)$settings['sort_hours_per_onsite_hour'];
    $editHours = ($includedPhotos * (float)$settings['retouch_minutes_per_photo']) / 60;
    $deliveryHours = (float)$settings['delivery_hours'];

    $totalHours = $prepHours + $travelHours + $onsiteHours + $sortHours + $editHours + $deliveryHours;

    $hourlyValue = $totalHours * (float)$settings['hourly_rate'];
    $kmCost = $roundtripKm * (float)$settings['km_rate'];
    $gearCost = (float)$settings['gear_cost'] + (float)$settings['backup_cost'];
    $commercialFee = $usage === 'commercial' ? (float)$settings['commercial_fee'] : 0;

    $calculated = $hourlyValue + $kmCost + $gearCost + $commercialFee;
    $rounded = thal_round_to($calculated, (float)$settings['rounding']);
    $range = max(0, (float)$settings['range_percent']) / 100;

    $priceMin = thal_round_to($rounded * (1 - $range), (float)$settings['rounding']);
    $priceMax = thal_round_to($rounded * (1 + $range), (float)$settings['rounding']);

    $hoursLabel = rtrim(rtrim(number_format($onsiteHours, 1, '.', ''), '0'), '.');
    $packName = ($onsiteHours >= 4 ? 'Premium ' : 'Essentiel ') . $hoursLabel . ' heures';
    if ($usage === 'commercial') {
        $packName .= ' Commercial';
    }

    return [
        'pack_name' => $packName,
        'included_photos' => $includedPhotos,
        'price_recommended' => (int)$rounded,
        'price_min' => (int)$priceMin,
        'price_max' => (int)$priceMax,
        'total_hours' => round($totalHours, 2),
        'prep_hours' => round($prepHours, 2),
        'travel_hours' => round($travelHours, 2),
        'onsite_hours' => round($onsiteHours, 2),
        'sort_hours' => round($sortHours, 2),
        'edit_hours' => round($editHours, 2),
        'delivery_hours' => round($deliveryHours, 2),
        'hourly_value' => round($hourlyValue, 2),
        'km_cost' => round($kmCost, 2),
        'gear_cost' => round($gearCost, 2),
        'commercial_fee' => round($commercialFee, 2),
        'usage' => $usage,
        'roundtrip_km' => round($roundtripKm, 1),
        'km_rate' => (float)$settings['km_rate'],
        'hourly_rate' => (float)$settings['hourly_rate'],
    ];
}

function thal_estimation_to_quote(array $estimate, array $pricing, array $settings): array {
    $date = date('Y-m-d');
    $valid = date('Y-m-d', strtotime('+30 days'));
    $start = '14:00';
    $endTimestamp = strtotime($start) + ((float)$pricing['onsite_hours'] * 3600);
    $end = date('H:i', $endTimestamp);

    $clientName = trim((string)($estimate['name'] ?? 'Client'));
    if ($clientName === '') $clientName = 'Client';

    $serviceType = (string)($estimate['type'] ?? 'Prestation photographique');
    $location = (string)($estimate['location'] ?? '');

    return [
        'quoteNumber' => 'DEV-' . date('Y') . '-' . date('His'),
        'quoteDate' => $date,
        'validUntil' => $valid,
        'clientName' => $clientName,
        'clientEmail' => (string)($estimate['email'] ?? ''),
        'clientPhone' => (string)($estimate['phone'] ?? ''),
        'clientAddress' => '',
        'serviceType' => $serviceType,
        'eventDate' => (string)($estimate['event_date'] ?? ''),
        'eventPlace' => $location,
        'startTime' => $start,
        'endTime' => $end,
        'photosDelivered' => $pricing['included_photos'] . ' photos retouchées incluses',
        'deliveryDelay' => '1 semaine',
        'description' => "Pack recommandé : {$pricing['pack_name']}\n" .
            "Usage : " . ($pricing['usage'] === 'commercial' ? 'Utilisation commerciale' : 'Cadre privé') . "\n" .
            "Estimation d’origine : entre {$pricing['price_min']} CHF et {$pricing['price_max']} CHF.",
        'distanceKm' => (string)$pricing['roundtrip_km'],
        'kmRate' => (string)$pricing['km_rate'],
        'prepHours' => (string)$pricing['prep_hours'],
        'travelHours' => (string)$pricing['travel_hours'],
        'sortHours' => (string)$pricing['sort_hours'],
        'editHours' => (string)$pricing['edit_hours'],
        'deliveryHours' => (string)$pricing['delivery_hours'],
        'hourlyRate' => (string)$pricing['hourly_rate'],
        'gearCost' => (string)$pricing['gear_cost'],
        'priceMode' => 'auto',
        'packagePrice' => (string)$pricing['price_recommended'],
        'discountPercent' => '0',
        'rounding' => (string)$settings['rounding'],
        'showCommercialRights' => $pricing['usage'] === 'commercial',
        'commercialRightsFee' => (string)$pricing['commercial_fee'],
        'commercialRightsLabel' => 'Utilisation commerciale',
        'showHourly' => true,
        'included' => "Prise de vue pendant {$pricing['onsite_hours']} heures\nTri des photos\nRetouches de base\nGalerie privée en ligne\nLivraison HD\nSauvegarde 1 an",
        'terms' => "Devis valable jusqu’à la date indiquée. Paiement à réception du devis validé ou au plus tard le jour de la prestation, sauf accord contraire.",
        '_meta' => [
            'source' => 'estimation',
            'sourceId' => (string)($estimate['_id'] ?? ''),
            'pricingEngine' => 'v0.5.0',
        ],
    ];
}
