<?php
header('Content-Type: application/json; charset=utf-8');
$file = __DIR__ . '/thal-studio/data/settings/packs.json';
$data = ['home_address'=>'Sainte-Croix, VD, Suisse', 'km_rate'=>0.60];
if (is_file($file)) {
  $json=json_decode((string)file_get_contents($file), true);
  if(is_array($json)) $data=array_merge($data,$json);
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);
