# THAL Studio V1.1 — sécurité + presets serveur

## Ajouts sécurité

- Cookies de session renforcés :
  - HttpOnly
  - SameSite=Lax
  - Secure si HTTPS
- Déconnexion automatique après 2 heures d’inactivité.
- Blocage de 15 minutes après 5 tentatives de connexion échouées.
- Pages marquées `noindex,nofollow`.
- Protection `.htaccess` :
  - pas de listing de dossier,
  - accès bloqué à `data/`,
  - accès bloqué à `config.php`.

## Presets de mise en page

Les 3 slots sont maintenant sauvegardés côté serveur dans :

```txt
data/layouts/preset-1.json
data/layouts/preset-2.json
data/layouts/preset-3.json
```

Le navigateur garde aussi une sauvegarde locale en fallback.

## Installation

1. Remplace ton dossier `thal-studio` par cette version.
2. Commit / push GitHub.
3. OVH → Déployer Git.
4. Connecte-toi à THAL Studio.
5. Va dans le générateur de devis et teste les 3 slots.

## Note importante

Le dossier `data/` doit être inscriptible par PHP.
Sur OVH, c’est généralement OK par défaut.
