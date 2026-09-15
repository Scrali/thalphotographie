<?php
/**
 * Déroulé en quatre étapes, commun à l'accueil et aux pages de gamme.
 */
$thalSteps = [
  ['Prise de contact',     "Vous m’écrivez par le formulaire, par e-mail ou sur WhatsApp, en me disant ce que vous avez en tête : type de séance, lieu, date approximative."],
  ['Devis sous 48 h',      "Je reviens vers vous avec un devis personnalisé gratuit, déplacement compris, et on cale ensemble la date et le déroulé."],
  ['La séance chez vous',  "Je viens avec tout le matériel, éclairage compris. Vous n’avez rien à préparer, ni à vous déplacer."],
  ['Livraison sécurisée',  "Je trie, je retouche, puis je vous envoie vos images en haute définition via SwissTransfer, avec un accès protégé par mot de passe."],
];
?>
<section class="wrap tight">
  <div class="sectionHead reveal">
    <h2>Comment ça se passe.</h2>
    <p>De votre premier message à la réception de vos images, quatre étapes et aucune surprise.</p>
  </div>
  <div class="steps reveal">
    <?php foreach ($thalSteps as $i => [$title, $text]): ?>
      <article>
        <span class="n">Étape <?= $i + 1 ?></span>
        <h3><?= htmlspecialchars($title) ?></h3>
        <p><?= htmlspecialchars($text) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
</section>
