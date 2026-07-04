<?php
$page_title = 'Réglages estimation — THAL Studio';
require __DIR__ . '/includes/header.php';

$file = __DIR__ . '/data/settings/estimation.json';

$defaults = [
    'home_address' => 'Sainte-Croix, VD, Suisse',
    'home_lat' => 46.8225,
    'home_lon' => 6.5019,
    'ors_api_key' => '',
    'hourly_rate' => 120,
    'editing_rate' => 80,
    'editing_hours_per_onsite_hour' => 0.65,
    'km_rate' => 0.80,
    'base_fee' => 80,
    'commercial_fee' => 250,
    'private_photos_per_hour' => 18,
    'commercial_photos_per_hour' => 22,
    'min_photos_private' => 12,
    'min_photos_commercial' => 20,
    'range_min_multiplier' => 0.90,
    'range_max_multiplier' => 1.18,
];

$current = $defaults;
if (is_file($file)) {
    $json = json_decode((string)file_get_contents($file), true);
    if (is_array($json)) $current = array_merge($defaults, $json);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($defaults as $key => $value) {
        if (isset($_POST[$key])) {
            $current[$key] = is_numeric($value) ? (float)$_POST[$key] : trim((string)$_POST[$key]);
        }
    }

    $dir = dirname($file);
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    $message = 'Réglages enregistrés.';
}

function field_value(array $data, string $key): string {
    return htmlspecialchars((string)$data[$key], ENT_QUOTES, 'UTF-8');
}
?>
<h2>Réglages estimation</h2>

<div class="panel-box narrow">
  <?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>

  <form method="post" class="admin-form">
    <label>Clé API OpenRouteService
      <input name="ors_api_key" value="<?= field_value($current, 'ors_api_key') ?>" placeholder="À créer gratuitement sur openrouteservice.org">
    </label>

    <label>Adresse de départ
      <input name="home_address" value="<?= field_value($current, 'home_address') ?>">
    </label>

    <label>Latitude départ
      <input type="number" step="0.000001" name="home_lat" value="<?= field_value($current, 'home_lat') ?>">
    </label>

    <label>Longitude départ
      <input type="number" step="0.000001" name="home_lon" value="<?= field_value($current, 'home_lon') ?>">
    </label>

    <label>Tarif horaire CHF/h
      <input type="number" step="5" min="0" name="hourly_rate" value="<?= field_value($current, 'hourly_rate') ?>">
    </label>

    <label>Tarif retouche CHF/h
      <input type="number" step="5" min="0" name="editing_rate" value="<?= field_value($current, 'editing_rate') ?>">
    </label>

    <label>Heures retouche par heure sur place
      <input type="number" step="0.05" min="0" name="editing_hours_per_onsite_hour" value="<?= field_value($current, 'editing_hours_per_onsite_hour') ?>">
    </label>

    <label>Prix du kilomètre CHF/km
      <input type="number" step="0.05" min="0" name="km_rate" value="<?= field_value($current, 'km_rate') ?>">
    </label>

    <label>Frais de base CHF
      <input type="number" step="5" min="0" name="base_fee" value="<?= field_value($current, 'base_fee') ?>">
    </label>

    <label>Supplément commercial CHF
      <input type="number" step="5" min="0" name="commercial_fee" value="<?= field_value($current, 'commercial_fee') ?>">
    </label>

    <label>Photos par heure — privé
      <input type="number" step="1" min="0" name="private_photos_per_hour" value="<?= field_value($current, 'private_photos_per_hour') ?>">
    </label>

    <label>Photos par heure — commercial
      <input type="number" step="1" min="0" name="commercial_photos_per_hour" value="<?= field_value($current, 'commercial_photos_per_hour') ?>">
    </label>

    <label>Minimum photos — privé
      <input type="number" step="1" min="0" name="min_photos_private" value="<?= field_value($current, 'min_photos_private') ?>">
    </label>

    <label>Minimum photos — commercial
      <input type="number" step="1" min="0" name="min_photos_commercial" value="<?= field_value($current, 'min_photos_commercial') ?>">
    </label>

    <label>Fourchette basse
      <input type="number" step="0.01" min="0" name="range_min_multiplier" value="<?= field_value($current, 'range_min_multiplier') ?>">
    </label>

    <label>Fourchette haute
      <input type="number" step="0.01" min="0" name="range_max_multiplier" value="<?= field_value($current, 'range_max_multiplier') ?>">
    </label>

    <button type="submit">Enregistrer les réglages</button>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
