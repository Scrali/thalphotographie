<?php
/**
 * Barre de navigation commune aux pages publiques.
 * Attend une variable $activeNav (ex: 'identite', 'portraits', 'animaux',
 * 'reportages', 'professionnels', 'pourquoi', 'galerie') définie avant
 * l'include pour surligner la page active.
 * $navSolid = true force le fond opaque (pages sans grande image en tête).
 */
$activeNav = $activeNav ?? '';
$navSolid  = $navSolid ?? true;

$thalPrestationItems = [
  'identite'       => ['href' => 'identite.php',       'label' => 'Identité'],
  'portraits'      => ['href' => 'portraits.php',      'label' => 'Portraits'],
  'animaux'        => ['href' => 'animaux.php',        'label' => 'Animaux de compagnie'],
  'reportages'     => ['href' => 'reportages.php',     'label' => 'Reportages'],
  'professionnels' => ['href' => 'professionnels.php', 'label' => 'Professionnels'],
];
$thalPrestationsActive = array_key_exists($activeNav, $thalPrestationItems);
?>
<div class="watermark" aria-hidden="true"></div>

<header class="nav<?= $navSolid ? ' solid' : '' ?>" id="thalNav">
  <a class="brand" href="index.html" aria-label="Accueil THAL Photographie">
    <img src="assets/thal1.png" alt="" />
    <span class="name">
      <span class="brandTitle">T·H·A·L</span>
      <span class="subtitle">Photographie</span>
    </span>
  </a>

  <nav class="navlinks" aria-label="Navigation principale">
    <span class="navDropdown">
      <button class="navlink navDropdownToggle<?= $thalPrestationsActive ? ' active' : '' ?>" type="button" aria-haspopup="true" aria-expanded="false">
        Prestations
        <svg class="caret" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <span class="navDropdownMenu">
        <?php foreach ($thalPrestationItems as $key => $item): ?>
          <a class="<?= $activeNav === $key ? 'active' : '' ?>" href="<?= htmlspecialchars($item['href']) ?>"><?= htmlspecialchars($item['label']) ?></a>
        <?php endforeach; ?>
      </span>
    </span>
    <a class="navlink<?= $activeNav === 'galerie' ? ' active' : '' ?>" href="galerie.html">Galerie</a>
    <a class="navlink<?= $activeNav === 'pourquoi' ? ' active' : '' ?>" href="pourquoi-thal.php">Pourquoi THAL</a>
    <a class="navCta" href="index.html#contact">Demander un devis</a>
  </nav>

  <button class="burger" type="button" id="thalBurger" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="thalMobileMenu">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
  </button>
</header>

<nav class="mobileMenu" id="thalMobileMenu" aria-label="Menu" hidden>
  <a href="index.html">Accueil</a>
  <?php foreach ($thalPrestationItems as $key => $item): ?>
    <a class="<?= $activeNav === $key ? 'active' : '' ?>" href="<?= htmlspecialchars($item['href']) ?>"><?= htmlspecialchars($item['label']) ?></a>
  <?php endforeach; ?>
  <a class="<?= $activeNav === 'galerie' ? 'active' : '' ?>" href="galerie.html">Galerie</a>
  <a class="<?= $activeNav === 'pourquoi' ? 'active' : '' ?>" href="pourquoi-thal.php">Pourquoi THAL</a>
  <a href="index.html#contact">Demander un devis</a>
</nav>
