<?php
require __DIR__ . '/includes/auth.php';
require_login();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>THAL Studio — Nouveau devis</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app">
  <aside class="nav">
    <div class="brand">
      <img src="assets/logo.png" alt="THAL Photographie">
      <div>
        <h1>THAL Studio</h1>
        <p>V0.4.0</p>
      </div>
    </div>

    <button class="nav-btn active" data-panel="dashboard">Tableau de bord</button>
    <button class="nav-btn" data-panel="devis">Devis</button>
    <button class="nav-btn" data-panel="contenu">Contenu</button>
    <button class="nav-btn" data-panel="designer">Designer A4</button>
    <button class="nav-btn" data-panel="parametres">Paramètres</button>

    <div class="actions">
      <button id="saveQuoteServer">Enregistrer le devis</button>
      <button id="saveJson">Sauvegarder JSON</button>
      <button id="loadJson">Charger JSON</button>
      <input id="loadFile" type="file" accept=".json" hidden>
      <p id="quoteSaveStatus" class="slot-status"></p>
      <button class="primary" id="exportPdf">Exporter PDF</button>
      <a class="logout-link" href="dashboard.php">Tableau de bord</a>
      <a class="logout-link" href="settings.php">Paramètres</a>
      <a class="logout-link" href="logout.php">Déconnexion</a>
    </div>
  </aside>

  <main class="editor">
    <section id="dashboard" class="panel active">
      <h2>Tableau de bord</h2>
      <div class="a4-status" id="pageStatus">Calcul A4...</div>
      <div class="stats">
        <div><span>Devis</span><strong id="dashNumber">DEV-2026-001</strong></div>
        <div><span>Client</span><strong id="dashClient">Orchestre — prestation privée</strong></div>
        <div><span>Total</span><strong id="dashTotal">300 CHF</strong></div>
        <div><span>Date</span><strong id="dashDate">12.09.2026</strong></div>
      </div>
      <p class="hint">Version simplifiée : moins de blocs superflus, couleur du carré total corrigée, et ajout des éléments indispensables : validité, paiement, signature.</p>
    </section>

    <section id="devis" class="panel">
      <h2>Informations devis</h2>
      <div class="form-grid">
        <label>Numéro devis<input id="quoteNumber" value="DEV-2026-001"></label>
        <label>Date devis<input id="quoteDate" type="date"></label>
        <label>Valable jusqu’au<input id="validUntil" type="date"></label>
        <label>Client<input id="clientName" value="Orchestre — prestation privée"></label>
        <label>Email client<input id="clientEmail" placeholder="email@client.ch"></label>
        <label>Téléphone client<input id="clientPhone" placeholder="+41 ..."></label>
        <label class="span2">Adresse client<textarea id="clientAddress" placeholder="Adresse complète"></textarea></label>

        <label class="span2">Type de prestation<input id="serviceType" value="Photographie d’orchestre lors d’un mariage"></label>
        <label>Date événement<input id="eventDate" type="date" value="2026-09-12"></label>
        <label>Lieu<input id="eventPlace" value="Château de Lucens"></label>
        <label>Début<input id="startTime" type="time" value="16:00"></label>
        <label>Fin<input id="endTime" type="time" value="21:00"></label>
        <div class="quick-times span2">
          <button type="button" data-start="09:00" data-end="12:00">Matin 9–12</button>
          <button type="button" data-start="14:00" data-end="17:00">Après-midi 14–17</button>
          <button type="button" data-start="16:00" data-end="21:00">Concert 16–21</button>
          <button type="button" data-start="18:00" data-end="22:00">Soirée 18–22</button>
        </div>
        <label>Photos livrées<input id="photosDelivered" value="100 à 150 photos retouchées"></label>
        <label>Délai livraison<input id="deliveryDelay" value="1 semaine"></label>
      </div>
    </section>

    <section id="contenu" class="panel">
      <h2>Contenu</h2>
      <div class="form-grid">
        <label class="span2">Description courte<textarea id="description">Couverture photographique de l’orchestre pendant le mariage. La prestation ne comprend pas le reportage complet des mariés ni une séance couple dédiée.</textarea></label>
        <label>Distance A/R km<input id="distanceKm" type="number" value="100"></label>
        <label>CHF/km<input id="kmRate" type="number" step="0.05" value="0.60"></label>
        <label>Préparation h<input id="prepHours" type="number" step="0.5" value="0.5"></label>
        <label>Trajet h<input id="travelHours" type="number" step="0.5" value="1.5"></label>
        <label>Tri h<input id="sortHours" type="number" step="0.5" value="1"></label>
        <label>Retouches h<input id="editHours" type="number" step="0.5" value="2.5"></label>
        <label>Livraison h<input id="deliveryHours" type="number" step="0.5" value="0.5"></label>
        <label>Taux horaire CHF/h<input id="hourlyRate" type="number" step="5" value="30"></label>
        <label>Frais matériel CHF<input id="gearCost" type="number" step="5" value="25"></label>
        <label>Mode de prix
          <select id="priceMode">
            <option value="manual" selected>Prix personnalisé</option>
            <option value="auto">Prix calculé automatiquement</option>
          </select>
        </label>
        <label>Prix personnalisé CHF<input id="packagePrice" type="number" value="300"></label>
        <label>Rabais %<input id="discountPercent" type="number" step="1" min="0" max="100" value="0"></label>
        <label>Arrondi CHF
          <select id="rounding">
            <option value="1">1 CHF</option>
            <option value="5">5 CHF</option>
            <option value="10" selected>10 CHF</option>
            <option value="50">50 CHF</option>
          </select>
        </label>
        <label class="check span2"><input id="showCommercialRights" type="checkbox" checked> Afficher les droits d’utilisation commerciale</label>
        <label>Droits commerciaux CHF<input id="commercialRightsFee" type="number" step="10" value="0"></label>
        <label>Libellé droits commerciaux<input id="commercialRightsLabel" value="Utilisation commerciale organique incluse"></label>

        <label class="span2">Inclus<textarea id="included">Prise de vue pendant 5 heures
