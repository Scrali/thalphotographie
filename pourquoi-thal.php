<?php
$activeNav = 'pourquoi';
$footerDisclaimer = '';

/**
 * Chaque bloc est illustré par une photo de la galerie. Les catégories sont
 * celles gérées dans THAL Studio ; si une image manque, le bloc reste lisible
 * (la zone image se contente du fond sombre).
 */
$thalWhy = [
  [
    'h'   => 'Mon approche',
    'p'   => 'Pour moi, une bonne photo tient avant tout à deux choses : la lumière et la relation avec la personne photographiée. Je prends le temps de jouer avec la lumière disponible, ou d’apporter la mienne selon le lieu, et je cherche surtout un vrai échange avec les personnes que je photographie. C’est souvent ce qui fait la différence entre une photo posée et une photo qui vous ressemble vraiment. Mon objectif reste simple : votre pleine satisfaction du résultat.',
    'cat' => 'Portraits',
  ],
  [
    'h'   => 'Matériel professionnel',
    'p'   => 'Je travaille avec un Nikon Z8 et plusieurs objectifs adaptés à chaque type de prise de vue : portrait, reportage, photo d’identité. Comme je me déplace systématiquement chez vous ou sur le lieu de votre choix, j’utilise une solution entièrement autonome (éclairage, et impression sur place pour les photos d’identité) qui me permet de travailler dans les mêmes conditions de qualité, où que nous soyons.',
    'cat' => 'Projets',
  ],
  [
    'h'   => 'Retouche et qualité de livraison',
    'p'   => 'La retouche que je propose reste sobre et naturelle : l’objectif est de sublimer l’image, pas de la dénaturer. Le délai de livraison dépend du volume de travail et du type de prestation, je vous communique un délai précis dès la prise de contact.',
    'cat' => 'Accueil',
  ],
  [
    'h'   => 'Livraison privée et sécurisée',
    'p'   => 'Vos photos vous sont transmises via SwissTransfer, avec un accès protégé par un mot de passe défini à l’avance : un moyen simple, sécurisé et hébergé en Suisse pour recevoir vos images en haute définition, sans création de compte.',
    'cat' => 'Evenements',
  ],
  [
    'h'   => 'Photographe local (Sainte-Croix / Nord vaudois)',
    'p'   => 'J’habite la région et je m’y déplace au quotidien pour l’ensemble de mes prestations, un ancrage local qui me permet de bien connaître le Nord vaudois et de rester facilement disponible pour mes clients.',
    'cat' => 'Nature',
  ],
];
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Pourquoi THAL Photographie ? • THAL Photographie</title>
  <meta name="description" content="Approche, matériel, retouche, livraison et ancrage local : pourquoi choisir THAL Photographie." />
  <meta name="theme-color" content="#0a0a0c" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
</head>
<body>
  <?php include __DIR__ . '/inc/site-nav.php'; ?>

  <main>
    <section class="wrap pageHero">
      <span class="eyebrow reveal">Pourquoi THAL Photographie ?</span>
      <h1 class="reveal">Ce qui fait la différence.</h1>
      <p class="lead reveal">
        Cette page explique la façon de travailler de THAL Photographie : l’approche, le matériel utilisé,
        la retouche, la livraison des images et l’ancrage local.
      </p>
    </section>

    <section class="wrap tight">
      <div class="why">
        <?php foreach ($thalWhy as $i => $block): ?>
          <article class="reveal">
            <div class="whyText">
              <h3><?= htmlspecialchars($block['h']) ?></h3>
              <p><?= htmlspecialchars($block['p']) ?></p>
            </div>
            <div class="whyImg" data-cat="<?= htmlspecialchars($block['cat']) ?>"></div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="actions reveal" style="margin-top:44px">
        <a class="btn" href="index.html#contact">Me contacter
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </section>

    <?php include __DIR__ . '/inc/site-steps.php'; ?>
    <?php include __DIR__ . '/inc/site-faq.php'; ?>
  </main>

  <?php include __DIR__ . '/inc/site-footer.php'; ?>

  <script>
  /* Illustre chaque bloc avec une photo de sa catégorie, prise dans la galerie. */
  (() => {
    const slots = [...document.querySelectorAll(".whyImg[data-cat]")];
    if (!slots.length) return;

    const normKey = (s) => String(s || "")
      .normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().trim();

    const url = new URL("./photos/gallery.auto.json", window.location.href);
    url.searchParams.set("v", Date.now());

    fetch(url, { cache: "no-store" })
      .then((res) => { if (!res.ok) throw new Error("HTTP " + res.status); return res.json(); })
      .then((data) => {
        const keys = Object.keys(data || {});
        const used = new Set();

        const pick = (wanted) => {
          const key = keys.find((k) => normKey(k) === normKey(wanted));
          const pool = key && Array.isArray(data[key]) ? data[key] : [];
          for (const entry of pool) {
            const file = typeof entry === "string" ? entry : entry && entry.file;
            if (!file) continue;
            const src = "photos/" + encodeURIComponent(key) + "/" + encodeURIComponent(file);
            if (!used.has(src)) { used.add(src); return src; }
          }
          return null;
        };

        // Une photo de la catégorie voulue, sinon n'importe quelle autre non utilisée.
        const fallbackOrder = keys;
        slots.forEach((slot) => {
          let src = pick(slot.dataset.cat);
          for (let i = 0; !src && i < fallbackOrder.length; i++) src = pick(fallbackOrder[i]);
          if (!src) return;
          const img = new Image();
          img.src = src;
          img.alt = "";
          img.loading = "lazy";
          slot.appendChild(img);
        });
      })
      .catch(() => { /* pas d'image : les blocs restent lisibles */ });
  })();
  </script>
</body>
</html>
