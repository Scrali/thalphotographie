<?php
$page_title = 'Estimations - THAL Studio';
require __DIR__ . '/includes/header.php';

require_once __DIR__ . '/includes/pricing.php';

$items = thal_estimation_items(__DIR__);
$total = count($items);
$month = count(array_filter($items, fn($it) => substr($it['createdAt'] ?? '', 0, 7) === date('Y-m')));
$potentialMin = array_sum(array_map(fn($it) => (float)$it['priceMin'], $items));
$potentialMax = array_sum(array_map(fn($it) => (float)$it['priceMax'], $items));

$gammes = thal_pack_gammes();
$packsByGamme = thal_pack_settings_by_gamme(thal_pack_settings(__DIR__));
?>
<div class="page-heading">
  <div>
    <p class="eyebrow">Demandes publiques</p>
    <h2>Estimations</h2>
  </div>
  <a class="button muted" href="estimation_settings.php">Modifier les packs</a>
</div>

<div class="metric-grid">
  <div class="metric-card">
    <span class="metric-label">Estimations totales</span>
    <strong><?= e((string)$total) ?></strong>
    <small>Toutes demandes enregistrées</small>
  </div>
  <div class="metric-card">
    <span class="metric-label">Ce mois</span>
    <strong><?= e((string)$month) ?></strong>
    <small><?= e(date('m.Y')) ?></small>
  </div>
  <div class="metric-card accent">
    <span class="metric-label">Potentiel estimé</span>
    <strong><?= e(thal_money((float)$potentialMin)) ?> - <?= e(thal_money((float)$potentialMax)) ?></strong>
    <small>Fourchette client</small>
  </div>
</div>

<div class="panel-box">
  <?php if (!$items): ?>
    <p class="empty-state">Aucune estimation enregistrée.</p>
  <?php else: ?>
  <table class="admin-table">
    <thead><tr><th>Date</th><th>Contact</th><th>Message</th><th>Lieu / date</th><th>Pack</th><th>Budget</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($items as $it): ?>
      <tr>
        <td><?= e(thal_date_label($it['createdAt'], true)) ?></td>
        <td><?= e($it['name'] ?: '-') ?><br><small><?= e($it['email'] ?: '-') ?></small><br><small><?= e($it['phone'] ?: '-') ?></small></td>
        <td style="max-width:280px; white-space:pre-wrap;"><?= e($it['message'] ?: '-') ?></td>
        <td><?= e($it['location'] ?: '-') ?><br><small><?= e(thal_date_label($it['eventDate'])) ?></small></td>
        <td><?= e($it['packName'] ?: '-') ?></td>
        <td><?= e(thal_money((float)$it['priceMin'])) ?> - <?= e(thal_money((float)$it['priceMax'])) ?><br><small>Conseillé : <?= e(thal_money((float)$it['priceRecommended'])) ?></small></td>
        <td><span class="badge"><?= e($it['status']) ?></span></td>
        <td class="actions-cell">
          <form method="post" action="estimation_convert.php" class="convert-form">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= e($it['id']) ?>">
            <select name="pack_id">
              <option value="">— Formule (à définir) —</option>
              <?php foreach ($gammes as $gKey => $gLabel): ?>
                <?php if (!empty($packsByGamme[$gKey])): ?>
                <optgroup label="<?= e($gLabel) ?>">
                  <?php foreach ($packsByGamme[$gKey] as $p): ?>
                    <option value="<?= e($p['id']) ?>"><?= e($p['name']) ?> — <?= !empty($p['custom']) ? 'Sur devis' : e(thal_money((float)$p['price'])) ?></option>
                  <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
            <button class="button small" type="submit">Créer devis</button>
          </form>
          <?php $gcalLink = thal_gcal_link($it); ?>
          <?php if ($gcalLink): ?>
            <a class="button small" href="<?= e($gcalLink) ?>" target="_blank" rel="noopener">Google Agenda</a>
          <?php endif; ?>
          <form method="post" action="estimation_delete.php" onsubmit="return confirm('Supprimer cette estimation ?');"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($it['id']) ?>"><button class="button small danger" type="submit">Supprimer</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
