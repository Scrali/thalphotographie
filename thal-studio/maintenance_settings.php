<?php
$page_title = 'Mode maintenance — THAL Studio';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/maintenance.php';

$settingsFile = __DIR__ . '/data/settings/maintenance.json';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_message'])) {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        http_response_code(403);
        exit('Jeton de sécurité invalide.');
    }
    $current = thal_maintenance_settings(__DIR__);
    $current['message'] = trim((string)($_POST['message'] ?? ''));
    file_put_contents($settingsFile, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    $message = 'Message enregistré.';
}

$isOn = thal_maintenance_is_on(__DIR__);
$previewUrl = thal_maintenance_preview_url(__DIR__);
$current = thal_maintenance_settings(__DIR__);
?>
<h2>Mode maintenance</h2>

<div class="panel-box wide">
  <p class="hint">Quand le mode maintenance est activé, tous les visiteurs voient une page « site en maintenance » — sauf toi, si tu ouvres le lien de prévisualisation ci-dessous une fois dans ton navigateur. Thal Studio (ici) reste toujours accessible, avec ou sans maintenance.</p>

  <div style="display:flex; align-items:center; gap:14px; margin:20px 0;">
    <span class="badge" style="<?= $isOn ? 'background:#3b1212;color:#ffbcbc;border:1px solid #6b2323;' : 'background:#123b1e;color:#bcffcf;border:1px solid #236b3a;' ?>">
      <?= $isOn ? 'Site en maintenance' : 'Site en ligne' ?>
    </span>

    <form method="post" action="maintenance_toggle.php">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <?php if ($isOn): ?>
        <input type="hidden" name="action" value="disable">
        <button type="submit" class="button">Remettre le site en ligne</button>
      <?php else: ?>
        <input type="hidden" name="action" value="enable">
        <button type="submit" class="button danger">Passer le site en maintenance</button>
      <?php endif; ?>
    </form>
  </div>

  <h3>Ton lien de prévisualisation</h3>
  <p class="hint">Ouvre ce lien une seule fois dans un navigateur (téléphone, ordinateur…) pour continuer à voir le vrai site même quand la maintenance est activée. Garde-le secret — toute personne qui l'ouvre a le même accès.</p>
  <div class="admin-form" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
    <input id="previewUrl" readonly value="<?= e($previewUrl) ?>" style="flex:1; min-width:260px; margin-top:0;">
    <button type="button" id="copyPreviewUrl" class="button muted">Copier</button>
    <a class="button muted" href="<?= e($previewUrl) ?>" target="_blank" rel="noopener">Ouvrir</a>
  </div>
  <p class="slot-status" id="copyStatus"></p>

  <h3>Message affiché aux visiteurs</h3>
  <?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>
  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="save_message" value="1">
    <label>Texte affiché sur la page de maintenance (facultatif)<textarea name="message" rows="3" placeholder="Le site est en cours de mise à jour. Merci de repasser dans un instant."><?= e($current['message']) ?></textarea></label>
    <button type="submit" class="button muted">Enregistrer le message</button>
  </form>
</div>

<script>
  const copyBtn = document.getElementById('copyPreviewUrl');
  if (copyBtn) {
    copyBtn.addEventListener('click', async () => {
      const input = document.getElementById('previewUrl');
      const status = document.getElementById('copyStatus');
      try {
        await navigator.clipboard.writeText(input.value);
        if (status) status.textContent = 'Lien copié.';
      } catch (e) {
        input.select();
        if (status) status.textContent = 'Sélectionne et copie le lien manuellement.';
      }
    });
  }
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
