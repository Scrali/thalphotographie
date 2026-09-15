<?php
/**
 * Bloc photo réutilisable pour les pages de gamme : un bandeau pleine largeur
 * suivi d'une bande de vignettes cliquables.
 *
 * Attend :
 *   $carouselCategory : nom de catégorie dans photos/gallery.auto.json (défaut).
 *   $carouselLabel    : texte affiché sous le bandeau et sur les vignettes.
 *   $carouselKey      : optionnel (ex: 'portraits'). Permet à Thal Studio
 *                       (Paramètres > Carrousels) de rediriger vers une autre
 *                       catégorie sans toucher au code.
 *
 * Si la catégorie ne contient aucune photo, le bloc entier se retire de la page
 * plutôt que d'afficher un trou.
 */
$carouselCategory = $carouselCategory ?? 'Accueil';
$carouselLabel    = $carouselLabel ?? 'Aperçu';

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
<div class="band reveal" id="<?= htmlspecialchars($blockId) ?>_band" data-cat="<?= htmlspecialchars($carouselCategory) ?>" data-label="<?= htmlspecialchars($carouselLabel) ?>" hidden>
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
  const band  = document.getElementById(<?= json_encode($blockId . '_band') ?>);
  const strip = document.getElementById(<?= json_encode($blockId . '_strip') ?>);
  if (!band || !strip) return;

  const cat   = band.dataset.cat;
  const label = band.dataset.label;
  const MAX   = 6;

  const normKey = (s) => String(s || "")
    .normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().trim();

  function filesFor(data, wanted) {
    if (!data || typeof data !== "object") return [];
    const want = normKey(wanted);
    const key = Object.keys(data).find((k) => normKey(k) === want) || wanted;
    const raw = Array.isArray(data[key]) ? data[key] : [];
    return raw
      .map((x) => (typeof x === "string" ? { cat: key, file: x }
                 : (x && typeof x.file === "string" ? { cat: key, file: x.file } : null)))
      .filter(Boolean);
  }

  function shuffle(arr) {
    const a = [...arr];
    for (let i = a.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  }

  const esc = (s) => String(s).replace(/[&<>"']/g,
    (m) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[m]));

  const url = (item) => "photos/" + encodeURIComponent(item.cat) + "/" + encodeURIComponent(item.file);

  const jsonUrl = new URL("./photos/gallery.auto.json", window.location.href);
  jsonUrl.searchParams.set("v", Date.now());

  fetch(jsonUrl, { cache: "no-store" })
    .then((res) => { if (!res.ok) throw new Error("HTTP " + res.status); return res.json(); })
    .then((data) => {
      const items = shuffle(filesFor(data, cat));
      if (!items.length) return;                 // catégorie vide : on laisse le bloc masqué

      const bandImg = band.querySelector("img");
      bandImg.src = url(items[0]);
      bandImg.alt = label;
      bandImg.loading = "lazy";
      band.hidden = false;

      const thumbs = items.slice(0, MAX);
      if (thumbs.length > 1) {
        strip.querySelector(".strip").innerHTML = thumbs.map((item) => `
          <figure class="tile" role="button" tabindex="0">
            <img src="${url(item)}" alt="${esc(label)}" loading="lazy">
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
        document.querySelectorAll("#" + CSS.escape(band.id) + " .reveal, #" + CSS.escape(strip.id) + " .reveal")
          .forEach((el) => el.classList.add("on"));
        band.classList.add("on");
      });
    })
    .catch(() => { /* galerie indisponible : les blocs restent masqués */ });
})();
</script>
