<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/models/Utilisateur.php';
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email']        ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    $utilisateur = getUtilisateurByEmail($email);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        $_SESSION['user'] = [
            'id'    => $utilisateur['id'],
            'nom'   => $utilisateur['nom'],
            'email' => $utilisateur['email'],
            'role'  => $utilisateur['role']
        ];
        header('Location: index.php');
        exit;
    } else {
        $error_message = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CampusFlow — Portail Administratif</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
  --ocean:      #1a4fd4;
  --ocean-dk:   #0d3aab;
  --ocean-dkr:  #082d88;
  --teal:       #0d8fd4;
  --amber:      #e8a020;
  --font:       'Outfit', sans-serif;
  --mono:       'JetBrains Mono', monospace;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body {
  height: 100%; font-family: var(--font);
  background: transparent !important; /* Force la transparence pour laisser voir .bg-scene */
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
}

/* ── Background animé ── */
.bg-scene {
  position: fixed; inset: 0; z-index: 0; overflow: hidden;
  background-color: #050e24; /* fallback */
  background-image: url('assets/images/im.jpg'); /* Enlève le filtre bleu, garde im.jpg sur login */
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  background-attachment: fixed;
}
.bg-scene::before {
  content: ''; position: absolute; inset: 5;
  background:
    radial-gradient(ellipse 80% 60% at 20% 10%, rgba(26,79,212,0.22) 0%, transparent 70%),
    radial-gradient(ellipse 60% 50% at 80% 90%, rgba(13,143,212,0.15) 0%, transparent 70%),
    radial-gradient(ellipse 50% 40% at 50% 50%, rgba(8,45,136,0.3) 0%, transparent 80%);
}
.bg-scene::after {
  content: ''; position: absolute; inset: 0;
  background-image: radial-gradient(rgba(26,79,212,0.12) 1px, transparent 1px);
  background-size: 32px 32px;
}
.orb { position: absolute; border-radius: 50%; filter: blur(60px); pointer-events: none; animation: float-orb 12s ease-in-out infinite alternate; }
.orb-1 { width:400px; height:400px; top:-100px; left:-100px; background:rgba(26,79,212,0.18); animation-duration:15s; }
.orb-2 { width:300px; height:300px; top:-80px; right:-80px; background:rgba(13,143,212,0.12); animation-duration:18s; animation-delay:-5s; }
.orb-3 { width:250px; height:250px; bottom:-60px; left:30%; background:rgba(232,160,32,0.08); animation-duration:20s; animation-delay:-8s; }
@keyframes float-orb { to { transform: translate(40px, 30px) scale(1.15); } }

.deco-line {
  position: absolute; top: 0; left: 50%; transform: translateX(-50%);
  width: 1px; height: 45%;
  background: linear-gradient(180deg, transparent, rgba(26,79,212,0.4), transparent);
  animation: line-pulse 3s ease-in-out infinite;
}
@keyframes line-pulse { 0%,100%{opacity:0.3} 50%{opacity:0.8} }

/* ── Wrapper ── */
.main-wrapper { min-height: 100vh; display: flex; flex-direction: column; position: relative; z-index: 10; width: 100%; }
.content { flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; }

/* ── Card Login ── */
.card-login {
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(26,79,212,0.2);
  border-radius: 24px; padding: 44px 40px 36px;
  width: 100%; max-width: 420px;
  backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
  box-shadow: 0 0 0 1px rgba(26,79,212,0.08), 0 20px 60px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.06);
  text-align: center; color: #fff;
  animation: card-in 0.7s cubic-bezier(0.16,1,0.3,1) forwards;
  position: relative;
}
@keyframes card-in { from{opacity:0;transform:translateY(30px) scale(0.97)} to{opacity:1;transform:translateY(0) scale(1)} }

/* Shimmer top */
.card-shimmer {
  position: absolute; top: 0; left: 20%; right: 20%; height: 2px;
  background: linear-gradient(90deg, transparent, var(--teal) 30%, var(--ocean) 70%, transparent);
  border-radius: 2px; opacity: 0.7; animation: shimmer 4s ease-in-out infinite;
}
@keyframes shimmer { 0%,100%{left:25%;right:25%;opacity:0.4} 50%{left:5%;right:5%;opacity:0.9} }

