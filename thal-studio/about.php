<?php
$page_title = 'À propos — THAL Studio';
require __DIR__ . '/includes/header.php';
$version = is_file(__DIR__ . '/VERSION') ? trim(file_get_contents(__DIR__ . '/VERSION')) : '0.4.0';
?>
<h2>À propos</h2>
<div class="panel-box">
  <p><strong>THAL Studio</strong></p>
  <p>Version : <strong><?= e($version) ?></strong></p>
  <p>Build : 2026.07</p>
  <p>Édition : Prospection & devis</p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
