<?php
/*
 * ─────────────────────────────────────────────────────────────
 *  CampusFlow — views/dashboard/dashboard.php
 *  Vue du tableau de bord universitaire
 *  Variables disponibles (injectées depuis public/dashboard.php) :
 *    $totalInscriptions  → getValeurTotaleStock()
 *    $coursNonAssignes   → getNombreProduitsEnAlerte()
 *    $totalFrais         → getProfitPotentielTotal()
 *    $topCours           → getTopProduitsVendus()
 * ─────────────────────────────────────────────────────────────
 */
?>

<style>
/* ═══════════════════════════════════════
   DASHBOARD UNIVERSITAIRE — CampusFlow
═══════════════════════════════════════ */

/* ── Page header ── */
.dash-page-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  flex-wrap: wrap; gap: 16px; margin-bottom: 32px;
}
.dash-page-title { font-weight: 800; font-size: 2rem; letter-spacing: -0.04em; color: var(--c-text); line-height: 1.1; }
.dash-page-title span { color: var(--c-ocean); }
.dash-page-sub { font-size: 0.875rem; color: var(--c-muted); margin-top: 5px; font-family: var(--font-mono); }
.dash-live-chip {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(26,79,212,0.08); border: 1px solid var(--c-border);
  border-radius: 50px; padding: 7px 16px;
}
.dash-live-dot { width: 7px; height: 7px; border-radius: 50%; background: #34d399; box-shadow: 0 0 8px rgba(52,211,153,0.6); animation: live-blink 1.8s ease-in-out infinite; }
@keyframes live-blink { 0%,100%{opacity:1} 50%{opacity:0.2} }
.dash-live-chip span { font-size: 0.75rem; color: var(--c-text-soft); font-family: var(--font-mono); letter-spacing: 0.05em; }

/* ── Grille KPI ── */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
.kpi-card {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 18px; padding: 24px 22px; position: relative; overflow: hidden;
  transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
  animation: kpi-in 0.5s cubic-bezier(0.16,1,0.3,1) both;
}
.kpi-card:hover { border-color: var(--c-border-lit); box-shadow: 0 8px 32px var(--c-ocean-glow); transform: translateY(-2px); }
.kpi-card:nth-child(1){animation-delay:0.05s}
.kpi-card:nth-child(2){animation-delay:0.10s}
.kpi-card:nth-child(3){animation-delay:0.15s}
.kpi-card:nth-child(4){animation-delay:0.20s}
@keyframes kpi-in { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

/* Barre de couleur top */
.kpi-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  border-radius: 18px 18px 0 0;
}
.kpi-card.blue::before  { background: linear-gradient(90deg, var(--c-ocean), var(--c-teal)); }
.kpi-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
.kpi-card.amber::before { background: linear-gradient(90deg, var(--c-amber), #f59e0b); }
.kpi-card.teal::before  { background: linear-gradient(90deg, var(--c-teal), #38bdf8); }

.kpi-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.kpi-icon {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
}
.kpi-icon.blue  { background: var(--c-ocean-light); color: var(--c-ocean); }
.kpi-icon.green { background: rgba(16,185,129,0.1); color: #10b981; }
.kpi-icon.amber { background: rgba(232,160,32,0.12); color: var(--c-amber); }
.kpi-icon.teal  { background: var(--c-teal-dim); color: var(--c-teal); }

.kpi-badge {
  font-size: 0.68rem; font-weight: 700; font-family: var(--font-mono);
  padding: 3px 9px; border-radius: 20px; letter-spacing: 0.04em;
}
.kpi-badge.up   { background: rgba(52,211,153,0.12); color: #10b981; }
.kpi-badge.warn { background: rgba(232,160,32,0.12); color: var(--c-amber); }
.kpi-badge.info { background: var(--c-ocean-dim); color: var(--c-ocean); }

.kpi-value { font-size: 2.2rem; font-weight: 800; letter-spacing: -0.04em; color: var(--c-text); line-height: 1; margin-bottom: 5px; }
.kpi-label { font-size: 0.82rem; color: var(--c-muted); font-weight: 500; margin-bottom: 2px; }
.kpi-sub   { font-size: 0.75rem; color: var(--c-muted); font-family: var(--font-mono); opacity: 0.7; }

/* ── Grille inférieure ── */
.dash-bottom { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }

/* ── Panel générique ── */
.dash-panel {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 18px; overflow: hidden;
  animation: kpi-in 0.5s cubic-bezier(0.16,1,0.3,1) 0.25s both;
}
.panel-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 22px; border-bottom: 1px solid var(--c-border);
}
.panel-title { font-size: 0.92rem; font-weight: 700; color: var(--c-text); display: flex; align-items: center; gap: 9px; }
.panel-title i { color: var(--c-ocean); font-size: 0.85rem; }
.panel-action {
  font-size: 0.78rem; font-weight: 600; color: var(--c-ocean);
  text-decoration: none; display: flex; align-items: center; gap: 5px;
  padding: 5px 12px; border-radius: 8px; background: var(--c-ocean-dim);
  transition: background 0.2s;
}
.panel-action:hover { background: rgba(26,79,212,0.15); }
.panel-body { padding: 18px 22px; }

/* ── Tableau top cours ── */
.top-table { width: 100%; border-collapse: collapse; }
.top-table thead tr { border-bottom: 1px solid var(--c-border); }
.top-table th {
  font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.08em; color: var(--c-muted); padding: 0 0 10px;
  font-family: var(--font-mono);
}
.top-table th:not(:first-child) { text-align: right; }
.top-table td { padding: 11px 0; vertical-align: middle; border-bottom: 1px solid rgba(26,79,212,0.05); }
.top-table td:not(:first-child) { text-align: right; }
.top-table tr:last-child td { border-bottom: none; }

.cours-name { font-size: 0.875rem; font-weight: 600; color: var(--c-text); }
.cours-category { font-size: 0.72rem; color: var(--c-muted); font-family: var(--font-mono); margin-top: 2px; }
.cours-inscriptions { font-size: 0.875rem; font-weight: 700; color: var(--c-ocean); }
.cours-bar-wrap { width: 80px; margin-left: auto; }
.cours-bar { height: 5px; border-radius: 5px; background: var(--c-ocean-dim); overflow: hidden; }
.cours-bar-fill { height: 100%; border-radius: 5px; background: linear-gradient(90deg, var(--c-ocean), var(--c-teal)); transition: width 1s cubic-bezier(0.16,1,0.3,1); }

/* ── Activité récente ── */
.activity-list { display: flex; flex-direction: column; gap: 12px; }
.activity-item { display: flex; align-items: flex-start; gap: 13px; }
.activity-avatar {
  width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.72rem; font-weight: 700; color: #fff;
}
.activity-avatar.blue  { background: linear-gradient(135deg, var(--c-ocean), var(--c-teal)); }
.activity-avatar.green { background: linear-gradient(135deg, #10b981, #059669); }
.activity-avatar.amber { background: linear-gradient(135deg, var(--c-amber), #f59e0b); }
.activity-avatar.purple{ background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.activity-body { flex: 1; }
.activity-text { font-size: 0.855rem; color: var(--c-text-soft); line-height: 1.4; }
.activity-text strong { color: var(--c-text); font-weight: 600; }
.activity-time { font-size: 0.72rem; color: var(--c-muted); font-family: var(--font-mono); margin-top: 2px; }
.activity-badge {
  font-size: 0.68rem; font-weight: 700; padding: 2px 8px; border-radius: 5px;
  font-family: var(--font-mono); flex-shrink: 0; margin-top: 2px;
}
.activity-badge.new    { background: rgba(52,211,153,0.12); color: #10b981; }
.activity-badge.update { background: var(--c-ocean-dim); color: var(--c-ocean); }
.activity-badge.pay    { background: rgba(232,160,32,0.12); color: var(--c-amber); }

/* ── Alerte cours non assignés ── */
.alert-panel {
  background: linear-gradient(135deg, rgba(232,160,32,0.08), rgba(232,160,32,0.04));
  border: 1.5px solid rgba(232,160,32,0.2); border-radius: 14px;
  padding: 16px 20px; display: flex; align-items: center; gap: 14px; margin-bottom: 24px;
  animation: kpi-in 0.5s cubic-bezier(0.16,1,0.3,1) 0.3s both;
}
.alert-panel-icon {
  width: 40px; height: 40px; border-radius: 11px;
  background: rgba(232,160,32,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.alert-panel-icon i { color: var(--c-amber); font-size: 1rem; }
.alert-panel-body { flex: 1; }
.alert-panel-title { font-size: 0.875rem; font-weight: 700; color: var(--c-text); margin-bottom: 2px; }
.alert-panel-desc  { font-size: 0.8rem; color: var(--c-text-soft); }
.alert-panel-link {
  font-size: 0.8rem; font-weight: 700; color: var(--c-amber);
  text-decoration: none; white-space: nowrap;
  display: flex; align-items: center; gap: 5px;
}

/* ── Grille stat rapide ── */
.quick-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 28px; }
.qs-card {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 14px; padding: 18px 16px; text-align: center;
  transition: border-color 0.2s, transform 0.2s;
  animation: kpi-in 0.5s cubic-bezier(0.16,1,0.3,1) both;
}
.qs-card:nth-child(1){animation-delay:0.30s}
.qs-card:nth-child(2){animation-delay:0.35s}
.qs-card:nth-child(3){animation-delay:0.40s}
.qs-card:hover { border-color: var(--c-border-lit); transform: translateY(-2px); }
.qs-value { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.04em; color: var(--c-ocean); }
.qs-label { font-size: 0.78rem; color: var(--c-muted); margin-top: 4px; font-weight: 500; }

/* Responsive */
@media(max-width:991px) { .kpi-grid{grid-template-columns:repeat(2,1fr)} .dash-bottom{grid-template-columns:1fr} .quick-stats{grid-template-columns:repeat(2,1fr)} }
@media(max-width:576px)  { .kpi-grid{grid-template-columns:1fr} .quick-stats{grid-template-columns:1fr} .dash-page-header{flex-direction:column} }
</style>

<!-- ══════════════════════════════
     EN-TÊTE DE PAGE
══════════════════════════════ -->
<div class="dash-page-header">
  <div>
    <h1 class="dash-page-title">Tableau de bord <span>universitaire</span></h1>
    <p class="dash-page-sub">
      Bienvenue, <?= htmlspecialchars($_SESSION['user']['nom']) ?> · Vue d'ensemble de CampusFlow
    </p>
  </div>
  <div class="dash-live-chip">
    <div class="dash-live-dot"></div>
    <span id="dashClock">--:--:--</span>
  </div>
</div>

<!-- ══════════════════════════════
     ALERTE COURS SANS PROF
══════════════════════════════ -->
<?php if ($coursNonAssignes > 0): ?>
<div class="alert-panel">
  <div class="alert-panel-icon"><i class="fas fa-exclamation-triangle"></i></div>
  <div class="alert-panel-body">
    <div class="alert-panel-title">
      <?= $coursNonAssignes ?> cours sans professeur assigné
    </div>
    <div class="alert-panel-desc">
      Ces cours n'ont pas encore d'enseignant référent. Pensez à les affecter avant le début du semestre.
    </div>
  </div>
  <a href="form-produit.php" class="alert-panel-link">
    Gérer <i class="fas fa-arrow-right"></i>
  </a>
</div>
<?php endif; ?>

<!-- ══════════════════════════════
     CARTES KPI PRINCIPALES
══════════════════════════════ -->
<div class="kpi-grid">

  <!-- Étudiants inscrits -->
  <div class="kpi-card blue">
    <div class="kpi-top">
      <div class="kpi-icon blue"><i class="fas fa-user-graduate"></i></div>
      <span class="kpi-badge up">↑ Actif</span>
    </div>
    <div class="kpi-value">
      <?= number_format($totalInscriptions, 0, ',', ' ') ?>
    </div>
    <div class="kpi-label">Étudiants inscrits</div>
    <div class="kpi-sub">Total des inscriptions actives</div>
  </div>

  <!-- Cours actifs -->
  <div class="kpi-card teal">
    <div class="kpi-top">
      <div class="kpi-icon teal"><i class="fas fa-book-open"></i></div>
      <span class="kpi-badge info">Semestre en cours</span>
    </div>
    <div class="kpi-value">
      <?= count($topCours) ?>
    </div>
    <div class="kpi-label">Cours actifs</div>
    <div class="kpi-sub">Dans le catalogue académique</div>
  </div>

  <!-- Cours sans professeur -->
  <div class="kpi-card amber">
    <div class="kpi-top">
      <div class="kpi-icon amber"><i class="fas fa-chalkboard-teacher"></i></div>
      <span class="kpi-badge warn">
        <?= $coursNonAssignes > 0 ? '⚠ ' . $coursNonAssignes . ' alerte(s)' : '✓ Complet' ?>
      </span>
    </div>
    <div class="kpi-value"><?= $coursNonAssignes ?></div>
    <div class="kpi-label">Cours sans professeur</div>
    <div class="kpi-sub">À affecter avant le semestre</div>
  </div>

  <!-- Frais de scolarité -->
  <div class="kpi-card green">
    <div class="kpi-top">
      <div class="kpi-icon green"><i class="fas fa-money-bill-wave"></i></div>
      <span class="kpi-badge up">↑ Encaissé</span>
    </div>
    <div class="kpi-value" style="font-size:1.55rem">
      <?= number_format($totalFrais, 0, ',', ' ') ?> <small style="font-size:0.9rem;font-weight:600;color:var(--c-muted)">FCFA</small>
    </div>
    <div class="kpi-label">Frais de scolarité</div>
    <div class="kpi-sub">Total encaissé ce semestre</div>
  </div>

</div>

<!-- ══════════════════════════════
     STATS RAPIDES
══════════════════════════════ -->
<div class="quick-stats">
  <div class="qs-card">
    <div class="qs-value"><?= count($topCours) ?></div>
    <div class="qs-label">Matières au catalogue</div>
  </div>
  <div class="qs-card">
    <div class="qs-value"><?= $coursNonAssignes ?></div>
    <div class="qs-label">Cours à affecter</div>
  </div>
  <div class="qs-card">
    <div class="qs-value" style="font-size:1.1rem"><?= date('M Y') ?></div>
    <div class="qs-label">Période en cours</div>
  </div>
</div>

<!-- ══════════════════════════════
     PANNEAU BAS : Top cours + Activité
══════════════════════════════ -->
<div class="dash-bottom">

  <!-- Top cours les plus demandés -->
  <div class="dash-panel">
    <div class="panel-header">
      <span class="panel-title"><i class="fas fa-trophy"></i> Top cours les plus demandés</span>
      <a href="index.php" class="panel-action">Voir tout <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="panel-body">
      <?php if (!empty($topCours)): ?>
        <?php
          // Calcul du max pour les barres de progression
          $maxInscriptions = max(array_column($topCours, 'total_vendu'));
        ?>
        <table class="top-table">
          <thead>
            <tr>
              <th>Cours / Matière</th>
              <th>Inscrits</th>
              <th>Progression</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($topCours as $cours): ?>
              <?php $pct = $maxInscriptions > 0 ? round(($cours['total_vendu'] / $maxInscriptions) * 100) : 0; ?>
              <tr>
                <td>
                  <div class="cours-name"><?= htmlspecialchars($cours['nom']) ?></div>
                  <div class="cours-category">
                    <?= htmlspecialchars($cours['categorie'] ?? 'Filière non définie') ?>
                  </div>
                </td>
                <td>
                  <span class="cours-inscriptions"><?= number_format($cours['total_vendu'], 0, ',', ' ') ?></span>
                </td>
                <td>
                  <div class="cours-bar-wrap">
                    <div class="cours-bar">
                      <div class="cours-bar-fill" style="width: <?= $pct ?>%"></div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div style="text-align:center;padding:32px 0;color:var(--c-muted)">
          <i class="fas fa-book-open" style="font-size:2rem;margin-bottom:10px;opacity:0.3"></i>
          <p style="font-size:0.875rem">Aucun cours enregistré pour le moment.</p>
          <a href="form-produit.php" style="font-size:0.82rem;color:var(--c-ocean);text-decoration:none;font-weight:600">
            + Ajouter un cours
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Activité récente simulée -->
  <div class="dash-panel">
    <div class="panel-header">
      <span class="panel-title"><i class="fas fa-clock"></i> Activité récente</span>
      <a href="historique-ventes.php" class="panel-action">Historique <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="panel-body">
      <div class="activity-list">

        <div class="activity-item">
          <div class="activity-avatar blue">FD</div>
          <div class="activity-body">
            <div class="activity-text"><strong>Fatou Diallo</strong> s'est inscrite à <strong>Mathématiques L1</strong></div>
            <div class="activity-time">Il y a 5 min</div>
          </div>
          <span class="activity-badge new">Nouveau</span>
        </div>

        <div class="activity-item">
          <div class="activity-avatar amber">MS</div>
          <div class="activity-body">
            <div class="activity-text"><strong>Moussa Sow</strong> a réglé ses frais de scolarité</div>
            <div class="activity-time">Il y a 23 min</div>
          </div>
          <span class="activity-badge pay">Paiement</span>
        </div>

        <div class="activity-item">
          <div class="activity-avatar green">AB</div>
          <div class="activity-body">
            <div class="activity-text">Cours <strong>Gestion Financière</strong> mis à jour — 3 crédits</div>
            <div class="activity-time">Il y a 1h</div>
          </div>
          <span class="activity-badge update">Modifié</span>
        </div>

        <div class="activity-item">
          <div class="activity-avatar purple">IF</div>
          <div class="activity-body">
            <div class="activity-text"><strong>Ibrahima Fall</strong> ajouté dans <strong>Droit L1</strong></div>
            <div class="activity-time">Il y a 2h</div>
          </div>
          <span class="activity-badge new">Nouveau</span>
        </div>

        <div class="activity-item">
          <div class="activity-avatar blue">AM</div>
          <div class="activity-body">
            <div class="activity-text">Prof. <strong>Aliou Mbaye</strong> affecté à <strong>Informatique L2</strong></div>
            <div class="activity-time">Il y a 3h</div>
          </div>
          <span class="activity-badge update">Affectation</span>
        </div>

      </div>
    </div>
  </div>

</div>

<!-- Actions rapides -->
<div style="background:var(--c-surface);border:1.5px solid var(--c-border);border-radius:18px;padding:22px 24px;animation:kpi-in 0.5s cubic-bezier(0.16,1,0.3,1) 0.4s both">
  <div style="font-size:0.82rem;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:0.1em;font-family:var(--font-mono);margin-bottom:16px">
    Actions rapides
  </div>
  <div style="display:flex;flex-wrap:wrap;gap:12px">
    <a href="form-produit.php" style="display:inline-flex;align-items:center;gap:8px;background:var(--c-ocean-dim);color:var(--c-ocean);padding:10px 18px;border-radius:10px;text-decoration:none;font-size:0.875rem;font-weight:600;border:1px solid var(--c-border);transition:all .2s"
       onmouseover="this.style.background='rgba(26,79,212,0.15)'" onmouseout="this.style.background='var(--c-ocean-dim)'">
      <i class="fas fa-user-plus"></i> Ajouter un étudiant
    </a>
    <a href="form-fournisseur.php" style="display:inline-flex;align-items:center;gap:8px;background:var(--c-teal-dim);color:var(--c-teal);padding:10px 18px;border-radius:10px;text-decoration:none;font-size:0.875rem;font-weight:600;border:1px solid var(--c-border);transition:all .2s"
       onmouseover="this.style.background='rgba(13,143,212,0.2)'" onmouseout="this.style.background='var(--c-teal-dim)'">
      <i class="fas fa-user-tie"></i> Ajouter un professeur
    </a>
    <a href="form-vente.php" style="display:inline-flex;align-items:center;gap:8px;background:rgba(16,185,129,0.1);color:#10b981;padding:10px 18px;border-radius:10px;text-decoration:none;font-size:0.875rem;font-weight:600;border:1px solid rgba(16,185,129,0.15);transition:all .2s"
       onmouseover="this.style.background='rgba(16,185,129,0.18)'" onmouseout="this.style.background='rgba(16,185,129,0.1)'">
      <i class="fas fa-file-signature"></i> Nouvelle inscription
    </a>
    <a href="historique-ventes.php" style="display:inline-flex;align-items:center;gap:8px;background:rgba(232,160,32,0.1);color:var(--c-amber);padding:10px 18px;border-radius:10px;text-decoration:none;font-size:0.875rem;font-weight:600;border:1px solid rgba(232,160,32,0.15);transition:all .2s"
       onmouseover="this.style.background='rgba(232,160,32,0.18)'" onmouseout="this.style.background='rgba(232,160,32,0.1)'">
      <i class="fas fa-history"></i> Historique inscriptions
    </a>
  </div>
</div>

<script>
// Horloge dashboard
(function tick() {
  const el = document.getElementById('dashClock');
  if (el) el.textContent = new Date().toLocaleTimeString('fr-FR');
  setTimeout(tick, 1000);
})();

// Animation des barres de progression au chargement
window.addEventListener('load', () => {
  document.querySelectorAll('.cours-bar-fill').forEach(bar => {
    const w = bar.style.width;
    bar.style.width = '0%';
    setTimeout(() => { bar.style.width = w; }, 300);
  });
});
</script>