Tri des photos
Retouches de base
Déplacement Sainte-Croix ↔ Lucens
Galerie privée en ligne
Téléchargement HD</textarea></label>

        <label class="span2">Conditions essentielles<textarea id="terms">Devis valable jusqu’à la date indiquée. Paiement à réception du devis validé ou au plus tard le jour de la prestation, sauf accord contraire. Toute heure supplémentaire ou demande non prévue fera l’objet d’un accord séparé.</textarea></label>
        <label class="check span2"><input id="showHourly" type="checkbox" checked> Afficher le détail du taux horaire dans le devis</label>
      </div>
    </section>

    <section id="designer" class="panel">
      <h2>Designer A4</h2>
      <div class="form-grid">
        <div class="layout-slots span2">
          <h3>Slots de mise en page</h3>
          <div class="slot-row">
            <button type="button" class="slot-save" data-save-slot="1">Sauver</button>
            <button type="button" class="slot-load" data-load-slot="1">Charger slot 1</button>
            <button type="button" class="slot-clear" data-clear-slot="1">Effacer</button>
          </div>
          <div class="slot-row">
            <button type="button" class="slot-save" data-save-slot="2">Sauver</button>
            <button type="button" class="slot-load" data-load-slot="2">Charger slot 2</button>
            <button type="button" class="slot-clear" data-clear-slot="2">Effacer</button>
          </div>
          <div class="slot-row">
            <button type="button" class="slot-save" data-save-slot="3">Sauver</button>
            <button type="button" class="slot-load" data-load-slot="3">Charger slot 3</button>
            <button type="button" class="slot-clear" data-clear-slot="3">Effacer</button>
          </div>
          <p id="slotStatus" class="slot-status">Les slots sont sauvegardés dans ce navigateur.</p>
        </div>

        <label>Mode compact<select id="compactMode"><option value="on" selected>Activé — 1 page A4</option><option value="off">Désactivé — plus aéré</option></select></label>
        <label>Zoom aperçu <span id="docScaleValue"></span><input id="docScale" type="range" min="40" max="250" value="100"></label>
        <label>Marges page <span id="pagePaddingValue"></span><input id="pagePadding" type="range" min="6" max="18" value="9"></label>
        <label>Espacement blocs <span id="blockGapValue"></span><input id="blockGap" type="range" min="3" max="16" value="6"></label>

        <label>Largeur logo <span id="logoWidthValue"></span><input id="logoWidth" type="range" min="70" max="260" value="132"></label>
        <label>Décalage horizontal logo <span id="logoOffsetXValue"></span><input id="logoOffsetX" type="range" min="-200" max="200" value="0"></label>
        <label>Assombrir logo <span id="logoDarkenValue"></span><input id="logoDarken" type="range" min="0" max="100" value="35"></label>
        <label>Mode couleur logo<select id="logoColorMode"><option value="original">Original</option><option value="solid" selected>Couleur choisie</option></select></label>
        <label>Couleur logo<input id="logoTintColor" type="color" value="#3f646a"></label>
        <label>Intensité teinte <span id="logoTintValue"></span><input id="logoTint" type="range" min="0" max="100" value="0"></label>
        
        <label>Position verticale logo <span id="logoTopValue"></span><input id="logoTop" type="range" min="-60" max="35" value="-33"></label>
        <label>Espace logo → premières tuiles <span id="headerGapValue"></span><input id="headerGap" type="range" min="-80" max="24" value="-20"></label>

        <label>Taille DEVIS <span id="titleSizeValue"></span><input id="titleSize" type="range" min="18" max="54" value="28"></label>
        <label>Décalage horizontal titre <span id="titleOffsetXValue"></span><input id="titleOffsetX" type="range" min="-200" max="200" value="0"></label>
        <label>Taille sous-titre <span id="subtitleSizeValue"></span><input id="subtitleSize" type="range" min="8" max="18" value="10"></label>
        <label>Taille texte <span id="bodySizeValue"></span><input id="bodySize" type="range" min="7" max="24" value="10.5" step="0.5"></label>
        <label>Taille tableaux <span id="smallTextSizeValue"></span><input id="smallTextSize" type="range" min="7" max="20" value="9" step="0.5"></label>
        <label>Taille contact <span id="contactSizeValue"></span><input id="contactSize" type="range" min="7" max="22" value="10" step="0.5"></label>

        <label>Couleur principale<input id="mainColor" type="color" value="#3f646a"></label>
        <label>Fond document<input id="paperColor" type="color" value="#ffffff"></label>
        <label>Couleur texte document<input id="documentTextColor" type="color" value="#000000"></label>
        <label>Couleur libellés / champs<input id="fieldLabelColor" type="color" value="#5f6f72"></label>
        <label>Couleur lignes<input id="lineColor" type="color" value="#cbdadc"></label>
        <label>Fond carré total<input id="priceBgColor" type="color" value="#111111"></label>
        <label>Texte carré total<select id="priceTextPreset"><option value="#ffffff" selected>Blanc</option><option value="#000000">Noir</option><option value="custom">Couleur personnalisée</option></select></label>
        <label>Couleur personnalisée texte total<input id="priceTextColor" type="color" value="#ffffff"></label>
        <label>Accent carré total<input id="priceAccentColor" type="color" value="#cbd5d6"></label>
      </div>
    </section>

    <section id="parametres" class="panel">
      <h2>Paramètres THAL</h2>
      <div class="form-grid">
        <label>Nom<input id="companyName" value="THAL Photographie"></label>
        <label>Email<input id="companyEmail" value="thalphotographie@bluewin.ch"></label>
        <label>Téléphone<input id="companyPhone" value="078 745 72 42"></label>
        <label>Site web<input id="companyWebsite" value="www.thalphotographie.ch"></label>
        <label>Adresse / mention<input id="companyAddress" value="Suisse romande"></label>
        <label>Message final<input id="finalNote" value="Merci pour votre confiance."></label>
      </div>
    </section>
  </main>

  <aside class="preview">
    <div class="preview-toolbar"><div class="page-meter" id="previewStatus">A4</div><button id="zoomOut" type="button">−</button><button id="zoom100" type="button">100%</button><button id="zoomIn" type="button">+</button></div>
    <div class="paper-viewport"><div id="document" class="paper"></div></div>
  </aside>
</div>
<script>
window.THAL_INITIAL_QUOTE = <?php echo json_encode($initialQuote, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="js/app.js"></script>
</body>
</html>
