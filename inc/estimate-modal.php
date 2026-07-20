<?php
/**
 * Bouton flottant "Obtenir une estimation" + modale, partagés par les pages de gamme.
 * Tout élément portant [data-thal-estimate-open] ouvre la modale ; un attribut
 * data-thal-type="Portrait" présélectionne le type, data-thal-message="..." préremplit le message.
 */
?>
<style>
/* THAL_ESTIMATE_MODAL_CSS_V070 */
.thal-estimate-floating{position:fixed;right:18px;bottom:18px;z-index:9998}
.thal-estimate-open{border:0;border-radius:999px;padding:13px 18px;background:#fff;color:#111;font-weight:900;cursor:pointer;box-shadow:0 14px 36px rgba(0,0,0,.35)}
.thal-modal[hidden]{display:none}.thal-modal{position:fixed;inset:0;z-index:9999}.thal-modal-backdrop{position:absolute;inset:0;background:rgba(0,0,0,.66);backdrop-filter:blur(8px)}
.thal-modal-panel{position:relative;width:min(840px,calc(100vw - 28px));max-height:calc(100vh - 32px);overflow:auto;margin:16px auto;background:#111;color:#f5f5f5;border:1px solid rgba(255,255,255,.16);border-radius:24px;padding:24px;box-shadow:0 24px 90px rgba(0,0,0,.55)}
.thal-modal-close{position:absolute;right:16px;top:14px;border:1px solid rgba(255,255,255,.2);background:#1f1f1f;color:#fff;border-radius:999px;width:36px;height:36px;cursor:pointer}
.thal-estimate-form h2{margin:0 0 8px;font-family:Georgia,serif;font-weight:400}.thal-estimate-form p{color:rgba(255,255,255,.68)}
.thal-estimate-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}.thal-estimate-form label{display:block;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);margin:0 0 12px}
.thal-estimate-form input,.thal-estimate-form select,.thal-estimate-form textarea{width:100%;box-sizing:border-box;display:block;margin-top:7px;background:#080808;color:#fff;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px;font-size:15px}
.thal-radio-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:8px 0 14px}.thal-radio-card{border:1px solid rgba(255,255,255,.18);border-radius:16px;padding:13px;cursor:pointer;background:#151515}
.thal-radio-card input{width:auto;display:inline-block;margin-right:8px}.thal-estimate-submit,.thal-map-button{width:100%;border:0;border-radius:999px;padding:14px;background:#fff;color:#111;font-weight:900;cursor:pointer}
.thal-map-button{background:#243235;color:#dceff2;border:1px solid rgba(255,255,255,.14)}.thal-estimate-result{margin-top:18px;border-top:1px solid rgba(255,255,255,.15);padding-top:16px}
.thal-estimate-price{background:#070707;border:1px solid rgba(255,255,255,.2);border-radius:18px;padding:16px;text-align:center;font-size:30px;font-family:Georgia,serif}
.thal-pack-list{list-style:none;padding:0;margin:12px 0;color:#dfecec}.thal-pack-list li{margin:6px 0}.thal-distance-status{color:#9fb4b8;font-size:12px;text-transform:none;letter-spacing:0;margin-top:5px}
.thal-location-field{position:relative}
.thal-suggestions{position:absolute;top:100%;left:0;right:0;z-index:20;margin:4px 0 0;padding:6px;list-style:none;background:#141414;border:1px solid rgba(255,255,255,.18);border-radius:12px;box-shadow:0 14px 36px rgba(0,0,0,.45);max-height:220px;overflow:auto}
.thal-suggestions[hidden]{display:none}
.thal-suggestions li{padding:9px 10px;border-radius:8px;cursor:pointer;font-size:13px;text-transform:none;letter-spacing:0;color:#f5f5f5}
.thal-suggestions li:hover,.thal-suggestions li.active{background:#243235}
.thal-identite-mode .thal-hide-identite{display:none}
.thal-identite-hide-inline[hidden]{display:none}
@media(max-width:720px){.thal-estimate-grid,.thal-radio-row{grid-template-columns:1fr}.thal-estimate-floating{left:14px;right:14px}.thal-estimate-open{width:100%}}
</style>

<div class="thal-estimate-floating">
  <button class="thal-estimate-open" type="button" data-thal-estimate-open>Demander un devis</button>
</div>

<div class="thal-modal" id="thalEstimateModal" hidden>
  <div class="thal-modal-backdrop" data-thal-estimate-close></div>
  <section class="thal-modal-panel" role="dialog" aria-modal="true" aria-labelledby="thalEstimateTitle">
    <button class="thal-modal-close" type="button" data-thal-estimate-close aria-label="Fermer">×</button>

    <form class="thal-estimate-form" id="thalEstimateForm">
      <h2 id="thalEstimateTitle">Demande de devis rapide</h2>
      <p id="thalEstimateIntro">Indiquez le lieu, la date, le temps sur place et l’usage prévu — je vous recontacte avec un devis personnalisé sous 48h.</p>
      <p class="thal-identite-hide-inline" id="thalIdentiteNote" hidden>Photo d’identité conforme (fedpol / passeport / visa / permis) : <strong>35 CHF</strong> pour 1 personne, <strong>25 CHF</strong> dès la 2ᵉ personne (même séance). Laissez vos coordonnées ci-dessous pour prendre rendez-vous.</p>

      <div class="thal-estimate-grid">
        <label>Type de prestation
          <select name="type">
            <option>Événement</option><option>Identité</option><option>Mariage</option><option>Portrait</option><option>Entreprise</option><option>Concert</option><option>Famille</option>
          </select>
        </label>
        <label class="thal-hide-identite">Date souhaitée
          <input type="date" name="event_date" id="thalEstimateDate">
          <button class="thal-map-button" type="button" id="thalOpenCalendar">Ouvrir le calendrier</button>
          <div class="thal-distance-status" id="thalDateStatus"></div>
        </label>
        <label class="thal-location-field thal-hide-identite">Lieu
          <input name="location" id="thalEstimateLocation" placeholder="Ville / adresse exacte" autocomplete="off">
          <ul class="thal-suggestions" id="thalLocationSuggestions" hidden></ul>
        </label>
        <label class="thal-hide-identite">Distance (estimation)
          <input type="number" step="0.1" min="0" name="roundtrip_km" id="thalEstimateKm" placeholder="Calcul automatique">
          <div class="thal-distance-status" id="thalDistanceStatus">Saisissez un lieu, puis calculez la distance approximative.</div>
        </label>
        <label class="thal-hide-identite">Temps sur place
          <select name="onsite_hours">
            <option value="1">1 heure</option><option value="2" selected>2 heures</option><option value="3">3 heures</option><option value="4">4 heures</option><option value="6">6 heures</option><option value="8">8 heures</option>
          </select>
        </label>
        <label class="thal-hide-identite">Calcul distance
          <button class="thal-map-button" type="button" id="thalCalculateDistance">Calculer automatiquement</button>
        </label>
      </div>

      <div class="thal-radio-row thal-hide-identite">
        <label class="thal-radio-card"><input type="radio" name="usage" value="private" checked> Cadre privé</label>
        <label class="thal-radio-card"><input type="radio" name="usage" value="commercial"> Utilisation commerciale</label>
      </div>

      <div class="thal-estimate-grid">
        <label>Nom / entreprise<input name="name" placeholder="Facultatif"></label>
        <label>Email<input type="email" name="email" placeholder="Facultatif"></label>
      </div>
      <label>Téléphone<input name="phone" placeholder="Facultatif"></label>
      <label>Message<textarea name="message" rows="3" placeholder="Infos utiles : nombre de personnes, horaires, attentes..."></textarea></label>

      <button class="thal-estimate-submit" type="submit" id="thalEstimateSubmitBtn">Envoyer ma demande</button>
      <div class="thal-estimate-result" id="thalEstimateResult" hidden></div>
    </form>
  </section>
</div>

<script>
(() => {
  const modal = document.getElementById("thalEstimateModal");
  const openTriggers = document.querySelectorAll("[data-thal-estimate-open]");
  const closeEls = document.querySelectorAll("[data-thal-estimate-close]");
  const form = document.getElementById("thalEstimateForm");
  const result = document.getElementById("thalEstimateResult");
  const distanceBtn = document.getElementById("thalCalculateDistance");
  const locationInput = document.getElementById("thalEstimateLocation");
  const kmInput = document.getElementById("thalEstimateKm");
  const distanceStatus = document.getElementById("thalDistanceStatus");
  const dateInput = document.getElementById("thalEstimateDate");
  const dateBtn = document.getElementById("thalOpenCalendar");
  const dateStatus = document.getElementById("thalDateStatus");
  const suggestionsList = document.getElementById("thalLocationSuggestions");
  const typeSelect = form ? form.querySelector("select[name='type']") : null;
  const identiteNote = document.getElementById("thalIdentiteNote");
  const estimateIntro = document.getElementById("thalEstimateIntro");
  let lastContactSummary = "";
  let suggestDebounce = null;
  let selectedCoords = null;
  let busyDatesPromise = null;

  if(!modal || !openTriggers.length || !form || !result) return;

  function isIdentiteSelected(){
    return !!typeSelect && typeSelect.value === "Identité";
  }

  function updateFormMode(){
    const identite = isIdentiteSelected();
    form.classList.toggle("thal-identite-mode", identite);
    if(identiteNote) identiteNote.hidden = !identite;
    if(estimateIntro) estimateIntro.hidden = identite;
  }

  if(typeSelect) typeSelect.addEventListener("change", updateFormMode);
  updateFormMode();

  function fetchBusyDates(){
    if(!busyDatesPromise){
      busyDatesPromise = fetch("estimation_calendar_busy.php", {headers:{"Accept":"application/json"}})
        .then(r => r.json())
        .then(data => (data && data.ok) ? (data.busy || []) : [])
        .catch(() => []);
    }
    return busyDatesPromise;
  }

  async function checkDateAvailability(){
    if(!dateInput || !dateStatus || !dateInput.value){ if(dateStatus) dateStatus.textContent = ""; return; }
    const busy = await fetchBusyDates();
    if(busy.includes(dateInput.value)){
      dateStatus.textContent = "⚠️ Cette date semble déjà prise — contactez-moi pour confirmer.";
    }else{
      dateStatus.textContent = "";
    }
  }

  const openModal = () => { modal.hidden = false; document.body.style.overflow = "hidden"; fetchBusyDates(); };
  const closeModal = () => { modal.hidden = true; document.body.style.overflow = ""; };

  function fillContact(summary){
    const contactForm = document.getElementById("contactForm");
    if(!contactForm){ location.href = "#contact"; return; }
    const msg = contactForm.querySelector("textarea[name='message'], textarea, input[name='message']");
    const name = contactForm.querySelector("input[name='name'], input[name='nom']");
    const email = contactForm.querySelector("input[type='email'], input[name='email']");
    const fd = new FormData(form);
    if(name && fd.get("name")) name.value = fd.get("name");
    if(email && fd.get("email")) email.value = fd.get("email");
    if(msg) msg.value = summary;
    closeModal();
    contactForm.scrollIntoView({behavior:"smooth", block:"start"});
  }

  openTriggers.forEach(btn => {
    btn.addEventListener("click", () => {
      if(typeSelect && btn.dataset.thalType){
        typeSelect.value = btn.dataset.thalType;
        updateFormMode();
      }
      if(btn.dataset.thalMessage){
        const messageField = form.querySelector("textarea[name='message']");
        if(messageField && !messageField.value) messageField.value = btn.dataset.thalMessage;
      }
      openModal();
    });
  });
  closeEls.forEach(el => el.addEventListener("click", closeModal));
  document.addEventListener("keydown", e => { if(e.key === "Escape" && !modal.hidden) closeModal(); });

  if(dateBtn && dateInput){
    dateBtn.addEventListener("click", () => {
      if(dateInput.showPicker) dateInput.showPicker();
      else dateInput.focus();
    });
    dateInput.addEventListener("change", checkDateAvailability);
  }

  async function calculateDistance(){
    const loc = locationInput ? locationInput.value.trim() : "";
    if(!loc){ distanceStatus.textContent = "Indiquez d’abord le lieu."; return; }
    distanceStatus.textContent = "Calcul de la distance…";
    try{
      let url = "estimation_distance.php?location=" + encodeURIComponent(loc);
      if(selectedCoords){
        url += "&lat=" + encodeURIComponent(selectedCoords.lat) + "&lon=" + encodeURIComponent(selectedCoords.lon);
      }
      const response = await fetch(url, {headers:{"Accept":"application/json"}});
      const data = await response.json();
      if(!data.ok){ distanceStatus.textContent = data.error || "Distance non disponible."; return; }
      kmInput.value = data.roundtrip_km;
      distanceStatus.textContent = `Environ ${data.roundtrip_km} km aller-retour • ~${data.roundtrip_minutes} min A/R — estimation indicative, le trajet exact sera recalculé pour le devis.`;
    }catch(e){
      distanceStatus.textContent = "Erreur lors du calcul de distance.";
    }
  }

  function hideSuggestions(){
    if(!suggestionsList) return;
    suggestionsList.hidden = true;
    suggestionsList.innerHTML = "";
    suggestionsList._items = [];
  }

  function renderSuggestions(items){
    if(!suggestionsList) return;
    if(!items.length){ hideSuggestions(); return; }
    suggestionsList._items = items;
    suggestionsList.innerHTML = items.map((s, i) => `<li data-index="${i}">${s.label}</li>`).join("");
    suggestionsList.hidden = false;
  }

  async function fetchSuggestions(text){
    try{
      const response = await fetch("estimation_geocode.php?text=" + encodeURIComponent(text), {headers:{"Accept":"application/json"}});
      const data = await response.json();
      if(!data.ok){ hideSuggestions(); return; }
      renderSuggestions(data.suggestions || []);
    }catch(e){
      hideSuggestions();
    }
  }

  if(distanceBtn) distanceBtn.addEventListener("click", calculateDistance);
  if(locationInput) locationInput.addEventListener("change", calculateDistance);

  if(locationInput){
    locationInput.addEventListener("input", () => {
      selectedCoords = null;
      const text = locationInput.value.trim();
      clearTimeout(suggestDebounce);
      if(text.length < 3){ hideSuggestions(); return; }
      suggestDebounce = setTimeout(() => fetchSuggestions(text), 300);
    });
    locationInput.addEventListener("keydown", e => {
      if(e.key === "Escape") hideSuggestions();
    });
  }

  if(suggestionsList){
    suggestionsList.addEventListener("mousedown", e => e.preventDefault());
    suggestionsList.addEventListener("click", e => {
      const li = e.target.closest("li");
      if(!li) return;
      const item = (suggestionsList._items || [])[Number(li.dataset.index)];
      if(!item) return;
      locationInput.value = item.label;
      selectedCoords = { lat: item.lat, lon: item.lon };
      hideSuggestions();
      calculateDistance();
    });
  }

  document.addEventListener("click", e => {
    if(!e.target.closest(".thal-location-field")) hideSuggestions();
  });

  form.addEventListener("submit", async e => {
    e.preventDefault();
    result.hidden = false;

    if(isIdentiteSelected()){
      const name = form.querySelector("input[name='name']").value.trim();
      const message = form.querySelector("textarea[name='message']").value.trim();
      lastContactSummary = [
        "Photo d’identité — 35 CHF (1 personne) / 25 CHF dès la 2ᵉ personne (même séance).",
        name ? `Nom : ${name}` : "",
        message
      ].filter(Boolean).join("\n");

      result.innerHTML = `
        <h3>Tarif</h3>
        <div class="thal-estimate-price">35 CHF <span style="font-size:15px">(1 personne)</span></div>
        <p style="text-align:center; margin:6px 0 0">25 CHF dès la 2ᵉ personne, même séance</p>
        <ul class="thal-pack-list">
          <li>✓ Prise de vue conforme (fedpol / passeport / visa / permis)</li>
          <li>✓ Tirage papier au format officiel</li>
          <li>✓ Version numérique HD</li>
        </ul>
        <button type="button" class="thal-estimate-submit" id="thalFillContact">Me contacter avec cette demande</button>
      `;
      const fillBtn = document.getElementById("thalFillContact");
      if(fillBtn) fillBtn.addEventListener("click", () => fillContact(lastContactSummary));
      return;
    }

    const typeVal = typeSelect ? typeSelect.value : "";
    const dateVal = (form.querySelector("input[name='event_date']") || {}).value || "";
    const locationVal = (form.querySelector("input[name='location']") || {}).value.trim() || "";
    const nameVal = form.querySelector("input[name='name']").value.trim();
    const messageVal = form.querySelector("textarea[name='message']").value.trim();

    lastContactSummary = [
      `Demande de devis — ${typeVal}`,
      dateVal ? `Date souhaitée : ${dateVal}` : "",
      locationVal ? `Lieu : ${locationVal}` : "",
      nameVal ? `Nom : ${nameVal}` : "",
      messageVal
    ].filter(Boolean).join("\n");

    result.innerHTML = "<p>Envoi en cours…</p>";

    try{
      const response = await fetch("estimation_submit.php", {
        method:"POST",
        body:new FormData(form),
        headers:{ "Accept":"application/json" }
      });
      const data = await response.json();

      if(!data.ok){
        result.innerHTML = "<p>Impossible d’envoyer votre demande pour le moment. Vous pouvez aussi me contacter directement.</p>";
        return;
      }

      result.innerHTML = `
        <h3>Demande envoyée</h3>
        <p>Merci ! Je reviens vers vous avec un devis personnalisé sous 48h.</p>
        <button type="button" class="thal-estimate-submit" id="thalFillContact">Me contacter avec cette demande</button>
      `;

      const fillBtn = document.getElementById("thalFillContact");
      if(fillBtn) fillBtn.addEventListener("click", () => fillContact(lastContactSummary));
    }catch(err){
      result.innerHTML = "<p>Erreur réseau. Merci de réessayer ou de me contacter directement.</p>";
    }
  });
})();
</script>
