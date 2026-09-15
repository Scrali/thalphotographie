<?php
http_response_code(503);
header('Retry-After: 3600');
header('X-Robots-Tag: noindex, nofollow');

$message = '';
$file = __DIR__ . '/thal-studio/data/settings/maintenance.json';
if (is_file($file)) {
    $data = json_decode((string)file_get_contents($file), true);
    if (is_array($data)) $message = trim((string)($data['message'] ?? ''));
}
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Site en maintenance • THAL Photographie</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{ color-scheme:dark; --ink:#0a0a0c; --ink-2:#121215; --line:rgba(255,255,255,.10);
           --text:#f4f2ef; --muted:rgba(244,242,239,.66); --muted-2:rgba(244,242,239,.42); --amber:#d9a668; }
    *{ box-sizing:border-box; }
    body{
      margin:0; min-height:100svh; display:flex; align-items:center; justify-content:center;
      padding:24px; background:var(--ink); color:var(--text); text-align:center;
      font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif; line-height:1.6;
    }
    .card{
      max-width:480px; padding:clamp(30px,5vw,48px);
      border:1px solid var(--line); border-radius:3px; background:var(--ink-2);
    }
    img{ width:76px; height:auto; margin:0 auto 22px; display:block; }
    h1{ margin:0 0 14px; font-family:"Fraunces", Georgia, serif; font-weight:300;
        font-size:clamp(24px,4vw,34px); letter-spacing:-.02em; }
    p{ margin:0; color:var(--muted); font-size:15px; }
    .contact{ margin-top:26px; padding-top:22px; border-top:1px solid var(--line);
              font-size:13px; color:var(--muted-2); }
    .contact a{ color:var(--amber); text-decoration:none; }
    .contact a:hover{ text-decoration:underline; text-underline-offset:3px; }
  </style>
</head>
<body>
  <div class="card">
    <img src="assets/thal1.png" alt="THAL Photographie" onerror="this.style.display='none'">
    <h1>Site en maintenance</h1>
    <p><?= $message !== '' ? e($message) : 'Le site est en cours de mise à jour. Merci de repasser dans un instant.' ?></p>
    <div class="contact">Une urgence ? <a href="mailto:contact@thalphotographie.ch">contact@thalphotographie.ch</a></div>
  </div>
</body>
</html>
