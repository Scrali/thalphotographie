<style>
    :root{
      --bg:#050816;
      --bg2:#0b1224;
      --panel:rgba(255,255,255,.065);
      --panel2:rgba(255,255,255,.04);
      --stroke:rgba(255,255,255,.11);
      --stroke-strong:rgba(255,255,255,.20);
      --text:rgba(255,255,255,.96);
      --muted:rgba(255,255,255,.72);
      --muted2:rgba(255,255,255,.52);
      --accent:#7cb8ff;
      --accent2:#b7d7ff;
      --glow:rgba(124,184,255,.22);
      --shadow:0 12px 36px rgba(0,0,0,.30), 0 34px 90px rgba(0,0,0,.46);
      --radius:20px;
      --radius2:28px;
      --max:1180px;

      --safe-top:env(safe-area-inset-top, 0px);
      --safe-right:env(safe-area-inset-right, 0px);
      --safe-bottom:env(safe-area-inset-bottom, 0px);
      --safe-left:env(safe-area-inset-left, 0px);

      --pad:clamp(14px, 2.6vw, 24px);
      --gap:clamp(14px, 2.4vw, 22px);
      --h1:clamp(34px, 6vw, 56px);
      --lead:clamp(16px, 2.4vw, 19px);
    }

    *{box-sizing:border-box; max-width:100%;}
    html,body{height:100%;}
    html{-webkit-text-size-adjust:100%; scroll-behavior:smooth;}
    body{
      margin:0;
      font-family:ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
      color:var(--text);
      background:
        radial-gradient(circle at 8% 6%, rgba(124,184,255,.20), transparent 0 32%),
        radial-gradient(circle at 92% 12%, rgba(211,237,245,.10), transparent 0 28%),
        radial-gradient(circle at 80% 88%, rgba(37,211,102,.055), transparent 0 26%),
        radial-gradient(circle at 35% 100%, rgba(124,184,255,.08), transparent 0 36%),
        linear-gradient(180deg, #020611 0%, #061020 42%, #020611 100%);
      background-attachment:fixed;
      overflow-x:hidden;
      padding-top:var(--safe-top);
      padding-right:var(--safe-right);
      padding-bottom:calc(var(--safe-bottom) + 10px);
      padding-left:var(--safe-left);
    }

    a{color:inherit; text-decoration:none;}
    .wrap{max-width:var(--max); margin:0 auto; padding:var(--pad) var(--pad) 70px;}

    .nav{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      padding:10px 12px;
      border:1px solid rgba(255,255,255,.11);
      background:rgba(8,13,28,.62);
      border-radius:999px;
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
      box-shadow:0 14px 45px rgba(0,0,0,.28), 0 0 0 1px rgba(124,184,255,.04) inset;
      position:sticky;
      top:calc(10px + var(--safe-top));
      z-index:50;
    }

    .brand{display:flex; align-items:center; gap:8px; padding:6px 10px; min-width:0; flex-shrink:0;}
    .brand img{
      height:64px;
      width:auto;
      display:block;
      flex-shrink:0;
      filter:drop-shadow(0 0 15px rgba(124,184,255,.25)) drop-shadow(0 6px 15px rgba(0,0,0,.45));
    }
    .brand .name{display:flex; align-items:center; gap:14px;}
    .brandTitle{color:#d3edf5; font-size:26px; font-weight:900; line-height:1;}
    .divider{width:1px; height:26px; background:rgba(211,237,245,.35); position:relative; top:2px;}
    .subtitle{color:rgba(211,237,245,.75); font-size:14px; font-weight:600; line-height:1; position:relative; top:1px;}

    .navlinks{display:flex; align-items:center; gap:8px; padding:6px 8px; flex-wrap:wrap; justify-content:flex-end;}

    .chip,.btn,.ghost{
      min-height:40px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      border-radius:999px;
      font-size:13px;
      font-family:inherit;
      transition:transform .16s ease, background .2s ease, border-color .2s ease, filter .2s ease;
      cursor:pointer;
    }

    .chip{padding:9px 13px; border:1px solid var(--stroke); background:rgba(255,255,255,.045); color:var(--muted); font-weight:650;}
    .chip:hover{transform:translateY(-1px); background:rgba(255,255,255,.075); border-color:rgba(255,255,255,.20);}
    .chip.active{color:var(--text); border-color:rgba(124,184,255,.45); background:rgba(124,184,255,.12);}

    .navDropdown{position:relative;}
    .navDropdownToggle{gap:6px; border:1px solid var(--stroke); font-family:inherit;}
    .navDropdownToggle .caret{font-size:10px; transition:transform .2s ease; display:inline-block;}
    .navDropdownToggle[aria-expanded="true"] .caret{transform:rotate(180deg);}
    .navDropdownMenu{
      position:absolute; top:calc(100% + 8px); left:50%; transform:translateX(-50%);
      min-width:190px; padding:8px; display:none; flex-direction:column; gap:2px;
      border-radius:16px; border:1px solid rgba(255,255,255,.14);
      background:rgba(8,13,28,.97); backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px);
      box-shadow:0 14px 45px rgba(0,0,0,.38); z-index:60;
    }
    .navDropdownMenu.open{display:flex;}
    .navDropdownMenu a{padding:9px 12px; border-radius:10px; color:var(--muted); font-weight:650; font-size:13px; text-align:left;}
    .navDropdownMenu a:hover{background:rgba(255,255,255,.06); color:var(--text);}
    .navDropdownMenu a.active{color:var(--text); background:rgba(124,184,255,.14);}

    .btn{padding:9px 15px; border:1px solid rgba(124,184,255,.44); background:linear-gradient(180deg, rgba(124,184,255,.34), rgba(59,130,246,.18)); color:var(--text); font-weight:800; box-shadow:0 0 30px rgba(124,184,255,.08) inset; border-width:1px; border-style:solid;}
    .btn:hover{transform:translateY(-1px); filter:brightness(1.08); border-color:rgba(124,184,255,.72);}

    .ghost{padding:9px 15px; border:1px solid var(--stroke); background:rgba(255,255,255,.045); color:var(--text); font-weight:800;}
    .ghost:hover{transform:translateY(-1px); background:rgba(255,255,255,.075);}

    .card{
      border:1px solid rgba(255,255,255,.09);
      background:linear-gradient(180deg, rgba(255,255,255,.085), rgba(255,255,255,.035));
      border-radius:var(--radius2);
      box-shadow:var(--shadow);
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
    }

    .pageHero{padding:clamp(26px, 4.4vw, 46px); margin:var(--gap) 0;}
    .pageKicker{
      display:inline-flex; align-items:center; width:max-content; margin:0 0 16px; padding:8px 12px;
      border-radius:999px; border:1px solid rgba(124,184,255,.24); background:rgba(124,184,255,.12);
      color:#d3edf5; font-size:12px; font-weight:900; letter-spacing:.12em; text-transform:uppercase;
    }
    .pageHero h1{margin:0 0 14px; font-size:var(--h1); line-height:1.05; letter-spacing:-1px;}
    .pageHero .lead{margin:0; color:var(--muted); max-width:70ch; line-height:1.6; font-size:var(--lead);}

    .actions{display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;}

    .sectionTitle{margin:22px 2px 12px; color:var(--muted); font-weight:800; font-size:13px; letter-spacing:.14em; text-transform:uppercase;}

    /* Cartes de formules (Portraits / Reportages / Professionnels) */
    .priceGrid{display:grid; grid-template-columns:repeat(3,1fr); gap:var(--gap); align-items:stretch;}
    .priceCard{
      padding:24px; border-radius:var(--radius); border:1px solid rgba(255,255,255,.09);
      background:linear-gradient(180deg, rgba(255,255,255,.075), rgba(255,255,255,.032));
      box-shadow:0 8px 24px rgba(0,0,0,.18);
      display:flex; flex-direction:column; gap:14px; min-width:0;
      transition:transform .25s ease, border-color .25s ease, box-shadow .25s ease;
    }
    .priceCard:hover{transform:translateY(-4px); border-color:rgba(124,184,255,.30); box-shadow:0 12px 30px rgba(0,0,0,.28), 0 0 40px rgba(124,184,255,.08);}
    .priceCard h3{margin:0; font-size:21px; color:#d3edf5; letter-spacing:-.2px;}
    .priceCard .priceDuration{color:var(--muted2); font-size:13px; font-weight:700;}
    .priceCard .priceValue{color:#d3edf5; font-size:28px; font-weight:900; margin:2px 0 0;}
    .priceCard .priceValue small{font-size:14px; color:var(--muted); font-weight:700;}
    .priceCard ul{margin:0; padding:0; list-style:none; display:grid; gap:8px; color:var(--muted); font-size:14px; line-height:1.4;}
    .priceCard ul li{display:flex; gap:8px; align-items:flex-start;}
    .priceCard ul li::before{content:"✓"; color:var(--accent); font-weight:900; flex:none;}
    .priceCard .priceCta{margin-top:auto;}

    .priceBadge{
      display:inline-flex; align-items:center; gap:6px; max-width:100%;
      padding:7px 12px; border-radius:999px; font-size:12px; font-weight:850;
      color:#dff3ff; border:1px solid rgba(124,184,255,.40); background:rgba(124,184,255,.14);
      letter-spacing:.02em; line-height:1.35; text-align:left;
    }

    .highlightBlock{
      margin-top:var(--gap); padding:24px; border-radius:var(--radius); text-align:center;
      border:1px solid rgba(124,184,255,.32);
      background:linear-gradient(180deg, rgba(124,184,255,.14), rgba(124,184,255,.04));
      box-shadow:0 0 40px rgba(124,184,255,.08) inset;
    }
    .highlightBlock h3{margin:0 0 8px; font-size:22px; color:#d3edf5;}
    .highlightBlock p{margin:0 0 16px; color:var(--muted); line-height:1.55;}

    .cardNote{margin:14px 2px 0; color:var(--muted2); font-size:14px; line-height:1.55;}

    .identityBox{padding:clamp(22px,4vw,34px); display:grid; gap:20px;}
    .identityPrices{display:grid; grid-template-columns:repeat(2,1fr); gap:14px;}
    .identityPriceItem{padding:18px; border-radius:16px; border:1px solid rgba(255,255,255,.09); background:rgba(255,255,255,.04); text-align:center;}
    .identityPriceItem .val{font-size:30px; font-weight:900; color:#d3edf5;}
    .identityPriceItem .lbl{color:var(--muted); font-size:14px; margin-top:4px;}
    .identityIncluded{display:grid; gap:8px; color:var(--muted); font-size:15px; line-height:1.5; margin:0; padding:0; list-style:none;}
    .identityIncluded li{display:flex; gap:8px; align-items:flex-start;}
    .identityIncluded li::before{content:"✓"; color:var(--accent); font-weight:900; flex:none;}

    .placeholderBlock{padding:20px; border-radius:var(--radius); border:1px dashed rgba(255,255,255,.28); background:rgba(255,255,255,.03); color:var(--muted); font-style:italic; line-height:1.6;}

    .disclaimer{margin-top:var(--gap); color:var(--muted2); font-size:12.5px; text-align:center; line-height:1.5;}

    .footer{margin-top:26px; color:var(--muted2); font-size:13px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:10px;}
    .footerLinks{display:flex; justify-content:center; flex-wrap:wrap; gap:10px;}
    .footerLink{border:0; padding:0; background:transparent; color:rgba(211,237,245,.72); font:inherit; cursor:pointer; text-decoration:underline; text-underline-offset:4px;}
    .footerLink:hover{color:#d3edf5;}

    .legalModal{position:fixed; inset:0; z-index:200; display:none; align-items:center; justify-content:center; padding:calc(var(--safe-top) + 18px) calc(var(--safe-right) + 18px) calc(var(--safe-bottom) + 18px) calc(var(--safe-left) + 18px); background:rgba(2,6,17,.62); backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px);}
    .legalModal.open{display:flex;}
    .legalBox{width:min(760px,100%); max-height:min(78vh,760px); overflow:auto; border:1px solid rgba(255,255,255,.14); border-radius:26px; background:linear-gradient(180deg, rgba(12,18,34,.88), rgba(5,8,22,.82)); box-shadow:0 24px 80px rgba(0,0,0,.55); padding:clamp(20px,4vw,34px);}
    .legalHead{display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:18px;}
    .legalHead h2{margin:0; color:#d3edf5; font-size:clamp(22px,3vw,32px);}
    .legalClose{width:42px; height:42px; border-radius:999px; border:1px solid rgba(255,255,255,.14); background:rgba(255,255,255,.055); color:var(--text); font-size:22px; cursor:pointer;}
    .legalContent{color:rgba(255,255,255,.72); font-size:14px; line-height:1.7;}
    .legalContent h3{color:#d3edf5; margin:22px 0 8px; font-size:17px;}
    .legalContent p{margin:0 0 12px;}

    .reveal{opacity:0; transform:translateY(10px); transition:opacity .6s ease, transform .6s ease;}
    .reveal.on{opacity:1; transform:translateY(0);}

    @media (max-width:900px){
      .priceGrid{grid-template-columns:1fr;}
      .identityPrices{grid-template-columns:1fr;}
    }

    @media (max-width:620px){
      .wrap{padding:10px 14px 60px;}
      .nav{border-radius:28px; padding:10px 12px; gap:10px;}
      .brand{padding:0;}
      .brand img{height:48px;}
      .brand .name{display:none;}
      .navlinks{display:flex; flex-wrap:wrap; justify-content:flex-end; gap:7px; padding:0;}
      .navlinks .chip,.navlinks .btn{min-height:34px; padding:7px 11px; font-size:12px;}
    }

    @media (prefers-reduced-motion:reduce){
      *{animation-duration:.001ms!important; animation-iteration-count:1!important; transition-duration:.001ms!important; scroll-behavior:auto!important;}
    }
  </style>
