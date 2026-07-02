<?php
$page_title = 'Paramètres — THAL Studio';
require __DIR__ . '/includes/header.php';
?>
<h2>Paramètres</h2>
<div class="cards">
  <a class="card" href="change_password.php">
    <span>🔒</span>
    <strong>Changer le mot de passe</strong>
    <p>Modifier le mot de passe de connexion sans générer de hash à la main.</p>
  </a>
  <div class="card">
    <span>👤</span>
    <strong>Utilisateur</strong>
    <p>Utilisateur actuel : <?= e(THAL_USER) ?></p>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
