<?php
$activeNav = '';
$footerDisclaimer = '';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Conditions générales de vente — THAL Photographie</title>
  <meta name="description" content="Conditions générales de vente et de prestation de THAL Photographie (Sainte-Croix, VD)." />
  <meta name="theme-color" content="#070b16" />
  <?php include __DIR__ . '/inc/site-styles.php'; ?>
  <style>
    .cgvDoc{padding:clamp(24px,4.5vw,44px); margin:var(--gap) 0;}
    .cgvDoc h1{margin:0 0 6px; font-size:clamp(28px,4vw,38px); letter-spacing:-.5px;}
    .cgvMeta{margin:0 0 28px; color:var(--muted2); font-size:14px;}
    .cgvDoc h2{margin:30px 0 10px; font-size:19px; color:#d3edf5; letter-spacing:-.2px;}
    .cgvDoc h2:first-of-type{margin-top:0;}
    .cgvDoc p{margin:0 0 12px; color:var(--muted); line-height:1.7; font-size:15px;}
    .cgvDoc strong{color:var(--text);}
    .cgvTableWrap{overflow-x:auto; margin:0 0 16px;}
    .cgvTable{width:100%; border-collapse:collapse; font-size:14px;}
    .cgvTable th,.cgvTable td{padding:10px 14px; border:1px solid rgba(255,255,255,.12); text-align:left; color:var(--muted);}
    .cgvTable th{color:#d3edf5; background:rgba(255,255,255,.04); font-weight:800;}
    .cgvDoc hr{border:0; border-top:1px solid rgba(255,255,255,.10); margin:30px 0;}
    .cgvFoot{color:var(--muted2); font-size:13px; font-style:italic;}
    .cgvHead{display:flex; align-items:flex-start; justify-content:space-between; gap:16px;}
    .cgvClose{flex:0 0 auto; width:42px; height:42px; border-radius:999px; border:1px solid rgba(255,255,255,.14); background:rgba(255,255,255,.055); color:var(--text); font-size:22px; cursor:pointer; text-decoration:none; display:flex; align-items:center; justify-content:center; line-height:1;}
  </style>
</head>
<body>
  <div class="wrap">
    <?php include __DIR__ . '/inc/site-nav.php'; ?>

    <main>
      <section class="card cgvDoc">
        <div class="cgvHead">
          <div>
            <h1>Conditions générales de vente et de prestation</h1>
            <p class="cgvMeta"><strong>THAL Photographie</strong> — Sainte-Croix (VD), Suisse<br>Version 1.0 — en vigueur au 1<sup>er</sup> août 2026</p>
          </div>
          <a href="index.html" class="cgvClose" id="cgvClose" aria-label="Fermer">×</a>
        </div>

        <h2>1. Champ d’application</h2>
        <p>Les présentes conditions générales (ci-après « CG ») régissent l’ensemble des prestations photographiques fournies par THAL Photographie (ci-après « le Photographe ») à ses clients (ci-après « le Client »).</p>
        <p>Elles font partie intégrante de tout devis, confirmation de commande ou facture émis par le Photographe. Toute dérogation doit faire l’objet d’un accord écrit. En validant un devis, le Client reconnaît avoir pris connaissance des présentes CG et les accepter sans réserve.</p>

        <h2>2. Devis et conclusion du contrat</h2>
        <p>Chaque prestation fait l’objet d’un devis écrit précisant la nature de la prestation, la date, le lieu, la durée de présence, les livrables et le prix.</p>
        <p>Le devis est valable trente (30) jours à compter de sa date d’émission, sauf mention contraire.</p>
        <p>Le contrat est réputé conclu dès la validation du devis par le Client, par signature du bloc « Bon pour accord » ou par confirmation écrite (courriel). Les tarifs indiqués sur le site internet du Photographe sont des tarifs de base indicatifs ; seul le devis validé fait foi.</p>

        <h2>3. Prix, acompte et paiement</h2>
        <p>Les prix sont exprimés en francs suisses (CHF). <strong>TVA non applicable</strong> (art. 10 al. 2 let. a LTVA — chiffre d’affaires inférieur au seuil d’assujettissement).</p>
        <p>Les frais de déplacement sont offerts dans un rayon de 20 km depuis Sainte-Croix ; au-delà, ils sont facturés selon le tarif kilométrique indiqué au devis.</p>
        <p><strong>Acompte</strong> — Pour les prestations dont le montant dépasse <strong>800 CHF</strong> ou pour toute réservation de date de mariage, un acompte de <strong>30 %</strong> est demandé à la validation du devis. La date n’est définitivement réservée qu’à réception de cet acompte. L’acompte est déduit du montant final et fait l’objet d’un reçu.</p>
        <p><strong>Paiement</strong> — Sauf accord contraire, le solde est payable dans les 30 jours suivant la date de facture. En cas de retard, le Photographe se réserve le droit de facturer un intérêt moratoire de 5 % l’an (art. 104 CO) ainsi que les frais de rappel effectifs.</p>
        <p>Les fichiers et tirages ne sont livrés qu’après paiement intégral, sauf accord écrit contraire.</p>

        <h2>4. Annulation et report</h2>
        <p><strong>Annulation par le Client</strong> — Toute annulation doit être notifiée par écrit. Les conditions suivantes s’appliquent :</p>
        <div class="cgvTableWrap">
          <table class="cgvTable">
            <thead><tr><th>Délai avant la prestation</th><th>Conséquence</th></tr></thead>
            <tbody>
              <tr><td>Plus de 30 jours</td><td>Acompte remboursé, hors frais déjà engagés</td></tr>
              <tr><td>De 30 à 8 jours</td><td>Acompte conservé à titre de dédommagement</td></tr>
              <tr><td>Moins de 8 jours</td><td>50 % du montant total dû</td></tr>
              <tr><td>Le jour même ou non-présentation</td><td>100 % du montant total dû</td></tr>
            </tbody>
          </table>
        </div>
        <p><strong>Report</strong> — Un report de date est possible sans frais s’il est demandé plus de 30 jours à l’avance et si le Photographe dispose de la nouvelle date. Au-delà, le report est traité comme une annulation suivie d’une nouvelle réservation.</p>
        <p><strong>Intempéries</strong> — Pour les séances en extérieur, le Photographe et le Client conviennent ensemble d’un report sans frais en cas de conditions météorologiques rendant la prestation impossible. La décision est prise au plus tard la veille.</p>
        <p><strong>Empêchement du Photographe</strong> — En cas de force majeure, de maladie ou d’accident empêchant le Photographe d’assurer la prestation, celui-ci s’engage à en informer le Client dans les meilleurs délais et à proposer soit un report, soit, dans la mesure du possible, un confrère de remplacement. À défaut, les sommes versées sont intégralement remboursées, à l’exclusion de tout autre dédommagement.</p>

        <h2>5. Réalisation de la prestation</h2>
        <p>Le Photographe exerce sa prestation avec le soin et la diligence requis, dans le respect des règles de l’art.</p>
        <p>Le Client reconnaît que le Photographe dispose d’une <strong>liberté créative</strong> dans le choix des cadrages, des réglages, du style de traitement et de la sélection des images livrées. Le nombre de photographies indiqué au devis constitue un minimum garanti.</p>
        <p>Le Client s’engage à faciliter le bon déroulement de la prestation : accès aux lieux, respect des horaires convenus, information des personnes présentes. Tout dépassement horaire à la demande du Client est facturé au tarif horaire indiqué au devis.</p>

        <h2>6. Livraison et conservation</h2>
        <p>Les images sélectionnées et retouchées sont livrées via une <strong>galerie privée en ligne</strong> permettant le téléchargement en haute définition.</p>
        <p>Le délai de livraison est indiqué au devis. À défaut, il est de <strong>2 à 3 semaines</strong> à compter de la prestation.</p>
        <p><strong>Conservation</strong> — Le Photographe conserve les fichiers livrés pendant <strong>12 mois</strong> à compter de la livraison. Passé ce délai, aucune restitution ne peut être garantie. <strong>Il appartient au Client d’effectuer ses propres sauvegardes.</strong> Les fichiers non retenus lors de la sélection ne sont pas conservés et ne peuvent être réclamés.</p>
        <p><strong>Retouches</strong> — Les prestations comprennent une retouche standard (exposition, colorimétrie, cadrage, corrections mineures). Toute retouche complémentaire ou demande de modification substantielle fait l’objet d’un devis séparé.</p>

        <h2>7. Propriété intellectuelle et licence d’utilisation</h2>
        <p>Les photographies demeurent la propriété intellectuelle de THAL Photographie conformément à la <strong>Loi fédérale sur le droit d’auteur et les droits voisins (LDA)</strong>. Sauf mention contraire dans le devis, seule la licence d’utilisation décrite ci-après est concédée au Client. Les droits d’auteur ne sont ni cédés ni transférés.</p>
        <p><strong>Licences concédées selon la prestation :</strong></p>
        <p>— <strong>Usage privé</strong> (clients particuliers) : le Client peut utiliser librement les images dans un cadre strictement personnel et familial, y compris sur ses réseaux sociaux personnels, à l’exclusion de tout usage promotionnel ou commercial.</p>
        <p>— <strong>Licence commerciale Web &amp; Réseaux sociaux</strong> (clients professionnels, associations, artistes) : licence non exclusive d’utilisation des images sur le site internet, les réseaux sociaux et les supports de présentation du Client, avec mention du crédit © THAL Photographie. Valable 2 ans pour toute nouvelle publication ; les contenus publiés durant cette période peuvent être maintenus en ligne au-delà. Renouvelable.</p>
        <p>— <strong>Licence étendue</strong> (publicité payante, affichage, campagne marketing, packaging, revente, impression grand format, exclusivité) : fait l’objet d’un accord écrit et d’une facturation complémentaire.</p>
        <p>Toute utilisation non prévue au devis, toute cession à un tiers ainsi que toute modification substantielle des images (recadrage dénaturant, filtres, montage) sans accord écrit du Photographe sont interdites.</p>

        <h2>8. Droit à l’image</h2>
        <p>Lorsque le Client utilise les photographies à des fins promotionnelles ou commerciales, il lui appartient de s’assurer qu’il dispose des autorisations nécessaires des personnes reconnaissables figurant sur les images, lorsque ces autorisations sont requises par la loi (art. 28 ss du Code civil suisse).</p>
        <p>Le Client garantit disposer des autorisations relatives aux lieux, biens et œuvres photographiés à sa demande.</p>

        <h2>9. Utilisation des images par le Photographe</h2>
        <p>Le Photographe se réserve le droit d’utiliser une sélection des images réalisées à des fins de <strong>promotion de son activité</strong> (portfolio, site internet, réseaux sociaux, concours, expositions), sauf opposition écrite du Client formulée avant la prestation.</p>
        <p>Le Client peut à tout moment demander le retrait d’une image spécifique de ces supports ; le Photographe y donne suite dans un délai raisonnable.</p>

        <h2>10. Responsabilité</h2>
        <p>La responsabilité du Photographe est limitée au montant de la prestation facturée. Elle ne saurait être engagée pour des dommages indirects ou immatériels.</p>
        <p>En cas de défaillance technique majeure (panne de matériel, perte de données malgré les précautions d’usage) rendant la livraison partiellement ou totalement impossible, le Photographe rembourse la part correspondante de la prestation, à l’exclusion de tout autre dédommagement.</p>
        <p>Le Photographe ne peut être tenu responsable des conditions extérieures échappant à son contrôle (météo, retards du déroulement d’un événement, comportement de tiers, restrictions d’accès imposées par un lieu).</p>

        <h2>11. Protection des données</h2>
        <p>Les données personnelles du Client (nom, coordonnées) sont collectées uniquement aux fins d’exécution du contrat et de gestion administrative, conformément à la <strong>Loi fédérale sur la protection des données (LPD)</strong>. Elles ne sont ni vendues ni transmises à des tiers, sous réserve des prestataires techniques nécessaires (hébergement de la galerie en ligne).</p>
        <p>Le Client dispose d’un droit d’accès, de rectification et de suppression de ses données, exerçable à l’adresse de contact du Photographe.</p>

        <h2>12. Droit applicable et for</h2>
        <p>Les présentes CG sont soumises au <strong>droit suisse</strong>.</p>
        <p>Tout litige sera soumis en priorité à une tentative de règlement amiable. À défaut, le for est fixé à <strong>Yverdon-les-Bains (VD)</strong>, sous réserve des fors impératifs prévus par la loi.</p>

        <hr>
        <p class="cgvFoot">THAL Photographie — Hirschi Jonathan, Rue du Centre 3, 1450 Sainte-Croix · thalphotographie@bluewin.ch · 078 745 72 42</p>
      </section>
    </main>

    <?php include __DIR__ . '/inc/site-footer.php'; ?>
  </div>
  <script>
    document.getElementById('cgvClose').addEventListener('click', function (e) {
      if (window.history.length > 1) {
        e.preventDefault();
        window.history.back();
      }
    });
  </script>
</body>
</html>
