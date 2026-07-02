<?php
require __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$lockFile = __DIR__ . '/data/security/login_attempts.json';

function get_client_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function read_attempts(string $file): array {
    if (!is_file($file)) {
        return [];
    }
    $data = json_decode((string)file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function write_attempts(string $file, array $data): void {
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = get_client_ip();
    $attempts = read_attempts($lockFile);
    $now = time();

    // Nettoyage des entrées anciennes
    foreach ($attempts as $key => $entry) {
        if (($entry['last'] ?? 0) < $now - 3600) {
            unset($attempts[$key]);
        }
    }

    $entry = $attempts[$ip] ?? ['count' => 0, 'last' => 0, 'locked_until' => 0];

    if (($entry['locked_until'] ?? 0) > $now) {
        $wait = (int)ceil((($entry['locked_until'] ?? 0) - $now) / 60);
        $error = 'Trop de tentatives. Réessaie dans environ ' . $wait . ' minute(s).';
    } elseif (login_user($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        unset($attempts[$ip]);
        write_attempts($lockFile, $attempts);
        header('Location: dashboard.php');
        exit;
    } else {
        $entry['count'] = (int)($entry['count'] ?? 0) + 1;
        $entry['last'] = $now;

        // Après 5 erreurs, blocage de 15 minutes
        if ($entry['count'] >= 5) {
            $entry['locked_until'] = $now + 900;
        }

        $attempts[$ip] = $entry;
        write_attempts($lockFile, $attempts);
        $error = 'Identifiant ou mot de passe incorrect.';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Connexion — THAL Studio</title>
  <meta name="robots" content="noindex,nofollow">
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
