<?php
/**
 * Barre de navigation commune aux pages de gamme.
 * Attend une variable $activeNav (ex: 'identite', 'portraits', 'reportages',
 * 'professionnels', 'pourquoi', 'galerie') définie avant l'include pour surligner la page active.
 */
$activeNav = $activeNav ?? '';

$thalPrestationItems = [
  'identite'       => ['href' => 'identite.php',      'label' => 'Identité'],
  'portraits'      => ['href' => 'portraits.php',      'label' => 'Portraits'],
  'animaux'        => ['href' => 'animaux.php',        'label' => 'Animaux de compagnie'],
  'reportages'     => ['href' => 'reportages.php',     'label' => 'Reportages'],
  'professionnels' => ['href' => 'professionnels.php', 'label' => 'Professionnels'],
];
$thalPrestationsActive = array_key_exists($activeNav, $thalPrestationItems);
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
    <div class="navDropdown">
      <button class="chip navDropdownToggle<?= $thalPrestationsActive ? ' active' : '' ?>" type="button" aria-haspopup="true" aria-expanded="false">
        Prestations <span class="caret">▾</span>
      </button>
      <div class="navDropdownMenu">
        <?php foreach ($thalPrestationItems as $key => $item): ?>
          <a class="<?= $activeNav === $key ? 'active' : '' ?>" href="<?= htmlspecialchars($item['href']) ?>"><?= htmlspecialchars($item['label']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <a class="chip<?= $activeNav === 'pourquoi' ? ' active' : '' ?>" href="pourquoi-thal.php">Pourquoi THAL</a>
    <a class="chip<?= $activeNav === 'galerie' ? ' active' : '' ?>" href="galerie.html">Galerie</a>
    <a class="btn" href="index.html#contact">Devis</a>
  </nav>
</header>

<script>
(() => {
  document.querySelectorAll(".navDropdownToggle").forEach(btn => {
    const menu = btn.nextElementSibling;
    btn.addEventListener("click", e => {
      e.stopPropagation();
      const isOpen = menu.classList.toggle("open");
      btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
      if(isOpen){
        menu.style.transform = "translateX(-50%)";
        const rect = menu.getBoundingClientRect();
        const overflowRight = rect.right - window.innerWidth;
        const overflowLeft = -rect.left;
        if(overflowRight > 0) menu.style.transform = `translateX(calc(-50% - ${overflowRight + 8}px))`;
        else if(overflowLeft > 0) menu.style.transform = `translateX(calc(-50% + ${overflowLeft + 8}px))`;
      }
    });
  });
  document.addEventListener("click", () => {
    document.querySelectorAll(".navDropdownMenu.open").forEach(m => {
      m.classList.remove("open");
      if(m.previousElementSibling) m.previousElementSibling.setAttribute("aria-expanded", "false");
    });
  });
  document.addEventListener("keydown", e => {
    if(e.key !== "Escape") return;
    document.querySelectorAll(".navDropdownMenu.open").forEach(m => {
      m.classList.remove("open");
      if(m.previousElementSibling) m.previousElementSibling.setAttribute("aria-expanded", "false");
    });
  });
})();
</script>
