<?php
/**
 * Barre de navigation commune aux pages de gamme.
 * Attend une variable $activeNav (ex: 'identite', 'portraits', 'reportages',
 * 'professionnels', 'pourquoi') définie avant l'include pour surligner la page active.
 */
$activeNav = $activeNav ?? '';

$thalNavItems = [
  'identite'       => ['href' => 'identite.php',      'label' => 'Identité'],
  'portraits'      => ['href' => 'portraits.php',      'label' => 'Portraits'],
  'reportages'     => ['href' => 'reportages.php',     'label' => 'Reportages'],
  'professionnels' => ['href' => 'professionnels.php', 'label' => 'Professionnels'],
  'pourquoi'       => ['href' => 'pourquoi-thal.php',  'label' => 'Pourquoi THAL'],
  'galerie'        => ['href' => 'galerie.html',       'label' => 'Galerie'],
];
?>
<header class="nav">
  <a class="brand" href="index.html" aria-label="Accueil">
    <img src="assets/thal1.png" alt="THAL" />
    <div class="name">
      <span class="brandTitle">T•H•A•L</span>
      <span class="divider"></span>
      <span class="subtitle">Photographie</span>
    </div>
  </a>

  <nav class="navlinks">
    <?php foreach ($thalNavItems as $key => $item): ?>
      <a class="chip<?= $activeNav === $key ? ' active' : '' ?>" href="<?= htmlspecialchars($item['href']) ?>"><?= htmlspecialchars($item['label']) ?></a>
    <?php endforeach; ?>
    <a class="btn" href="index.html#contact">Devis</a>
  </nav>
</header>
