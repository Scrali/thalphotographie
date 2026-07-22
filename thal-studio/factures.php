<?php
$page_title = 'Mes factures - THAL Studio';
require __DIR__ . '/includes/header.php';

$quotes = array_values(array_filter(thal_quote_items(__DIR__), fn($q) => $q['docType'] === 'facture'));
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

$invoicedTotal = array_sum(array_map(fn($quote) => (float)$quote['amount'], $quotes));
$balanceTotal = array_sum(array_map(fn($quote) => max(0, (float)$quote['amount'] - (float)$quote['depositAmount']), $quotes));
?>
<div class="page-heading">
  <div>
    <p class="eyebrow">Historique</p>
    <h2>Mes factures</h2>
  </div>
  <a class="button" href="quotes.php">Convertir un devis</a>
</div>

<div class="metric-grid small">
  <div class="metric-card">
    <span class="metric-label">Factures affichées</span>
    <strong><?= e((string)count($quotes)) ?></strong>
    <small><?= $search !== '' ? 'Résultat de recherche' : 'Toutes les factures' ?></small>
  </div>
  <div class="metric-card">
    <span class="metric-label">Montant facturé</span>
    <strong><?= e(thal_money((float)$invoicedTotal)) ?></strong>
    <small>Total des factures affichées</small>
  </div>
  <div class="metric-card accent">
    <span class="metric-label">Solde restant dû</span>
    <strong><?= e(thal_money((float)$balanceTotal)) ?></strong>
    <small>Après déduction des acomptes reçus</small>
  </div>
</div>

<div class="panel-box">
  <form method="get" class="search-form">
    <input type="search" name="s" value="<?= e($search) ?>" placeholder="Rechercher une facture, client, lieu, prestation...">
    <button type="submit">Rechercher</button>
    <?php if ($search !== ''): ?><a class="button small muted" href="factures.php">Réinitialiser</a><?php endif; ?>
  </form>

  <?php if (!$quotes): ?>
    <p class="empty-state">Aucune facture trouvée. Les factures se créent depuis un devis existant, avec le bouton « Convertir en facture ».</p>
    <a class="button" href="quotes.php">Voir mes devis</a>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Numéro</th>
          <th>Client</th>
          <th>Prestation</th>
          <th>Montant</th>
          <th>Acompte reçu</th>
          <th>Solde à payer</th>
          <th>Prestation réalisée</th>
          <th>Modification</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($quotes as $quote):
        $balanceDue = max(0, (float)$quote['amount'] - (float)$quote['depositAmount']);
      ?>
        <tr>
          <td><strong><?= e($quote['quoteNumber']) ?></strong><br><small><?= e(thal_date_label($quote['quoteDate'])) ?></small></td>
          <td><?= e($quote['clientName']) ?><br><small><?= e($quote['clientEmail'] ?: $quote['clientPhone']) ?></small></td>
          <td><?= e($quote['serviceType'] ?: '-') ?><br><small><?= e($quote['eventPlace'] ?: '-') ?></small></td>
          <td><?= e($quote['amount'] > 0 ? thal_money((float)$quote['amount']) : '-') ?></td>
          <td><?= e($quote['depositAmount'] > 0 ? thal_money((float)$quote['depositAmount']) : '-') ?></td>
          <td><strong><?= e(thal_money($balanceDue)) ?></strong></td>
          <td><?= e(thal_date_label($quote['serviceDoneDate'])) ?></td>
          <td><?= e(thal_date_label($quote['updatedAt'], true)) ?></td>
          <td class="actions-cell">
            <a class="button small" href="devis.php?q=<?= urlencode($quote['id']) ?>">Ouvrir</a>
            <form method="post" action="quote_duplicate.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($quote['id']) ?>"><button class="button small muted" type="submit">Dupliquer</button></form>
            <form method="post" action="quote_delete.php" onsubmit="return confirm('Supprimer cette facture ?');"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($quote['id']) ?>"><input type="hidden" name="redirect" value="factures.php"><button class="button small danger" type="submit">Supprimer</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