/* ── Logo ── */
.login-logo {
  width: 72px; height: 72px; margin: 0 auto 18px;
  background: linear-gradient(135deg, var(--ocean), var(--ocean-dkr));
  border-radius: 20px; display: flex; align-items: center; justify-content: center;
  box-shadow: 0 0 0 1px rgba(26,79,212,0.3), 0 8px 32px rgba(26,79,212,0.4), inset 0 1px 0 rgba(255,255,255,0.2);
  animation: logo-pulse 3s ease-in-out infinite;
}
@keyframes logo-pulse {
  0%,100%{box-shadow:0 0 0 1px rgba(26,79,212,0.3),0 8px 32px rgba(26,79,212,0.4),inset 0 1px 0 rgba(255,255,255,0.2)}
  50%{box-shadow:0 0 0 1px rgba(26,79,212,0.5),0 12px 48px rgba(26,79,212,0.6),inset 0 1px 0 rgba(255,255,255,0.2)}
}
.login-logo i { color: #fff; font-size: 1.75rem; }

.login-title { font-weight: 800; font-size: 1.65rem; letter-spacing: -0.03em; color: white; margin-bottom: 5px; }
.login-subtitle { font-size: 0.875rem; color: rgba(255,255,255,0.45); font-family: var(--mono); letter-spacing: 0.04em; margin-bottom: 14px; }

.portal-badge {
  display: inline-flex; align-items: center; gap: 7px;
  background: rgba(26,79,212,0.15); border: 1px solid rgba(26,79,212,0.3);
  border-radius: 50px; padding: 5px 14px; margin-bottom: 28px;
}
.portal-dot { width:6px; height:6px; border-radius:50%; background:var(--teal); animation:live 1.8s ease-in-out infinite; }
@keyframes live { 0%,100%{opacity:1} 50%{opacity:0.2} }
.portal-badge span { font-size:0.72rem; color:rgba(255,255,255,0.6); font-family:var(--mono); letter-spacing:0.08em; text-transform:uppercase; }

/* ── Alerte erreur ── */
.alert-campusflow {
  background: rgba(220,38,38,0.12); border: 1px solid rgba(220,38,38,0.3);
  border-radius: 12px; padding: 12px 16px; margin-bottom: 20px;
  display: flex; align-items: center; gap: 10px; text-align: left;
  animation: alert-in 0.3s ease;
}
@keyframes alert-in { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.alert-campusflow i { color: #f87171; font-size: 0.85rem; flex-shrink: 0; }
.alert-campusflow span { font-size: 0.84rem; color: #fca5a5; }
.alert-campusflow .btn-close { filter: invert(1); opacity: 0.5; margin-left: auto; }

/* ── Séparateur section ── */
.form-sep {
  text-align: left; margin-bottom: 18px;
  font-size: 0.7rem; color: rgba(255,255,255,0.35); font-family: var(--mono);
  letter-spacing: 0.1em; text-transform: uppercase;
}

/* ── Champs ── */
.input-wrap { position: relative; margin-bottom: 14px; }
.input-icon-cf {
  position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
  font-size: 0.78rem; color: var(--ocean); opacity: 0.7; pointer-events: none;
  transition: opacity 0.2s, color 0.2s;
}
.input-wrap:focus-within .input-icon-cf { opacity: 1; color: var(--teal); }
.form-control-cf {
  width: 100%; padding: 13px 14px 13px 42px;
  background: rgba(255,255,255,0.05); border: 1px solid rgba(26,79,212,0.2);
  border-radius: 12px; color: white; font-size: 0.9rem; font-family: var(--font);
  outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  -webkit-appearance: none;
}
.form-control-cf::placeholder { color: rgba(255,255,255,0.3); }
.form-control-cf:focus {
  background: rgba(26,79,212,0.08); border-color: rgba(26,79,212,0.6);
  box-shadow: 0 0 0 3px rgba(26,79,212,0.15), 0 0 20px rgba(26,79,212,0.1);
}
/* Styles Bootstrap invalides */
.form-control-cf.is-invalid { border-color: rgba(220,38,38,0.6) !important; }
.was-validated .form-control-cf:invalid { border-color: rgba(220,38,38,0.6); }

/* Toggle mot de passe */
.pw-toggle {
  position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.3);
  font-size: 0.78rem; transition: color 0.2s; padding: 4px;
}
.pw-toggle:hover { color: rgba(255,255,255,0.7); }

/* ── Bouton submit ── */
.btn-cf-login {
  width: 100%; padding: 14px;
  background: linear-gradient(135deg, var(--ocean), var(--ocean-dk));
  color: white; font-weight: 700; font-size: 0.95rem; font-family: var(--font);
  border: none; border-radius: 12px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 9px;
  box-shadow: 0 4px 24px rgba(26,79,212,0.4), inset 0 1px 0 rgba(255,255,255,0.15);
  transition: transform 0.2s, box-shadow 0.2s; margin-top: 6px; letter-spacing: 0.02em;
}
.btn-cf-login:hover { transform: translateY(-2px); box-shadow: 0 8px 36px rgba(26,79,212,0.55), inset 0 1px 0 rgba(255,255,255,0.15); }
.btn-cf-login:active { transform: translateY(0); }
.btn-cf-login:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

/* ── Footer card ── */
.card-footer-cf {
  text-align: center; margin-top: 24px; padding-top: 20px;
  border-top: 1px solid rgba(26,79,212,0.12);
}
.card-footer-cf p { font-size: 0.78rem; color: rgba(255,255,255,0.3); font-family: var(--mono); }
.card-footer-cf a { color: rgba(96,165,250,0.8); text-decoration: none; transition: color 0.2s; }
.card-footer-cf a:hover { color: var(--teal); }

/* ── Footer page ── */
.footer-login {
  position: relative; z-index: 10; text-align: center;
  padding: 14px 0; font-size: 0.78rem;
  color: rgba(255,255,255,0.2); font-family: var(--mono); letter-spacing: 0.05em;
  border-top: 1px solid rgba(26,79,212,0.08);
}
</style>
</head>
<body>

<!-- Fond animé -->
<div class="bg-scene">
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
  <div class="deco-line"></div>
</div>

<div class="main-wrapper">
  <div class="content">
    <div class="card-login">
      <div class="card-shimmer"></div>

      <!-- Logo & Brand -->
      <div class="login-logo"><i class="fas fa-graduation-cap"></i></div>
      <h1 class="login-title">CampusFlow</h1>
      <p class="login-subtitle">Système de Gestion Universitaire</p>
      <div class="portal-badge">
        <div class="portal-dot"></div>
        <span>Portail Administratif</span>
      </div>

      <!-- Alerte erreur PHP -->
      <?php if (!empty($error_message)): ?>
        <div class="alert-campusflow alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle"></i>
          <span><?= htmlspecialchars($error_message) ?></span>
          <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
      <?php endif; ?>

      <div class="form-sep">Authentification requise</div>

      <!-- Formulaire — champ name="mot_de_passe" conservé comme dans l'original -->
      <form method="post" action="" class="needs-validation" novalidate id="loginForm">

        <div class="input-wrap">
          <i class="fas fa-envelope input-icon-cf"></i>
          <input
            type="email"
            class="form-control-cf"
            id="email"
            name="email"
            placeholder="Adresse e-mail"
            required
            autocomplete="email"
            autofocus
          >
        </div>

        <div class="input-wrap">
          <i class="fas fa-lock input-icon-cf"></i>
          <input
            type="password"
            class="form-control-cf"
            id="mot_de_passe"
            name="mot_de_passe"
            placeholder="Mot de passe"
            required
            autocomplete="current-password"
            style="padding-right:44px"
          >
          <button type="button" class="pw-toggle" onclick="togglePw()" id="pwToggle">
            <i class="fas fa-eye" id="pwIcon"></i>
          </button>
        </div>

        <button type="submit" class="btn-cf-login" id="loginBtn">
          <i class="fas fa-sign-in-alt"></i>
          <span>Accéder au portail</span>
        </button>

      </form>

      <div class="card-footer-cf">
        <p>© 2026 CampusFlow · <a href="landing.php">← Retour à l'accueil</a></p>
      </div>
    </div>
  </div>

  <footer class="footer-login">
    © 2026 — CampusFlow · Projet L2 LIAGE-ISM | +221 78 406 17 91 · info@campusflow.sn
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validation Bootstrap native conservée
(() => {
  'use strict';
  document.querySelectorAll('.needs-validation').forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      form.classList.add('was-validated');
    });
  });
})();

// Toggle affichage mot de passe
function togglePw() {
  const f = document.getElementById('mot_de_passe');
  const i = document.getElementById('pwIcon');
  f.type = f.type === 'password' ? 'text' : 'password';
  i.className = f.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

// Feedback visuel au submit
document.getElementById('loginForm').addEventListener('submit', function() {
  if (this.checkValidity()) {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i><span>Connexion en cours…</span>';
    btn.disabled = true;
  }
});
</script>
</body>
</html>