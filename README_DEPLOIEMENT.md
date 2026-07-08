# THAL Photographie - version propre

Cette version a été reconstruite à partir de l'archive originale en gardant uniquement les éléments cohérents :

- le site public : `index.html`, `galerie.html`, `assets/`, `photos/` ;
- les endpoints publics d'estimation : `estimation_config.php`, `estimation_distance.php`, `estimation_submit.php` ;
- l'espace admin : `thal-studio/` ;
- le script de galerie : `scripts/build_gallery_auto.py` ;
- le workflow GitHub de génération de galerie.

## Avant mise en ligne

1. Envoyer le contenu de ce dossier à la racine du site OVH.
2. Ouvrir `/thal-studio/login.php`.
3. Vérifier le mot de passe admin dans `thal-studio/config.php`.
4. Dans THAL Studio > Packs & tarifs, renseigner la clé OpenRouteService si l'estimation de distance doit fonctionner.
5. Tester :
   - `/index.html`
   - `/galerie.html`
   - `/thal-studio/login.php`
   - `/thal-studio/devis.php`
   - `/thal-studio/estimations.php`

## Nettoyage effectué

- Suppression des fichiers racine mélangés ou mal renommés.
- Conservation uniquement des vraies images dans `photos/`.
- Régénération de `photos/gallery.auto.json`.
- Protection du dossier `thal-studio/data/` par `.htaccess`.
- Ajout de jetons de sécurité sur les actions sensibles de l'admin.
- Correction des textes avec accents cassés.
