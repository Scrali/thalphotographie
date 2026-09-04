<?php
$activeNav = 'reportages';
$footerDisclaimer = 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Reportages — THAL Photographie</title>
  <meta name="description" content="Reportage photo pour mariages, concerts, anniversaires ou événements associatifs. Tri, retouche, galerie privée et téléchargement HD inclus." />
  <meta name="theme-color" content="#070b16" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
</head>
<body>
  <div class="wrap">
    <?php include __DIR__ . '/inc/site-nav.php'; ?>

    <main>
      <section class="card pageHero reveal">
        <span class="pageKicker">Reportages</span>
        <h1>Un reportage à la hauteur de votre événement.</h1>
        <p class="lead">
          Mariage, concert, anniversaire, événement associatif : la nature de l’événement ne change pas la formule —
          seuls les exemples diffèrent. Ce qui compte, c’est la durée de couverture et le soin apporté au résultat.
          Je me déplace directement sur le lieu de votre événement.
        </p>
      </section>

      <?php $carouselCategory = 'Evenements'; $carouselLabel = 'Reportages'; include __DIR__ . '/inc/photo-carousel.php'; ?>

      <section class="priceGrid">
        <article class="priceCard reveal">
          <h3>Essentiel</h3>
          <div class="priceDuration">Couverture de 2 h</div>
          <div class="priceValue">dès <small>CHF</small> 390</div>
          <ul>
            <li>Tri et sélection des meilleures images</li>
            <li>Retouche professionnelle</li>
            <li>Galerie privée en ligne</li>
            <li>Téléchargement HD</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Essentiel (2 h de couverture) — dès 390 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>

        <article class="priceCard reveal">
          <h3>Demi-journée</h3>
          <div class="priceDuration">Couverture de 4 h</div>
          <div class="priceValue">dès <small>CHF</small> 690</div>
          <ul>
            <li>Tri et sélection des meilleures images</li>
            <li>Retouche professionnelle</li>
            <li>Galerie privée en ligne</li>
            <li>Téléchargement HD</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Demi-journée (4 h de couverture) — dès 690 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>

        <article class="priceCard reveal">
          <h3>Étendu</h3>
          <div class="priceDuration">Couverture de 6 h</div>
          <div class="priceValue">dès <small>CHF</small> 990</div>
          <ul>
            <li>Tri et sélection des meilleures images</li>
            <li>Retouche professionnelle</li>
            <li>Galerie privée en ligne</li>
            <li>Téléchargement HD</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Étendu (6 h de couverture) — dès 990 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>
      </section>

      <div class="highlightBlock reveal">
        <h3>Mariage — journée complète</h3>
        <p>
          Des préparatifs à la soirée : une couverture continue pensée sur mesure pour votre mariage.
          Formule construite avec vous selon le déroulé de la journée.
        </p>
        <a class="btn" href="index.html?prefill=<?= urlencode('Mariage journée complète (préparatifs à la soirée) — devis personnalisé.') ?>#contact">Demander un devis personnalisé</a>
      </div>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
</body>
</html>
