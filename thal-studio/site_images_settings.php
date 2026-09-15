<?php
$page_title = 'Photos du site — THAL Studio';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/site_images.php';

$slots   = thal_site_image_slots();
$current = thal_site_images(__DIR__);
$gallery = thal_gallery_scan(__DIR__);
ksort($gallery);

$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        http_response_code(403);
        exit('Jeton de sécurité invalide.');
    }
    $submitted = is_array($_POST['slot'] ?? null) ? $_POST['slot'] : [];
    if (thal_site_images_save($submitted, __DIR__)) {
        $current = thal_site_images(__DIR__);
        $message = 'Photos enregistrées. Rechargez le site pour les voir.';
        foreach ($slots as $key => $slot) {
            $wanted = trim((string)($submitted[$key] ?? ''));
            if ($wanted !== '' && $current[$key] === '') {
                $error = 'Une photo choisie n’existe plus dans la galerie : cet emplacement est repassé en automatique.';
                break;
            }
        }
    } else {
        $error = 'Impossible d’écrire photos/site_images.json. Vérifiez les droits du dossier photos.';
    }
}

/** Emplacements regroupés pour l'affichage. */
$grouped = [];
foreach ($slots as $key => $slot) {
    $grouped[$slot['group']][$key] = $slot;
}
?>
<h2>Photos du site</h2>

<div class="panel-box wide">
  <p class="hint">
    Choisissez ici, pour chaque emplacement du site, la photo exacte à afficher. Les photos proposées sont
    celles de votre galerie (onglet « Galerie ») : pour en ajouter une nouvelle, chargez-la d’abord dans la
    galerie, elle apparaîtra ensuite dans les listes ci-dessous.
  </p>
  <p class="hint">
    Laissé sur « Automatique », un emplacement continue de piocher une photo au hasard dans sa catégorie,
    comme aujourd’hui. C’est pratique pour que le site change tout seul ; fixez une photo uniquement là où
    vous voulez garder la main.
  </p>

  <?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>
  <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>

  <?php if (!$gallery): ?>
    <p class="hint">Votre galerie est vide : chargez d’abord des photos dans l’onglet « Galerie ».</p>
  <?php else: ?>
  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <?php foreach ($grouped as $groupName => $groupSlots): ?>
      <h3 class="siteImagesGroup"><?= e($groupName) ?></h3>

      <?php foreach ($groupSlots as $key => $slot): ?>
        <?php
          $value = $current[$key];
          $thumb = $value !== '' ? thal_site_image_thumb($value) : '';
          $full  = $value !== '' ? thal_site_image_full($value) : '';
        ?>
        <div class="siteImagesRow">
          <div class="siteImagesPreview">
            <img id="preview-<?= e($key) ?>"
                 src="<?= e($thumb) ?>"
                 data-fallback="<?= e($full) ?>"
                 alt=""
                 <?= $thumb === '' ? 'hidden' : '' ?>
                 onerror="if(this.dataset.fallback && this.src.indexOf('_thumbs')>-1){this.src=this.dataset.fallback;}else{this.hidden=true;}">
            <span class="siteImagesEmpty" id="empty-<?= e($key) ?>" <?= $thumb === '' ? '' : 'hidden' ?>>Automatique</span>
          </div>

          <label>
            <?= e($slot['label']) ?>
            <select name="slot[<?= e($key) ?>]" data-preview="<?= e($key) ?>">
              <option value="">— Automatique : <?= e($slot['auto']) ?> —</option>
              <?php foreach ($gallery as $cat => $files): ?>
                <?php if (!$files) continue; ?>
                <optgroup label="<?= e($cat) ?>">
                  <?php foreach ($files as $file): ?>
                    <?php $opt = $cat . '/' . $file; ?>
                    <option value="<?= e($opt) ?>" <?= $value === $opt ? 'selected' : '' ?>><?= e($file) ?></option>
                  <?php endforeach; ?>
                </optgroup>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
      <?php endforeach; ?>
    <?php endforeach; ?>

    <button type="submit" class="button">Enregistrer</button>
  </form>
  <?php endif; ?>
</div>

<style>
  .siteImagesGroup{
    margin:26px 0 12px; padding-bottom:8px; border-bottom:1px solid rgba(255,255,255,.12);
    font-size:14px; letter-spacing:.08em; text-transform:uppercase; opacity:.8;
  }
  .siteImagesGroup:first-of-type{margin-top:6px;}
  .siteImagesRow{display:flex; align-items:center; gap:16px; margin-bottom:14px;}
  .siteImagesRow label{flex:1; min-width:0; margin:0;}
  .siteImagesPreview{
    flex:none; position:relative; width:104px; height:78px; overflow:hidden;
    display:grid; place-items:center; border:1px solid rgba(255,255,255,.14);
    border-radius:8px; background:rgba(255,255,255,.04);
  }
  .siteImagesPreview img{width:100%; height:100%; object-fit:cover;}
  .siteImagesEmpty{font-size:11px; letter-spacing:.06em; text-transform:uppercase; opacity:.5;}
  @media(max-width:620px){
    .siteImagesRow{flex-direction:column; align-items:stretch;}
    .siteImagesPreview{width:100%; height:150px;}
  }
</style>

<script>
  /* Met à jour l'aperçu quand on change de photo, sans recharger la page. */
  document.querySelectorAll('select[data-preview]').forEach(function (select) {
    select.addEventListener('change', function () {
      var key = select.dataset.preview;
      var img = document.getElementById('preview-' + key);
      var empty = document.getElementById('empty-' + key);
      var value = select.value;

      if (!value) {
        img.hidden = true;
        img.removeAttribute('src');
        empty.hidden = false;
        return;
      }

      var parts = value.split('/');
      var cat = encodeURIComponent(parts.shift());
      var file = encodeURIComponent(parts.join('/'));
      img.dataset.fallback = '../photos/' + cat + '/' + file;
      img.src = '../photos/_thumbs/' + cat + '/' + file;
      img.hidden = false;
      empty.hidden = true;
    });
  });
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
