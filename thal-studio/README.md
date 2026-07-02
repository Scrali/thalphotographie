# THAL Studio HTML V0.3.2 — Devis essentiel

## Changements
- Suppression du bloc “Non inclus” séparé.
- Suppression de lignes superflues.
- Ajout des éléments indispensables :
  - validité du devis,
  - conditions de paiement,
  - acceptation/signature,
  - contact complet.
- Correction de la couleur du carré Total devis :
  - fond,
  - texte,
  - accent.

## Utilisation
Ouvre `index.html`, règle le devis, puis exporte en PDF.


## V0.3.3 — Taux horaire
- Ajout du champ **Taux horaire CHF/h**.
- Ajout du champ **Frais matériel CHF**.
- Calcul automatique : valeur temps, déplacement, frais matériel, coût calculé indicatif, taux horaire effectif.
- Option pour afficher/masquer le détail du taux horaire dans le devis.


## V0.3.4 — Impression des couleurs
- Ajout de `print-color-adjust: exact`.
- Correction du carré Total devis pour conserver sa couleur à l’impression/PDF.

Important : dans Chrome/Edge, il faut aussi cocher **Graphiques d’arrière-plan** dans la fenêtre d’impression.


## V0.4 — Calculateur de prix automatique
- Mode **Prix personnalisé** : le total reste manuel.
- Mode **Prix calculé automatiquement** : le total est calculé depuis :
  - heures totales × taux horaire,
  - déplacement km × CHF/km,
  - frais matériel,
  - remise CHF,
  - arrondi.
- Le devis affiche :
  - prix conseillé calculé,
  - remise / geste commercial,
  - taux horaire effectif,
  - couverture du coût calculé.


## V0.4.1 — Logo et droits commerciaux
- Ajout d’un curseur **Assombrir logo**.
- Ajout d’une case pour afficher/masquer les **droits d’utilisation commerciale**.
- Ajout d’un montant pour les droits commerciaux.
- Le montant des droits commerciaux est intégré au prix calculé automatiquement.


## V0.4.2 — Sans bloc Contact
- Suppression du bloc Contact dans le corps du devis.
- Les coordonnées restent uniquement dans le pied de page.
- Le bloc Acceptation du devis prend toute la largeur.


## V0.4.3 — Nettoyage récapitulatif
- Suppression de la répétition des droits commerciaux dans le récapitulatif.
- Le bloc Déplacement affiche maintenant aussi le temps de déplacement compté.
- Le carré Total devis est réduit pour devenir un vrai petit bloc visuel.


## V0.4.4 — Zoom, fond blanc, total centré
- Zoom aperçu de 40% à 250%.
- Boutons de zoom : -, 100%, +.
- Zoom avec Ctrl + molette dans l’aperçu.
- Fond document par défaut en blanc pur (#FFFFFF).
- Le carré Total devis est centré verticalement dans sa colonne.


## V0.4.6 — En-tête n°2 + sélections date/heure
- En-tête variante 2 : logo à gauche, marque + DEVIS + prestation à droite.
- Les champs de date utilisent le sélecteur calendrier natif du navigateur.
- Les champs d’heure utilisent le sélecteur horaire natif.
- Ajout de boutons rapides : Matin, Après-midi, Concert 16–21, Soirée.


## V0.4.7.1 — Correctif document vide
- Correction du bug qui pouvait vider l’aperçu.
- Suppression du texte “T.H.A.L Photographie” au-dessus de DEVIS.
- Ajout d’un curseur “Décalage horizontal titre”.


## V0.4.9.1 — Correctif logo
- Retour au logo PNG fiable : il reste visible.
- Ajout du décalage horizontal du logo.
- Assombrissement du logo.
- Teinte couleur optionnelle par surcouche.


## V0.5.0 — Logo couleur + rapprochement contenu
- Correction de la couleur du logo : la teinte utilise maintenant le masque du logo PNG.
- Le curseur **Intensité teinte** fait réellement passer le logo de l’original vers la couleur choisie.
- Le curseur **Espace logo → premières tuiles** peut maintenant aller en négatif pour rapprocher les tuiles du haut de page.


## V0.5.1 — Nettoyage logo
- Suppression du curseur **Espace logo → DEVIS**.
- Suppression du doublon **Assombrir logo**.
- Ajout d’un mode logo :
  - Original,
  - Couleur choisie.
- La couleur du logo fonctionne via SVG inline recolorable.


## V0.5.2 — Slots de mise en page
- Suppression de la ligne sous l’en-tête.
- Ajout de 3 slots de sauvegarde de mise en page.
- Les slots sauvegardent uniquement l’apparence : logo, couleurs, tailles, marges, zoom, espacements.
- Sauvegarde locale dans le navigateur via `localStorage`.


## V0.5.3 — Rabais en pourcentage
- Le rabais n’est plus saisi en CHF.
- Nouveau champ **Rabais %**.
- En mode prix automatique, le prix est calculé ainsi :
  - coût calculé,
  - moins rabais en %,
  - puis arrondi selon le réglage choisi.
- Le récapitulatif affiche le pourcentage de rabais.


## V0.5.4 — Correctif affichage du texte
- Correction du bug `discountAmount` / `discountPercent`.
- Le devis s'affiche à nouveau dans l'aperçu.
- Les champs manquants ne bloquent plus le JavaScript.
- Le rabais en % est pris en compte.
- Suppression d'un doublon potentiel des droits commerciaux dans le récapitulatif.
