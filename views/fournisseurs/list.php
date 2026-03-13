<?php
/*
 * CampusFlow — src/views/fournisseurs/list.php
 * Anciennement : liste des fournisseurs
 * Désormais    : liste des professeurs
 * Variable disponible : $fournisseurs
 */
?>

<style>
/* ════════════════════════════════════
   LISTE DES PROFESSEURS — CampusFlow
════════════════════════════════════ */
.prof-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 18px; margin-top: 24px;
}
.prof-card {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 16px; overflow: hidden; transition: all 0.25s;
  animation: card-in 0.4s cubic-bezier(0.16,1,0.3,1) both;
}
.prof-card:nth-child(1){animation-delay:.05s}
.prof-card:nth-child(2){animation-delay:.10s}
.prof-card:nth-child(3){animation-delay:.15s}
.prof-card:nth-child(4){animation-delay:.20s}
.prof-card:nth-child(5){animation-delay:.25s}
.prof-card:nth-child(6){animation-delay:.30s}
@keyframes card-in{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
.prof-card:hover { border-color: var(--c-border-lit); box-shadow: 0 8px 32px var(--c-ocean-glow); transform: translateY(-2px); }

/* Top barre couleur */
.prof-card-top { height: 4px; background: linear-gradient(90deg, var(--c-ocean), var(--c-teal)); }

.prof-card-body { padding: 20px; }
.prof-header { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px; }
.prof-avatar {
  width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-teal));
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; font-weight: 800; color: #fff;
  box-shadow: 0 4px 14px rgba(26,79,212,0.3);
}
.prof-name { font-size: 1rem; font-weight: 700; color: var(--c-text); margin-bottom: 3px; }
.prof-id { font-size: 0.72rem; color: var(--c-muted); font-family: var(--font-mono); }

.prof-infos { display: flex; flex-direction: column; gap: 8px; margin-bottom: 18px; }
.prof-info-row { display: flex; align-items: center; gap: 10px; }
.prof-info-icon {
  width: 28px; height: 28px; border-radius: 7px;
  background: var(--c-ocean-dim); display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.prof-info-icon i { color: var(--c-ocean); font-size: 0.72rem; }
.prof-info-text { font-size: 0.855rem; color: var(--c-text-soft); }
.prof-info-text.empty { color: var(--c-muted); font-style: italic; }

.prof-actions { display: flex; gap: 8px; padding-top: 14px; border-top: 1px solid var(--c-border); }
.prof-btn {
  flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
  padding: 8px 12px; border-radius: 9px; font-size: 0.82rem; font-weight: 600;
  text-decoration: none; transition: all 0.2s; font-family: var(--font);
}
.prof-btn-edit { background: var(--c-ocean-dim); color: var(--c-ocean); border: 1px solid var(--c-border); }
.prof-btn-edit:hover { background: rgba(26,79,212,0.15); color: var(--c-ocean); }
.prof-btn-del { background: rgba(220,38,38,0.06); color: #dc2626; border: 1px solid rgba(220,38,38,0.15); }
.prof-btn-del:hover { background: rgba(220,38,38,0.12); }

/* État vide */
.empty-state { text-align:center; padding:56px 20px; color:var(--c-muted); }
.empty-state-icon { width:64px;height:64px;border-radius:18px;background:var(--c-ocean-dim);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.6rem;color:var(--c-ocean); }
</style>

<!-- ══════════════════════════ EN-TÊTE ══════════════════════════ -->
<div class="page-header-cf" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:28px">
  <div>
    <h1 style="font-weight:800;font-size:1.9rem;letter-spacing:-.04em;color:var(--c-text)">
      Corps <span style="color:var(--c-ocean)">enseignant</span>
    </h1>
    <p style="font-size:0.82rem;color:var(--c-muted);margin-top:4px;font-family:var(--font-mono)">
      <?= count($fournisseurs ?? []) ?> professeur<?= count($fournisseurs ?? []) > 1 ? 's' : '' ?> enregistré<?= count($fournisseurs ?? []) > 1 ? 's' : '' ?>
    </p>
  </div>
  <a href="form-fournisseur.php" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;background:linear-gradient(135deg,var(--c-ocean),var(--c-ocean-dark));color:#fff;font-weight:700;font-size:.875rem;text-decoration:none;box-shadow:0 2px 14px rgba(26,79,212,.35);transition:all .2s"
     onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 22px rgba(26,79,212,.5)'"
     onmouseout="this.style.transform='none';this.style.boxShadow='0 2px 14px rgba(26,79,212,.35)'">
    <i class="fas fa-user-tie"></i> Ajouter un professeur
  </a>
</div>

<!-- ══════════════════════════ GRILLE CARTES ══════════════════════════ -->
<?php if (empty($fournisseurs)): ?>
  <div class="empty-state">
    <div class="empty-state-icon"><i class="fas fa-chalkboard-teacher"></i></div>
    <p style="font-size:.875rem;margin-bottom:18px">Aucun professeur enregistré pour le moment.</p>
    <a href="form-fournisseur.php" style="display:inline-flex;align-items:center;gap:7px;padding:9px 20px;background:linear-gradient(135deg,var(--c-ocean),var(--c-ocean-dark));color:#fff;border-radius:10px;text-decoration:none;font-size:.875rem;font-weight:700">
      <i class="fas fa-user-tie"></i> Ajouter le premier professeur
    </a>
  </div>
<?php else: ?>
  <div class="prof-grid">
    <?php foreach ($fournisseurs as $prof): ?>
      <div class="prof-card">
        <div class="prof-card-top"></div>
        <div class="prof-card-body">

          <div class="prof-header">
            <div class="prof-avatar">
              <?= strtoupper(substr($prof['nom_fournisseur'], 0, 2)) ?>
            </div>
            <div>
              <div class="prof-name"><?= htmlspecialchars($prof['nom_fournisseur']) ?></div>
              <div class="prof-id">Prof. #<?= str_pad($prof['id'], 3, '0', STR_PAD_LEFT) ?></div>
            </div>
          </div>

          <div class="prof-infos">
            <div class="prof-info-row">
              <div class="prof-info-icon"><i class="fas fa-user"></i></div>
              <span class="prof-info-text <?= empty($prof['contact_personne']) ? 'empty' : '' ?>">
                <?= !empty($prof['contact_personne'])
                    ? htmlspecialchars($prof['contact_personne'])
                    : 'Spécialité non renseignée' ?>
              </span>
            </div>
            <div class="prof-info-row">
              <div class="prof-info-icon"><i class="fas fa-phone"></i></div>
              <span class="prof-info-text <?= empty($prof['telephone']) ? 'empty' : '' ?>">
                <?= !empty($prof['telephone'])
                    ? htmlspecialchars($prof['telephone'])
                    : 'Téléphone non renseigné' ?>
              </span>
            </div>
            <?php if (!empty($prof['email'])): ?>
            <div class="prof-info-row">
              <div class="prof-info-icon"><i class="fas fa-envelope"></i></div>
              <span class="prof-info-text"><?= htmlspecialchars($prof['email']) ?></span>
            </div>
            <?php endif; ?>
          </div>

          <div class="prof-actions">
            <a href="form-fournisseur.php?action=edit&id=<?= $prof['id'] ?>" class="prof-btn prof-btn-edit">
              <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="traitement-fournisseur.php?action=delete&id=<?= $prof['id'] ?>"
               class="prof-btn prof-btn-del"
               onclick="return confirm('Supprimer le professeur <?= htmlspecialchars(addslashes($prof['nom_fournisseur'])) ?> ?')">
              <i class="fas fa-trash-alt"></i> Supprimer
            </a>
          </div>

        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>