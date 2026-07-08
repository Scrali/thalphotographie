<?php
$page_title = 'Mes devis - THAL Studio';
require __DIR__ . '/includes/header.php';

$quotes = thal_quote_items(__DIR__);
$search = trim((string)($_GET['s'] ?? ''));

if ($search !== '') {
    $needle = thal_lower($search);
    $quotes = array_values(array_filter($quotes, function ($quote) use ($needle) {
        $haystack = thal_lower(implode(' ', [
            $quote['quoteNumber'] ?? '',
            $quote['clientName'] ?? '',
            $quote['clientEmail'] ?? '',
            $quote['eventDate'] ?? '',
            $quote['eventPlace'] ?? '',
            $quote['serviceType'] ?? '',
        ]));

        return str_contains($haystack, $needle);
    }));
}

$quotedTotal = array_sum(array_map(fn($quote) => (float)$quote['amount'], $quotes));
?>
<div class="page-heading">
  <div>
    <p class="eyebrow">Historique</p>
    <h2>Mes devis</h2>
  </div>
  <a class="button" href="devis.php">Nouveau devis</a>
</div>

<div class="metric-grid small">
  <div class="metric-card">
    <span class="metric-label">Devis affichés</span>
    <strong><?= e((string)count($quotes)) ?></strong>
    <small><?= $search !== '' ? 'Résultat de recherche' : 'Tous les devis' ?></small>
  </div>
  <div class="metric-card">
    <span class="metric-label">Montant total</span>
    <strong><?= e(thal_money((float)$quotedTotal)) ?></strong>
    <small>Prix personnalisés sauvegardés</small>
  </div>
</div>

<div class="panel-box">
  <form method="get" class="search-form">
    <input type="search" name="s" value="<?= e($search) ?>" placeholder="Rechercher un devis, client, lieu, prestation...">
    <button type="submit">Rechercher</button>
    <?php if ($search !== ''): ?><a class="button small muted" href="quotes.php">Réinitialiser</a><?php endif; ?>
  </form>

  <?php if (!$quotes): ?>
    <p class="empty-state">Aucun devis trouvé.</p>
    <a class="button" href="devis.php">Créer un devis</a>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Numéro</th>
          <th>Client</th>
          <th>Prestation</th>
          <th>Montant</th>
          <th>Événement</th>
          <th>Modification</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($quotes as $quote): ?>
        <tr>
          <td><strong><?= e($quote['quoteNumber']) ?></strong><br><small><?= e(thal_date_label($quote['quoteDate'])) ?></small></td>
          <td><?= e($quote['clientName']) ?><br><small><?= e($quote['clientEmail'] ?: $quote['clientPhone']) ?></small></td>
          <td><?= e($quote['serviceType'] ?: '-') ?><br><small><?= e($quote['eventPlace'] ?: '-') ?></small></td>
          <td><?= e($quote['amount'] > 0 ? thal_money((float)$quote['amount']) : '-') ?></td>
          <td><?= e(thal_date_label($quote['eventDate'])) ?></td>
          <td><?= e(thal_date_label($quote['updatedAt'], true)) ?></td>
          <td class="actions-cell">
            <a class="button small" href="devis.php?q=<?= urlencode($quote['id']) ?>">Ouvrir</a>
            <form method="post" action="quote_duplicate.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($quote['id']) ?>"><button class="button small muted" type="submit">Dupliquer</button></form>
            <form method="post" action="quote_delete.php" onsubmit="return confirm('Supprimer ce devis ?');"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($quote['id']) ?>"><button class="button small danger" type="submit">Supprimer</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
