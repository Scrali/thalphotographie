<?php
$page_title = 'Changer le mot de passe — THAL Studio';
require __DIR__ . '/includes/header.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Session expirée. Recharge la page.';
    } else {
        $old = $_POST['old_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!password_verify($old, THAL_PASSWORD_HASH)) {
            $error = 'Ancien mot de passe incorrect.';
        } elseif (strlen($new) < 10) {
            $error = 'Le nouveau mot de passe doit contenir au moins 10 caractères.';
        } elseif ($new !== $confirm) {
            $error = 'La confirmation ne correspond pas.';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $config = "<?php\n";
            $config .= "// Configuration THAL Studio.\n";
            $config .= "// Ces identifiants ne sont PAS ceux d'OVH.\n\n";
            $config .= "define('THAL_USER', '" . addslashes(THAL_USER) . "');\n\n";
            $config .= "define('THAL_PASSWORD_HASH', '" . $hash . "');\n";

            $ok = file_put_contents(__DIR__ . '/config.php', $config);

            if ($ok === false) {
                $error = 'Impossible d’écrire dans config.php. Vérifie les permissions du fichier.';
            } else {
                $message = 'Mot de passe modifié avec succès.';
            }
        }
    }
}
?>
<h2>Changer le mot de passe</h2>

<div class="panel-box narrow">
  <?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>
  <?php if ($error): ?><p class="error-box"><?= e($error) ?></p><?php endif; ?>

  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <label>Ancien mot de passe
      <input type="password" name="old_password" required>
    </label>

    <label>Nouveau mot de passe
      <input type="password" name="new_password" required minlength="10">
    </label>

    <label>Confirmer le nouveau mot de passe
      <input type="password" name="confirm_password" required minlength="10">
    </label>

    <button type="submit">Enregistrer</button>
  </form>

  <p class="hint">Après un déploiement Git, le fichier config.php peut être remplacé par la version du dépôt. Pour un changement définitif, pense aussi à mettre à jour le hash dans GitHub.</p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
