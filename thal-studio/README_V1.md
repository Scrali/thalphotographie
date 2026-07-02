# THAL Studio V1.0 — base PHP

## Identifiants temporaires

- Utilisateur : `jonathan`
- Mot de passe : `ChangeMoi!2026`

## Installation

1. Remplace le dossier `thal-studio` de ton dépôt par ce dossier.
2. Commit / push sur GitHub.
3. OVH : Déployer Git.
4. Ouvre : `/thal-studio/`

## Nouveautés

- Tableau de bord.
- Connexion / déconnexion.
- Générateur de devis conservé dans `devis.php`.
- Page paramètres.
- Changement de mot de passe depuis l'interface.
- Structure prête pour clients et devis sauvegardés côté serveur.

## Important sur le mot de passe

La page `change_password.php` modifie `config.php` sur le serveur OVH.
Si tu redéploies depuis GitHub, le fichier `config.php` du dépôt peut écraser celui du serveur.

Pour un changement permanent :
1. Change le mot de passe dans l'interface.
2. Copie le nouveau hash de `config.php` si nécessaire.
3. Mets à jour le `config.php` dans GitHub.
