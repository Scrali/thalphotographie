<?php
/**
 * Carrousel photo réutilisable pour les pages de gamme.
 * Attend $carouselCategory (nom de catégorie dans photos/gallery.auto.json)
 * et $carouselLabel (texte affiché au-dessus + badge sur les photos).
 */
$carouselCategory = $carouselCategory ?? 'Accueil';
$carouselLabel = $carouselLabel ?? 'Aperçu';
$carouselId = 'thalCarousel_' . preg_replace('/[^a-zA-Z0-9]/', '', $carouselCategory);
?>
<style>
.thal-carousel{position:relative; margin:var(--gap) 0; height:clamp(240px,42vw,420px); overflow:hidden; cursor:pointer;}
.thal-carousel .carouselTrack,.thal-carousel .carouselSlide,.thal-carousel .carouselSlide img{height:100%;}
.thal-carousel .carouselSlide img{object-fit:contain; background:#050816;}
.carouselTrack{position:relative; width:100%; height:100%;}
.carouselSlide{position:absolute; inset:0; opacity:0; transition:opacity 1s ease; z-index:1; background:#000;}
.carouselSlide.active{opacity:1; z-index:2;}
.carouselSlide img{width:100%; height:100%; display:block; object-fit:cover; background:#000;}
.svcBadge{position:absolute; left:10px; bottom:10px; padding:8px 10px; border-radius:999px; border:1px solid rgba(255,255,255,.16); background:rgba(0,0,0,.40); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); font-size:12px; font-weight:850; color:rgba(255,255,255,.92); z-index:3;}
.cNav{position:absolute; inset:0; pointer-events:none; display:flex; align-items:center; justify-content:space-between; padding:10px; z-index:6;}
.cBtn{pointer-events:auto; width:42px; height:42px; border-radius:999px; border:1px solid rgba(255,255,255,.15); background:rgba(0,0,0,.32); color:rgba(255,255,255,.92); font-weight:900; cursor:pointer; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px);}
.cBtn:hover{background:rgba(0,0,0,.48);}
.cBtn:disabled{opacity:.35; cursor:not-allowed;}
.cDots{position:absolute; left:12px; right:12px; bottom:10px; display:flex; justify-content:center; gap:6px; pointer-events:none; z-index:6;}
.dot{width:7px; height:7px; border-radius:999px; border:1px solid rgba(255,255,255,.20); background:rgba(255,255,255,.16); opacity:.75;}
.dot.on{background:rgba(124,184,255,.58); border-color:rgba(124,184,255,.72); opacity:1;}
.carouselEmpty{height:100%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.65); font-weight:850; letter-spacing:.2px; background:rgba(0,0,0,.25);}
@media(max-width:620px){.cBtn{width:36px; height:36px;}}
</style>

<div class="sectionTitle reveal">En images</div>
<section class="thal-carousel card reveal" id="<?= htmlspecialchars($carouselId) ?>" data-cat="<?= htmlspecialchars($carouselCategory) ?>" data-label="<?= htmlspecialchars($carouselLabel) ?>" aria-label="Aperçu <?= htmlspecialchars($carouselLabel) ?>">
  <div class="carouselTrack"></div>
  <div class="cNav">
    <button class="cBtn prev" type="button" aria-label="Précédent">←</button>
    <button class="cBtn next" type="button" aria-label="Suivant">→</button>
  </div>
  <div class="cDots" aria-hidden="true"></div>
</section>

