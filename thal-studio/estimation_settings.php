<?php
$page_title = 'Réglages estimation — THAL Studio';
require __DIR__ . '/includes/header.php';

$file = __DIR__ . '/data/settings/estimation.json';

$defaults = [
    'home_address' => 'Sainte-Croix, VD, Suisse',
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
    if (is_array($json)) {
        $current = array_merge($defaults, $json);
    }
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = [
        'home_address' => trim($_POST['home_address'] ?? $defaults['home_address']),
        'hourly_rate' => (float)($_POST['hourly_rate'] ?? $defaults['hourly_rate']),
        'editing_rate' => (float)($_POST['editing_rate'] ?? $defaults['editing_rate']),
        'editing_hours_per_onsite_hour' => (float)($_POST['editing_hours_per_onsite_hour'] ?? $defaults['editing_hours_per_onsite_hour']),
        'km_rate' => (float)($_POST['km_rate'] ?? $defaults['km_rate']),
        'base_fee' => (float)($_POST['base_fee'] ?? $defaults['base_fee']),
        'commercial_fee' => (float)($_POST['commercial_fee'] ?? $defaults['commercial_fee']),
        'private_photos_per_hour' => (int)($_POST['private_photos_per_hour'] ?? $defaults['private_photos_per_hour']),
        'commercial_photos_per_hour' => (int)($_POST['commercial_photos_per_hour'] ?? $defaults['commercial_photos_per_hour']),
        'min_photos_private' => (int)($_POST['min_photos_private'] ?? $defaults['min_photos_private']),
        'min_photos_commercial' => (int)($_POST['min_photos_commercial'] ?? $defaults['min_photos_commercial']),
        'range_min_multiplier' => (float)($_POST['range_min_multiplier'] ?? $defaults['range_min_multiplier']),
        'range_max_multiplier' => (float)($_POST['range_max_multiplier'] ?? $defaults['range_max_multiplier']),
    ];

    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

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
    <label>Adresse de départ
      <input name="home_address" value="<?= field_value($current, 'home_address') ?>">
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

    <label>Supplément utilisation commerciale CHF
      <input type="number" step="5" min="0" name="commercial_fee" value="<?= field_value($current, 'commercial_fee') ?>">
    </label>

    <label>Photos incluses par heure — privé
      <input type="number" step="1" min="0" name="private_photos_per_hour" value="<?= field_value($current, 'private_photos_per_hour') ?>">
    </label>

    <label>Photos incluses par heure — commercial
      <input type="number" step="1" min="0" name="commercial_photos_per_hour" value="<?= field_value($current, 'commercial_photos_per_hour') ?>">
    </label>

    <label>Minimum photos incluses — privé
      <input type="number" step="1" min="0" name="min_photos_private" value="<?= field_value($current, 'min_photos_private') ?>">
    </label>

    <label>Minimum photos incluses — commercial
      <input type="number" step="1" min="0" name="min_photos_commercial" value="<?= field_value($current, 'min_photos_commercial') ?>">
    </label>

    <label>Fourchette basse, multiplicateur
      <input type="number" step="0.01" min="0" name="range_min_multiplier" value="<?= field_value($current, 'range_min_multiplier') ?>">
    </label>

    <label>Fourchette haute, multiplicateur
      <input type="number" step="0.01" min="0" name="range_max_multiplier" value="<?= field_value($current, 'range_max_multiplier') ?>">
    </label>

    <button type="submit">Enregistrer les réglages</button>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
