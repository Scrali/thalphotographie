/* ==========================================================================
   THAL Photographie — comportements communs
   Chargé par toutes les pages publiques. Tout est optionnel : chaque bloc
   ne s'active que si les éléments concernés existent sur la page.
   ========================================================================== */
(() => {
  "use strict";

  /* ------------------------------------------------ barre de navigation */
  const nav = document.getElementById("thalNav");
  if (nav) {
    const onScroll = () => nav.classList.toggle("scrolled", window.scrollY > 40);
    onScroll();
    addEventListener("scroll", onScroll, { passive: true });
  }

  const dropToggle = document.querySelector(".navDropdownToggle");
  const dropMenu = document.querySelector(".navDropdownMenu");
  if (dropToggle && dropMenu) {
    const closeDrop = () => {
      dropMenu.classList.remove("open");
      dropToggle.setAttribute("aria-expanded", "false");
    };
    dropToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      const open = dropMenu.classList.toggle("open");
      dropToggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
    document.addEventListener("click", closeDrop);
    addEventListener("keydown", (e) => { if (e.key === "Escape") closeDrop(); });
  }

  const burger = document.getElementById("thalBurger");
  const mobileMenu = document.getElementById("thalMobileMenu");
  if (burger && mobileMenu) {
    burger.addEventListener("click", () => {
      const open = mobileMenu.hidden;
      mobileMenu.hidden = !open;
      burger.setAttribute("aria-expanded", open ? "true" : "false");
      burger.setAttribute("aria-label", open ? "Fermer le menu" : "Ouvrir le menu");
      document.body.style.overflow = open ? "hidden" : "";
    });
    addEventListener("keydown", (e) => {
      if (e.key !== "Escape" || mobileMenu.hidden) return;
      mobileMenu.hidden = true;
      burger.setAttribute("aria-expanded", "false");
      document.body.style.overflow = "";
    });
  }

  /* ------------------------------------------------ apparition au scroll */
  const revealables = document.querySelectorAll(".reveal");
  if (revealables.length) {
    if (!("IntersectionObserver" in window)) {
      revealables.forEach((el) => el.classList.add("on"));
    } else {
      const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add("on");
          io.unobserve(entry.target);
        });
      }, { threshold: 0.08, rootMargin: "0px 0px -50px" });
      revealables.forEach((el) => io.observe(el));
    }
  }

  /* ------------------------------ lueur qui suit la souris sur les cartes */
  let spotlightFrame = null;
  document.querySelectorAll(".priceCard").forEach((card) => {
    card.addEventListener("pointermove", (e) => {
      if (spotlightFrame) return;
      spotlightFrame = requestAnimationFrame(() => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty("--mx", (e.clientX - rect.left) + "px");
        card.style.setProperty("--my", (e.clientY - rect.top) + "px");
        spotlightFrame = null;
      });
    }, { passive: true });
  });

  /* ------------------------------------------------------- année en pied */
  const yearEl = document.getElementById("y");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  /* ------------------------------------------------- compteur de visites */
  if (!window.__thalVisitSent) {
    window.__thalVisitSent = true;
    fetch("visit_track.php", { credentials: "same-origin", cache: "no-store", keepalive: true })
      .catch(() => {});
  }
})();

/* ==========================================================================
   Visionneuse d'images partagée.
   Usage : thalLightbox(listeDeTuiles, indexDeDepart)
   Chaque tuile doit contenir une <img> et, si possible, une <figcaption>.
   ========================================================================== */
window.thalLightbox = (function () {
  let box = null, items = [], index = 0, lastFocus = null;

  function render() {
    const tile = items[index];
    const img = tile.querySelector("img");
    const cap = tile.querySelector("figcaption");
    const full = img.dataset.full || img.src;
    box.querySelector("img").src = full;
    box.querySelector("img").alt = img.alt || "";
    box.querySelector(".lb__cap").textContent =
      (cap ? cap.textContent + " · " : "") + (index + 1) + " / " + items.length;
  }

  function step(dir) {
    index = (index + dir + items.length) % items.length;
    render();
  }

  function onKey(e) {
    if (e.key === "Escape") close();
    else if (e.key === "ArrowRight") step(1);
    else if (e.key === "ArrowLeft") step(-1);
  }

  function close() {
    removeEventListener("keydown", onKey);
    if (box) box.remove();
    box = null;
    document.body.style.overflow = "";
    if (lastFocus) lastFocus.focus();
  }

  function open(tiles, start) {
    items = Array.from(tiles).filter((t) => !t.hidden);
    if (!items.length) return;
    index = Math.max(0, items.indexOf(start));
    lastFocus = document.activeElement;

    box = document.createElement("div");
    box.className = "lb";
    box.innerHTML =
      '<button type="button" class="lb__btn lb__close" aria-label="Fermer"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 6L6 18M6 6l12 12"/></svg></button>' +
      '<button type="button" class="lb__btn lb__prev" aria-label="Image précédente"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 18l-6-6 6-6"/></svg></button>' +
      '<img alt=""><div class="lb__cap"></div>' +
      '<button type="button" class="lb__btn lb__next" aria-label="Image suivante"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 18l6-6-6-6"/></svg></button>';

    document.body.appendChild(box);
    document.body.style.overflow = "hidden";
    render();

    box.querySelector(".lb__close").addEventListener("click", close);
    box.querySelector(".lb__prev").addEventListener("click", (e) => { e.stopPropagation(); step(-1); });
    box.querySelector(".lb__next").addEventListener("click", (e) => { e.stopPropagation(); step(1); });
    box.addEventListener("click", (e) => { if (e.target === box) close(); });
    box.querySelector(".lb__close").focus();
    addEventListener("keydown", onKey);
  }

  return open;
})();
