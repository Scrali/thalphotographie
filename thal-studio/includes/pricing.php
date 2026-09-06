<?php
function thal_pack_gammes(): array {
    return [
        'identite'=>'Identité',
        'portraits'=>'Portraits',
        'animaux'=>'Animaux de compagnie',
        'reportages'=>'Reportages',
        'professionnels'=>'Professionnels',
    ];
}

function thal_pack_id_slug(string $gamme, string $name): string {
    $value = $gamme . ' ' . $name;
    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($converted !== false) $value = $converted;
    $value = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $value));
    $value = trim((string)$value, '_');
    return substr($value ?: ($gamme . '_pack'), 0, 60);
}

function thal_pack_settings(?string $baseDir = null): array {
    $baseDir = $baseDir ?: dirname(__DIR__);
    $file = $baseDir . '/data/settings/packs.json';
    $defaults = [
        'range_percent'=>10,
        'rounding'=>10,
        'packs'=>[
            ['id'=>'identite_photo','gamme'=>'identite','name'=>'Photo d’identité à domicile','details'=>'1 personne, 4 photos, zone 15 km incluse (depuis Sainte-Croix), +0.75 CHF/km au-delà','price'=>45,'photos'=>4,'custom'=>false,'features'=>['Impression sur place','Zone 15 km incluse','Tirage papier au format officiel']],
            ['id'=>'identite_supplement_2','gamme'=>'identite','name'=>'2ᵉ personne','details'=>'Même passage, tarif réduit','price'=>20,'photos'=>4,'custom'=>false,'features'=>[]],
            ['id'=>'identite_supplement_3plus','gamme'=>'identite','name'=>'3ᵉ personne et suivantes','details'=>'Même passage, tarif réduit','price'=>10,'photos'=>4,'custom'=>false,'features'=>[]],
            ['id'=>'portrait_individuel','gamme'=>'portraits','name'=>'Individuel','details'=>'45 minutes','price'=>195,'photos'=>10,'custom'=>false,'features'=>['Sélection accompagnée','Livraison privée sécurisée']],
            ['id'=>'portrait_duo','gamme'=>'portraits','name'=>'Duo / Grossesse','details'=>'1 heure','price'=>245,'photos'=>15,'custom'=>false,'features'=>['Sélection accompagnée','Livraison privée sécurisée']],
            ['id'=>'portrait_famille','gamme'=>'portraits','name'=>'Famille','details'=>'1 heure 30','price'=>325,'photos'=>20,'custom'=>false,'features'=>['Sélection accompagnée','Livraison privée sécurisée']],
            ['id'=>'portrait_groupe','gamme'=>'portraits','name'=>'Grand groupe','details'=>'École, amis, famille élargie','price'=>0,'photos'=>0,'custom'=>true,'features'=>[]],
            ['id'=>'animaux_essentiel','gamme'=>'animaux','name'=>'Essentiel','details'=>'30 minutes, intérieur ou extérieur','price'=>165,'photos'=>8,'custom'=>false,'features'=>['Intérieur ou extérieur','Livraison privée sécurisée']],
            ['id'=>'animaux_duo','gamme'=>'animaux','name'=>'Duo / Fratrie','details'=>'45 minutes, 2 animaux ou animal et propriétaire','price'=>225,'photos'=>12,'custom'=>false,'features'=>['Livraison privée sécurisée']],
            ['id'=>'animaux_balade','gamme'=>'animaux','name'=>'Balade extérieure','details'=>'1 heure, en mouvement, plusieurs lieux','price'=>295,'photos'=>15,'custom'=>false,'features'=>['Livraison privée sécurisée']],
            ['id'=>'reportage_essentiel','gamme'=>'reportages','name'=>'Essentiel','details'=>'2 heures de couverture','price'=>390,'photos'=>0,'custom'=>false,'features'=>['Tri et sélection des meilleures images','Retouche professionnelle','Livraison privée sécurisée','Téléchargement HD']],
            ['id'=>'reportage_demi_journee','gamme'=>'reportages','name'=>'Demi-journée','details'=>'4 heures de couverture','price'=>690,'photos'=>0,'custom'=>false,'features'=>['Tri et sélection des meilleures images','Retouche professionnelle','Livraison privée sécurisée','Téléchargement HD']],
            ['id'=>'reportage_etendu','gamme'=>'reportages','name'=>'Étendu','details'=>'6 heures de couverture','price'=>990,'photos'=>0,'custom'=>false,'features'=>['Tri et sélection des meilleures images','Retouche professionnelle','Livraison privée sécurisée','Téléchargement HD']],
            ['id'=>'reportage_mariage','gamme'=>'reportages','name'=>'Mariage — journée complète','details'=>'Préparatifs à la soirée','price'=>0,'photos'=>0,'custom'=>true,'features'=>[]],
            ['id'=>'pro_artisan','gamme'=>'professionnels','name'=>'Artisan','details'=>'Portrait, atelier, équipe','price'=>490,'photos'=>15,'custom'=>false,'features'=>['Portrait professionnel','Images d’atelier','Photos d’équipe']],
            ['id'=>'pro_pme','gamme'=>'professionnels','name'=>'PME','details'=>'Collaborateurs, locaux, communication','price'=>890,'photos'=>25,'custom'=>false,'features'=>['Portraits des collaborateurs','Images des locaux','Contenu pour votre communication']],
            ['id'=>'pro_corporate','gamme'=>'professionnels','name'=>'Corporate / multi-sites','details'=>'Plusieurs sites ou équipes','price'=>0,'photos'=>0,'custom'=>true,'features'=>['Coordination sur mesure','Contenu de communication complet']],
        ],
    ];
    if (!is_file($file)) return $defaults;
    $data = json_decode((string)file_get_contents($file), true);
    if (!is_array($data)) return $defaults;
    $merged = array_merge($defaults, $data);
    if (empty($merged['packs'])) $merged['packs'] = $defaults['packs'];
    return $merged;
}

