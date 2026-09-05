<?php
$activeNav = 'professionnels';
$footerDisclaimer = 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Photographie pour professionnels — THAL Photographie</title>
  <meta name="description" content="Portraits d’équipe, images de locaux et de communication pour artisans, PME et groupes multi-sites. Licence commerciale Web & réseaux sociaux incluse." />
  <meta name="theme-color" content="#070b16" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
</head>
<body>
  <div class="wrap">
    <?php include __DIR__ . '/inc/site-nav.php'; ?>

    <main>
      <section class="card pageHero reveal">
        <span class="pageKicker">Professionnels</span>
        <h1>Des images qui représentent votre entreprise.</h1>
        <p class="lead">
          Portraits d’équipe, images de vos locaux, contenu pour votre communication : un résultat prêt à l’emploi,
          avec les droits d’utilisation qui vont avec. La séance se déroule directement dans vos locaux.
        </p>
      </section>

      <?php $carouselCategory = 'Commandes professionnelles'; $carouselLabel = 'Professionnels'; include __DIR__ . '/inc/photo-carousel.php'; ?>

      <section class="priceGrid">
        <article class="priceCard reveal">
          <h3>Artisan</h3>
          <div class="priceValue">dès <small>CHF</small> 490</div>
          <ul>
            <li>Portrait professionnel</li>
            <li>Images d’atelier</li>
            <li>Photos d’équipe</li>
          </ul>
          <span class="priceBadge">Licence commerciale Web &amp; réseaux sociaux (2 ans) incluse</span>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule Artisan (portrait, atelier, équipe) — dès 490 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>

        <article class="priceCard reveal">
          <h3>PME</h3>
          <div class="priceValue">dès <small>CHF</small> 890</div>
          <ul>
            <li>Portraits des collaborateurs</li>
            <li>Images des locaux</li>
            <li>Contenu pour votre communication</li>
          </ul>
          <span class="priceBadge">Licence commerciale Web &amp; réseaux sociaux (2 ans) incluse</span>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Formule PME (collaborateurs, locaux, communication) — dès 890 CHF.') ?>#contact">Demander un devis</a>
          </div>
        </article>

        <article class="priceCard reveal">
          <h3>Corporate / multi-sites</h3>
          <div class="priceValue">Sur devis</div>
          <ul>
            <li>Plusieurs sites ou équipes</li>
            <li>Coordination sur mesure</li>
            <li>Contenu de communication complet</li>
          </ul>
          <span class="priceBadge">Licence commerciale Web &amp; réseaux sociaux (2 ans) incluse</span>
          <div class="priceCta">
            <a class="btn" href="index.html?prefill=<?= urlencode('Corporate / multi-sites — devis personnalisé.') ?>#contact">Demander un devis</a>
          </div>
        </article>
      </section>

      <p class="cardNote reveal">
        Chaque formule inclut le tri, la retouche professionnelle et la livraison privée sécurisée via SwissTransfer.
      </p>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
</body>
</html>
