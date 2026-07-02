<?php
$page_title = 'Mes devis — THAL Studio';
require __DIR__ . '/includes/header.php';

$dir = __DIR__ . '/data/quotes';
$quotes = [];

if (is_dir($dir)) {
    foreach (glob($dir . '/*.json') as $file) {
        $data = json_decode((string)file_get_contents($file), true);
        if (!is_array($data)) {
            continue;
        }
        $meta = $data['_meta'] ?? [];
        $quotes[] = [
            'id' => basename($file, '.json'),
            'quoteNumber' => $data['quoteNumber'] ?? ($meta['quoteNumber'] ?? 'DEV'),
            'clientName' => $data['clientName'] ?? ($meta['clientName'] ?? 'Client'),
            'eventDate' => $data['eventDate'] ?? '',
            'updatedAt' => $meta['updatedAt'] ?? date('c', filemtime($file)),
        ];
    }
}

usort($quotes, fn($a, $b) => strcmp($b['updatedAt'], $a['updatedAt']));
?>
<h2>Mes devis</h2>

<div class="panel-box">
  <?php if (!$quotes): ?>
    <p>Aucun devis sauvegardé pour le moment.</p>
    <a class="button" href="devis.php">Créer un devis</a>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Numéro</th>
          <th>Client</th>
          <th>Date événement</th>
          <th>Dernière modification</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($quotes as $q): ?>
        <tr>
          <td><?= e($q['quoteNumber']) ?></td>
          <td><?= e($q['clientName']) ?></td>
          <td><?= e($q['eventDate']) ?></td>
          <td><?= e(date('d.m.Y H:i', strtotime($q['updatedAt']))) ?></td>
          <td><a class="button small" href="devis.php?q=<?= urlencode($q['id']) ?>">Ouvrir</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
