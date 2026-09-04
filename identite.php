<?php
$activeNav = 'identite';
$footerDisclaimer = 'Service à domicile, zone 15 km incluse (depuis Sainte-Croix). Au-delà : 0.75 CHF/km. Devis personnalisé gratuit sous 48h. TVA non applicable.';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Photo d’identité à domicile — THAL Photographie</title>
  <meta name="description" content="Photo d’identité conforme aux normes fedpol (passeport, carte d’identité, visa, permis), prise et imprimée à domicile dans le Nord vaudois. Résultat fiable, accepté du premier coup." />
  <meta name="theme-color" content="#070b16" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
</head>
<body>
  <div class="wrap">
    <?php include __DIR__ . '/inc/site-nav.php'; ?>

    <main>
      <section class="card pageHero reveal">
        <span class="pageKicker">Photos d’identité</span>
        <h1>Photo d’identité à domicile</h1>
        <p class="lead">
          Format et fond conformes aux exigences fedpol pour passeport, carte d’identité, visa et permis de séjour.
          Je me déplace à votre domicile avec le matériel de prise de vue et d’impression : photo prise et imprimée
          sur place, contrôle qualité inclus — zéro mauvaise surprise au guichet.
        </p>
      </section>

      <?php $carouselCategory = 'Portraits'; $carouselLabel = 'Photos d’identité'; include __DIR__ . '/inc/photo-carousel.php'; ?>

      <section class="card identityBox reveal">
        <div class="identityPrices">
          <div class="identityPriceItem">
            <div class="val">45 CHF</div>
            <div class="lbl">1 personne, 4 photos<br>zone 15 km incluse</div>
          </div>
          <div class="identityPriceItem">
            <div class="val">20 CHF</div>
            <div class="lbl">2ᵉ personne<br>(même passage)</div>
          </div>
          <div class="identityPriceItem">
            <div class="val">10 CHF</div>
            <div class="lbl">dès la 3ᵉ personne<br>(même passage)</div>
          </div>
        </div>

        <p class="cardNote" style="margin:0">
          Zone incluse : 15 km depuis Sainte-Croix. Au-delà : 0.75 CHF/km —
          <a href="https://www.google.com/maps/dir/?api=1&amp;origin=Sainte-Croix,+VD,+Suisse" target="_blank" rel="noopener" style="color:var(--accent); text-decoration:underline;">vérifiez votre distance sur Google Maps</a>,
          ou indiquez simplement votre localité dans le message de contact : je confirme le tarif exact avant de valider le rendez-vous.
        </p>

        <p class="cardNote" style="margin:0">
          Idéal pour les familles : chaque personne supplémentaire photographiée durant le même passage bénéficie du tarif réduit.
        </p>

        <ul class="identityIncluded">
          <li>Prise de vue conforme aux normes en vigueur (fedpol / passeport / visa / permis)</li>
          <li>Impression sur place, format officiel</li>
          <li>Version numérique haute définition remise après la séance</li>
        </ul>

        <div class="actions">
          <a class="btn" href="index.html?prefill=<?= urlencode('Bonjour, je souhaite prendre rendez-vous pour une photo d’identité à domicile.') ?>#contact">Prendre rendez-vous</a>
        </div>
      </section>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
</body>
</html>
