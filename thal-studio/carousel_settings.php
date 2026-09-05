<?php
$page_title = 'Carrousels du site — THAL Studio';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/carousel_map.php';

$file = __DIR__ . '/data/settings/carousel_map.json';
$current = thal_carousel_map_settings(__DIR__);
$pageLabels = thal_carousel_page_labels();
$pageDefaults = thal_carousel_page_defaults();
$liveCategories = array_keys(thal_gallery_scan(__DIR__));
sort($liveCategories);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        http_response_code(403);
        exit('Jeton de sécurité invalide.');
    }
    foreach (array_keys($pageLabels) as $key) {
        $current[$key] = trim((string)($_POST['map'][$key] ?? ''));
    }
    if (!is_dir(dirname($file))) mkdir(dirname($file), 0755, true);
    file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    $message = 'Carrousels enregistrés.';
}
?>
<h2>Carrousels du site</h2>

<div class="panel-box wide">
  <p class="hint">
    Le site public affiche un carrousel de photos sur plusieurs pages, chacun lié à une catégorie de ta galerie.
    Comme la galerie se gère ici en ligne (onglet "Galerie"), il arrive qu'une catégorie soit renommée ou réorganisée
    et que le carrousel d'une page ne trouve plus ses photos. Choisis ici, pour chaque page, quelle catégorie de la
    galerie elle doit utiliser — sans avoir besoin de modifier le code.
  </p>

  <?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>

  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <?php foreach ($pageLabels as $key => $label): ?>
      <label>
        <?= e($label) ?> <small style="text-transform:none; opacity:.7;">(par défaut dans le code : « <?= e($pageDefaults[$key]) ?> »)</small>
        <select name="map[<?= e($key) ?>]">
          <option value="">— Utiliser la valeur par défaut du code —</option>
          <?php foreach ($liveCategories as $cat): ?>
            <option value="<?= e($cat) ?>" <?= $current[$key] === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    <?php endforeach; ?>

    <button type="submit" class="button">Enregistrer</button>
  </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
