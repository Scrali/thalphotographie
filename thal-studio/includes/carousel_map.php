<?php
function thal_carousel_map_settings(?string $baseDir = null): array {
    $baseDir = $baseDir ?: dirname(__DIR__);
    $file = $baseDir . '/data/settings/carousel_map.json';
    $defaults = [
        'accueil' => '',
        'portraits' => '',
        'animaux' => '',
        'reportages' => '',
        'professionnels' => '',
    ];
    if (!is_file($file)) return $defaults;
    $data = json_decode((string)file_get_contents($file), true);
    return is_array($data) ? array_merge($defaults, $data) : $defaults;
}

function thal_carousel_page_labels(): array {
    return [
        'accueil' => 'Pourquoi THAL (carrousel "THAL Photographie")',
        'portraits' => 'Portraits',
        'animaux' => 'Animaux de compagnie',
        'reportages' => 'Reportages',
        'professionnels' => 'Professionnels',
    ];
}

function thal_carousel_page_defaults(): array {
    return [
        'accueil' => 'Accueil',
        'portraits' => 'Portraits',
        'animaux' => 'Animaux',
        'reportages' => 'Evenements',
        'professionnels' => 'Commandes professionnelles',
    ];
}
