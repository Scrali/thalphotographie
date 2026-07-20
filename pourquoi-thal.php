<?php
$activeNav = 'pourquoi';
// Pas de ligne tarifaire sur cette page de présentation.
$footerDisclaimer = '';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Pourquoi THAL Photographie ? — THAL Photographie</title>
  <meta name="description" content="Approche, matériel, retouche, livraison et ancrage local : pourquoi choisir THAL Photographie." />
  <meta name="theme-color" content="#070b16" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
</head>
<body>
  <div class="wrap">
    <?php include __DIR__ . '/inc/site-nav.php'; ?>

    <main>
      <section class="card pageHero reveal">
        <span class="pageKicker">Pourquoi THAL Photographie ?</span>
        <h1>Ce qui fait la différence.</h1>
        <p class="lead">
          Cette page explique la façon de travailler de THAL Photographie : l’approche, le matériel utilisé,
          la retouche, la livraison des images et l’ancrage local.
        </p>
      </section>

      <?php $carouselCategory = 'Accueil'; $carouselLabel = 'THAL Photographie'; include __DIR__ . '/inc/photo-carousel.php'; ?>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Mon approche</h2>
        <div class="placeholderBlock">[À COMPLÉTER]</div>
      </section>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Matériel professionnel</h2>
        <div class="placeholderBlock">[À COMPLÉTER]</div>
      </section>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Retouche et qualité de livraison</h2>
        <div class="placeholderBlock">[À COMPLÉTER]</div>
      </section>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Galerie privée en ligne</h2>
        <div class="placeholderBlock">[À COMPLÉTER]</div>
      </section>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Photographe local (Sainte-Croix / Nord vaudois)</h2>
        <div class="placeholderBlock">[À COMPLÉTER]</div>
      </section>

      <div class="actions reveal" style="justify-content:center; margin-top:var(--gap)">
        <a class="btn" href="index.html#contact">Me contacter</a>
      </div>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
</body>
</html>
