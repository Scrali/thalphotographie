<?php
header('Content-Type: application/json; charset=utf-8');
$defaults = [
  'home_address' => 'Sainte-Croix, VD, Suisse',
  'km_rate' => 0.80,
  'hourly_rate' => 120
];
$file = __DIR__ . '/thal-studio/data/settings/estimation.json';
if (is_file($file)) {
  $data = json_decode((string)file_get_contents($file), true);
  if (is_array($data)) {
    $defaults = array_merge($defaults, $data);
  }
}
echo json_encode($defaults, JSON_UNESCAPED_UNICODE);
