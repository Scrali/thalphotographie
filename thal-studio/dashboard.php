<?php
$page_title = 'Tableau de bord — THAL Studio';
require __DIR__ . '/includes/header.php';
?>
<h2>Tableau de bord</h2>
<div class="cards">
  <a class="card primary" href="devis.php">
    <span>📄</span>
    <strong>Nouveau devis</strong>
    <p>Créer ou modifier un devis avec le générateur actuel.</p>
  </a>
  <a class="card" href="quotes.php">
    <span>📂</span>
    <strong>Mes devis</strong>
    <p>Espace prévu pour l’historique des devis.</p>
  </a>
  <a class="card" href="clients.php">
    <span>👤</span>
    <strong>Clients</strong>
    <p>Espace prévu pour les fiches clients.</p>
  </a>
  <a class="card" href="settings.php">
    <span>⚙️</span>
    <strong>Paramètres</strong>
    <p>Changer le mot de passe et gérer l’application.</p>
  </a>
  <a class="card" href="estimations.php">
    <span>📊</span>
    <strong>Estimations</strong>
    <p>Voir les demandes d’estimation faites depuis le site.</p>
  </a>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
