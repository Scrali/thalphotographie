<?php
$page_title = 'Packs & tarifs — THAL Studio';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/pricing.php';

$file = __DIR__ . '/data/settings/packs.json';
$current = thal_pack_settings(__DIR__);
$message = '';
$gammes = thal_pack_gammes();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        http_response_code(403);
        exit('Jeton de sécurité invalide.');
    }

    $current['range_percent'] = (float)($_POST['range_percent'] ?? 10);
    $current['rounding'] = (float)($_POST['rounding'] ?? 10);

    $newPacks = [];
    foreach (array_keys($gammes) as $gKey) {
        $rows = $_POST['packs'][$gKey] ?? [];
        foreach ($rows as $row) {
            $name = trim((string)($row['name'] ?? ''));
            if ($name === '') continue;
            $featuresRaw = (string)($row['features'] ?? '');
            $features = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $featuresRaw))));
            $existingId = trim((string)($row['id'] ?? ''));
            $newPacks[] = [
                'id'=>$existingId !== '' ? $existingId : thal_pack_id_slug($gKey, $name),
                'gamme'=>$gKey,
                'name'=>$name,
                'details'=>trim((string)($row['details'] ?? '')),
                'price'=>(float)($row['price'] ?? 0),
                'photos'=>(int)($row['photos'] ?? 0),
                'custom'=>!empty($row['custom']),
                'features'=>$features,
            ];
        }
    }
    if ($newPacks) $current['packs'] = $newPacks;

    if (!is_dir(dirname($file))) mkdir(dirname($file), 0755, true);
    file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    $message = 'Packs et tarifs enregistrés.';
}

function fv($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$rowsPerGamme = 4;
$packsByGamme = thal_pack_settings_by_gamme($current);
?>
<h2>Packs & tarifs</h2>
<p class="hint">Ces formules reflètent les prix affichés sur le site public (Identité, Portraits, Reportages, Professionnels). Modifie-les ici pour qu'elles restent identiques des deux côtés — elles servent aussi à préremplir un devis depuis une estimation.</p>

<div class="panel-box wide">
  <?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>

  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <h3>Fourchette d'estimation</h3>
    <label>Fourchette client ± %<input type="number" step="1" min="0" name="range_percent" value="<?= fv($current['range_percent']) ?>"></label>
    <label>Arrondi CHF<input type="number" step="1" min="1" name="rounding" value="<?= fv($current['rounding']) ?>"></label>
    <p class="hint">Utilisé uniquement pour calculer la fourchette « potentiel estimé » affichée sur la page Estimations.</p>

    <?php foreach ($gammes as $gKey => $gLabel): ?>
      <h3><?= e($gLabel) ?></h3>
      <?php for ($i = 0; $i < $rowsPerGamme; $i++):
        $pack = $packsByGamme[$gKey][$i] ?? ['id'=>'','name'=>'','details'=>'','price'=>'','photos'=>'','custom'=>false,'features'=>[]];
        $features = implode("\n", $pack['features'] ?? []);
      ?>
        <div class="pack-editor">
          <h4>Formule <?= $i + 1 ?></h4>
          <input type="hidden" name="packs[<?= e($gKey) ?>][<?= $i ?>][id]" value="<?= fv($pack['id']) ?>">
          <label>Nom<input name="packs[<?= e($gKey) ?>][<?= $i ?>][name]" value="<?= fv($pack['name']) ?>"></label>
          <label>Détails (durée, contenu…)<input name="packs[<?= e($gKey) ?>][<?= $i ?>][details]" value="<?= fv($pack['details']) ?>"></label>
          <label>Prix CHF<input type="number" step="5" min="0" name="packs[<?= e($gKey) ?>][<?= $i ?>][price]" value="<?= fv($pack['price']) ?>"></label>
          <label>Photos incluses<input type="number" step="1" min="0" name="packs[<?= e($gKey) ?>][<?= $i ?>][photos]" value="<?= fv($pack['photos']) ?>"></label>
          <label class="checkbox-row"><input type="checkbox" name="packs[<?= e($gKey) ?>][<?= $i ?>][custom]" value="1" <?= !empty($pack['custom']) ? 'checked' : '' ?>> Prix « sur devis » (pas de tarif fixe)</label>
          <label>Inclus, une ligne par élément<textarea name="packs[<?= e($gKey) ?>][<?= $i ?>][features]" rows="3"><?= fv($features) ?></textarea></label>
        </div>
      <?php endfor; ?>
    <?php endforeach; ?>

    <button type="submit">Enregistrer</button>
  </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
