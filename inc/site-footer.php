<?php
/**
 * Pied de page commun aux pages publiques.
 * Attend une variable optionnelle $footerDisclaimer (string) : ligne tarifaire
 * discrète affichée juste avant le pied de page.
 */
$footerDisclaimer = $footerDisclaimer ?? '';
?>
<?php if ($footerDisclaimer !== ''): ?>
  <div class="wrap disclaimer"><?= htmlspecialchars($footerDisclaimer) ?></div>
<?php endif; ?>

<footer class="footer">
  <div class="wrap footInner">
    <div>© <span id="y"></span> THAL Photographie · Sainte-Croix (VD)</div>
    <div class="footerLinks">
      <button class="footerLink" type="button" data-modal="mentions">Mentions légales</button>
      <span aria-hidden="true">·</span>
      <button class="footerLink" type="button" data-modal="conditions">Conditions d’utilisation</button>
      <span aria-hidden="true">·</span>
      <a class="footerLink" href="cgv.php">Conditions générales de vente</a>
    </div>
  </div>
</footer>

<div class="legalModal" id="legalModal" aria-hidden="true">
  <div class="legalBox" role="dialog" aria-modal="true" aria-labelledby="legalTitle">
    <div class="legalHead">
      <h2 id="legalTitle">Mentions légales</h2>
      <button class="legalClose" type="button" aria-label="Fermer">×</button>
    </div>
    <div class="legalContent" id="legalContent"></div>
  </div>
</div>

<script src="assets/site.js?v=<?= (int)(@filemtime(__DIR__ . '/../assets/site.js') ?: time()) ?>" defer></script>
<script src="assets/legal.js?v=<?= (int)(@filemtime(__DIR__ . '/../assets/legal.js') ?: time()) ?>" defer></script>
