<?php
$page_title = 'Estimations — THAL Studio';
require __DIR__ . '/includes/header.php';

$dir = __DIR__ . '/data/estimations';
$items = [];
if (is_dir($dir)) {
    foreach (glob($dir . '/*.json') as $file) {
        $data = json_decode((string)file_get_contents($file), true);
        if (is_array($data)) { $data['_id'] = basename($file); $items[] = $data; }
    }
}
usort($items, fn($a, $b) => strcmp($b['createdAt'] ?? '', $a['createdAt'] ?? ''));

$total=count($items); $month=0; $potentialMin=0; $potentialMax=0;
foreach($items as $it){ if(substr($it['createdAt']??'',0,7)===date('Y-m'))$month++; $potentialMin+=(float)($it['price_min']??0); $potentialMax+=(float)($it['price_max']??0); }
?>
<h2>Estimations</h2>
<?php if (!empty($_GET['error'])): ?>
  <div class="panel-box">
    <p class="error">Erreur conversion estimation : <?= e($_GET['error']) ?></p>
  </div>
<?php endif; ?>
<div class="cards">
  <div class="card"><span>📊</span><strong><?= e((string)$total) ?></strong><p>estimations totales</p></div>
  <div class="card"><span>📅</span><strong><?= e((string)$month) ?></strong><p>ce mois</p></div>
  <div class="card"><span>💰</span><strong><?= e(number_format($potentialMin,0,'.',"'")) ?>–<?= e(number_format($potentialMax,0,'.',"'")) ?> CHF</strong><p>potentiel estimé</p></div>
</div>
<div class="panel-box">
  <p><a class="button small" href="estimation_settings.php">Modifier les packs</a></p>
  <?php if(!$items): ?><p>Aucune estimation enregistrée.</p>
  <?php else: ?>
  <table class="admin-table">
    <thead><tr><th>Date</th><th>Contact</th><th>Lieu / date</th><th>Pack</th><th>Budget</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($items as $it): ?>
      <tr>
        <td><?= e(date('d.m.Y H:i', strtotime($it['createdAt'] ?? 'now'))) ?></td>
        <td><?= e($it['name'] ?? '') ?><br><small><?= e($it['email'] ?? '') ?></small><br><small><?= e($it['phone'] ?? '') ?></small></td>
        <td><?= e($it['location'] ?? '') ?><br><small><?= e($it['event_date'] ?? '') ?></small><br><small><?= e((string)($it['roundtrip_km'] ?? 0)) ?> km A/R</small></td>
        <td><?= e($it['pack_name'] ?? '') ?><br><small><?= e((string)($it['included_photos'] ?? '')) ?> photos incluses</small></td>
        <td><?= e(number_format((float)($it['price_min'] ?? 0),0,'.',"'")) ?>–<?= e(number_format((float)($it['price_max'] ?? 0),0,'.',"'")) ?> CHF<br><small>Conseillé : <?= e(number_format((float)($it['price_recommended'] ?? 0),0,'.',"'")) ?> CHF</small></td>
        <td><?= e($it['status'] ?? 'new') ?></td>
        <td class="actions-cell">
          <form method="post" action="estimation_convert.php"><input type="hidden" name="id" value="<?= e($it['_id']) ?>"><button class="button small" type="submit">Créer devis</button></form>
          <form method="post" action="estimation_delete.php" onsubmit="return confirm('Supprimer cette estimation ?');"><input type="hidden" name="id" value="<?= e($it['_id']) ?>"><button class="button small danger" type="submit">Supprimer</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
