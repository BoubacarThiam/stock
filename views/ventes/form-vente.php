<?php
/*
 * CampusFlow — src/views/ventes/form-vente.php
 * Anciennement : formulaire enregistrement vente
 * Désormais    : formulaire nouvelle inscription étudiant
 * Variable disponible : $produits (= liste des cours disponibles)
 */
?>

<style>
/* ════════════════════════════════════
   FORMULAIRE INSCRIPTION — CampusFlow
════════════════════════════════════ */
.insc-layout { display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start; }

/* Card principale */
.insc-card {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 18px; overflow: hidden; box-shadow: 0 2px 16px rgba(26,79,212,0.05);
}
.insc-card-header {
  padding: 16px 24px; border-bottom: 1px solid var(--c-border);
  display: flex; align-items: center; gap: 10px;
  background: linear-gradient(135deg, rgba(26,79,212,0.04), rgba(13,143,212,0.02));
}
.insc-card-header i { color: var(--c-ocean); }
.insc-card-header span { font-size: 0.875rem; font-weight: 700; color: var(--c-text); }
.insc-card-body { padding: 28px 24px; }

/* Select cours amélioré */
.cours-select-wrap { position: relative; margin-bottom: 20px; }
.cours-select-wrap i.select-icon {
  position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
  color: var(--c-ocean); font-size: 0.85rem; pointer-events: none; z-index: 1;
}
.cours-select {
  width: 100%; padding: 13px 14px 13px 42px;
  background: var(--c-bg); border: 1.5px solid var(--c-border);
  border-radius: 12px; font-size: 0.9rem; font-family: var(--font);
  color: var(--c-text); outline: none; cursor: pointer;
  transition: border-color 0.2s, box-shadow 0.2s;
  -webkit-appearance: none;
}
.cours-select:focus { border-color: var(--c-border-lit); box-shadow: 0 0 0 3px var(--c-ocean-dim); background: var(--c-surface); }

/* Preview cours sélectionné */
.cours-preview {
  background: linear-gradient(135deg, rgba(26,79,212,0.06), rgba(13,143,212,0.04));
  border: 1.5px solid var(--c-border); border-radius: 12px; padding: 18px;
  margin-bottom: 20px; display: none;
}
.cours-preview.visible { display: block; animation: preview-in 0.3s ease; }
@keyframes preview-in { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.cours-preview-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--c-muted); font-family: var(--font-mono); margin-bottom: 10px; }
.cours-preview-name { font-size: 1.05rem; font-weight: 700; color: var(--c-text); margin-bottom: 4px; }
.cours-preview-meta { font-size: 0.82rem; color: var(--c-text-soft); display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.preview-chip { display: inline-flex; align-items: center; gap: 5px; background: var(--c-ocean-dim); color: var(--c-ocean); padding: 3px 9px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; font-family: var(--font-mono); }

/* Quantité avec +/- -->
.qty-wrap { margin-bottom: 20px; }
.qty-ctrl { display: flex; align-items: center; gap: 0; border: 1.5px solid var(--c-border); border-radius: 12px; overflow: hidden; width: fit-content; }
.qty-btn {
  width: 44px; height: 44px; background: var(--c-bg); border: none; cursor: pointer;
  font-size: 1rem; color: var(--c-ocean); font-weight: 700; transition: background 0.15s;
  display: flex; align-items: center; justify-content: center;
}
.qty-btn:hover { background: var(--c-ocean-dim); }
.qty-input {
  width: 70px; height: 44px; border: none; border-left: 1.5px solid var(--c-border); border-right: 1.5px solid var(--c-border);
  text-align: center; font-size: 1.1rem; font-weight: 700; color: var(--c-text);
  font-family: var(--font); background: var(--c-surface); outline: none;
}

/* Récapitulatif latéral -->
.recap-card {
  background: var(--c-surface); border: 1.5px solid var(--c-border);
  border-radius: 18px; overflow: hidden; position: sticky; top: 90px;
}
.recap-header {
  padding: 16px 20px; border-bottom: 1px solid var(--c-border);
  background: linear-gradient(135deg, var(--c-ocean), var(--c-ocean-dark));
}
.recap-header span { font-size: 0.875rem; font-weight: 700; color: rgba(255,255,255,0.9); }
.recap-body { padding: 20px; }
.recap-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.recap-lbl { font-size: 0.82rem; color: var(--c-muted); }
.recap-val { font-size: 0.875rem; font-weight: 600; color: var(--c-text); }
.recap-divider { height: 1px; background: var(--c-border); margin: 14px 0; }
.recap-total-lbl { font-size: 0.9rem; font-weight: 700; color: var(--c-text); }
.recap-total-val { font-size: 1.4rem; font-weight: 800; color: var(--c-ocean); letter-spacing: -0.03em; }
.recap-note { font-size: 0.78rem; color: var(--c-muted); margin-top: 14px; line-height: 1.5; display: flex; gap: 7px; }
.recap-note i { color: var(--c-ocean); margin-top: 1px; flex-shrink: 0; font-size: 0.75rem; }

/* Bouton submit */
.btn-insc-submit {
  width: 100%; padding: 14px; margin-top: 16px;
  background: linear-gradient(135deg, var(--c-ocean), var(--c-ocean-dark));
  color: #fff; font-weight: 700; font-size: 0.95rem; font-family: var(--font);
  border: none; border-radius: 12px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 9px;
  box-shadow: 0 4px 20px rgba(26,79,212,0.35); transition: all 0.2s;
}
.btn-insc-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 28px rgba(26,79,212,0.5); }

