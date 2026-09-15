<?php
/**
 * Gabarit complet d'une page de gamme.
 * Attend $gammeKey (ex: 'portraits') défini avant l'include.
 */
require_once __DIR__ . '/gammes-data.php';

$gammes = thal_gammes();
if (empty($gammeKey) || !isset($gammes[$gammeKey])) {
    http_response_code(404);
    exit('Gamme inconnue.');
}

$g = $gammes[$gammeKey];
$activeNav = $gammeKey;
$footerDisclaimer = $g['disclaimer'];

/** Lien de contact pré-rempli. */
function thal_devis_href(string $message): string
{
    return 'index.html?prefill=' . urlencode($message) . '#contact';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= htmlspecialchars($g['metaTitle']) ?> • THAL Photographie</title>
  <meta name="description" content="<?= htmlspecialchars($g['metaDesc']) ?>" />
  <meta name="theme-color" content="#0a0a0c" />
  <?php include __DIR__ . '/site-styles.php'; ?>
</head>
<body>
  <?php include __DIR__ . '/site-nav.php'; ?>

  <main>
    <section class="wrap pageHero">
      <div class="pageHeadGrid">
        <div>
          <span class="eyebrow reveal"><?= htmlspecialchars($g['kicker']) ?></span>
          <h1 class="reveal"><?= htmlspecialchars($g['title']) ?></h1>
          <p class="lead reveal"><?= htmlspecialchars($g['lead']) ?></p>
        </div>
        <div class="pageFacts reveal">
          <?php foreach ($g['facts'] as [$k, $v]): ?>
            <div><span class="k"><?= htmlspecialchars($k) ?></span><span class="v"><?= htmlspecialchars($v) ?></span></div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <?php
      $carouselKey      = $g['carousel']['key'];
      $carouselCategory = $g['carousel']['category'];
      $carouselLabel    = $g['carousel']['label'];
      include __DIR__ . '/photo-carousel.php';
    ?>

    <section class="wrap">
      <div class="sectionHead reveal">
        <h2>Les formules.</h2>
        <p><?= htmlspecialchars($g['pricesIntro']) ?></p>
      </div>

      <div class="priceGrid">
        <?php foreach ($g['cards'] as $c): ?>
          <article class="priceCard reveal">
            <h3><?= $c['h'] /* contient parfois <sup> */ ?></h3>
            <div class="priceDuration"><?= htmlspecialchars($c['dur']) ?></div>
            <div class="priceValue">
              <b<?= !empty($c['quote']) ? ' class="quote"' : '' ?>><?= htmlspecialchars($c['amount']) ?></b>
              <?php if (!empty($c['unit'])): ?><small><?= htmlspecialchars($c['unit']) ?></small><?php endif; ?>
            </div>
            <ul>
              <?php foreach ($c['li'] as $line): ?><li><?= htmlspecialchars($line) ?></li><?php endforeach; ?>
            </ul>
            <?php if (!empty($c['badge'])): ?><span class="priceBadge"><?= $c['badge'] ?></span><?php endif; ?>
            <div class="priceCta">
              <a href="<?= htmlspecialchars(thal_devis_href($c['prefill'])) ?>"><?= htmlspecialchars($g['cta']) ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <?php if (!empty($g['highlight'])): ?>
        <div class="highlightBlock reveal">
          <div>
            <h3><?= htmlspecialchars($g['highlight']['h']) ?></h3>
            <p><?= htmlspecialchars($g['highlight']['p']) ?></p>
          </div>
          <a class="ghost" href="<?= htmlspecialchars(thal_devis_href($g['highlight']['prefill'])) ?>"><?= htmlspecialchars($g['highlight']['b']) ?></a>
        </div>
      <?php endif; ?>

      <?php if (!empty($g['note'])): ?>
        <p class="cardNote reveal"><?= $g['note'] /* peut contenir un lien */ ?></p>
      <?php endif; ?>
    </section>

    <?php include __DIR__ . '/site-steps.php'; ?>
    <?php include __DIR__ . '/site-faq.php'; ?>

    <section class="wrap tight">
      <div class="highlightBlock reveal">
        <div>
          <h3>Une question sur cette prestation ?</h3>
          <p>Dites-moi ce que vous avez en tête, je reviens vers vous avec un devis personnalisé gratuit sous 48 h.</p>
        </div>
        <a class="btn" href="<?= htmlspecialchars(thal_devis_href('Bonjour, j’ai une question sur la gamme ' . $g['nav'] . '.')) ?>"><?= htmlspecialchars($g['cta']) ?>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/site-footer.php'; ?>
</body>
</html>
