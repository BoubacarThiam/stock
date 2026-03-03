<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isActive($page) {
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion de Stock — PME Sénégal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* ═══════════════════════════════════════════════
   TOKENS — Bleu Océan · Light Mode
═══════════════════════════════════════════════ */
:root {
  /* Backgrounds */
  --c-bg:           #f0f5fb;
  --c-surface:      #ffffff;
  --c-nav-bg:       rgba(255, 255, 255, 0.88);

  /* Ocean blues */
  --c-ocean:        #1a6cf6;
  --c-ocean-dark:   #0d4fd4;
  --c-ocean-deeper: #0a3ca8;
  --c-ocean-light:  #e8f0fe;
  --c-ocean-dim:    rgba(26, 108, 246, 0.10);
  --c-ocean-glow:   rgba(26, 108, 246, 0.30);

  /* Accent — amber/gold pour contraste chaud */
  --c-amber:        #f59e0b;
  --c-amber-dim:    rgba(245, 158, 11, 0.15);

  /* Teal secondaire */
  --c-teal:         #0ea5e9;
  --c-teal-dim:     rgba(14, 165, 233, 0.12);

  /* Texte */
  --c-text:         #0f1e3d;
  --c-text-soft:    #3d5480;
  --c-muted:        #7f96be;
  --c-faint:        rgba(15, 30, 61, 0.55);

  /* Bordures */
  --c-border:       rgba(26, 108, 246, 0.12);
  --c-border-lit:   rgba(26, 108, 246, 0.35);

  /* Glass */
  --c-glass:        rgba(26, 108, 246, 0.05);
  --c-glass-hover:  rgba(26, 108, 246, 0.09);

  /* Misc */
  --radius-sm: 9px;
  --radius-md: 16px;
  --nav-h:     68px;
  --font:      'Outfit', sans-serif;
  --font-mono: 'JetBrains Mono', monospace;
  --ease:      cubic-bezier(0.16, 1, 0.3, 1);

  /* Shadows */
  --shadow-nav:  0 4px 32px rgba(26,108,246,0.10), 0 1px 0 rgba(26,108,246,0.08);
  --shadow-drop: 0 8px 24px rgba(15,30,61,0.10), 0 32px 64px rgba(15,30,61,0.08);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
  height: 100%;
  background: var(--c-bg);
  font-family: var(--font);
  color: var(--c-text);
}
.wrapper { display: flex; flex-direction: column; min-height: 100vh; }
.content { flex: 1; }

/* ═══════════════════════════════════════════════
   AMBIENT — lumières océan douces
═══════════════════════════════════════════════ */
.ambient {
  position: fixed; inset: 0;
  pointer-events: none; z-index: 0; overflow: hidden;
}
.ambient span {
  position: absolute; border-radius: 50%; filter: blur(90px);
}
.ambient span:nth-child(1) {
  width: 500px; height: 500px;
  top: -200px; left: -100px;
  background: rgba(26, 108, 246, 0.07);
  animation: blob-a 16s ease-in-out infinite alternate;
}
.ambient span:nth-child(2) {
  width: 380px; height: 380px;
  top: -120px; right: -80px;
  background: rgba(14, 165, 233, 0.06);
  animation: blob-b 20s ease-in-out infinite alternate;
}
.ambient span:nth-child(3) {
  width: 280px; height: 280px;
  top: 20px; left: 40%;
  background: rgba(245, 158, 11, 0.04);
  animation: blob-c 24s ease-in-out infinite alternate;
}
@keyframes blob-a { to { transform: translate(70px, 50px) scale(1.2); } }
@keyframes blob-b { to { transform: translate(-60px, 40px) scale(1.15); } }
@keyframes blob-c { to { transform: translate(30px, -20px) scale(1.1); } }

/* ═══════════════════════════════════════════════
   NAVBAR
═══════════════════════════════════════════════ */
.navbar-custom {
  position: sticky; top: 0; z-index: 1050;
  height: var(--nav-h);
  padding: 0 2rem;
  background: var(--c-nav-bg);
  backdrop-filter: blur(24px) saturate(180%);
  -webkit-backdrop-filter: blur(24px) saturate(180%);
  border-bottom: 1px solid var(--c-border);
  box-shadow: var(--shadow-nav);
  animation: nav-in 0.5s var(--ease) forwards;
}
@keyframes nav-in {
  from { opacity: 0; transform: translateY(-12px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ligne shimmer bleue en bas de navbar */
.navbar-custom::after {
  content: '';
  position: absolute;
  bottom: 0; left: 15%; right: 15%; height: 2px;
  background: linear-gradient(90deg,
    transparent,
    var(--c-teal) 25%,
    var(--c-ocean) 50%,
    var(--c-amber) 75%,
    transparent
  );
  animation: shimmer 5s ease-in-out infinite;
  opacity: 0.5;
  border-radius: 2px;
}
@keyframes shimmer {
  0%,100% { left: 25%; right: 25%; opacity: 0.3; }
  50%      { left:  5%; right:  5%; opacity: 0.7; }
}

/* ── BRAND ─────────────────────────────────── */
.navbar-brand {
  display: flex; align-items: center; gap: 12px;
  text-decoration: none; padding: 0; flex-shrink: 0;
}
.brand-cube { position: relative; width: 40px; height: 40px; flex-shrink: 0; }
.brand-cube-face {
  width: 100%; height: 100%;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-ocean-deeper));
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  box-shadow:
    0 0 0 1px rgba(26,108,246,0.25),
    0 4px 20px rgba(26,108,246,0.35),
    inset 0 1px 0 rgba(255,255,255,0.3);
  transition: transform 0.35s var(--ease), box-shadow 0.35s;
}
.brand-cube-face i { color: #fff; font-size: 0.92rem; }
.navbar-brand:hover .brand-cube-face {
  transform: rotate(-8deg) scale(1.08);
  box-shadow:
    0 0 0 1px rgba(26,108,246,0.4),
    0 8px 30px rgba(26,108,246,0.45),
    inset 0 1px 0 rgba(255,255,255,0.3);
}
/* petit badge amber */
.brand-pip {
  position: absolute; top: -3px; right: -3px;
  width: 9px; height: 9px; border-radius: 50%;
  background: var(--c-amber);
  border: 2px solid var(--c-surface);
  box-shadow: 0 0 8px rgba(245,158,11,0.6);
  animation: pip 2.6s ease-in-out infinite;
}
@keyframes pip {
  0%,100% { transform: scale(1); opacity: 1; }
  50%      { transform: scale(1.5); opacity: 0.5; }
}
.brand-text { display: flex; flex-direction: column; line-height: 1; }
.brand-name {
  font-weight: 800; font-size: 1.18rem; letter-spacing: -0.03em;
  background: linear-gradient(110deg, var(--c-text) 40%, var(--c-ocean));
  -webkit-background-clip: text; background-clip: text;
  -webkit-text-fill-color: transparent;
}
.brand-sub {
  font-family: var(--font-mono);
  font-size: 0.59rem; letter-spacing: 0.13em;
  color: var(--c-muted); text-transform: uppercase; margin-top: 2px;
}

/* ── NAV LINKS ─────────────────────────────── */
.navbar-nav .nav-link {
  font-size: 0.875rem; font-weight: 500;
  color: var(--c-text-soft) !important;
  padding: 0.42rem 0.88rem !important;
  border-radius: var(--radius-sm);
  display: flex; align-items: center; gap: 7px;
  position: relative; white-space: nowrap;
  transition: color 0.2s, background 0.2s;
}
.navbar-nav .nav-link .nav-icon {
  font-size: 0.78rem;
  color: var(--c-muted);
  transition: transform 0.25s var(--ease), color 0.2s;
}
.navbar-nav .nav-link:hover {
  color: var(--c-ocean) !important;
  background: var(--c-ocean-dim);
}
.navbar-nav .nav-link:hover .nav-icon {
  color: var(--c-ocean);
  transform: translateY(-1.5px);
}
.navbar-nav .nav-link.active {
  color: var(--c-ocean) !important;
  background: var(--c-ocean-dim);
  font-weight: 600;
}
.navbar-nav .nav-link.active .nav-icon { color: var(--c-ocean); }

/* pip actif */
.navbar-nav .nav-link.active::after {
  content: '';
  position: absolute;
  bottom: 2px; left: 50%; transform: translateX(-50%);
  width: 18px; height: 2px;
  background: var(--c-ocean);
  border-radius: 2px;
  box-shadow: 0 0 6px var(--c-ocean-glow);
}

/* Dashboard — badge spécial */
.nav-badge {
  background: linear-gradient(135deg, var(--c-ocean), var(--c-teal)) !important;
  color: #fff !important;
  font-weight: 600 !important;
  box-shadow: 0 2px 12px rgba(26,108,246,0.30) !important;
  border: none !important;
}
.nav-badge .nav-icon { color: rgba(255,255,255,0.85) !important; }
.nav-badge:hover {
  background: linear-gradient(135deg, var(--c-ocean-dark), var(--c-ocean)) !important;
  color: #fff !important;
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(26,108,246,0.40) !important;
}
.nav-badge::after { display: none !important; }

/* caret custom */
.dropdown-toggle::after { display: none !important; }
.caret {
  font-size: 0.6rem; color: var(--c-muted); opacity: 0.7;
  transition: transform 0.25s var(--ease), opacity 0.2s;
}
.dropdown.show .caret { transform: rotate(180deg); opacity: 1; color: var(--c-ocean); }

/* ── DROPDOWN ──────────────────────────────── */
.dropdown-menu {
  background: #ffffff !important;
  border: 1px solid var(--c-border) !important;
  border-radius: var(--radius-md) !important;
  padding: 8px !important;
  margin-top: 8px !important;
  min-width: 228px;
  box-shadow: var(--shadow-drop) !important;
  animation: dd-in 0.2s var(--ease) forwards;
}
@keyframes dd-in {
  from { opacity: 0; transform: translateY(-8px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* séparateur section */
.dd-section {
  font-family: var(--font-mono);
  font-size: 0.62rem; letter-spacing: 0.13em;
  text-transform: uppercase;
  color: var(--c-muted);
  padding: 5px 10px 3px;
  display: block;
}

.dropdown-item {
  font-size: 0.87rem; font-weight: 400;
  color: var(--c-text-soft) !important;
  padding: 8px 10px !important;
  border-radius: var(--radius-sm) !important;
  display: flex !important; align-items: center; gap: 10px;
  position: relative; overflow: hidden;
  transition: background 0.15s, color 0.15s, padding-left 0.18s;
}
.dropdown-item::before {
  content: '';
  position: absolute; left: 0; top: 18%; bottom: 18%;
  width: 0; background: var(--c-ocean);
  border-radius: 4px;
  transition: width 0.18s var(--ease);
}
.dropdown-item:hover::before { width: 2.5px; }
.dropdown-item:hover {
  background: var(--c-ocean-dim) !important;
  color: var(--c-ocean) !important;
  padding-left: 15px !important;
}
.dd-icon {
  width: 30px; height: 30px;
  border-radius: 8px;
  background: var(--c-ocean-light);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; font-size: 0.73rem;
  color: var(--c-ocean);
  transition: background 0.15s, box-shadow 0.15s, transform 0.2s;
}
.dropdown-item:hover .dd-icon {
  background: var(--c-ocean-dim);
  box-shadow: 0 0 10px var(--c-ocean-glow);
  transform: scale(1.08);
}
.dropdown-divider { border-color: var(--c-border) !important; margin: 6px 0 !important; }

/* ── CLOCK CHIP ────────────────────────────── */
.clock-chip {
  display: flex; align-items: center; gap: 7px;
  background: var(--c-ocean-light);
  border: 1px solid var(--c-border);
  border-radius: 50px;
  padding: 5px 13px;
  font-family: var(--font-mono);
  font-size: 0.73rem; color: var(--c-ocean);
  letter-spacing: 0.05em;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.clock-chip:hover {
  border-color: var(--c-border-lit);
  box-shadow: 0 0 12px var(--c-ocean-glow);
}
.live-dot {
  width: 6px; height: 6px; border-radius: 50%;
  background: var(--c-teal);
  box-shadow: 0 0 6px rgba(14,165,233,0.7);
  animation: live 1.8s ease-in-out infinite;
}
@keyframes live { 0%,100% { opacity: 1; } 50% { opacity: 0.25; } }

/* ── DIVIDER ───────────────────────────────── */
.vdiv {
  width: 1px; height: 22px;
  background: var(--c-border);
  flex-shrink: 0;
}

/* ── USER PILL ─────────────────────────────── */
.user-pill {
  display: flex; align-items: center; gap: 9px;
  background: var(--c-ocean-light);
  border: 1px solid var(--c-border);
  border-radius: 50px;
  padding: 4px 13px 4px 4px;
  cursor: pointer; text-decoration: none;
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.user-pill:hover {
  border-color: var(--c-border-lit);
  background: rgba(26,108,246,0.08);
  box-shadow: 0 0 20px var(--c-ocean-glow);
}
.u-avatar {
  width: 32px; height: 32px; border-radius: 50%;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-teal));
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 0.76rem; color: #fff;
  position: relative; flex-shrink: 0;
  box-shadow: 0 0 0 2px rgba(26,108,246,0.25);
}
.u-ring {
  position: absolute; inset: -3px; border-radius: 50%;
  border: 1.5px solid transparent;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-amber)) border-box;
  -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: destination-out; mask-composite: exclude;
  animation: spin 8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.u-info { display: flex; flex-direction: column; line-height: 1.25; }
.u-name {
  font-size: 0.83rem; font-weight: 600; color: var(--c-text);
  max-width: 115px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.u-role {
  font-family: var(--font-mono);
  font-size: 0.6rem; color: var(--c-ocean); letter-spacing: 0.06em;
  opacity: 0.8;
}
.u-caret {
  font-size: 0.58rem; color: var(--c-muted);
  transition: transform 0.25s var(--ease), color 0.2s;
}
.dropdown.show .u-caret { transform: rotate(180deg); color: var(--c-ocean); }

/* ── LOGIN BTN ─────────────────────────────── */
.btn-connect {
  display: inline-flex; align-items: center; gap: 8px;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-ocean-dark));
  color: #fff !important; font-weight: 700; font-size: 0.83rem;
  border-radius: var(--radius-sm); padding: 8px 20px;
  text-decoration: none; letter-spacing: 0.02em;
  box-shadow: 0 2px 16px rgba(26,108,246,0.35), inset 0 1px 0 rgba(255,255,255,0.2);
  transition: transform 0.2s, box-shadow 0.2s;
}
.btn-connect:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(26,108,246,0.45), inset 0 1px 0 rgba(255,255,255,0.2);
  color: #fff !important;
}

/* ── SCROLL BAR ────────────────────────────── */
.scroll-bar {
  position: absolute; bottom: 0; left: 0;
  height: 2.5px; width: 0%;
  background: linear-gradient(90deg, var(--c-ocean), var(--c-teal), var(--c-amber));
  background-size: 200% 100%;
  box-shadow: 0 0 8px var(--c-ocean-glow);
  transition: width 0.08s linear;
  animation: bar-shift 3s linear infinite;
}
@keyframes bar-shift { to { background-position: 200% 0%; } }

/* ── TOGGLER ───────────────────────────────── */
.navbar-toggler {
  border: 1px solid var(--c-border) !important;
  border-radius: var(--radius-sm) !important;
  padding: 7px 11px;
  background: var(--c-ocean-light) !important;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.navbar-toggler:hover {
  border-color: var(--c-border-lit) !important;
  box-shadow: 0 0 12px var(--c-ocean-glow) !important;
}
.navbar-toggler:focus { box-shadow: none !important; }
.t-bars { display: flex; flex-direction: column; gap: 4px; width: 18px; }
.t-bar { height: 1.5px; border-radius: 2px; background: var(--c-ocean); transition: all 0.3s; }
.t-bar:nth-child(2) { width: 70%; opacity: 0.6; }
.t-bar:nth-child(3) { width: 45%; opacity: 0.35; }

/* ── STAGGER ANIMATION ─────────────────────── */
.navbar-nav > .nav-item { animation: item-in 0.4s var(--ease) both; }
.navbar-nav > .nav-item:nth-child(1) { animation-delay: 0.06s; }
.navbar-nav > .nav-item:nth-child(2) { animation-delay: 0.11s; }
.navbar-nav > .nav-item:nth-child(3) { animation-delay: 0.16s; }
.navbar-nav > .nav-item:nth-child(4) { animation-delay: 0.21s; }
.navbar-nav > .nav-item:nth-child(5) { animation-delay: 0.26s; }
@keyframes item-in {
  from { opacity: 0; transform: translateY(7px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── RESPONSIVE ────────────────────────────── */
@media (max-width: 991px) {
  .navbar-custom { height: auto; min-height: var(--nav-h); padding: 10px 1rem; }
  .navbar-collapse {
    background: rgba(255,255,255,0.97);
    backdrop-filter: blur(20px);
    border: 1px solid var(--c-border);
    border-radius: var(--radius-md);
    padding: 14px; margin-top: 10px;
    box-shadow: 0 20px 60px rgba(26,108,246,0.12);
    animation: dd-in 0.2s var(--ease) forwards;
  }
  .navbar-nav .nav-link { padding: 0.58rem 0.85rem !important; }
  .dropdown-menu {
    background: var(--c-ocean-light) !important;
    border: none !important; box-shadow: none !important;
    padding-left: 14px !important; margin-top: 4px !important;
    animation: none;
  }
  .clock-chip, .vdiv, .u-role { display: none !important; }
}
</style>
</head>
<body>

<!-- Ambient ocean light -->
<div class="ambient" aria-hidden="true"><span></span><span></span><span></span></div>

<div class="wrapper">

<nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
  <div class="container-fluid align-items-center gap-2">

    <!-- ── BRAND ── -->
    <a class="navbar-brand" href="index.php">
      <div class="brand-cube">
        <div class="brand-cube-face"><i class="fas fa-boxes"></i></div>
        <div class="brand-pip"></div>
      </div>
      <div class="brand-text">
        <span class="brand-name">StockPME</span>
        <span class="brand-sub">Sénégal · v2.0</span>
      </div>
    </a>

    <!-- Mobile toggler -->
    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Menu">
      <div class="t-bars">
        <div class="t-bar"></div>
        <div class="t-bar"></div>
        <div class="t-bar"></div>
      </div>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto align-items-lg-center gap-lg-1 mt-2 mt-lg-0">

        <!-- STOCK -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= isActive('index.php') ?>" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-layer-group nav-icon"></i> Stock
            <i class="fas fa-chevron-down caret"></i>
          </a>
          <ul class="dropdown-menu">
            <span class="dd-section">Inventaire</span>
            <li><a class="dropdown-item" href="index.php">
              <span class="dd-icon"><i class="fas fa-list"></i></span> Liste des Produits
            </a></li>
            <li><a class="dropdown-item" href="form-produit.php">
              <span class="dd-icon"><i class="fas fa-plus-circle"></i></span> Ajouter un Produit
            </a></li>
          </ul>
        </li>

        <!-- FOURNISSEURS -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-truck nav-icon"></i> Fournisseurs
            <i class="fas fa-chevron-down caret"></i>
          </a>
          <ul class="dropdown-menu">
            <span class="dd-section">Partenaires</span>
            <li><a class="dropdown-item" href="gestion-fournisseurs.php">
              <span class="dd-icon"><i class="fas fa-list"></i></span> Liste des Fournisseurs
            </a></li>
            <li><a class="dropdown-item" href="form-fournisseur.php">
              <span class="dd-icon"><i class="fas fa-plus-circle"></i></span> Ajouter un Fournisseur
            </a></li>
          </ul>
        </li>

        <!-- PRODUITS -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= isActive('form-produit.php') ?>" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-box nav-icon"></i> Produits
            <i class="fas fa-chevron-down caret"></i>
          </a>
          <ul class="dropdown-menu">
            <span class="dd-section">Catalogue</span>
            <li><a class="dropdown-item" href="form-produit.php">
              <span class="dd-icon"><i class="fas fa-plus"></i></span> Ajouter un Produit
            </a></li>
            <li><a class="dropdown-item" href="index.php">
              <span class="dd-icon"><i class="fas fa-list"></i></span> Liste des Produits
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="categories.php">
              <span class="dd-icon"><i class="fas fa-tags"></i></span> Gestion des Catégories
            </a></li>
          </ul>
        </li>

        <!-- VENTES -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= isActive('form-vente.php') ?>" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-cash-register nav-icon"></i> Ventes
            <i class="fas fa-chevron-down caret"></i>
          </a>
          <ul class="dropdown-menu">
            <span class="dd-section">Transactions</span>
            <li><a class="dropdown-item" href="form-vente.php">
              <span class="dd-icon"><i class="fas fa-plus-circle"></i></span> Enregistrer une Vente
            </a></li>
            <li><a class="dropdown-item" href="historique-ventes.php">
              <span class="dd-icon"><i class="fas fa-history"></i></span> Historique des Ventes
            </a></li>
          </ul>
        </li>

        <!-- DASHBOARD -->
        <li class="nav-item">
          <a class="nav-link nav-badge <?= isActive('dashboard.php') ?>" href="dashboard.php">
            <i class="fas fa-chart-line nav-icon"></i> Dashboard
          </a>
        </li>

      </ul>

      <!-- ── RIGHT SIDE ── -->
      <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">

        <!-- Horloge live -->
        <div class="clock-chip d-none d-lg-flex">
          <div class="live-dot"></div>
          <span id="liveClock">--:--:--</span>
        </div>

        <div class="vdiv d-none d-lg-flex"></div>

        <!-- Utilisateur ou Connexion -->
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item dropdown list-unstyled">
            <a class="user-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <div class="u-avatar">
                <div class="u-ring"></div>
                <?= strtoupper(substr(htmlspecialchars($_SESSION['user']['nom']), 0, 2)) ?>
              </div>
              <div class="u-info">
                <span class="u-name"><?= htmlspecialchars($_SESSION['user']['nom']) ?></span>
                <span class="u-role">Gestionnaire</span>
              </div>
              <i class="fas fa-chevron-down u-caret"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <span class="dd-section">Mon compte</span>
              <li><a class="dropdown-item" href="logout.php">
                <span class="dd-icon"><i class="fas fa-sign-out-alt"></i></span> Déconnexion
              </a></li>
            </ul>
          </li>
        <?php else: ?>
          <a class="btn-connect" href="login.php">
            <i class="fas fa-sign-in-alt"></i> Connexion
          </a>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- Barre de progression -->
  <div class="scroll-bar" id="scrollBar"></div>
</nav>

<div class="content container mt-4">
<!-- Fin du code header.php -->

<script>
// Horloge en temps réel
(function tick() {
  const el = document.getElementById('liveClock');
  if (el) el.textContent = new Date().toLocaleTimeString('fr-FR');
  setTimeout(tick, 1000);
})();

// Barre de scroll
window.addEventListener('scroll', () => {
  const s = document.documentElement;
  const pct = s.scrollHeight - s.clientHeight > 0
    ? (s.scrollTop / (s.scrollHeight - s.clientHeight)) * 100 : 0;
  document.getElementById('scrollBar').style.width = pct + '%';
}, { passive: true });

// Ombre navbar au scroll
window.addEventListener('scroll', () => {
  document.getElementById('mainNav').style.boxShadow = window.scrollY > 10
    ? '0 6px 40px rgba(26,108,246,0.18)'
    : '0 4px 32px rgba(26,108,246,0.10), 0 1px 0 rgba(26,108,246,0.08)';
}, { passive: true });
</script>