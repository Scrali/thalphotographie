<?php
// Outil local pour générer le hash du mot de passe.
// À supprimer du serveur après utilisation.

$hash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Générer hash — THAL Studio</title>
  <style>
    body{font-family:Arial,sans-serif;background:#111;color:#fff;padding:40px}
    input,button,textarea{font-size:16px;padding:10px;border-radius:8px;border:1px solid #444}
    input,textarea{background:#fff;color:#111;width:100%;max-width:760px}
    button{margin-top:10px;cursor:pointer}
    textarea{height:90px;margin-top:20px}
  </style>
</head>
<body>
  <h1>Générer un hash de mot de passe</h1>
  <p>Entre ton mot de passe, copie le hash généré, puis colle-le dans <code>config.php</code>.</p>
  <form method="post">
    <input type="password" name="password" placeholder="Nouveau mot de passe" required>
    <br>
    <button type="submit">Générer</button>
  </form>

  <?php if ($hash): ?>
    <textarea readonly><?= htmlspecialchars($hash, ENT_QUOTES, 'UTF-8') ?></textarea>
    <p>Après usage, supprime <code>generate_hash.php</code> du serveur.</p>
  <?php endif; ?>
</body>
</html>
