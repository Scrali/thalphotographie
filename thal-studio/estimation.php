<?php
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$defaults = [
    'type' => 'Événement',
    'hours' => 3,
    'travel_km' => 30,
    'travel_hours' => 0.5,
    'editing_hours' => 2,
    'hourly_rate' => 90,
    'km_rate' => 0.80,
    'gear_cost' => 20,
    'commercial' => 'non',
    'name' => '',
    'email' => '',
    'phone' => '',
];

$data = array_merge($defaults, $_POST);
$result = null;
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hours = max(0, (float)$data['hours']);
    $travelKm = max(0, (float)$data['travel_km']);
    $travelHours = max(0, (float)$data['travel_hours']);
    $editingHours = max(0, (float)$data['editing_hours']);
    $hourlyRate = max(0, (float)$data['hourly_rate']);
    $kmRate = max(0, (float)$data['km_rate']);
    $gearCost = max(0, (float)$data['gear_cost']);
    $commercialFee = ($data['commercial'] ?? 'non') === 'oui' ? 150 : 0;

    $totalHours = $hours + $travelHours + $editingHours;
    $timeCost = $totalHours * $hourlyRate;
    $kmCost = $travelKm * $kmRate;
    $total = round(($timeCost + $kmCost + $gearCost + $commercialFee) / 10) * 10;

    $result = compact('totalHours', 'timeCost', 'kmCost', 'gearCost', 'commercialFee', 'total');

    $dir = __DIR__ . '/data/estimations';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $entry = [
        'createdAt' => date('c'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        'type' => $data['type'],
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'hours' => $hours,
        'editing_hours' => $editingHours,
        'travel_hours' => $travelHours,
        'travel_km' => $travelKm,
        'commercial' => $data['commercial'],
        'total' => $total,
    ];

    $id = date('Ymd_His') . '_' . substr(md5(json_encode($entry)), 0, 8);
    $saved = file_put_contents($dir . '/' . $id . '.json', json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Estimation — THAL Photographie</title>
  <link rel="stylesheet" href="css/estimation.css">
</head>
<body>
<main class="estimator">
<section class="card">
<img src="assets/logo.png" alt="THAL Photographie" class="logo">
<h1>Estimation de devis</h1>
<p class="intro">Estimation indicative. Le devis final est confirmé après échange.</p>

<form method="post">
<label>Type de prestation
<select name="type">
<?php foreach (['Événement','Mariage','Portrait','Entreprise','Concert','Famille'] as $type): ?>
<option value="<?= e($type) ?>" <?= ($data['type'] ?? '') === $type ? 'selected' : '' ?>><?= e($type) ?></option>
<?php endforeach; ?>
</select>
</label>

<div class="grid">
<label>Temps de prise de vue<input type="number" step="0.5" min="0" name="hours" value="<?= e($data['hours']) ?>"></label>
<label>Temps retouche / tri<input type="number" step="0.5" min="0" name="editing_hours" value="<?= e($data['editing_hours']) ?>"></label>
<label>Temps déplacement<input type="number" step="0.25" min="0" name="travel_hours" value="<?= e($data['travel_hours']) ?>"></label>
<label>Distance A/R en km<input type="number" step="1" min="0" name="travel_km" value="<?= e($data['travel_km']) ?>"></label>
</div>

<details>
<summary>Paramètres de calcul</summary>
<div class="grid">
<label>Taux horaire CHF/h<input type="number" step="5" min="0" name="hourly_rate" value="<?= e($data['hourly_rate']) ?>"></label>
<label>Tarif kilométrique CHF/km<input type="number" step="0.05" min="0" name="km_rate" value="<?= e($data['km_rate']) ?>"></label>
<label>Frais matériel / divers<input type="number" step="5" min="0" name="gear_cost" value="<?= e($data['gear_cost']) ?>"></label>
<label>Usage commercial
<select name="commercial">
<option value="non" <?= ($data['commercial'] ?? '') === 'non' ? 'selected' : '' ?>>Non</option>
<option value="oui" <?= ($data['commercial'] ?? '') === 'oui' ? 'selected' : '' ?>>Oui</option>
</select>
</label>
</div>
</details>

<div class="grid">
<label>Nom / entreprise<input name="name" value="<?= e($data['name']) ?>" placeholder="Facultatif"></label>
<label>Email<input type="email" name="email" value="<?= e($data['email']) ?>" placeholder="Facultatif"></label>
</div>
<label>Téléphone<input name="phone" value="<?= e($data['phone']) ?>" placeholder="Facultatif"></label>

<button type="submit">Calculer l’estimation</button>
</form>

<?php if ($result): ?>
<section class="result">
<h2>Estimation indicative</h2>
<div class="price"><?= number_format($result['total'], 0, '.', "'") ?> CHF</div>
<table>
<tr><td>Temps total estimé</td><td><?= number_format($result['totalHours'], 1, ',', '') ?> h</td></tr>
<tr><td>Temps de travail</td><td><?= number_format($result['timeCost'], 0, '.', "'") ?> CHF</td></tr>
<tr><td>Déplacement</td><td><?= number_format($result['kmCost'], 0, '.', "'") ?> CHF</td></tr>
<tr><td>Matériel / frais</td><td><?= number_format($result['gearCost'], 0, '.', "'") ?> CHF</td></tr>
<?php if ($result['commercialFee'] > 0): ?>
<tr><td>Usage commercial</td><td><?= number_format($result['commercialFee'], 0, '.', "'") ?> CHF</td></tr>
<?php endif; ?>
</table>
<p class="note"><?= $saved ? 'Votre estimation a été enregistrée.' : 'Cette estimation ne constitue pas une offre contractuelle.' ?></p>
</section>
<?php endif; ?>
</section>
</main>
</body>
</html>
