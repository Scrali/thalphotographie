<?php
$page_title = 'Estimations — THAL Studio';
require __DIR__ . '/includes/header.php';

$dir = __DIR__ . '/data/estimations';
$items = [];

if (is_dir($dir)) {
    foreach (glob($dir . '/*.json') as $file) {
        $data = json_decode((string)file_get_contents($file), true);
        if (is_array($data)) {
            $items[] = $data;
        }
    }
}

usort($items, fn($a, $b) => strcmp($b['createdAt'] ?? '', $a['createdAt'] ?? ''));

$total = count($items);
$month = 0;
$potential = 0;

foreach ($items as $it) {
    if (substr($it['createdAt'] ?? '', 0, 7) === date('Y-m')) {
        $month++;
    }
    $potential += (float)($it['total'] ?? 0);
}
?>
<h2>Estimations</h2>

<div class="cards">
  <div class="card"><span>📊</span><strong><?= e((string)$total) ?></strong><p>estimations totales</p></div>
  <div class="card"><span>📅</span><strong><?= e((string)$month) ?></strong><p>ce mois</p></div>
  <div class="card"><span>💰</span><strong><?= e(number_format($potential, 0, '.', "'")) ?> CHF</strong><p>potentiel estimé</p></div>
</div>

<div class="panel-box">
  <p>Page publique : <a class="button small" target="_blank" href="estimation.php">Ouvrir l’estimation client</a></p>

  <?php if (!$items): ?>
    <p>Aucune estimation enregistrée.</p>
  <?php else: ?>
    <table class="admin-table">
      <thead><tr><th>Date</th><th>Nom</th><th>Email</th><th>Type</th><th>Montant</th></tr></thead>
      <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?= e(date('d.m.Y H:i', strtotime($it['createdAt'] ?? 'now'))) ?></td>
          <td><?= e($it['name'] ?? '') ?></td>
          <td><?= e($it['email'] ?? '') ?></td>
          <td><?= e($it['type'] ?? '') ?></td>
          <td><?= e(number_format((float)($it['total'] ?? 0), 0, '.', "'")) ?> CHF</td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
