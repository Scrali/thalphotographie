<?php
$activeNav = 'identite';
$footerDisclaimer = 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Photos d’identité conformes — THAL Photographie</title>
  <meta name="description" content="Photo d’identité conforme aux normes fedpol (passeport, carte d’identité, visa, permis), à Sainte-Croix (VD). Résultat fiable, accepté du premier coup." />
  <meta name="theme-color" content="#070b16" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
</head>
<body>
  <div class="wrap">
    <?php include __DIR__ . '/inc/site-nav.php'; ?>

    <main>
      <section class="card pageHero reveal">
        <span class="pageKicker">Photos d’identité</span>
        <h1>Une photo d’identité conforme,<br>acceptée du premier coup.</h1>
        <p class="lead">
          Format et fond conformes aux exigences fedpol pour passeport, carte d’identité, visa et permis de séjour.
          Prise de vue soignée, contrôle qualité avant impression : zéro mauvaise surprise au guichet.
        </p>
      </section>

      <?php $carouselCategory = 'Portraits'; $carouselLabel = 'Photos d’identité'; include __DIR__ . '/inc/photo-carousel.php'; ?>

      <section class="card identityBox reveal">
        <div class="identityPrices">
          <div class="identityPriceItem">
            <div class="val">35 CHF</div>
            <div class="lbl">1 personne</div>
          </div>
          <div class="identityPriceItem">
            <div class="val">25 CHF</div>
            <div class="lbl">dès la 2ᵉ personne<br>(même séance)</div>
          </div>
        </div>

        <p class="cardNote" style="margin:0">
          Idéal pour les familles : chaque personne supplémentaire photographiée durant la même séance bénéficie du tarif réduit.
        </p>

        <ul class="identityIncluded">
          <li>Prise de vue conforme aux normes en vigueur (fedpol / passeport / visa / permis)</li>
          <li>Tirage papier au format officiel</li>
          <li>Version numérique haute définition remise après la séance</li>
        </ul>

        <div class="actions">
          <a class="btn" href="index.html?prefill=<?= urlencode('Bonjour, je souhaite prendre rendez-vous pour une photo d’identité.') ?>#contact">Prendre rendez-vous</a>
        </div>
      </section>

      <section class="card reveal" style="padding:22px">
        <h3 style="margin:0 0 8px; font-size:19px; color:#d3edf5">Entreprise ou groupe ?</h3>
        <p style="margin:0 0 14px; color:var(--muted); line-height:1.6">
          Pour une équipe, une école ou un groupe nombreux, un devis personnalisé permet d’organiser les séances selon vos contraintes d’horaire et de lieu.
        </p>
        <a class="ghost" href="index.html?prefill=<?= urlencode('Demande de devis groupe / entreprise pour des photos d’identité.') ?>#contact">Demander un devis groupe</a>
      </section>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
</body>
</html>