<script>
(() => {
  const root = document.getElementById(<?= json_encode($carouselId) ?>);
  if(!root) return;

  const AUTO_MS = 3800;
  const LIMIT = 10;
  const cat = root.dataset.cat;
  const label = root.dataset.label;

  function normKey(s){
    return String(s || "").normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
  }

  function findCategoryKey(data, wanted){
    if(!data || typeof data !== "object") return wanted;
    const w = normKey(wanted);
    const keys = Object.keys(data);
    return keys.find(k => normKey(k) === w) || wanted;
  }

  function normalizeFiles(data, wanted){
    const key = findCategoryKey(data, wanted);
    const raw = Array.isArray(data?.[key]) ? data[key] : [];
    return raw
      .map(x => typeof x === "string" ? { cat:key, file:x } : (x && typeof x.file === "string" ? { cat:key, file:x.file } : null))
      .filter(Boolean);
  }

  function shuffle(arr){
    const a = [...arr];
    for(let i=a.length-1;i>0;i--){
      const j = Math.floor(Math.random()*(i+1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  }

  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, m=>({ "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#39;" }[m]));
  }

  const track = root.querySelector(".carouselTrack");
  const dotsWrap = root.querySelector(".cDots");
  const prevBtn = root.querySelector(".prev");
  const nextBtn = root.querySelector(".next");
  let items = [];
  let timer = null;

  function update(){
    const idx = Number(root.dataset.idx || 0);
    [...root.querySelectorAll(".carouselSlide")].forEach((s,i)=>s.classList.toggle("active", i===idx));
    [...root.querySelectorAll(".dot")].forEach((d,i)=>d.classList.toggle("on", i===idx));
  }

  function step(dir){
    if(items.length <= 1) return;
    const idx = Number(root.dataset.idx || 0);
    root.dataset.idx = String((idx + dir + items.length) % items.length);
    update();
  }

  function startAuto(){
    if(items.length <= 1) return;
    if(timer) clearInterval(timer);
    timer = setInterval(()=>step(1), AUTO_MS);
  }

  function render(){
    root.dataset.idx = "0";

    if(!items.length){
      track.innerHTML = `<div class="carouselSlide active"><div class="carouselEmpty">Aucune photo</div></div>`;
      if(dotsWrap) dotsWrap.innerHTML = "";
      if(prevBtn) prevBtn.disabled = true;
      if(nextBtn) nextBtn.disabled = true;
      return;
    }

    track.innerHTML = items.map((s, i)=>{
      const src = `photos/${encodeURIComponent(s.cat)}/${encodeURIComponent(s.file)}`;
      return `
        <div class="carouselSlide ${i === 0 ? "active" : ""}">
          <img loading="${i === 0 ? "eager" : "lazy"}" src="${src}" alt="${escapeHtml(label)}">
          <div class="svcBadge">${escapeHtml(label)}</div>
        </div>`;
    }).join("");

    if(dotsWrap){
      dotsWrap.innerHTML = items.map((_,i)=>`<span class="dot ${i===0 ? "on" : ""}"></span>`).join("");
    }

    if(prevBtn){
      prevBtn.disabled = items.length <= 1;
      prevBtn.onclick = e => { e.stopPropagation(); step(-1); };
    }
    if(nextBtn){
      nextBtn.disabled = items.length <= 1;
      nextBtn.onclick = e => { e.stopPropagation(); step(1); };
    }

    update();
    startAuto();
  }

  root.addEventListener("click", (e)=>{
    if(e.target.closest("button")) return;
    const url = new URL("galerie.html", window.location.href);
    url.searchParams.set("cat", cat);
    window.location.href = url.toString();
  });

  let sx=0, sy=0, dx=0, dy=0, active=false;
  root.addEventListener("touchstart", e=>{
    if(!e.touches || e.touches.length!==1) return;
    active=true; sx=e.touches[0].clientX; sy=e.touches[0].clientY; dx=0; dy=0;
  }, { passive:true });
  root.addEventListener("touchmove", e=>{
    if(!active || !e.touches || e.touches.length!==1) return;
    dx = e.touches[0].clientX - sx; dy = e.touches[0].clientY - sy;
  }, { passive:true });
  root.addEventListener("touchend", ()=>{
    if(!active) return;
    active=false;
    if(Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)*1.2){
      if(dx < 0) step(1); else step(-1);
    }
  }, { passive:true });

  const jsonUrl = new URL("./photos/gallery.auto.json", window.location.href);
  jsonUrl.searchParams.set("v", Date.now());

  fetch(jsonUrl, { cache:"no-store" })
    .then(res => { if(!res.ok) throw new Error("HTTP " + res.status); return res.json(); })
    .then(data => {
      items = shuffle(normalizeFiles(data, cat)).slice(0, LIMIT);
      render();
    })
    .catch(()=>{
      track.innerHTML = `<div class="carouselSlide active"><div class="carouselEmpty">Galerie indisponible</div></div>`;
    });
})();
</script>
