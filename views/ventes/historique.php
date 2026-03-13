<?php
/*
 * CampusFlow — src/views/ventes/historique.php
 * Anciennement : historique des ventes
 * Désormais    : historique des inscriptions
 * Variables : $ventes (inscriptions), $dateDebut, $dateFin
 */
?>

<style>
/* ════════════════════════════════════
   HISTORIQUE INSCRIPTIONS — CampusFlow
════════════════════════════════════ */
.hist-filter-card {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 16px; padding: 22px 24px; margin-bottom: 24px;
  box-shadow: 0 2px 10px rgba(26,79,212,0.04);
}
.hist-filter-title {
  font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.1em; color: var(--c-muted); font-family: var(--font-mono);
  margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
}
.hist-filter-grid { display: grid; grid-template-columns: 1fr 1fr auto; gap: 14px; align-items: end; }

/* Labels & inputs filtres */
.filter-label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--c-text-soft); margin-bottom: 6px; }
.filter-input {
  width: 100%; padding: 10px 14px; background: var(--c-bg);
  border: 1.5px solid var(--c-border); border-radius: 10px;
  font-size: 0.875rem; font-family: var(--font); color: var(--c-text);
  outline: none; transition: border-color 0.2s, box-shadow 0.2s;
}
.filter-input:focus { border-color: var(--c-border-lit); box-shadow: 0 0 0 3px var(--c-ocean-dim); }
.btn-filter {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 11px 22px; border-radius: 10px; border: none; cursor: pointer;
  font-size: 0.875rem; font-weight: 700; font-family: var(--font);
  background: linear-gradient(135deg, var(--c-ocean), var(--c-ocean-dark)); color: #fff;
  box-shadow: 0 2px 12px rgba(26,79,212,0.3); transition: all 0.2s; white-space: nowrap;
}
.btn-filter:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(26,79,212,0.45); }

