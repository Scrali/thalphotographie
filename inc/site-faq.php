<?php
/**
 * Questions fréquentes, communes à l'accueil et aux pages de gamme.
 * Les réponses reprennent les informations déjà publiées sur le site
 * (zone de 15 km, 0.75 CHF/km, Nikon Z8, SwissTransfer, réponse sous 24 h).
 */
$thalFaq = [
  ["Vous vous déplacez jusqu’où ?",
   "Je me déplace chez vous ou sur le lieu de votre choix, dans tout le Nord vaudois et plus largement en Suisse romande. Pour les photos d’identité, une zone de 15 km depuis Sainte-Croix est incluse dans le tarif, au-delà le déplacement est facturé 0.75 CHF/km. Pour les autres prestations, le déplacement est compris jusqu’à 20 km."],
  ["Faut-il un studio ou une pièce particulière ?",
   "Non. J’apporte mon propre éclairage, ce qui me permet de travailler dans les mêmes conditions de qualité chez vous, dans vos locaux ou en extérieur. Un salon, un atelier ou un coin de jardin suffisent."],
  ["Avec quel matériel travaillez-vous ?",
   "Un Nikon Z8 et plusieurs objectifs adaptés à chaque type de prise de vue : portrait, reportage, photo d’identité. Pour les photos d’identité, j’emporte aussi une imprimante : la photo est donc imprimée sur place au format officiel."],
  ["Comment recevrai-je mes photos ?",
   "Via SwissTransfer, avec un accès protégé par un mot de passe défini à l’avance. C’est simple, sécurisé, hébergé en Suisse, et vous n’avez aucun compte à créer. Les fichiers sont en haute définition."],
  ["Quel est le délai de livraison ?",
   "Il dépend du volume de travail et du type de prestation. Je vous communique un délai précis dès la prise de contact, pour que vous sachiez à quoi vous en tenir avant de réserver."],
  ["Les photos d’identité sont-elles acceptées au guichet ?",
   "Oui. Le format et le fond sont conformes aux exigences fedpol pour le passeport, la carte d’identité, le visa et le permis de séjour, et je fais le contrôle qualité sur place avant de partir."],
];
?>
<section class="wrap tight">
  <div class="sectionHead reveal">
    <h2>Questions fréquentes.</h2>
    <p>Si votre question n’est pas là, écrivez-moi : je réponds sous 24 h (jours ouvrés).</p>
  </div>
  <div class="faq reveal">
    <?php foreach ($thalFaq as [$q, $a]): ?>
      <details>
        <summary><?= htmlspecialchars($q) ?><svg class="plus" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg></summary>
        <div class="ans"><?= htmlspecialchars($a) ?></div>
      </details>
    <?php endforeach; ?>
  </div>
</section>
