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

      <?php $carouselKey = 'accueil'; $carouselCategory = 'Accueil'; $carouselLabel = 'THAL Photographie'; include __DIR__ . '/inc/photo-carousel.php'; ?>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Mon approche</h2>
        <p style="margin:0; color:var(--muted); line-height:1.7">
          Pour moi, une bonne photo tient avant tout à deux choses : la lumière et la relation avec la personne
          photographiée. Je prends le temps de jouer avec la lumière disponible — ou d’apporter la mienne selon le
          lieu — et je cherche surtout un vrai échange avec les personnes que je photographie. C’est souvent ce qui
          fait la différence entre une photo posée et une photo qui vous ressemble vraiment. Mon objectif reste
          simple : votre pleine satisfaction du résultat.
        </p>
      </section>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Matériel professionnel</h2>
        <p style="margin:0; color:var(--muted); line-height:1.7">
          Je travaille avec un Nikon Z8 et plusieurs objectifs adaptés à chaque type de prise de vue — portrait,
          reportage, photo d’identité. Comme je me déplace systématiquement chez vous ou sur le lieu de votre choix,
          j’utilise une solution entièrement autonome (éclairage, et impression sur place pour les photos
          d’identité) qui me permet de travailler dans les mêmes conditions de qualité, où que nous soyons.
        </p>
      </section>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Retouche et qualité de livraison</h2>
        <p style="margin:0; color:var(--muted); line-height:1.7">
          La retouche que je propose reste sobre et naturelle : l’objectif est de sublimer l’image, pas de la
          dénaturer. Le délai de livraison dépend du volume de travail et du type de prestation — je vous
          communique un délai précis dès la prise de contact.
        </p>
      </section>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Livraison privée et sécurisée</h2>
        <p style="margin:0; color:var(--muted); line-height:1.7">
          Vos photos vous sont transmises via SwissTransfer, avec un accès protégé par un mot de passe défini à
          l’avance : un moyen simple, sécurisé et hébergé en Suisse pour recevoir vos images en haute définition,
          sans création de compte.
        </p>
      </section>

      <section class="card reveal" style="padding:clamp(22px,4vw,32px); margin-top:var(--gap)">
        <h2 style="margin:0 0 10px; font-size:22px; color:#d3edf5">Photographe local (Sainte-Croix / Nord vaudois)</h2>
        <p style="margin:0; color:var(--muted); line-height:1.7">
          J’habite la région et je m’y déplace au quotidien pour l’ensemble de mes prestations — un ancrage local
          qui me permet de bien connaître le Nord vaudois et de rester facilement disponible pour mes clients.
        </p>
      </section>

      <div class="actions reveal" style="justify-content:center; margin-top:var(--gap)">
        <a class="btn" href="index.html#contact">Me contacter</a>
      </div>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
</body>
</html>
