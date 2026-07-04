<?php
$page_title = 'Mes devis — THAL Studio';
require __DIR__ . '/includes/header.php';
$dir = __DIR__ . '/data/quotes';
$quotes = [];
$search = trim($_GET['s'] ?? '');
if (is_dir($dir)) {
    foreach (glob($dir . '/*.json') as $file) {
        $data = json_decode((string)file_get_contents($file), true);
        if (!is_array($data)) continue;
        $meta = $data['_meta'] ?? [];
        $item = [
            'id'=>basename($file, '.json'),
            'quoteNumber'=>$data['quoteNumber'] ?? ($meta['quoteNumber'] ?? 'DEV'),
            'clientName'=>$data['clientName'] ?? ($meta['clientName'] ?? 'Client'),
            'eventDate'=>$data['eventDate'] ?? ($meta['eventDate'] ?? ''),
            'updatedAt'=>$meta['updatedAt'] ?? date('c', filemtime($file)),
        ];
        $haystack = mb_strtolower($item['quoteNumber'].' '.$item['clientName'].' '.$item['eventDate']);
        if ($search === '' || str_contains($haystack, mb_strtolower($search))) $quotes[] = $item;
    }
}
usort($quotes, fn($a,$b)=>strcmp($b['updatedAt'],$a['updatedAt']));
?>
<h2>Mes devis</h2>
<div class="panel-box">
<form method="get" class="search-form">
<input type="search" name="s" value="<?= e($search) ?>" placeholder="Rechercher un devis, un client, une date...">
<button type="submit">Rechercher</button>
<?php if ($search !== ''): ?><a class="button small" href="quotes.php">Réinitialiser</a><?php endif; ?>
</form>
<?php if (!$quotes): ?>
<p>Aucun devis trouvé.</p><a class="button" href="devis.php">Créer un devis</a>
<?php else: ?>
<table class="admin-table"><thead><tr><th>Numéro</th><th>Client</th><th>Date événement</th><th>Dernière modification</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($quotes as $q): ?>
<tr>
<td><?= e($q['quoteNumber']) ?></td>
<td><?= e($q['clientName']) ?></td>
<td><?= e($q['eventDate']) ?></td>
<td><?= e(date('d.m.Y H:i', strtotime($q['updatedAt']))) ?></td>
<td class="actions-cell">
<a class="button small" href="devis.php?q=<?= urlencode($q['id']) ?>">Ouvrir</a>
<form method="post" action="quote_duplicate.php"><input type="hidden" name="id" value="<?= e($q['id']) ?>"><button class="button small" type="submit">Dupliquer</button></form>
<form method="post" action="quote_delete.php" onsubmit="return confirm('Supprimer ce devis ?');"><input type="hidden" name="id" value="<?= e($q['id']) ?>"><button class="button small danger" type="submit">Supprimer</button></form>
</td>
</tr>
<?php endforeach; ?>
</tbody></table>
<?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