function thal_pack_settings_by_gamme(array $settings): array {
    $byGamme = [];
    foreach (array_keys(thal_pack_gammes()) as $gKey) $byGamme[$gKey] = [];
    foreach ($settings['packs'] as $pack) {
        $gKey = (string)($pack['gamme'] ?? '');
        if (!isset($byGamme[$gKey])) $byGamme[$gKey] = [];
        $byGamme[$gKey][] = $pack;
    }
    return $byGamme;
}

function thal_round_to(float $value, float $step): float {
    $step = max(1, $step);
    return round($value / $step) * $step;
}

function thal_find_pack(string $packId, array $packs): ?array {
    foreach ($packs as $pack) {
        if ((string)($pack['id'] ?? '') === $packId) return $pack;
    }
    return null;
}

function thal_pack_calculate(array $pack, array $settings): array {
    $basePrice = (float)($pack['price'] ?? 0);
    $recommended = $basePrice;
    $range = max(0, (float)$settings['range_percent']) / 100;

    return [
        'pack_id'=>(string)($pack['id'] ?? ''),
        'pack_name'=>(string)($pack['name'] ?? ''),
        'pack_gamme'=>(string)($pack['gamme'] ?? ''),
        'pack_details'=>(string)($pack['details'] ?? ''),
        'included_photos'=>(int)($pack['photos'] ?? 0),
        'features'=>array_values($pack['features'] ?? []),
        'is_custom'=>!empty($pack['custom']),
        'base_price'=>(int)$basePrice,
        'price_recommended'=>(int)$recommended,
        'price_min'=>(int)thal_round_to($recommended * (1 - $range), (float)$settings['rounding']),
        'price_max'=>(int)thal_round_to($recommended * (1 + $range), (float)$settings['rounding']),
    ];
}

function thal_estimation_to_quote(array $estimate, array $pricing, array $settings): array {
    $clientName = trim((string)($estimate['name'] ?? 'Client'));
    if ($clientName === '') $clientName = 'Client';

    $features = $pricing['included_photos'] > 0
        ? array_merge([$pricing['included_photos'] . ' photos retouchées'], $pricing['features'] ?? [])
        : ($pricing['features'] ?? []);
    $included = implode("\n", array_map(fn($f) => "- " . $f, $features));

    $descriptionParts = [];
    if (!empty($pricing['pack_name'])) {
        $descriptionParts[] = "Formule retenue : {$pricing['pack_name']}" . ($pricing['pack_details'] ? " ({$pricing['pack_details']})" : '');
    }
    if (!empty($estimate['message'])) {
        $descriptionParts[] = "Message du client :\n" . $estimate['message'];
    }

    return [
        'quoteNumber'=>'DEV-' . date('Y') . '-' . date('His'),
        'quoteDate'=>date('Y-m-d'),
        'validUntil'=>date('Y-m-d', strtotime('+30 days')),
        'clientName'=>$clientName,
        'clientEmail'=>(string)($estimate['email'] ?? ''),
        'clientPhone'=>(string)($estimate['phone'] ?? ''),
        'clientAddress'=>'',
        'serviceType'=>(string)($pricing['pack_name'] ?: 'Prestation photographique'),
        'eventDate'=>(string)($estimate['event_date'] ?? ''),
        'eventPlace'=>(string)($estimate['location'] ?? ''),
        'startTime'=>'',
        'endTime'=>'',
        'photosDelivered'=>$pricing['included_photos'] > 0 ? $pricing['included_photos'] . ' photos retouchées incluses' : '',
        'deliveryDelay'=>'2 à 3 semaines',
        'description'=>implode("\n\n", $descriptionParts),
        'distanceKm'=>'',
        'kmRate'=>'',
        'prepHours'=>'0',
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
        'showHourly'=>false,
        'included'=>$included,
        'terms'=>"Devis valable jusqu’à la date indiquée. Paiement à réception du devis validé ou au plus tard le jour de la prestation, sauf accord contraire.",
        '_meta'=>['source'=>'estimation','sourceId'=>(string)($estimate['_id'] ?? ''),'pricingEngine'=>'pack-v1.0'],
    ];
}
