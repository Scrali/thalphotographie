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
  <title>Site en maintenance — THAL Photographie</title>
  <style>
    :root{ color-scheme: dark; }
    *{ box-sizing:border-box; }
    body{
      margin:0;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px;
      background:radial-gradient(circle at 50% 0%, #10192f 0%, #05070f 65%);
      color:#e7edf3;
      font-family:"Segoe UI", system-ui, -apple-system, Arial, sans-serif;
      text-align:center;
    }
    .card{
      max-width:480px;
      padding:clamp(28px,5vw,44px);
      border:1px solid rgba(255,255,255,.12);
      border-radius:26px;
      background:linear-gradient(180deg, rgba(12,18,34,.78), rgba(5,8,22,.7));
      box-shadow:0 24px 80px rgba(0,0,0,.5);
    }
    img{ width:64px; height:64px; margin-bottom:18px; filter:drop-shadow(0 0 15px rgba(124,184,255,.25)); }
    h1{ margin:0 0 12px; font-size:clamp(22px,4vw,28px); letter-spacing:-.3px; }
    p{ margin:0 0 8px; color:#a9b4c2; line-height:1.6; font-size:15px; }
    .contact{ margin-top:20px; font-size:13px; color:#7c8798; }
    .contact a{ color:#9fd0ff; text-decoration:none; }
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
