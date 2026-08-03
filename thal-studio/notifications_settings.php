<?php
$page_title = 'Notifications — THAL Studio';
require __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/notifications.php';

$file = __DIR__ . '/data/settings/notifications.json';
$current = thal_notification_settings(__DIR__);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        http_response_code(403);
        exit('Jeton de sécurité invalide.');
    }

    $current['enabled'] = !empty($_POST['enabled']);
    $current['ntfy_topic'] = trim((string)($_POST['ntfy_topic'] ?? ''));

    if (!is_dir(dirname($file))) mkdir(dirname($file), 0755, true);
    file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    $message = 'Notifications enregistrées.';

    if (isset($_POST['send_test'])) {
        $sent = thal_send_ntfy_notification(
            'Test THAL Studio',
            'Si tu reçois ce message, les notifications fonctionnent.',
            __DIR__,
            'https://thalphotographie.ch/thal-studio/estimations.php'
        );
        $message .= $sent ? ' Notification de test envoyée — vérifie ton téléphone.' : ' Échec de l’envoi du test (canal vide ou notifications désactivées ?).';
    }
}
?>
<h2>Notifications</h2>

<div class="panel-box wide">
  <p class="hint">Reçois une notification sur ton téléphone dès qu'un client envoie une demande via le formulaire de contact du site. Ça passe par <strong>ntfy.sh</strong>, un service gratuit, sans compte ni carte bancaire.</p>

  <h3>Configuration sur ton téléphone (une seule fois)</h3>
  <ol class="hint" style="line-height:1.9; padding-left:20px;">
    <li>Installe l'app gratuite <strong>ntfy</strong> (App Store ou Google Play).</li>
    <li>Ouvre l'app et appuie sur le bouton « + » pour ajouter un abonnement.</li>
    <li>Colle exactement ce nom de canal : <code><?= e($current['ntfy_topic']) ?></code></li>
    <li>Reviens ici, coche « Activer », enregistre, puis clique sur « Enregistrer et tester ».</li>
  </ol>

  <?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>

  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label class="checkbox-row"><input type="checkbox" name="enabled" value="1" <?= !empty($current['enabled']) ? 'checked' : '' ?>> Activer les notifications push</label>
    <label>Nom du canal (topic ntfy)<input name="ntfy_topic" value="<?= e($current['ntfy_topic']) ?>"></label>
    <p class="hint">Garde ce nom secret : toute personne qui le connaît peut s'abonner au même canal et voir tes notifications. Tu peux le changer ici si besoin — pense alors à mettre à jour l'abonnement dans l'app.</p>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <button type="submit">Enregistrer</button>
      <button type="submit" name="send_test" value="1">Enregistrer et tester</button>
    </div>
  </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
