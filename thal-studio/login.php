<?php
require __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login_user($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Identifiant ou mot de passe incorrect.';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Connexion — THAL Studio</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <main class="login-page">
    <section class="login-card">
      <img src="assets/logo.png" alt="THAL Photographie" class="login-logo">
      <h1>THAL Studio</h1>
      <p class="subtitle">Connexion sécurisée</p>

      <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
      <?php endif; ?>

      <form method="post">
        <label>Identifiant<input type="text" name="username" required autofocus></label>
        <label>Mot de passe<input type="password" name="password" required></label>
        <button type="submit">Connexion</button>
      </form>
    </section>
  </main>
</body>
</html>
