<?php
/**
 * Bloc photo réutilisable pour les pages de gamme : un bandeau pleine largeur
 * suivi d'une bande de vignettes cliquables.
 *
 * Attend :
 *   $carouselCategory : nom de catégorie dans photos/gallery.auto.json (défaut).
 *   $carouselLabel    : texte affiché sous le bandeau et sur les vignettes.
 *   $carouselKey      : optionnel (ex: 'portraits'). Sert à deux choses :
 *                       Thal Studio > Carrousels peut rediriger la catégorie,
 *                       et Thal Studio > Photos du site peut fixer la photo du
 *                       bandeau (emplacement "band_<clé>").
 *
 * Si la catégorie ne contient aucune photo, le bloc entier se retire de la page
 * plutôt que d'afficher un trou.
 */
$carouselCategory = $carouselCategory ?? 'Accueil';
$carouselLabel    = $carouselLabel ?? 'Aperçu';
$carouselSlot     = !empty($carouselKey) ? 'band_' . $carouselKey : '';

if (!empty($carouselKey)) {
    $carouselMapFile = __DIR__ . '/../thal-studio/data/settings/carousel_map.json';
    if (is_file($carouselMapFile)) {
        $carouselMapData = json_decode((string)file_get_contents($carouselMapFile), true);
        if (is_array($carouselMapData) && !empty($carouselMapData[$carouselKey])) {
            $carouselCategory = (string)$carouselMapData[$carouselKey];
        }
    }
}

$blockId = 'thalPhotos_' . preg_replace('/[^a-zA-Z0-9]/', '', $carouselCategory);
?>
<div class="band reveal" id="<?= htmlspecialchars($blockId) ?>_band"
     data-cat="<?= htmlspecialchars($carouselCategory) ?>"
     data-label="<?= htmlspecialchars($carouselLabel) ?>"
     data-slot="<?= htmlspecialchars($carouselSlot) ?>" hidden>
  <img alt="" />
  <div class="bandCap wrap"><span><?= htmlspecialchars($carouselLabel) ?></span></div>
</div>

<section class="wrap tight" id="<?= htmlspecialchars($blockId) ?>_strip" hidden>
  <div class="sectionHead row reveal">
    <div>
      <h2>Quelques images.</h2>
      <p>Cliquez sur une photo pour l’agrandir.</p>
    </div>
    <a class="ghost" href="galerie.html?cat=<?= urlencode($carouselCategory) ?>">Voir toute la galerie
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
  </div>
  <div class="strip reveal"></div>
</section>

<script>
(() => {
  const run = () => {
  const band  = document.getElementById(<?= json_encode($blockId . '_band') ?>);
  const strip = document.getElementById(<?= json_encode($blockId . '_strip') ?>);
  if (!band || !strip || !window.thalPhotos) return;

  const cat   = band.dataset.cat;
  const label = band.dataset.label;
  const slot  = band.dataset.slot;
  const MAX   = 6;

  const esc = (s) => String(s).replace(/[&<>"']/g,
    (m) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[m]));

  const shuffle = (arr) => {
    const a = [...arr];
    for (let i = a.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  };

  window.thalPhotos().then(({ itemsOf, chosen }) => {
    const items = itemsOf(cat);
    if (!items.length) return;                  // catégorie vide : bloc masqué

    // Photo fixée dans THAL Studio, sinon une au hasard dans la catégorie.
    const pick = (slot && chosen(slot)) || shuffle(items)[0];

    const bandImg = band.querySelector("img");
    bandImg.src = pick.src;
    bandImg.alt = label;
    bandImg.loading = "lazy";
    band.hidden = false;

    const thumbs = shuffle(items).slice(0, MAX);
    if (thumbs.length > 1) {
      strip.querySelector(".strip").innerHTML = thumbs.map((item) => `
        <figure class="tile" role="button" tabindex="0">
          <img src="${item.src}" alt="${esc(label)}" loading="lazy">
          <figcaption>${esc(label)}</figcaption>
        </figure>`).join("");

      const tiles = strip.querySelectorAll(".tile");
      tiles.forEach((tile) => {
        tile.addEventListener("click", () => window.thalLightbox(tiles, tile));
        tile.addEventListener("keydown", (e) => {
          if (e.key !== "Enter" && e.key !== " ") return;
          e.preventDefault();
          window.thalLightbox(tiles, tile);
        });
      });
      strip.hidden = false;
    }

    // Les blocs viennent d'apparaître : on relance l'animation d'entrée.
    requestAnimationFrame(() => {
      band.classList.add("on");
      strip.querySelectorAll(".reveal").forEach((el) => el.classList.add("on"));
    });
  });
  };
  // site.js est chargé en "defer" : il s'exécute avant DOMContentLoaded, donc
  // on attend cet évènement pour être sûr que window.thalPhotos existe.
  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", run);
  else run();
})();
</script>