@media(max-width:991px) { .insc-layout { grid-template-columns: 1fr; } .recap-card { position: static; } }
</style>

<!-- ══════════════════════════ EN-TÊTE ══════════════════════════ -->
<div style="display:flex;align-items:center;gap:14px;margin-bottom:28px">
  <a href="historique-ventes.php" style="width:38px;height:38px;border-radius:10px;background:var(--c-ocean-dim);border:1.5px solid var(--c-border);display:flex;align-items:center;justify-content:center;color:var(--c-ocean);text-decoration:none;flex-shrink:0"
     onmouseover="this.style.background='rgba(26,79,212,.15)'" onmouseout="this.style.background='var(--c-ocean-dim)'">
    <i class="fas fa-arrow-left"></i>
  </a>
  <div>
    <h1 style="font-weight:800;font-size:1.75rem;letter-spacing:-.04em;color:var(--c-text)">
      Nouvelle <span style="color:var(--c-ocean)">inscription</span>
    </h1>
    <p style="font-size:.82rem;color:var(--c-muted);font-family:var(--font-mono);margin-top:3px">
      Inscrire un étudiant à un cours · Le stock sera mis à jour automatiquement
    </p>
  </div>
</div>

<!-- ══════════════════════════ LAYOUT PRINCIPAL ══════════════════════════ -->
<div class="insc-layout">

  <!-- ── Formulaire ── -->
  <div class="insc-card">
    <div class="insc-card-header">
      <i class="fas fa-file-signature"></i>
      <span>Formulaire d'inscription</span>
    </div>
    <div class="insc-card-body">

      <form action="traitement-vente.php" method="post" id="inscForm">

        <!-- Section 1 : Sélection du cours -->
        <div style="margin-bottom:24px">
          <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--c-muted);font-family:var(--font-mono);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--c-border);display:block">
            <i class="fas fa-book-open" style="margin-right:6px"></i>Sélection du cours
          </span>

          <label style="display:block;font-size:.82rem;font-weight:600;color:var(--c-text-soft);margin-bottom:8px" for="id_produit">
            Cours / Matière <span style="color:var(--c-ocean)">*</span>
          </label>
          <div class="cours-select-wrap">
            <i class="fas fa-book-open select-icon"></i>
            <select class="cours-select" id="id_produit" name="id_produit" required onchange="updatePreview(this)">
              <option value="">— Choisir un cours —</option>
              <?php foreach ($produits as $cours): ?>
                <option value="<?= $cours['id'] ?>"
                        data-nom="<?= htmlspecialchars($cours['nom_produit']) ?>"
                        data-stock="<?= $cours['quantite_en_stock'] ?>"
                        data-prix="<?= $cours['prix_vente'] ?>">
                  <?= htmlspecialchars($cours['nom_produit']) ?>
                  (Places disponibles : <?= $cours['quantite_en_stock'] ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Preview cours -->
          <div class="cours-preview" id="coursPreview">
            <div class="cours-preview-title">Cours sélectionné</div>
            <div class="cours-preview-name" id="previewNom">—</div>
            <div class="cours-preview-meta">
              <span class="preview-chip"><i class="fas fa-users" style="font-size:.65rem"></i> <span id="previewStock">0</span> places dispo.</span>
              <span class="preview-chip"><i class="fas fa-money-bill-wave" style="font-size:.65rem"></i> <span id="previewPrix">0</span> FCFA</span>
            </div>
          </div>
        </div>

        <!-- Section 2 : Nombre d'inscriptions -->
        <div style="margin-bottom:28px">
          <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--c-muted);font-family:var(--font-mono);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--c-border);display:block">
            <i class="fas fa-users" style="margin-right:6px"></i>Nombre d'étudiants à inscrire
          </span>

          <label style="display:block;font-size:.82rem;font-weight:600;color:var(--c-text-soft);margin-bottom:10px">
            Quantité inscrite <span style="color:var(--c-ocean)">*</span>
          </label>
          <div class="qty-wrap">
            <div class="qty-ctrl">
              <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
              <input type="number" class="qty-input" id="quantite" name="quantite"
                     min="1" value="1" required onchange="updateRecap()">
              <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <button type="submit" class="btn-cf-submit" style="flex:1;min-width:200px;padding:13px;border-radius:11px;border:none;cursor:pointer;font-size:.9rem;font-weight:700;font-family:var(--font);background:linear-gradient(135deg,var(--c-ocean),var(--c-ocean-dark));color:#fff;box-shadow:0 3px 16px rgba(26,79,212,.35);display:inline-flex;align-items:center;justify-content:center;gap:9px;transition:all .2s"
                  onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 24px rgba(26,79,212,.5)'"
                  onmouseout="this.style.transform='none';this.style.boxShadow='0 3px 16px rgba(26,79,212,.35)'">
            <i class="fas fa-check-circle"></i> Confirmer l'inscription
          </button>
          <a href="index.php" style="display:inline-flex;align-items:center;gap:7px;padding:13px 20px;border-radius:11px;text-decoration:none;font-size:.875rem;font-weight:600;font-family:var(--font);color:var(--c-text-soft);background:var(--c-bg);border:1.5px solid var(--c-border);transition:all .2s"
             onmouseover="this.style.borderColor='var(--c-border-lit)'" onmouseout="this.style.borderColor='var(--c-border)'">
            <i class="fas fa-times"></i> Annuler
          </a>
        </div>

      </form>
    </div>
  </div>

  <!-- ── Récapitulatif ── -->
  <div class="recap-card">
    <div class="recap-header">
      <span><i class="fas fa-receipt" style="margin-right:7px;opacity:.8"></i>Récapitulatif</span>
    </div>
    <div class="recap-body">
      <div class="recap-row">
        <span class="recap-lbl">Cours sélectionné</span>
        <span class="recap-val" id="rcapCours" style="max-width:160px;text-align:right;font-size:.8rem">—</span>
      </div>
      <div class="recap-row">
        <span class="recap-lbl">Nb étudiants</span>
        <span class="recap-val" id="rcapQty">1</span>
      </div>
      <div class="recap-row">
        <span class="recap-lbl">Frais unitaires</span>
        <span class="recap-val" id="rcapPrix">— FCFA</span>
      </div>
      <div class="recap-divider"></div>
      <div class="recap-row">
        <span class="recap-total-lbl">Total à percevoir</span>
        <span class="recap-total-val" id="rcapTotal">— FCFA</span>
      </div>
      <div class="recap-note">
        <i class="fas fa-info-circle"></i>
        Le nombre de places disponibles sera mis à jour automatiquement après confirmation.
      </div>

      <button type="submit" form="inscForm" class="btn-insc-submit">
        <i class="fas fa-check-circle"></i> Confirmer
      </button>
    </div>
  </div>

