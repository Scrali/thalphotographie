/* ==========================================================================
   THAL Photographie — fenêtres Mentions légales / Conditions d'utilisation
   Partagé par les pages PHP, index.html et galerie.html.
   ========================================================================== */
(() => {
  "use strict";

  const legalTexts = {
    mentions: {
      title: "Mentions légales",
      html: `
        <h3>Éditeur du site</h3>
        <p><strong>THAL Photographie</strong><br>Jonathan<br>Sainte-Croix, Suisse romande</p>

        <h3>Contact</h3>
        <p>E-mail : contact@thalphotographie.ch<br>Téléphone / WhatsApp : +41 78 745 72 42</p>

        <h3>Responsabilité</h3>
        <p>Les informations présentes sur ce site sont fournies à titre indicatif. THAL Photographie s’efforce de maintenir les contenus à jour, sans garantir l’absence totale d’erreurs.</p>

        <h3>Droits d’auteur</h3>
        <p>Les textes, photographies, logos, images et éléments graphiques présents sur ce site sont protégés. Toute reproduction, modification ou utilisation sans autorisation écrite préalable est interdite.</p>
      `
    },
    conditions: {
      title: "Conditions d’utilisation",
      html: `
        <h3>Utilisation du site</h3>
        <p>Ce site présente les prestations, projets et photographies de THAL Photographie. L’utilisateur s’engage à utiliser le site de manière respectueuse et conforme à la loi.</p>

        <h3>Demandes de contact</h3>
        <p>L’envoi d’un formulaire ou d’un message WhatsApp ne constitue pas une réservation ferme. Chaque projet est confirmé après échange et validation des conditions convenues.</p>

        <h3>Photographies</h3>
        <p>Les images affichées sont destinées à présenter le travail photographique. Elles ne peuvent pas être copiées, téléchargées, réutilisées ou diffusées sans autorisation.</p>

        <h3>Données transmises</h3>
        <p>Les informations envoyées via le formulaire de contact sont utilisées uniquement pour répondre à la demande. Elles ne sont pas revendues à des tiers.</p>
      `
    }
  };

  const modal = document.getElementById("legalModal");
  if (!modal) return;

  const titleEl = document.getElementById("legalTitle");
  const contentEl = document.getElementById("legalContent");
  const closeBtn = modal.querySelector(".legalClose");
  let lastFocus = null;

  function openModal(type) {
    const item = legalTexts[type];
    if (!item) return;
    lastFocus = document.activeElement;
    titleEl.textContent = item.title;
    contentEl.innerHTML = item.html;
    modal.classList.add("open");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    closeBtn.focus();
  }

  function closeModal() {
    modal.classList.remove("open");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
    if (lastFocus) lastFocus.focus();
  }

  document.querySelectorAll("[data-modal]").forEach((btn) => {
    btn.addEventListener("click", () => openModal(btn.dataset.modal));
  });
  closeBtn.addEventListener("click", closeModal);
  modal.addEventListener("click", (e) => { if (e.target === modal) closeModal(); });
  addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.classList.contains("open")) closeModal();
  });
})();
