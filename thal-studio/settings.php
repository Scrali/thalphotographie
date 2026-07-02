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
  <div class="card">
    <span>💾</span>
    <strong>Presets serveur</strong>
    <p>Les 3 slots de mise en page sont maintenant sauvegardés sur OVH, avec fallback local navigateur.</p>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