</div>

<script>
// Données des cours pour le preview
const coursData = {};
document.querySelectorAll('#id_produit option[value]').forEach(opt => {
  if (opt.value) {
    coursData[opt.value] = {
      nom:   opt.dataset.nom,
      stock: parseInt(opt.dataset.stock) || 0,
      prix:  parseFloat(opt.dataset.prix) || 0
    };
  }
});

function updatePreview(sel) {
  const preview = document.getElementById('coursPreview');
  const val = sel.value;
  if (val && coursData[val]) {
    const c = coursData[val];
    document.getElementById('previewNom').textContent   = c.nom;
    document.getElementById('previewStock').textContent = c.stock;
    document.getElementById('previewPrix').textContent  = c.prix.toLocaleString('fr-FR');
    preview.classList.add('visible');
    document.getElementById('rcapCours').textContent = c.nom;
    document.getElementById('rcapPrix').textContent  = c.prix.toLocaleString('fr-FR') + ' FCFA';
  } else {
    preview.classList.remove('visible');
    document.getElementById('rcapCours').textContent = '—';
    document.getElementById('rcapPrix').textContent  = '— FCFA';
  }
  updateRecap();
}

function updateRecap() {
  const sel = document.getElementById('id_produit');
  const qty = parseInt(document.getElementById('quantite').value) || 1;
  document.getElementById('rcapQty').textContent = qty;
  if (sel.value && coursData[sel.value]) {
    const total = coursData[sel.value].prix * qty;
    document.getElementById('rcapTotal').textContent = total.toLocaleString('fr-FR') + ' FCFA';
  } else {
    document.getElementById('rcapTotal').textContent = '— FCFA';
  }
}

function changeQty(delta) {
  const input = document.getElementById('quantite');
  const newVal = Math.max(1, (parseInt(input.value) || 1) + delta);
  input.value = newVal;
  updateRecap();
}
</script>