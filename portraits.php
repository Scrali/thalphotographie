<?php
$activeNav = 'portraits';
$footerDisclaimer = 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Portraits — THAL Photographie</title>
  <meta name="description" content="Séances portrait individuel, duo, grossesse ou famille. Sélection, retouche professionnelle et livraison privée sécurisée incluses." />
  <meta name="theme-color" content="#070b16" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
</head>
<body>
  <div class="wrap">
    <?php include __DIR__ . '/inc/site-nav.php'; ?>

    <main>
      <section class="card pageHero reveal">
        <span class="pageKicker">Portraits</span>
        <h1>Des portraits qui vous ressemblent.</h1>
        <p class="lead">
          Trois formules pensées pour un portrait individuel, un moment à deux ou une séance en famille — avec un résultat
          livré prêt à partager. La séance a lieu chez vous ou sur le lieu de votre choix : je me déplace avec mon matériel.
        </p>
      </section>

      <?php $carouselCategory = 'Portraits'; $carouselLabel = 'Portraits'; include __DIR__ . '/inc/photo-carousel.php'; ?>

      <section class="priceGrid">
        <article class="priceCard reveal">
          <h3>Individuel</h3>
          <div class="priceDuration">Séance de 45 min</div>
          <div class="priceValue">dès <small>CHF</small> 195</div>
          <ul>
            <li>10 photos retouchées</li>
            <li>Sélection accompagnée</li>
            <li>Livraison privée sécurisée</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Individuel (45 min, 10 photos) — dès 195 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>

        <article class="priceCard reveal">
          <h3>Duo / Grossesse</h3>
          <div class="priceDuration">Séance de 1 h</div>
          <div class="priceValue">dès <small>CHF</small> 245</div>
          <ul>
            <li>15 photos retouchées</li>
            <li>Sélection accompagnée</li>
            <li>Livraison privée sécurisée</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Duo / Grossesse (1 h, 15 photos) — dès 245 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>

        <article class="priceCard reveal">
          <h3>Famille</h3>
          <div class="priceDuration">Séance de 1 h 30</div>
          <div class="priceValue">dès <small>CHF</small> 325</div>
          <ul>
            <li>20 photos retouchées</li>
            <li>Sélection accompagnée</li>
            <li>Livraison privée sécurisée</li>
          </ul>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Famille (1 h 30, 20 photos) — dès 325 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>
      </section>

      <p class="cardNote reveal">
        Chaque séance comprend la sélection, la retouche professionnelle et la livraison privée sécurisée via SwissTransfer.
      </p>

      <div class="highlightBlock reveal">
        <h3>Grand groupe</h3>
        <p>
          Pour une école, un groupe d’amis ou une famille élargie, un devis personnalisé permet d’organiser la séance
          selon vos contraintes d’horaire et de lieu.
        </p>
        <a class="btn" href="index.html?prefill=<?= urlencode('Demande de devis groupe (école, amis, famille élargie).') ?>#contact">Demander un devis groupe</a>
      </div>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
</body>
</html>
