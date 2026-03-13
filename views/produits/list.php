<?php
/*
 * CampusFlow — src/views/produits/list.php
 * Anciennement : liste des produits / État du Stock
 * Désormais    : liste des étudiants
 * Variables disponibles : $etudiants (= $produits depuis index.php)
 */

// Compatibilité : supporte les deux noms de variable
if (!isset($etudiants) && isset($produits)) $etudiants = $produits;
?>

<style>
/* ════════════════════════════════════
   LISTE DES ÉTUDIANTS — CampusFlow
════════════════════════════════════ */
.page-header-cf {
  display: flex; align-items: flex-start; justify-content: space-between;
  flex-wrap: wrap; gap: 16px; margin-bottom: 28px;
}
.page-title-cf { font-weight: 800; font-size: 1.9rem; letter-spacing: -0.04em; color: var(--c-text); line-height: 1.1; }
.page-title-cf span { color: var(--c-ocean); }
.page-subtitle-cf { font-size: 0.82rem; color: var(--c-muted); margin-top: 4px; font-family: var(--font-mono); }
.header-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

/* Boutons header */
.btn-cf {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 18px; border-radius: 10px; font-size: 0.875rem;
  font-weight: 600; text-decoration: none; border: none; cursor: pointer;
  transition: all 0.2s; font-family: var(--font);
}
.btn-cf-primary { background: linear-gradient(135deg, var(--c-ocean), var(--c-ocean-dark)); color: #fff; box-shadow: 0 2px 12px rgba(26,79,212,0.3); }
.btn-cf-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(26,79,212,0.45); color: #fff; }
.btn-cf-outline { background: transparent; color: var(--c-ocean); border: 1.5px solid var(--c-border-lit); }
.btn-cf-outline:hover { background: var(--c-ocean-dim); color: var(--c-ocean); }
.btn-cf-danger { background: rgba(220,38,38,0.08); color: #dc2626; border: 1.5px solid rgba(220,38,38,0.2); }
.btn-cf-danger:hover { background: rgba(220,38,38,0.15); color: #dc2626; }
.btn-cf-sm { padding: 6px 13px; font-size: 0.8rem; }

/* Barre de recherche */
.search-bar-wrap { position: relative; }
.search-bar-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--c-muted); font-size: 0.82rem; pointer-events: none; }
.search-input-cf {
  padding: 9px 14px 9px 36px; border: 1.5px solid var(--c-border);
  border-radius: 10px; font-size: 0.875rem; font-family: var(--font);
  background: var(--c-surface); color: var(--c-text); width: 280px;
  transition: border-color 0.2s, box-shadow 0.2s; outline: none;
}
.search-input-cf:focus { border-color: var(--c-border-lit); box-shadow: 0 0 0 3px var(--c-ocean-dim); }
.search-input-cf::placeholder { color: var(--c-muted); }

/* Alerte succès */
.alert-cf-success {
  background: rgba(16,185,129,0.08); border: 1.5px solid rgba(16,185,129,0.2);
  border-radius: 12px; padding: 12px 16px; margin-bottom: 20px;
  display: flex; align-items: center; gap: 10px; animation: alert-in 0.3s ease;
}
@keyframes alert-in { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.alert-cf-success i { color: #10b981; font-size: 0.85rem; }
.alert-cf-success span { font-size: 0.875rem; color: #065f46; font-weight: 500; }
.alert-cf-success .btn-close { margin-left: auto; }

/* Compteur -->
.table-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; }
.table-count { font-size: 0.82rem; color: var(--c-muted); font-family: var(--font-mono); }
.table-count strong { color: var(--c-ocean); }

/* Table */
.cf-table-wrap {
  background: rgba(255, 255, 255, 0.45); /* Rend la table semi-transparente */
  backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
  border: 1.5px solid rgba(255, 255, 255, 0.5);
  border-radius: 16px; overflow: hidden;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
}
.cf-table { width: 100%; border-collapse: collapse; }
.cf-table thead tr { background: linear-gradient(135deg, var(--c-text) 0%, #1a2d5a 100%); }
.cf-table thead th {
  padding: 13px 16px; font-size: 0.75rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.7);
  font-family: var(--font-mono); border: none; white-space: nowrap;
}
.cf-table thead th:first-child { border-radius: 0; }
.cf-table tbody tr {
  border-bottom: 1px solid rgba(26,79,212,0.05);
  transition: background 0.15s;
}
.cf-table tbody tr:last-child { border-bottom: none; }
.cf-table tbody tr:hover { background: var(--c-ocean-dim); }
.cf-table tbody tr.row-alert { background: rgba(220,38,38,0.04); }
.cf-table tbody tr.row-alert:hover { background: rgba(220,38,38,0.08); }
.cf-table td { padding: 13px 16px; font-size: 0.875rem; color: var(--c-text-soft); vertical-align: middle; }

/* Avatar étudiant */
.student-cell { display: flex; align-items: center; gap: 11px; }
.student-avatar {
  width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-teal));
  display: flex; align-items: center; justify-content: center;
  font-size: 0.72rem; font-weight: 700; color: #fff;
}
.student-name { font-weight: 600; color: var(--c-text); font-size: 0.875rem; }
.student-id { font-size: 0.72rem; color: var(--c-muted); font-family: var(--font-mono); margin-top: 1px; }

/* Badge filière */
.badge-filiere {
  display: inline-block; padding: 3px 10px; border-radius: 6px;
  font-size: 0.75rem; font-weight: 600; font-family: var(--font-mono);
  background: var(--c-ocean-dim); color: var(--c-ocean);
}

/* Frais */
.frais-amount { font-weight: 700; color: var(--c-text); }
.frais-sub { font-size: 0.72rem; color: var(--c-muted); font-family: var(--font-mono); }

/* Statut badge */
.status-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; font-family: var(--font-mono);
}
.status-badge.ok     { background: rgba(52,211,153,0.12); color: #059669; }
.status-badge.alert  { background: rgba(220,38,38,0.1);   color: #dc2626; }
.status-badge i { font-size: 0.6rem; }

/* Actions td */
.actions-cell { display: flex; align-items: center; gap: 7px; }

/* État vide */
.empty-state {
  text-align: center; padding: 56px 20px; color: var(--c-muted);
}
.empty-state-icon {
  width: 64px; height: 64px; border-radius: 18px;
  background: var(--c-ocean-dim); display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px; font-size: 1.6rem; color: var(--c-ocean);
}
.empty-state p { font-size: 0.875rem; margin-bottom: 18px; }

/* Responsive */
@media(max-width:768px) {
  .cf-table thead th:nth-child(3),
  .cf-table tbody td:nth-child(3) { display: none; }
  .search-input-cf { width: 100%; }
  .page-header-cf { flex-direction: column; }
}
</style>

<!-- ══════════════════════════ EN-TÊTE ══════════════════════════ -->
<div class="page-header-cf">
  <div>
    <h1 class="page-title-cf">Liste des <span>Étudiants</span></h1>
    <p class="page-subtitle-cf">
      <?= count($etudiants ?? []) ?> étudiant<?= count($etudiants ?? []) > 1 ? 's' : '' ?> enregistré<?= count($etudiants ?? []) > 1 ? 's' : '' ?> · Semestre en cours
    </p>
  </div>
  <div class="header-actions">
    <!-- Recherche AJAX (id conservé : search-input) -->
    <div class="search-bar-wrap">
      <i class="fas fa-search"></i>
      <input type="text" id="search-input" class="search-input-cf" placeholder="Rechercher un étudiant…">
    </div>
    <a href="export/stock-csv.php" class="btn-cf btn-cf-outline">
      <i class="fas fa-download"></i> Exporter CSV
    </a>
    <a href="form-produit.php" class="btn-cf btn-cf-primary">
      <i class="fas fa-user-plus"></i> Ajouter un étudiant
    </a>
  </div>
</div>

<!-- ══════════════════════════ ALERTE SUCCÈS ══════════════════════════ -->
<?php if (isset($_SESSION['message'])): ?>
  <div class="alert-cf-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i>
    <span><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></span>
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Fermer"></button>
  </div>
<?php endif; ?>

<!-- ══════════════════════════ TABLE ══════════════════════════ -->
<div class="table-meta">
  <span class="table-count">
    <strong><?= count($etudiants ?? []) ?></strong> étudiant<?= count($etudiants ?? []) > 1 ? 's' : '' ?> trouvé<?= count($etudiants ?? []) > 1 ? 's' : '' ?>
  </span>
</div>

<div class="cf-table-wrap">
  <table class="cf-table" id="students-table">
    <thead>
      <tr>
        <th><i class="fas fa-user-graduate" style="margin-right:6px;opacity:.6"></i> Étudiant</th>
        <th><i class="fas fa-layer-group" style="margin-right:6px;opacity:.6"></i> Filière</th>
        <th><i class="fas fa-money-bill-wave" style="margin-right:6px;opacity:.6"></i> Frais de scolarité</th>
        <th><i class="fas fa-users" style="margin-right:6px;opacity:.6"></i> Effectif filière</th>
        <th><i class="fas fa-circle" style="margin-right:6px;opacity:.6"></i> Statut</th>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <th><i class="fas fa-cog" style="margin-right:6px;opacity:.6"></i> Actions</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody id="product-table-body">
      <?php if (empty($etudiants)): ?>
        <tr>
          <td colspan="6">
            <div class="empty-state">
              <div class="empty-state-icon"><i class="fas fa-user-graduate"></i></div>
              <p>Aucun étudiant enregistré pour le moment.</p>
              <a href="form-produit.php" class="btn-cf btn-cf-primary">
                <i class="fas fa-user-plus"></i> Ajouter le premier étudiant
              </a>
            </div>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($etudiants as $etudiant): ?>
          <?php $enAlerte = $etudiant['quantite_en_stock'] < $etudiant['seuil_alerte']; ?>
          <tr class="<?= $enAlerte ? 'row-alert' : '' ?>">

            <!-- Nom étudiant avec avatar initiales -->
            <td>
              <div class="student-cell">
                <div class="student-avatar">
                  <?= strtoupper(substr($etudiant['nom_produit'], 0, 2)) ?>
                </div>
                <div>
                  <div class="student-name"><?= htmlspecialchars($etudiant['nom_produit']) ?></div>
                  <div class="student-id">#<?= str_pad($etudiant['id'] ?? '0', 4, '0', STR_PAD_LEFT) ?></div>
                </div>
              </div>
            </td>

            <!-- Filière (= catégorie) -->
            <td>
              <span class="badge-filiere">
                <?= htmlspecialchars($etudiant['nom_categorie'] ?? 'Non définie') ?>
              </span>
            </td>

            <!-- Frais scolarité (= prix de vente) -->
            <td>
              <div class="frais-amount">
                <?= number_format($etudiant['prix_vente'], 0, ',', ' ') ?> FCFA
              </div>
              <div class="frais-sub">par semestre</div>
            </td>

            <!-- Effectif filière (= quantité en stock) -->
            <td>
              <span style="font-weight:700;color:var(--c-ocean)">
                <?= $etudiant['quantite_en_stock'] ?>
              </span>
              <span style="font-size:0.75rem;color:var(--c-muted)"> / <?= $etudiant['seuil_alerte'] ?> min.</span>
            </td>

            <!-- Statut -->
            <td>
              <?php if ($enAlerte): ?>
                <span class="status-badge alert">
                  <i class="fas fa-exclamation-circle"></i> Places limitées
                </span>
              <?php else: ?>
                <span class="status-badge ok">
                  <i class="fas fa-check-circle"></i> Inscrit
                </span>
              <?php endif; ?>
            </td>

            <!-- Actions admin -->
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
              <td>
                <div class="actions-cell">
                  <a href="form-produit.php?action=edit&id=<?= $etudiant['id'] ?>" class="btn-cf btn-cf-outline btn-cf-sm">
                    <i class="fas fa-edit"></i> Modifier
                  </a>
                  <a href="traitement-produit.php?action=delete&id=<?= $etudiant['id'] ?>"
                     class="btn-cf btn-cf-danger btn-cf-sm"
                     onclick="return confirm('Supprimer l\'étudiant <?= htmlspecialchars(addslashes($etudiant['nom_produit'])) ?> ?')">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            <?php endif; ?>

          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>