/* Stats résumé */
.hist-summary { display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
.hist-stat {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 12px; padding: 14px 18px; flex: 1; min-width: 150px;
}
.hist-stat-val { font-size: 1.5rem; font-weight: 800; color: var(--c-ocean); letter-spacing: -0.04em; }
.hist-stat-lbl { font-size: 0.75rem; color: var(--c-muted); margin-top: 3px; font-family: var(--font-mono); }

/* Table inscriptions */
.hist-table-wrap {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 16px; overflow: hidden; box-shadow: 0 2px 16px rgba(26,79,212,0.05);
}
.hist-table { width: 100%; border-collapse: collapse; }
.hist-table thead tr { background: linear-gradient(135deg, var(--c-text) 0%, #1a2d5a 100%); }
.hist-table thead th {
  padding: 13px 16px; font-size: 0.72rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.08em;
  color: rgba(255,255,255,0.65); font-family: var(--font-mono); border: none; white-space: nowrap;
}
.hist-table tbody tr { border-bottom: 1px solid rgba(26,79,212,0.05); transition: background 0.15s; }
.hist-table tbody tr:last-child { border-bottom: none; }
.hist-table tbody tr:hover { background: var(--c-ocean-dim); }
.hist-table td { padding: 13px 16px; font-size: 0.875rem; color: var(--c-text-soft); vertical-align: middle; }

/* Badge ID inscription */
.insc-id {
  display: inline-block; background: var(--c-ocean-dim); color: var(--c-ocean);
  padding: 3px 9px; border-radius: 6px; font-size: 0.72rem;
  font-weight: 700; font-family: var(--font-mono);
}

/* Date chip */
.date-chip { font-size: 0.82rem; color: var(--c-text-soft); font-family: var(--font-mono); }
.date-chip .time { font-size: 0.72rem; color: var(--c-muted); display: block; margin-top: 1px; }

/* Étudiant cell */
.etud-cell { display: flex; align-items: center; gap: 10px; }
.etud-mini-avatar {
  width: 30px; height: 30px; border-radius: 8px;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-teal));
  display: flex; align-items: center; justify-content: center;
  font-size: 0.65rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.etud-name { font-weight: 600; color: var(--c-text); }

/* Montant */
.montant-cell { font-weight: 800; color: var(--c-text); }
.montant-cell small { font-size: 0.72rem; font-weight: 500; color: var(--c-muted); }

/* Vendeur = administrateur */
.admin-chip {
  display: inline-flex; align-items: center; gap: 5px;
  background: var(--c-teal-dim); color: var(--c-teal);
  padding: 3px 9px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;
}

/* État vide */
.empty-hist { text-align:center; padding:56px 20px; color:var(--c-muted); }
.empty-hist-icon { width:56px;height:56px;border-radius:14px;background:var(--c-ocean-dim);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.4rem;color:var(--c-ocean); }

@media(max-width:768px) {
  .hist-filter-grid { grid-template-columns: 1fr; }
  .hist-table thead th:nth-child(1),
  .hist-table tbody td:nth-child(1) { display: none; }
}
</style>

<!-- ══════════════════════════ EN-TÊTE ══════════════════════════ -->
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:28px">
  <div>
    <h1 style="font-weight:800;font-size:1.9rem;letter-spacing:-.04em;color:var(--c-text)">
      Historique des <span style="color:var(--c-ocean)">inscriptions</span>
    </h1>
    <p style="font-size:.82rem;color:var(--c-muted);margin-top:4px;font-family:var(--font-mono)">
      Toutes les inscriptions et paiements de scolarité
    </p>
  </div>
  <a href="form-vente.php" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;background:linear-gradient(135deg,var(--c-ocean),var(--c-ocean-dark));color:#fff;font-weight:700;font-size:.875rem;text-decoration:none;box-shadow:0 2px 14px rgba(26,79,212,.35)">
    <i class="fas fa-file-signature"></i> Nouvelle inscription
  </a>
</div>

<!-- ══════════════════════════ FILTRE ══════════════════════════ -->
<div class="hist-filter-card">
  <div class="hist-filter-title">
    <i class="fas fa-filter"></i> Filtrer par période
  </div>
  <form method="GET" class="hist-filter-grid">
    <div>
      <label class="filter-label" for="date_debut">Date de début</label>
      <input type="date" class="filter-input" id="date_debut" name="date_debut"
             value="<?= htmlspecialchars($dateDebut) ?>">
    </div>
    <div>
      <label class="filter-label" for="date_fin">Date de fin</label>
      <input type="date" class="filter-input" id="date_fin" name="date_fin"
             value="<?= htmlspecialchars($dateFin) ?>">
    </div>
    <div>
      <button type="submit" class="btn-filter">
        <i class="fas fa-search"></i> Filtrer
      </button>
    </div>
  </form>
</div>

<!-- ══════════════════════════ STATS RÉSUMÉ ══════════════════════════ -->
<?php if (!empty($ventes)): ?>
  <?php
    $totalMontant   = array_sum(array_column($ventes, 'total_vente'));
    $totalInscrits  = array_sum(array_column($ventes, 'quantite_vendue'));
  ?>
  <div class="hist-summary">
    <div class="hist-stat">
      <div class="hist-stat-val"><?= count($ventes) ?></div>
      <div class="hist-stat-lbl">Inscriptions trouvées</div>
    </div>
    <div class="hist-stat">
      <div class="hist-stat-val"><?= $totalInscrits ?></div>
      <div class="hist-stat-lbl">Étudiants concernés</div>
    </div>
    <div class="hist-stat">
      <div class="hist-stat-val" style="font-size:1.1rem"><?= number_format($totalMontant, 0, ',', ' ') ?></div>
      <div class="hist-stat-lbl">FCFA encaissés</div>
    </div>
  </div>
<?php endif; ?>

<!-- ══════════════════════════ TABLE ══════════════════════════ -->
<div class="hist-table-wrap">
  <table class="hist-table">
    <thead>
      <tr>
        <th>N° Inscription</th>
        <th>Date</th>
        <th>Étudiant / Cours</th>
        <th>Nb inscrits</th>
        <th>Administrateur</th>
        <th>Montant</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($ventes)): ?>
        <tr>
          <td colspan="6">
            <div class="empty-hist">
              <div class="empty-hist-icon"><i class="fas fa-file-signature"></i></div>
              <p style="font-size:.875rem;margin-bottom:16px">Aucune inscription trouvée pour cette période.</p>
              <a href="form-vente.php" style="display:inline-flex;align-items:center;gap:7px;padding:9px 20px;background:linear-gradient(135deg,var(--c-ocean),var(--c-ocean-dark));color:#fff;border-radius:10px;text-decoration:none;font-size:.875rem;font-weight:700">
                <i class="fas fa-file-signature"></i> Créer une inscription
              </a>
            </div>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($ventes as $insc): ?>
          <tr>
            <td>
              <span class="insc-id">#<?= str_pad($insc['id'], 5, '0', STR_PAD_LEFT) ?></span>
            </td>
            <td>
              <span class="date-chip">
                <?= date('d/m/Y', strtotime($insc['date_vente'])) ?>
                <span class="time"><?= date('H:i', strtotime($insc['date_vente'])) ?></span>
              </span>
            </td>
            <td>
              <div class="etud-cell">
                <div class="etud-mini-avatar">
                  <?= strtoupper(substr($insc['nom_produit'], 0, 2)) ?>
                </div>
                <div>
                  <div class="etud-name"><?= htmlspecialchars($insc['nom_produit']) ?></div>
                </div>
              </div>
            </td>
            <td>
              <span style="font-weight:700;color:var(--c-ocean)"><?= $insc['quantite_vendue'] ?></span>
              <span style="font-size:.75rem;color:var(--c-muted)"> étudiant<?= $insc['quantite_vendue'] > 1 ? 's' : '' ?></span>
            </td>
            <td>
              <span class="admin-chip">
                <i class="fas fa-user-shield" style="font-size:.65rem"></i>
                <?= htmlspecialchars($insc['nom_utilisateur']) ?>
              </span>
            </td>
            <td>
              <span class="montant-cell">
                <?= number_format($insc['total_vente'], 0, ',', ' ') ?>
                <small>FCFA</small>
              </span>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>