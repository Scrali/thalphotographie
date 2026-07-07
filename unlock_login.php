<?php
$file = __DIR__ . '/thal-studio/data/security/login_attempts.json';

if (file_exists($file)) {
    unlink($file);
    echo "<h2>✅ Blocage supprimé.</h2>";
} else {
    echo "<h2>ℹ️ Aucun fichier de blocage trouvé.</h2>";
}

echo "<p>Tu peux maintenant te reconnecter à THAL Studio.</p>";
