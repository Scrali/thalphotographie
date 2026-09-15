<?php
/**
 * Styles communs à toutes les pages publiques.
 * La feuille de style vit dans assets/site.css : elle est partagée avec
 * index.html et galerie.html, qui la chargent directement par <link>.
 * Ne pas recopier de CSS ici, sinon les deux versions divergeront.
 */
$thalCssVersion = @filemtime(__DIR__ . '/../assets/site.css') ?: time();
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/site.css?v=<?= (int)$thalCssVersion ?>">
