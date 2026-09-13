<?php
$page_title = 'Statistiques de visite — THAL Studio';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/visits.php';

$summary = thal_visits_summary(__DIR__);
$countries = $summary['countries'];
uasort($countries, fn($a, $b) => ($b['count'] ?? 0) <=> ($a['count'] ?? 0));

$daily = $summary['daily'];
krsort($daily);
$daily = array_slice($daily, 0, 30, true);

$today = date('Y-m-d');
$last7 = 0;
foreach ($summary['daily'] as $day => $count) {
    if ($day >= date('Y-m-d', strtotime('-6 days'))) $last7 += (int)$count;
}
?>
<div class="page-heading">
  <div>
    <p class="eyebrow">THAL Studio</p>
    <h2>Statistiques de visite</h2>
  </div>
</div>

<p class="hint">
  Une visite est comptée une seule fois par période de 30 minutes d'activité continue : naviguer d'une page à
  l'autre ou recharger la même page ne crée pas de nouvelle entrée. Seul le pays de chaque visiteur est conservé —
  jamais son adresse IP.
</p>

<div class="metric-grid">
  <div class="metric-card accent">
    <span class="metric-label">Visites totales</span>
    <strong><?= e((string)$summary['total']) ?></strong>
    <small>Depuis la mise en place du compteur</small>
  </div>
  <div class="metric-card">
    <span class="metric-label">7 derniers jours</span>
    <strong><?= e((string)$last7) ?></strong>
    <small>Visites</small>
  </div>
  <div class="metric-card">
    <span class="metric-label">Pays différents</span>
    <strong><?= e((string)count($countries)) ?></strong>
    <small>Origines détectées</small>
  </div>
</div>

<div class="dashboard-grid">
  <section class="panel-box">
    <div class="panel-title">
      <h3>Visites par pays</h3>
    </div>
    <?php if (!$countries): ?>
      <p class="empty-state">Aucune visite enregistrée pour le moment.</p>
    <?php else: ?>
      <table class="admin-table compact">
        <thead><tr><th>Pays</th><th>Visites</th></tr></thead>
        <tbody>
        <?php foreach ($countries as $code => $info): ?>
          <tr>
            <td><?= e((string)($info['name'] ?? $code)) ?></td>
            <td><?= e((string)($info['count'] ?? 0)) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>

  <section class="panel-box">
    <div class="panel-title">
      <h3>30 derniers jours</h3>
    </div>
    <?php if (!$daily): ?>
      <p class="empty-state">Aucune visite enregistrée pour le moment.</p>
    <?php else: ?>
      <table class="admin-table compact">
        <thead><tr><th>Date</th><th>Visites</th></tr></thead>
        <tbody>
        <?php foreach ($daily as $day => $count): ?>
          <tr>
            <td><?= e($day === $today ? 'Aujourd’hui' : $day) ?></td>
            <td><?= e((string)$count) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
