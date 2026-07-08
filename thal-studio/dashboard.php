<?php
$page_title = 'Tableau de bord - THAL Studio';
require __DIR__ . '/includes/header.php';

$quotes = thal_quote_items(__DIR__);
$estimations = thal_estimation_items(__DIR__);
$clients = thal_collect_clients($quotes, $estimations);

$quotedTotal = array_sum(array_map(fn($q) => (float)$q['amount'], $quotes));
$potentialMin = array_sum(array_map(fn($e) => (float)$e['priceMin'], $estimations));
$potentialMax = array_sum(array_map(fn($e) => (float)$e['priceMax'], $estimations));
$newEstimations = count(array_filter($estimations, fn($e) => ($e['status'] ?? 'new') === 'new'));
$recentQuotes = array_slice($quotes, 0, 5);
$recentClients = array_slice($clients, 0, 5);
?>
<div class="page-heading">
  <div>
    <p class="eyebrow">THAL Studio</p>
    <h2>Tableau de bord</h2>
  </div>
  <div class="heading-actions">
    <a class="button" href="devis.php">Nouveau devis</a>
    <a class="button muted" href="estimation_settings.php">Packs & tarifs</a>
  </div>
</div>

<div class="metric-grid">
  <a class="metric-card accent" href="quotes.php">
    <span class="metric-label">Devis enregistrés</span>
    <strong><?= e((string)count($quotes)) ?></strong>
    <small><?= e(thal_money((float)$quotedTotal)) ?> au total</small>
  </a>
  <a class="metric-card" href="clients.php">
    <span class="metric-label">Clients identifiés</span>
    <strong><?= e((string)count($clients)) ?></strong>
    <small>Depuis les devis et estimations</small>
  </a>
  <a class="metric-card" href="estimations.php">
    <span class="metric-label">Estimations à suivre</span>
    <strong><?= e((string)$newEstimations) ?></strong>
    <small><?= e(thal_money((float)$potentialMin)) ?> - <?= e(thal_money((float)$potentialMax)) ?></small>
  </a>
</div>

<div class="dashboard-grid">
  <section class="panel-box">
    <div class="panel-title">
      <h3>Derniers devis</h3>
      <a href="quotes.php">Tout voir</a>
    </div>

    <?php if (!$recentQuotes): ?>
      <p class="empty-state">Aucun devis enregistré pour le moment.</p>
    <?php else: ?>
      <table class="admin-table compact">
        <thead><tr><th>Devis</th><th>Client</th><th>Montant</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($recentQuotes as $quote): ?>
          <tr>
            <td><strong><?= e($quote['quoteNumber']) ?></strong><br><small><?= e(thal_date_label($quote['eventDate'])) ?></small></td>
            <td><?= e($quote['clientName']) ?><br><small><?= e($quote['eventPlace'] ?: $quote['serviceType']) ?></small></td>
            <td><?= e($quote['amount'] > 0 ? thal_money((float)$quote['amount']) : '-') ?></td>
            <td><a class="button small" href="devis.php?q=<?= urlencode($quote['id']) ?>">Ouvrir</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>

  <section class="panel-box">
    <div class="panel-title">
      <h3>Clients récents</h3>
      <a href="clients.php">Tout voir</a>
    </div>

    <?php if (!$recentClients): ?>
      <p class="empty-state">Les clients apparaîtront ici dès qu'un devis ou une estimation existe.</p>
    <?php else: ?>
      <div class="client-list">
        <?php foreach ($recentClients as $client): ?>
          <div class="client-row">
            <div>
              <strong><?= e($client['name']) ?></strong>
              <small><?= e($client['latestLabel'] ?: 'Activité récente') ?> · <?= e(thal_date_label($client['lastActivity'], true)) ?></small>
            </div>
            <a class="button small muted" href="devis.php?<?= e(thal_prefill_query($client)) ?>">Devis</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
