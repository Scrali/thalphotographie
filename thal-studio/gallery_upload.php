<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
thal_json_api_guard(512);
require_login();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

if (!csrf_check($_POST['csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null))) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Jeton de sécurité invalide']);
    exit;
}

$data = thal_gallery_scan(__DIR__);
$categories = array_keys($data);

$requestedCategory = trim((string)($_POST['category'] ?? ''));
$autoSort = $requestedCategory === '' || $requestedCategory === '__auto__';
$fallbackCategory = 'À trier';

if (!$autoSort) {
    $requestedCategory = thal_sanitize_category($requestedCategory);
    if ($requestedCategory === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Catégorie invalide']);
        exit;
    }
}

if (empty($_FILES['files']) || !is_array($_FILES['files']['name'] ?? null)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Aucun fichier reçu']);
    exit;
}

$photosDir = thal_photos_dir(__DIR__);
$results = [];
$count = count($_FILES['files']['name']);

for ($i = 0; $i < $count; $i++) {
    $originalName = (string)$_FILES['files']['name'][$i];
    $tmpPath = (string)$_FILES['files']['tmp_name'][$i];
    $uploadError = (int)$_FILES['files']['error'][$i];

    if ($uploadError !== UPLOAD_ERR_OK || $originalName === '') {
        $message = match ($uploadError) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Fichier trop volumineux pour la configuration du serveur',
            UPLOAD_ERR_PARTIAL => 'Envoi interrompu, réessaie',
            UPLOAD_ERR_NO_FILE => 'Aucun fichier reçu',
            default => 'Échec de l’envoi',
        };
        $results[] = ['name' => $originalName, 'ok' => false, 'error' => $message];
        continue;
    }

    if (!thal_is_allowed_image_ext(pathinfo($originalName, PATHINFO_EXTENSION))) {
        $results[] = ['name' => $originalName, 'ok' => false, 'error' => 'Format non supporté (jpg, png, webp, gif)'];
        continue;
    }

    if (@getimagesize($tmpPath) === false) {
        $results[] = ['name' => $originalName, 'ok' => false, 'error' => 'Fichier image invalide'];
        continue;
    }

    $category = $autoSort
        ? (thal_guess_category($originalName, $categories) ?: $fallbackCategory)
        : $requestedCategory;
    $category = thal_sanitize_category($category) ?: $fallbackCategory;

    $destDir = $photosDir . '/' . $category;
    if (!is_dir($destDir) && !mkdir($destDir, 0755, true) && !is_dir($destDir)) {
        $results[] = ['name' => $originalName, 'ok' => false, 'error' => 'Impossible de créer la catégorie'];
        continue;
    }

    $finalName = thal_sanitize_filename($originalName);
    $base = pathinfo($finalName, PATHINFO_FILENAME);
    $ext = pathinfo($finalName, PATHINFO_EXTENSION);
    $suffix = 1;
    while (is_file($destDir . '/' . $finalName)) {
        $finalName = $base . '_' . $suffix . ($ext !== '' ? '.' . $ext : '');
        $suffix++;
    }

    if (!move_uploaded_file($tmpPath, $destDir . '/' . $finalName)) {
        $results[] = ['name' => $originalName, 'ok' => false, 'error' => 'Écriture sur le serveur impossible'];
        continue;
    }

    // Redimensionne côté serveur si l'image dépasse la taille utile pour le site (GD requis).
    thal_resize_image($destDir . '/' . $finalName);

    if (!isset($data[$category])) {
        $data[$category] = [];
    }
    $data[$category][] = $finalName;
    if (!in_array($category, $categories, true)) {
        $categories[] = $category;
    }

    $results[] = ['name' => $originalName, 'ok' => true, 'category' => $category, 'file' => $finalName];
}

thal_gallery_save($data, __DIR__);

echo json_encode(['ok' => true, 'results' => $results, 'data' => $data], JSON_UNESCAPED_UNICODE);
