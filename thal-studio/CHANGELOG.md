# THAL Studio — Changelog

## V1.2.0 — Galerie

### Ajouté
- Page admin `Galerie` : upload par glisser-déposer, sans passer par Git.
- Tri automatique des photos déposées selon le nom de fichier (ex. `mariage_012.jpg` → catégorie « Mariage »), avec catégorie « À trier » en repli.
- Réorganisation des photos par glisser-déposer, y compris entre catégories.
- Création et suppression de catégories (dossiers) depuis l'interface.
- Suppression de photos individuelles depuis l'interface.

### Corrigé
- `gallery.auto.json` contenait un BOM UTF-8 qui faisait échouer `json_decode()` côté PHP (le site public n'était pas affecté, seul le nouvel outil d'administration en dépendait).
- `thal_sanitize_category()` utilisait `mb_substr()` sans vérifier que l'extension `mbstring` est active.

### À tester
- Glisser-déposer une photo dans la zone de tri automatique.
- Glisser-déposer une photo dans une catégorie précise.
- Déplacer une photo d'une catégorie à une autre par glisser-déposer.
- Supprimer une photo.
- Créer puis supprimer une catégorie vide.

## V0.4.0 — Prospection & devis

### Ajouté
- Page publique `estimation.php`.
- Enregistrement des estimations dans `data/estimations/`.
- Page admin `Estimations`.
- Statistiques simples : total, mois, potentiel estimé.
- Bouton Charger slot plus visible.
- Bouton Sauver slot plus discret.
- Bouton Effacer slot rouge.
- Confirmation avant d’écraser un slot.
- Page `À propos`.
- Dossiers `data` conservés via `.gitkeep`.

### À tester
- Connexion.
- Nouveau devis.
- Enregistrer le devis.
- Mes devis.
- Designer A4.
- Slots.
- Estimations.
