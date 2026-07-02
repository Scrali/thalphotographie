# THAL Studio — version OVH avec connexion PHP

## Fichiers principaux

- `index.php` : redirige vers login ou application.
- `login.php` : page de connexion.
- `app.php` : THAL Studio protégé.
- `logout.php` : déconnexion.
- `config.php` : identifiant + hash du mot de passe.
- `generate_hash.php` : outil pour générer un hash de mot de passe.

## Identifiants temporaires

Utilisateur :

```txt
jonathan
```

Mot de passe temporaire :

```txt
ChangeMoi!2026
```

À changer avant mise en ligne.

## Installation OVH

1. Dézippe ce dossier.
2. Envoie le dossier complet dans ton hébergement OVH, par exemple :

```txt
/www/thal-studio/
```

3. Ouvre :

```txt
https://thalphotographie.ch/thal-studio/
```

4. Connecte-toi avec les identifiants temporaires.
5. Change immédiatement le mot de passe.

## Changer le mot de passe

1. Ouvre :

```txt
https://thalphotographie.ch/thal-studio/generate_hash.php
```

2. Entre ton nouveau mot de passe.
3. Copie le hash généré.
4. Ouvre `config.php`.
5. Remplace la valeur de `THAL_PASSWORD_HASH`.
6. Supprime `generate_hash.php` du serveur.

## Important

Les identifiants de cette page ne sont pas ceux d’OVH.
Ils servent uniquement à protéger THAL Studio.

## Sauvegarde

Les 3 slots restent sauvegardés dans le navigateur via `localStorage`.
Pour transférer entre appareils, utilise l’export/import JSON.


## Correctif HTTP 500 OVH

Si `login.php` affiche une erreur 500, la cause la plus fréquente est une directive `.htaccess` refusée par OVH.

Cette version remplace le fichier `.htaccess` par :

```apache
DirectoryIndex index.php
```

Un fichier `test_php.php` est aussi fourni pour vérifier que PHP fonctionne.
Après test, supprime `test_php.php` du serveur.
