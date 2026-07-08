<?php
$page_title = 'Estimations - THAL Studio';
require __DIR__ . '/includes/header.php';

$items = thal_estimation_items(__DIR__);
$total = count($items);
$month = count(array_filter($items, fn($it) => substr($it['createdAt'] ?? '', 0, 7) === date('Y-m')));
$potentialMin = array_sum(array_map(fn($it) => (float)$it['priceMin'], $items));
$potentialMax = array_sum(array_map(fn($it) => (float)$it['priceMax'], $items));
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
    <thead><tr><th>Date</th><th>Contact</th><th>Lieu / date</th><th>Pack</th><th>Budget</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($items as $it): ?>
      <tr>
        <td><?= e(thal_date_label($it['createdAt'], true)) ?></td>
        <td><?= e($it['name'] ?: '-') ?><br><small><?= e($it['email'] ?: '-') ?></small><br><small><?= e($it['phone'] ?: '-') ?></small></td>
        <td><?= e($it['location'] ?: '-') ?><br><small><?= e(thal_date_label($it['eventDate'])) ?></small></td>
        <td><?= e($it['packName'] ?: '-') ?></td>
        <td><?= e(thal_money((float)$it['priceMin'])) ?> - <?= e(thal_money((float)$it['priceMax'])) ?><br><small>Conseillé : <?= e(thal_money((float)$it['priceRecommended'])) ?></small></td>
        <td><span class="badge"><?= e($it['status']) ?></span></td>
        <td class="actions-cell">
          <form method="post" action="estimation_convert.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($it['id']) ?>"><button class="button small" type="submit">Créer devis</button></form>
          <form method="post" action="estimation_delete.php" onsubmit="return confirm('Supprimer cette estimation ?');"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($it['id']) ?>"><button class="button small danger" type="submit">Supprimer</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
