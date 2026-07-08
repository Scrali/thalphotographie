<?php
$page_title = 'Clients - THAL Studio';
require __DIR__ . '/includes/header.php';

$quotes = thal_quote_items(__DIR__);
$estimations = thal_estimation_items(__DIR__);
$clients = thal_collect_clients($quotes, $estimations);
$search = trim((string)($_GET['s'] ?? ''));

if ($search !== '') {
    $needle = thal_lower($search);
    $clients = array_values(array_filter($clients, function ($client) use ($needle) {
        $haystack = thal_lower(implode(' ', [
            $client['name'] ?? '',
            $client['email'] ?? '',
            $client['phone'] ?? '',
            $client['address'] ?? '',
            $client['latestLabel'] ?? '',
        ]));

        return str_contains($haystack, $needle);
    }));
}

$withEmail = count(array_filter($clients, fn($client) => trim((string)$client['email']) !== ''));
$quotedTotal = array_sum(array_map(fn($client) => (float)$client['quotedTotal'], $clients));
?>
<div class="page-heading">
  <div>
    <p class="eyebrow">Carnet automatique</p>
    <h2>Clients</h2>
  </div>
  <a class="button" href="devis.php">Nouveau devis</a>
</div>

<div class="metric-grid small">
  <div class="metric-card">
    <span class="metric-label">Clients</span>
    <strong><?= e((string)count($clients)) ?></strong>
    <small><?= $search !== '' ? 'Résultat de recherche' : 'Contacts détectés' ?></small>
  </div>
  <div class="metric-card">
    <span class="metric-label">Avec email</span>
    <strong><?= e((string)$withEmail) ?></strong>
    <small>Prêts pour l'envoi manuel</small>
  </div>
  <div class="metric-card">
    <span class="metric-label">Devis cumulés</span>
    <strong><?= e(thal_money((float)$quotedTotal)) ?></strong>
    <small>Sur les clients affichés</small>
  </div>
</div>

<div class="panel-box">
  <form method="get" class="search-form">
    <input type="search" name="s" value="<?= e($search) ?>" placeholder="Rechercher un client, email, téléphone...">
    <button type="submit">Rechercher</button>
    <?php if ($search !== ''): ?><a class="button small muted" href="clients.php">Réinitialiser</a><?php endif; ?>
  </form>

  <?php if (!$clients): ?>
    <p class="empty-state">Aucun client trouvé. Les fiches se créent automatiquement à partir des devis et estimations.</p>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Client</th>
          <th>Contact</th>
          <th>Activité</th>
          <th>Valeur</th>
          <th>Dernier mouvement</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($clients as $client): ?>
        <tr>
          <td>
            <strong><?= e($client['name']) ?></strong>
            <?php if ($client['address']): ?><br><small><?= e($client['address']) ?></small><?php endif; ?>
          </td>
          <td>
            <?= e($client['email'] ?: '-') ?>
            <br><small><?= e($client['phone'] ?: '-') ?></small>
          </td>
          <td>
            <span class="badge"><?= e((string)$client['quoteCount']) ?> devis</span>
            <span class="badge muted"><?= e((string)$client['estimateCount']) ?> estim.</span>
          </td>
          <td>
            <?= e($client['quotedTotal'] > 0 ? thal_money((float)$client['quotedTotal']) : '-') ?>
            <?php if ($client['potentialMax'] > 0): ?><br><small>Potentiel <?= e(thal_money((float)$client['potentialMin'])) ?> - <?= e(thal_money((float)$client['potentialMax'])) ?></small><?php endif; ?>
          </td>
          <td>
            <?= e($client['latestLabel'] ?: '-') ?>
            <br><small><?= e(thal_date_label($client['lastActivity'], true)) ?></small>
          </td>
          <td class="actions-cell">
            <a class="button small" href="devis.php?<?= e(thal_prefill_query($client)) ?>">Nouveau devis</a>
            <?php if ($client['latestQuoteId']): ?><a class="button small muted" href="devis.php?q=<?= urlencode($client['latestQuoteId']) ?>">Dernier devis</a><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
