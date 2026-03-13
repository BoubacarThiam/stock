<?php
/*
 * CampusFlow — src/views/produits/form.php
 * Anciennement : formulaire produit
 * Désormais    : formulaire étudiant (ajout / modification)
 * Variables : $isEditMode, $produit (= données étudiant), $fournisseurs (= professeurs)
 */
$isEditMode = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']);
$etudiant   = $produit ?? [];   // alias sémantique
?>

<style>
/* ════════════════════════════════════
   FORMULAIRE ÉTUDIANT — CampusFlow
════════════════════════════════════ */
.form-page-header {
  display: flex; align-items: center; gap: 14px; margin-bottom: 28px;
}
.form-back-btn {
  width: 38px; height: 38px; border-radius: 10px;
  background: var(--c-ocean-dim); border: 1.5px solid var(--c-border);
  display: flex; align-items: center; justify-content: center;
  color: var(--c-ocean); text-decoration: none; transition: all 0.2s; flex-shrink: 0;
}
.form-back-btn:hover { background: rgba(26,79,212,0.15); color: var(--c-ocean); }
.form-page-title { font-weight: 800; font-size: 1.75rem; letter-spacing: -0.04em; color: var(--c-text); }
.form-page-title span { color: var(--c-ocean); }
.form-page-sub { font-size: 0.82rem; color: var(--c-muted); font-family: var(--font-mono); margin-top: 3px; }

/* Card formulaire */
.cf-form-card {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 18px; overflow: hidden;
  box-shadow: 0 2px 16px rgba(26,79,212,0.05);
}
.cf-form-card-header {
  padding: 16px 24px; border-bottom: 1px solid var(--c-border);
  display: flex; align-items: center; gap: 10px;
  background: linear-gradient(135deg, rgba(26,79,212,0.04), rgba(13,143,212,0.02));
}
.cf-form-card-header i { color: var(--c-ocean); font-size: 0.95rem; }
.cf-form-card-header span { font-size: 0.875rem; font-weight: 700; color: var(--c-text); }
.cf-form-card-body { padding: 28px 24px; }

/* Section label */
.form-section-label {
  font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.1em; color: var(--c-muted); font-family: var(--font-mono);
  margin-bottom: 16px; padding-bottom: 8px;
  border-bottom: 1px solid var(--c-border); display: block;
}
.form-section { margin-bottom: 28px; }

/* Labels et inputs */
.cf-label {
  display: block; font-size: 0.82rem; font-weight: 600;
  color: var(--c-text-soft); margin-bottom: 6px;
}
.cf-label .required { color: var(--c-ocean); margin-left: 2px; }
.cf-input, .cf-select, .cf-textarea {
  width: 100%; padding: 11px 14px;
  background: var(--c-bg); border: 1.5px solid var(--c-border);
  border-radius: 10px; font-size: 0.875rem; font-family: var(--font);
  color: var(--c-text); outline: none;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  -webkit-appearance: none;
}
.cf-input:focus, .cf-select:focus, .cf-textarea:focus {
  border-color: var(--c-border-lit);
  box-shadow: 0 0 0 3px var(--c-ocean-dim);
  background: var(--c-surface);
}
.cf-input::placeholder, .cf-textarea::placeholder { color: var(--c-muted); }
.cf-select { cursor: pointer; }
.cf-textarea { resize: vertical; min-height: 90px; }

/* Champ avec icône -->
.input-with-icon { position: relative; }
.input-with-icon i {
  position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
  color: var(--c-muted); font-size: 0.8rem; pointer-events: none;
}
.input-with-icon .cf-input { padding-left: 38px; }
.input-with-icon:focus-within i { color: var(--c-ocean); }

/* Indicateur marge → crédits -->
.credits-display {
  background: var(--c-ocean-dim); border: 1.5px solid var(--c-border);
  border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 12px;
  margin-top: 4px;
}
.credits-display i { color: var(--c-ocean); font-size: 0.9rem; }
.credits-display-label { font-size: 0.8rem; color: var(--c-text-soft); }
.credits-display-value { font-size: 1.2rem; font-weight: 800; color: var(--c-ocean); font-family: var(--font-mono); margin-left: auto; }

/* Boutons submit */
.form-actions { display: flex; align-items: center; gap: 12px; padding-top: 8px; flex-wrap: wrap; }
.btn-cf-submit {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 28px; border-radius: 10px; border: none; cursor: pointer;
  font-size: 0.9rem; font-weight: 700; font-family: var(--font); letter-spacing: 0.01em;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-ocean-dark)); color: #fff;
  box-shadow: 0 3px 16px rgba(26,79,212,0.35); transition: all 0.2s;
}
.btn-cf-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(26,79,212,0.45); }
.btn-cf-cancel {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 20px; border-radius: 10px; text-decoration: none;
  font-size: 0.875rem; font-weight: 600; font-family: var(--font);
  color: var(--c-text-soft); background: var(--c-bg);
  border: 1.5px solid var(--c-border); transition: all 0.2s;
}
.btn-cf-cancel:hover { border-color: var(--c-border-lit); color: var(--c-text); }

