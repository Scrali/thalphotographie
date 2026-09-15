<?php
/**
 * Choix manuel des photos affichées sur le site public.
 *
 * Chaque "emplacement" du site (grande image d'accueil, bandeau d'une page de
 * gamme, illustration d'un bloc de Pourquoi THAL) peut être fixé sur une photo
 * précise. Laissé vide, l'emplacement reprend le comportement automatique :
 * une photo au hasard dans la catégorie correspondante.
 *
 * Le fichier est écrit dans photos/site_images.json parce que les pages
 * publiques le lisent en JavaScript, comme gallery.auto.json. Le dossier
 * thal-studio/data est protégé par un .htaccess et ne conviendrait pas.
 */

require_once __DIR__ . '/functions.php';

function thal_site_images_path(?string $baseDir = null): string
{
    return thal_photos_dir($baseDir) . '/site_images.json';
}

/**
 * Les emplacements, dans l'ordre d'affichage du formulaire.
 * 'auto' décrit ce qui se passe quand l'emplacement est laissé vide.
 */
function thal_site_image_slots(): array
{
    return [
        'hero' => [
            'group' => 'Accueil',
            'label' => 'Grande image d’accueil',
            'auto'  => 'une photo au hasard dans « Accueil »',
        ],
        'band_identite' => [
            'group' => 'Bandeaux des pages de prestation',
            'label' => 'Identité',
            'auto'  => 'une photo au hasard dans la catégorie liée à Identité',
        ],
        'band_portraits' => [
            'group' => 'Bandeaux des pages de prestation',
            'label' => 'Portraits',
            'auto'  => 'une photo au hasard dans la catégorie liée à Portraits',
        ],
        'band_animaux' => [
            'group' => 'Bandeaux des pages de prestation',
            'label' => 'Animaux de compagnie',
            'auto'  => 'une photo au hasard dans la catégorie liée à Animaux',
        ],
        'band_reportages' => [
            'group' => 'Bandeaux des pages de prestation',
            'label' => 'Reportages',
            'auto'  => 'une photo au hasard dans la catégorie liée à Reportages',
        ],
        'band_professionnels' => [
            'group' => 'Bandeaux des pages de prestation',
            'label' => 'Professionnels',
            'auto'  => 'une photo au hasard dans la catégorie liée à Professionnels',
        ],
        'pourquoi_1' => [
            'group' => 'Pourquoi THAL',
            'label' => 'Bloc 1 — Mon approche',
            'auto'  => 'une photo au hasard dans « Portraits »',
        ],
        'pourquoi_2' => [
            'group' => 'Pourquoi THAL',
            'label' => 'Bloc 2 — Matériel professionnel',
            'auto'  => 'une photo au hasard dans « Projets »',
        ],
        'pourquoi_3' => [
            'group' => 'Pourquoi THAL',
            'label' => 'Bloc 3 — Retouche et livraison',
            'auto'  => 'une photo au hasard dans « Accueil »',
        ],
        'pourquoi_4' => [
            'group' => 'Pourquoi THAL',
            'label' => 'Bloc 4 — Livraison sécurisée',
            'auto'  => 'une photo au hasard dans « Evenements »',
        ],
        'pourquoi_5' => [
            'group' => 'Pourquoi THAL',
            'label' => 'Bloc 5 — Photographe local',
            'auto'  => 'une photo au hasard dans « Nature »',
        ],
    ];
}

/** Réglages actuels : un tableau emplacement => "Catégorie/fichier.jpg" (ou ''). */
function thal_site_images(?string $baseDir = null): array
{
    $defaults = array_fill_keys(array_keys(thal_site_image_slots()), '');
    $file = thal_site_images_path($baseDir);
    if (!is_file($file)) {
        return $defaults;
    }
    $data = json_decode((string)file_get_contents($file), true);
    if (!is_array($data)) {
        return $defaults;
    }
    return array_merge($defaults, array_intersect_key($data, $defaults));
}

/**
 * Enregistre les réglages. Chaque valeur doit désigner une photo qui existe
 * réellement dans la galerie, sinon elle est remise à vide (retour à
 * l'automatique) plutôt que de créer un lien mort sur le site.
 */
function thal_site_images_save(array $input, ?string $baseDir = null): bool
{
    $slots = thal_site_image_slots();
    $gallery = thal_gallery_scan($baseDir);

    $valid = [];
    foreach ($gallery as $cat => $files) {
        foreach ($files as $file) {
            $valid[$cat . '/' . $file] = true;
        }
    }

    $clean = [];
    foreach (array_keys($slots) as $key) {
        $value = trim((string)($input[$key] ?? ''));
        $clean[$key] = isset($valid[$value]) ? $value : '';
    }

    $file = thal_site_images_path($baseDir);
    $dir = dirname($file);
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        return false;
    }
    $json = json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return file_put_contents($file, $json, LOCK_EX) !== false;
}

/** Chemin web de la vignette d'une photo, avec repli sur l'original. */
function thal_site_image_thumb(string $value): string
{
    [$cat, $file] = array_pad(explode('/', $value, 2), 2, '');
    if ($cat === '' || $file === '') return '';
    return '../photos/_thumbs/' . rawurlencode($cat) . '/' . rawurlencode($file);
}

function thal_site_image_full(string $value): string
{
    [$cat, $file] = array_pad(explode('/', $value, 2), 2, '');
    if ($cat === '' || $file === '') return '';
    return '../photos/' . rawurlencode($cat) . '/' . rawurlencode($file);
}
