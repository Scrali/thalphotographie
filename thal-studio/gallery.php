<?php
$page_title = 'Galerie - THAL Studio';
require __DIR__ . '/includes/header.php';

$galleryData = thal_gallery_scan(__DIR__);
$totalPhotos = array_sum(array_map('count', $galleryData));
$totalCategories = count($galleryData);
?>
<div class="page-heading">
  <div>
    <p class="eyebrow">Photos du site public</p>
    <h2>Galerie</h2>
  </div>
</div>

<div class="metric-grid small">
  <div class="metric-card">
    <span class="metric-label">Photos</span>
    <strong><?= e((string)$totalPhotos) ?></strong>
  </div>
  <div class="metric-card">
    <span class="metric-label">Catégories</span>
    <strong><?= e((string)$totalCategories) ?></strong>
  </div>
</div>

<div class="panel-box gallery-autozone" id="autoDropzone">
  <div class="gallery-autozone-text">
    <h3>Tri automatique</h3>
    <p class="hint">Dépose des photos ici : la catégorie est devinée à partir du nom du fichier (ex. <code>mariage_012.jpg</code> → « Mariage »). Sans correspondance, elles vont dans « À trier », déplaçable ensuite par glisser-déposer.</p>
  </div>
  <label class="button">
    Choisir des fichiers
    <input type="file" id="autoFileInput" accept="image/*" multiple hidden>
  </label>
</div>

<div class="upload-progress-wrap" id="uploadProgressWrap" hidden>
  <div class="upload-progress-bar"><div class="upload-progress-fill" id="uploadProgressFill"></div></div>
  <p class="upload-progress-label" id="uploadProgressLabel"></p>
</div>

<div class="panel-box narrow">
  <h3>Nouvelle catégorie</h3>
  <form id="newCategoryForm" class="inline-form">
    <input type="text" id="newCategoryName" placeholder="Ex. Mariage, Corporate, Concert…" required>
    <button class="button" type="submit">Créer</button>
  </form>
  <p class="slot-status" id="categoryStatus"></p>
</div>

<div id="gallerySections">
  <?php foreach ($galleryData as $category => $files): ?>
    <section class="gallery-category" data-category="<?= e($category) ?>">
      <div class="gallery-category-head">
        <h3><?= e($category) ?> <span class="badge muted"><?= e((string)count($files)) ?></span></h3>
        <div class="gallery-category-actions">
          <label class="button small">
            Ajouter ici
            <input type="file" class="gallery-cat-input" accept="image/*" multiple hidden>
          </label>
          <?php if (empty($files)): ?>
            <button type="button" class="button small danger gallery-delete-cat">Supprimer la catégorie</button>
          <?php endif; ?>
        </div>
      </div>
      <div class="gallery-grid" data-category="<?= e($category) ?>">
        <?php foreach ($files as $file): ?>
          <figure class="gallery-thumb" draggable="true" data-file="<?= e($file) ?>">
            <img src="../photos/<?= rawurlencode($category) ?>/<?= rawurlencode($file) ?>" alt="<?= e($file) ?>" loading="lazy">
            <button type="button" class="gallery-thumb-delete" aria-label="Supprimer">×</button>
            <figcaption><?= e($file) ?></figcaption>
            <select class="gallery-thumb-move" aria-label="Déplacer vers">
              <option value="">Déplacer vers…</option>
              <?php foreach (array_keys($galleryData) as $otherCategory): if ($otherCategory === $category) continue; ?>
                <option value="<?= e($otherCategory) ?>"><?= e($otherCategory) ?></option>
              <?php endforeach; ?>
            </select>
          </figure>
        <?php endforeach; ?>
        <?php if (empty($files)): ?>
          <p class="empty-state gallery-empty">Aucune photo. Glisse-en ici ou utilise « Ajouter ici ».</p>
        <?php endif; ?>
      </div>
    </section>
  <?php endforeach; ?>
</div>

<script>
window.THAL_CSRF_TOKEN = <?php echo json_encode(csrf_token(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
</script>
<script src="js/gallery.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