/* Grille responsive */
.row-cf { display: grid; gap: 16px; margin-bottom: 16px; }
.row-cf-2 { grid-template-columns: 1fr 1fr; }
.row-cf-3 { grid-template-columns: 1fr 1fr 1fr; }
.row-cf-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
@media(max-width:768px) { .row-cf-2,.row-cf-3,.row-cf-4 { grid-template-columns: 1fr; } }
</style>

<!-- ══════════════════════════ EN-TÊTE ══════════════════════════ -->
<div class="form-page-header">
  <a href="index.php" class="form-back-btn"><i class="fas fa-arrow-left"></i></a>
  <div>
    <h1 class="form-page-title">
      <?= $isEditMode ? 'Modifier l\'<span>étudiant</span>' : 'Ajouter un <span>étudiant</span>' ?>
    </h1>
    <p class="form-page-sub">
      <?= $isEditMode
        ? 'Mise à jour du dossier · #' . str_pad($etudiant['id'] ?? '0', 4, '0', STR_PAD_LEFT)
        : 'Création d\'un nouveau dossier étudiant' ?>
    </p>
  </div>
</div>

<!-- ══════════════════════════ FORMULAIRE ══════════════════════════ -->
<div class="cf-form-card">
  <div class="cf-form-card-header">
    <i class="fas fa-<?= $isEditMode ? 'user-edit' : 'user-plus' ?>"></i>
    <span><?= $isEditMode ? 'Modification du dossier étudiant' : 'Nouveau dossier étudiant' ?></span>
  </div>
  <div class="cf-form-card-body">

    <form action="traitement-produit.php?action=<?= $isEditMode ? 'edit&id=' . $etudiant['id'] : 'add' ?>" method="post">

      <!-- ── Section 1 : Identité ── -->
      <div class="form-section">
        <span class="form-section-label"><i class="fas fa-id-card" style="margin-right:6px"></i>Identité de l'étudiant</span>
        <div class="row-cf row-cf-2">
          <div>
            <label class="cf-label" for="nom_produit">Nom complet <span class="required">*</span></label>
            <div class="input-with-icon">
              <i class="fas fa-user"></i>
              <input type="text" class="cf-input" id="nom_produit" name="nom_produit"
                     placeholder="Ex : Fatou Diallo"
                     value="<?= htmlspecialchars($etudiant['nom_produit'] ?? '') ?>" required>
            </div>
          </div>
          <div>
            <label class="cf-label" for="id_categorie">Filière / Département <span class="required">*</span></label>
            <select class="cf-select" id="id_categorie" name="id_categorie">
              <option value="">— Sélectionner une filière —</option>
              <option value="1"  <?= (($etudiant['id_categorie'] ?? '') == 1)  ? 'selected' : '' ?>>Licence 1 — Sciences</option>
              <option value="2"  <?= (($etudiant['id_categorie'] ?? '') == 2)  ? 'selected' : '' ?>>Licence 2 — Informatique</option>
              <option value="3"  <?= (($etudiant['id_categorie'] ?? '') == 3)  ? 'selected' : '' ?>>Licence 3 — Gestion</option>
              <option value="4"  <?= (($etudiant['id_categorie'] ?? '') == 4)  ? 'selected' : '' ?>>Licence 1 — Droit</option>
              <option value="5"  <?= (($etudiant['id_categorie'] ?? '') == 5)  ? 'selected' : '' ?>>Licence 2 — Économie</option>
              <option value="6"  <?= (($etudiant['id_categorie'] ?? '') == 6)  ? 'selected' : '' ?>>Master 1 — Management</option>
              <option value="7"  <?= (($etudiant['id_categorie'] ?? '') == 7)  ? 'selected' : '' ?>>Master 2 — Finance</option>
              <option value="8"  <?= (($etudiant['id_categorie'] ?? '') == 8)  ? 'selected' : '' ?>>Licence 3 — Marketing</option>
              <option value="9"  <?= (($etudiant['id_categorie'] ?? '') == 9)  ? 'selected' : '' ?>>Doctorat</option>
              <option value="10" <?= (($etudiant['id_categorie'] ?? '') == 10) ? 'selected' : '' ?>>Autre</option>
            </select>
          </div>
        </div>
      </div>

      <!-- ── Section 2 : Affectation professeur ── -->
      <div class="form-section">
        <span class="form-section-label"><i class="fas fa-chalkboard-teacher" style="margin-right:6px"></i>Professeur référent</span>
        <div>
          <label class="cf-label" for="id_fournisseur">Professeur principal assigné</label>
          <select class="cf-select" id="id_fournisseur" name="id_fournisseur">
            <option value="">— Aucun / Non assigné —</option>
            <?php foreach ($fournisseurs as $prof): ?>
              <option value="<?= $prof['id'] ?>"
                <?= (isset($etudiant['id_fournisseur']) && $etudiant['id_fournisseur'] == $prof['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($prof['nom_fournisseur']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- ── Section 3 : Informations académiques ── -->
      <div class="form-section">
        <span class="form-section-label"><i class="fas fa-book-open" style="margin-right:6px"></i>Informations académiques</span>
        <div class="row-cf row-cf-4">
          <div>
            <label class="cf-label" for="prix_achat">Frais d'inscription (FCFA) <span class="required">*</span></label>
            <div class="input-with-icon">
              <i class="fas fa-tag"></i>
              <input type="number" class="cf-input" id="prix_achat" name="prix_achat" step="0.01"
                     placeholder="Ex : 50000"
                     value="<?= htmlspecialchars($etudiant['prix_achat'] ?? '') ?>" required>
            </div>
          </div>
          <div>
            <label class="cf-label" for="prix_vente">Frais de scolarité (FCFA) <span class="required">*</span></label>
            <div class="input-with-icon">
              <i class="fas fa-money-bill-wave"></i>
              <input type="number" class="cf-input" id="prix_vente" name="prix_vente" step="0.01"
                     placeholder="Ex : 150000"
                     value="<?= htmlspecialchars($etudiant['prix_vente'] ?? '') ?>" required>
            </div>
          </div>
          <div>
            <label class="cf-label" for="quantite_en_stock">Effectif actuel filière <span class="required">*</span></label>
            <div class="input-with-icon">
              <i class="fas fa-users"></i>
              <input type="number" class="cf-input" id="quantite_en_stock" name="quantite_en_stock"
                     placeholder="Ex : 45"
                     value="<?= htmlspecialchars($etudiant['quantite_en_stock'] ?? 0) ?>" required>
            </div>
          </div>
          <div>
            <label class="cf-label" for="seuil_alerte">Seuil d'alerte (min. étudiants) <span class="required">*</span></label>
            <div class="input-with-icon">
              <i class="fas fa-bell"></i>
              <input type="number" class="cf-input" id="seuil_alerte" name="seuil_alerte"
                     placeholder="Ex : 10"
                     value="<?= htmlspecialchars($etudiant['seuil_alerte'] ?? 10) ?>" required>
            </div>
          </div>
        </div>

        <!-- Indicateur crédits (= marge bénéficiaire) -->
        <div class="credits-display" id="credits-panel" style="display:none">
          <i class="fas fa-star"></i>
          <span class="credits-display-label">Crédits ECTS calculés</span>
          <span class="credits-display-value"><span id="marge-display">0</span> crédits</span>
        </div>
      </div>

      <!-- ── Section 4 : Notes / Observations ── -->
      <div class="form-section">
        <span class="form-section-label"><i class="fas fa-sticky-note" style="margin-right:6px"></i>Notes & observations</span>
        <div>
          <label class="cf-label" for="description">Informations complémentaires</label>
          <textarea class="cf-textarea" id="description" name="description"
                    placeholder="Bourses, situation particulière, notes académiques…"><?= htmlspecialchars($etudiant['description'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- ── Actions ── -->
      <div class="form-actions">
        <button type="submit" class="btn-cf-submit">
          <i class="fas fa-<?= $isEditMode ? 'save' : 'user-plus' ?>"></i>
          <?= $isEditMode ? 'Enregistrer les modifications' : 'Créer le dossier étudiant' ?>
        </button>
        <a href="index.php" class="btn-cf-cancel">
          <i class="fas fa-times"></i> Annuler
        </a>
      </div>

    </form>
  </div>
</div>

<script>
// Calcul crédits ECTS en temps réel (= marge bénéficiaire)
(function () {
  const achat  = document.getElementById('prix_achat');
  const vente  = document.getElementById('prix_vente');
  const display = document.getElementById('marge-display');
  const panel   = document.getElementById('credits-panel');

  function calc() {
    const a = parseFloat(achat.value)  || 0;
    const v = parseFloat(vente.value)  || 0;
    if (v > 0) {
      const credits = Math.round(((v - a) / v) * 30); // 30 crédits max par semestre
      display.textContent = Math.max(0, credits);
      panel.style.display = 'flex';
    } else {
      panel.style.display = 'none';
    }
  }
  achat.addEventListener('input', calc);
  vente.addEventListener('input', calc);
  calc();
})();
</script>