<?php
$activeNav = 'animaux';
$footerDisclaimer = 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Photos d’animaux de compagnie — THAL Photographie</title>
  <meta name="description" content="Séance photo pour votre chien, chat ou autre compagnon, en intérieur ou en extérieur, chez vous ou sur le lieu de votre choix." />
  <meta name="theme-color" content="#070b16" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
</head>
<body>
  <div class="wrap">
    <?php include __DIR__ . '/inc/site-nav.php'; ?>

    <main>
      <section class="card pageHero reveal">
        <span class="pageKicker">Animaux de compagnie</span>
        <h1>Des photos qui capturent leur personnalité.</h1>
        <p class="lead">
          Chien, chat ou autre compagnon : une séance en intérieur ou en extérieur, chez vous ou sur le lieu de votre
          choix, pensée pour mettre en valeur son caractère.
        </p>
      </section>

      <?php $carouselKey = 'animaux'; $carouselCategory = 'Animaux'; $carouselLabel = 'Animaux de compagnie'; include __DIR__ . '/inc/photo-carousel.php'; ?>

      <section class="priceGrid">
        <article class="priceCard reveal">
          <h3>Essentiel</h3>
          <div class="priceDuration">Séance de 30 min</div>
          <div class="priceValue">dès <small>CHF</small> 165</div>
          <ul>
            <li>8 photos retouchées</li>
            <li>Intérieur ou extérieur</li>
            <li>Livraison privée sécurisée</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Essentiel animaux (30 min, 8 photos) — dès 165 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>

        <article class="priceCard reveal">
          <h3>Duo / Fratrie</h3>
          <div class="priceDuration">Séance de 45 min</div>
          <div class="priceValue">dès <small>CHF</small> 225</div>
          <ul>
            <li>12 photos retouchées</li>
            <li>2 animaux, ou animal et propriétaire</li>
            <li>Livraison privée sécurisée</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Duo / Fratrie animaux (45 min, 12 photos) — dès 225 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>

        <article class="priceCard reveal">
          <h3>Balade extérieure</h3>
          <div class="priceDuration">Séance de 1 h</div>
          <div class="priceValue">dès <small>CHF</small> 295</div>
          <ul>
            <li>15 photos retouchées</li>
            <li>En mouvement, plusieurs lieux</li>
            <li>Livraison privée sécurisée</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Balade extérieure animaux (1 h, 15 photos) — dès 295 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>
      </section>

      <p class="cardNote reveal">
        Chaque séance comprend la sélection, la retouche professionnelle et la livraison privée sécurisée via SwissTransfer.
      </p>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
</body>
</html>
