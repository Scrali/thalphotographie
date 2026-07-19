<?php
$page_title = 'Packs & tarifs — THAL Studio';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/pricing.php';

$file = __DIR__ . '/data/settings/packs.json';
$current = thal_pack_settings(__DIR__);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        http_response_code(403);
        exit('Jeton de sécurité invalide.');
    }

    $current['ors_api_key'] = trim((string)($_POST['ors_api_key'] ?? ''));
    $current['google_calendar_ical_url'] = trim((string)($_POST['google_calendar_ical_url'] ?? ''));
    $current['home_address'] = trim((string)($_POST['home_address'] ?? 'Sainte-Croix, VD, Suisse'));
    $current['home_lat'] = (float)($_POST['home_lat'] ?? 46.8225);
    $current['home_lon'] = (float)($_POST['home_lon'] ?? 6.5019);
    $current['distance_margin_percent'] = (float)($_POST['distance_margin_percent'] ?? 15);
    $current['km_included'] = (float)($_POST['km_included'] ?? 30);
    $current['km_rate'] = (float)($_POST['km_rate'] ?? 0.60);
    $current['commercial_percent'] = (float)($_POST['commercial_percent'] ?? 30);
    $current['range_percent'] = (float)($_POST['range_percent'] ?? 10);
    $current['rounding'] = (float)($_POST['rounding'] ?? 10);

    $packs = [];
    for ($i = 0; $i < 5; $i++) {
        $name = trim((string)($_POST['pack_name'][$i] ?? ''));
        if ($name === '') continue;
        $featuresRaw = (string)($_POST['pack_features'][$i] ?? '');
        $features = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $featuresRaw))));
        $packs[] = [
            'id'=>'pack_' . ($i + 1),
            'name'=>$name,
            'duration_hours'=>(float)($_POST['pack_duration'][$i] ?? 1),
            'base_price'=>(float)($_POST['pack_price'][$i] ?? 0),
            'photos'=>(int)($_POST['pack_photos'][$i] ?? 0),
            'features'=>$features,
        ];
    }
    if ($packs) $current['packs'] = $packs;
    if (!is_dir(dirname($file))) mkdir(dirname($file), 0755, true);
    file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    $message = 'Packs et tarifs enregistrés.';
}

function fv($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<h2>Packs & tarifs</h2>

<div class="panel-box wide">
  <?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>

  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <h3>Déplacement</h3>
    <label>Clé API OpenRouteService<input name="ors_api_key" value="<?= fv($current['ors_api_key']) ?>"></label>
    <label>Adresse de départ<input name="home_address" value="<?= fv($current['home_address']) ?>"></label>
    <label>Latitude départ<input type="number" step="0.000001" name="home_lat" value="<?= fv($current['home_lat']) ?>"></label>
    <label>Longitude départ<input type="number" step="0.000001" name="home_lon" value="<?= fv($current['home_lon']) ?>"></label>
    <label>Marge sur la distance calculée en %
      <input type="number" step="1" min="0" max="100" name="distance_margin_percent" value="<?= fv($current['distance_margin_percent']) ?>">
    </label>
    <p class="hint">OpenRouteService peut proposer un itinéraire un peu plus court que celui de Google Maps. Cette marge gonfle le résultat pour éviter de sous-estimer le trajet réel.</p>
    <label>Kilomètres inclus<input type="number" step="1" min="0" name="km_included" value="<?= fv($current['km_included']) ?>"></label>
    <label>Prix du km supplémentaire<input type="number" step="0.05" min="0" name="km_rate" value="<?= fv($current['km_rate']) ?>"></label>

    <h3>Google Agenda</h3>
    <label>URL privée iCal de l'agenda
      <input name="google_calendar_ical_url" value="<?= fv($current['google_calendar_ical_url']) ?>" placeholder="https://calendar.google.com/calendar/ical/.../private-.../basic.ics">
    </label>
    <p class="hint">Dans Google Agenda : Paramètres → ton agenda → «Intégrer l'agenda» → copie l'«Adresse secrète au format iCal». Elle sert uniquement à repérer les dates déjà prises sur le formulaire d'estimation ; garde-la secrète.</p>

    <h3>Suppléments</h3>
    <label>Supplément commercial en %<input type="number" step="1" min="0" name="commercial_percent" value="<?= fv($current['commercial_percent']) ?>"></label>
    <label>Fourchette client ± %<input type="number" step="1" min="0" name="range_percent" value="<?= fv($current['range_percent']) ?>"></label>
    <label>Arrondi CHF<input type="number" step="1" min="1" name="rounding" value="<?= fv($current['rounding']) ?>"></label>

    <h3>Packs</h3>
    <?php for ($i = 0; $i < 5; $i++):
      $pack = $current['packs'][$i] ?? ['name'=>'','duration_hours'=>'','base_price'=>'','photos'=>'','features'=>[]];
      $features = implode("\n", $pack['features'] ?? []);
    ?>
      <div class="pack-editor">
        <h4>Pack <?= $i + 1 ?></h4>
        <label>Nom<input name="pack_name[]" value="<?= fv($pack['name']) ?>"></label>
        <label>Durée en heures<input type="number" step="0.5" min="0" name="pack_duration[]" value="<?= fv($pack['duration_hours']) ?>"></label>
        <label>Prix de base CHF<input type="number" step="10" min="0" name="pack_price[]" value="<?= fv($pack['base_price']) ?>"></label>
        <label>Photos incluses<input type="number" step="1" min="0" name="pack_photos[]" value="<?= fv($pack['photos']) ?>"></label>
        <label>Inclus, une ligne par élément<textarea name="pack_features[]" rows="4"><?= fv($features) ?></textarea></label>
      </div>
    <?php endfor; ?>
    <button type="submit">Enregistrer</button>
  </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
