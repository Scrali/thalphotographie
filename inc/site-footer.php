<?php
/**
 * Pied de page commun aux pages de gamme.
 * Attend une variable optionnelle $footerDisclaimer (string) : ligne tarifaire
 * discrète affichée en bas de page. Absente sur pourquoi-thal.php.
 */
$footerDisclaimer = $footerDisclaimer ?? '';
?>
<?php if ($footerDisclaimer !== ''): ?>
  <div class="disclaimer"><?= htmlspecialchars($footerDisclaimer) ?></div>
<?php endif; ?>

<div class="footer">
  <div>© <span id="y"></span> THAL — Photographie</div>
  <div class="footerLinks">
    <button class="footerLink" type="button" data-modal="mentions">Mentions légales</button>
    <span>•</span>
    <button class="footerLink" type="button" data-modal="conditions">Conditions d’utilisation</button>
    <span>•</span>
    <a class="footerLink" href="cgv.php">Conditions générales de vente</a>
  </div>
</div>

<div class="legalModal" id="legalModal" aria-hidden="true">
  <div class="legalBox" role="dialog" aria-modal="true" aria-labelledby="legalTitle">
    <div class="legalHead">
      <h2 id="legalTitle">Mentions légales</h2>
      <button class="legalClose" type="button" aria-label="Fermer">×</button>
    </div>
    <div class="legalContent" id="legalContent"></div>
  </div>
</div>

<script>
  document.getElementById("y").textContent = new Date().getFullYear();

  const legalTexts = {
    mentions: {
      title: "Mentions légales",
      html: `
        <h3>Éditeur du site</h3>
        <p><strong>THAL — Photographie</strong><br>Jonathan<br>Suisse romande</p>

        <h3>Contact</h3>
        <p>E-mail : contact@thalphotographie.ch<br>Téléphone / WhatsApp : +41 78 745 72 42</p>

        <h3>Responsabilité</h3>
        <p>Les informations présentes sur ce site sont fournies à titre indicatif. THAL — Photographie s’efforce de maintenir les contenus à jour, sans garantir l’absence totale d’erreurs.</p>

        <h3>Droits d’auteur</h3>
        <p>Les textes, photographies, logos, images et éléments graphiques présents sur ce site sont protégés. Toute reproduction, modification ou utilisation sans autorisation écrite préalable est interdite.</p>
      `
    },
    conditions: {
      title: "Conditions d’utilisation",
      html: `
        <h3>Utilisation du site</h3>
        <p>Ce site présente les prestations, projets et photographies de THAL — Photographie. L’utilisateur s’engage à utiliser le site de manière respectueuse et conforme à la loi.</p>

        <h3>Demandes de contact</h3>
        <p>L’envoi d’un formulaire ou d’un message WhatsApp ne constitue pas une réservation ferme. Chaque projet est confirmé après échange et validation des conditions convenues.</p>

        <h3>Photographies</h3>
        <p>Les images affichées sont destinées à présenter le travail photographique. Elles ne peuvent pas être copiées, téléchargées, réutilisées ou diffusées sans autorisation.</p>

        <h3>Données transmises</h3>
        <p>Les informations envoyées via le formulaire de contact sont utilisées uniquement pour répondre à la demande. Elles ne sont pas revendues à des tiers.</p>
      `
    }
  };

  const legalModal = document.getElementById("legalModal");
  const legalTitle = document.getElementById("legalTitle");
  const legalContent = document.getElementById("legalContent");
  const legalClose = document.querySelector(".legalClose");

  function openLegalModal(type){
    const item = legalTexts[type];
    if(!item) return;
    legalTitle.textContent = item.title;
    legalContent.innerHTML = item.html;
    legalModal.classList.add("open");
    legalModal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    legalClose.focus();
  }

  function closeLegalModal(){
    legalModal.classList.remove("open");
    legalModal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }

  document.querySelectorAll("[data-modal]").forEach(btn=>{
    btn.addEventListener("click", ()=>openLegalModal(btn.dataset.modal));
  });

  legalClose.addEventListener("click", closeLegalModal);

  legalModal.addEventListener("click", (e)=>{
    if(e.target === legalModal) closeLegalModal();
  });

  document.addEventListener("keydown", (e)=>{
    if(e.key === "Escape" && legalModal.classList.contains("open")) closeLegalModal();
  });

  const revealEls = [...document.querySelectorAll(".reveal")];
  const io = new IntersectionObserver((entries)=>{
    entries.forEach(e=>{
      if(e.isIntersecting) e.target.classList.add("on");
    });
  }, { threshold: 0.12 });
  revealEls.forEach(el=>io.observe(el));

  document.addEventListener("click", (e)=>{
    const a = e.target.closest("a,button");
    if(a) a.blur();
  }, { passive:true });
</script>
