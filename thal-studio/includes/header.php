<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
require_login();
$page_title = $page_title ?? 'THAL Studio';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($page_title) ?></title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-nav">
    <div class="admin-brand">
      <img src="assets/logo.png" alt="THAL Photographie">
      <div>
        <h1>THAL Studio</h1>
        <p>V0.4.2</p>
      </div>
    </div>
    <a href="dashboard.php">Tableau de bord</a>
    <a href="devis.php">Nouveau devis</a>
    <a href="quotes.php">Mes devis</a>
    <a href="clients.php">Clients</a>
    <a href="estimations.php">Estimations</a>
    <a href="settings.php">Paramètres</a>
    <a href="estimation_settings.php">Réglages estimation</a>
    <a href="about.php">À propos</a>
    <a href="logout.php">Déconnexion</a>
  </aside>
  <main class="admin-main">
