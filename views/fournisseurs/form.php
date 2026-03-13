<?php
/*
 * CampusFlow — src/views/fournisseurs/form.php
 * Anciennement : formulaire fournisseur
 * Désormais    : formulaire professeur
 * Variables : $isEditMode, $fournisseur (= données professeur)
 */
$pageTitle = $isEditMode ? 'Modifier le Professeur' : 'Ajouter un Professeur';
?>

<style>
/* Réutilise les styles du form produit — rien de supplémentaire nécessaire */
</style>

<!-- ══════════════════════════ EN-TÊTE ══════════════════════════ -->
<div class="form-page-header" style="display:flex;align-items:center;gap:14px;margin-bottom:28px">
  <a href="gestion-fournisseurs.php" style="width:38px;height:38px;border-radius:10px;background:var(--c-ocean-dim);border:1.5px solid var(--c-border);display:flex;align-items:center;justify-content:center;color:var(--c-ocean);text-decoration:none;transition:all .2s;flex-shrink:0"
     onmouseover="this.style.background='rgba(26,79,212,.15)'" onmouseout="this.style.background='var(--c-ocean-dim)'">
    <i class="fas fa-arrow-left"></i>
  </a>
  <div>
    <h1 style="font-weight:800;font-size:1.75rem;letter-spacing:-.04em;color:var(--c-text)">
      <?= $isEditMode
          ? 'Modifier le <span style="color:var(--c-ocean)">professeur</span>'
          : 'Ajouter un <span style="color:var(--c-ocean)">professeur</span>' ?>
    </h1>
    <p style="font-size:.82rem;color:var(--c-muted);font-family:var(--font-mono);margin-top:3px">
      <?= $isEditMode ? 'Mise à jour de la fiche enseignant' : 'Création d\'une nouvelle fiche enseignant' ?>
    </p>
  </div>
</div>

<!-- ══════════════════════════ FORMULAIRE ══════════════════════════ -->
<div style="background:var(--c-surface);border:1.5px solid var(--c-border);border-radius:18px;overflow:hidden;box-shadow:0 2px 16px rgba(26,79,212,.05)">

  <!-- Header card -->
  <div style="padding:16px 24px;border-bottom:1px solid var(--c-border);display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,rgba(26,79,212,.04),rgba(13,143,212,.02))">
    <i class="fas fa-<?= $isEditMode ? 'user-edit' : 'user-tie' ?>" style="color:var(--c-ocean)"></i>
    <span style="font-size:.875rem;font-weight:700;color:var(--c-text)">
      <?= $isEditMode ? 'Fiche enseignant — modification' : 'Nouvelle fiche enseignant' ?>
    </span>
  </div>

  <div style="padding:28px 24px">
    <form action="traitement-fournisseur.php?action=<?= $isEditMode ? 'edit&id=' . $fournisseur['id'] : 'add' ?>" method="post">

      <!-- ── Identité ── -->
      <div style="margin-bottom:24px">
        <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--c-muted);font-family:var(--font-mono);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--c-border);display:block">
          <i class="fas fa-id-card" style="margin-right:6px"></i>Identité de l'enseignant
        </span>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div>
            <label style="display:block;font-size:.82rem;font-weight:600;color:var(--c-text-soft);margin-bottom:6px" for="nom_fournisseur">
              Nom complet <span style="color:var(--c-ocean)">*</span>
            </label>
            <div style="position:relative">
              <i class="fas fa-user" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--c-muted);font-size:.8rem;pointer-events:none"></i>
              <input type="text" id="nom_fournisseur" name="nom_fournisseur"
                     style="width:100%;padding:11px 14px 11px 38px;background:var(--c-bg);border:1.5px solid var(--c-border);border-radius:10px;font-size:.875rem;font-family:var(--font);color:var(--c-text);outline:none;transition:border-color .2s,box-shadow .2s"
                     placeholder="Ex : Dr. Aliou Mbaye"
                     value="<?= htmlspecialchars($fournisseur['nom_fournisseur'] ?? '') ?>" required
                     onfocus="this.style.borderColor='var(--c-border-lit)';this.style.boxShadow='0 0 0 3px var(--c-ocean-dim)'"
                     onblur="this.style.borderColor='var(--c-border)';this.style.boxShadow='none'">
            </div>
          </div>
          <div>
            <label style="display:block;font-size:.82rem;font-weight:600;color:var(--c-text-soft);margin-bottom:6px" for="contact_personne">
              Spécialité / Discipline
            </label>
            <div style="position:relative">
              <i class="fas fa-book" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--c-muted);font-size:.8rem;pointer-events:none"></i>
              <input type="text" id="contact_personne" name="contact_personne"
                     style="width:100%;padding:11px 14px 11px 38px;background:var(--c-bg);border:1.5px solid var(--c-border);border-radius:10px;font-size:.875rem;font-family:var(--font);color:var(--c-text);outline:none;transition:border-color .2s,box-shadow .2s"
                     placeholder="Ex : Mathématiques, Informatique…"
                     value="<?= htmlspecialchars($fournisseur['contact_personne'] ?? '') ?>"
                     onfocus="this.style.borderColor='var(--c-border-lit)';this.style.boxShadow='0 0 0 3px var(--c-ocean-dim)'"
                     onblur="this.style.borderColor='var(--c-border)';this.style.boxShadow='none'">
            </div>
          </div>
        </div>
      </div>

      <!-- ── Coordonnées ── -->
      <div style="margin-bottom:24px">
        <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--c-muted);font-family:var(--font-mono);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--c-border);display:block">
          <i class="fas fa-address-book" style="margin-right:6px"></i>Coordonnées
        </span>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
          <div>
            <label style="display:block;font-size:.82rem;font-weight:600;color:var(--c-text-soft);margin-bottom:6px" for="telephone">Téléphone</label>
            <div style="position:relative">
              <i class="fas fa-phone" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--c-muted);font-size:.8rem;pointer-events:none"></i>
              <input type="tel" id="telephone" name="telephone"
                     style="width:100%;padding:11px 14px 11px 38px;background:var(--c-bg);border:1.5px solid var(--c-border);border-radius:10px;font-size:.875rem;font-family:var(--font);color:var(--c-text);outline:none;transition:border-color .2s,box-shadow .2s"
                     placeholder="+221 XX XXX XX XX"
                     value="<?= htmlspecialchars($fournisseur['telephone'] ?? '') ?>"
                     onfocus="this.style.borderColor='var(--c-border-lit)';this.style.boxShadow='0 0 0 3px var(--c-ocean-dim)'"
                     onblur="this.style.borderColor='var(--c-border)';this.style.boxShadow='none'">
            </div>
          </div>
          <div>
            <label style="display:block;font-size:.82rem;font-weight:600;color:var(--c-text-soft);margin-bottom:6px" for="email">Adresse e-mail</label>
            <div style="position:relative">
              <i class="fas fa-envelope" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--c-muted);font-size:.8rem;pointer-events:none"></i>
              <input type="email" id="email" name="email"
                     style="width:100%;padding:11px 14px 11px 38px;background:var(--c-bg);border:1.5px solid var(--c-border);border-radius:10px;font-size:.875rem;font-family:var(--font);color:var(--c-text);outline:none;transition:border-color .2s,box-shadow .2s"
                     placeholder="prof@universite.sn"
                     value="<?= htmlspecialchars($fournisseur['email'] ?? '') ?>"
                     onfocus="this.style.borderColor='var(--c-border-lit)';this.style.boxShadow='0 0 0 3px var(--c-ocean-dim)'"
                     onblur="this.style.borderColor='var(--c-border)';this.style.boxShadow='none'">
            </div>
          </div>
        </div>
        <div>
          <label style="display:block;font-size:.82rem;font-weight:600;color:var(--c-text-soft);margin-bottom:6px" for="adresse">Bureau / Adresse</label>
          <textarea id="adresse" name="adresse" rows="3"
                    style="width:100%;padding:11px 14px;background:var(--c-bg);border:1.5px solid var(--c-border);border-radius:10px;font-size:.875rem;font-family:var(--font);color:var(--c-text);outline:none;resize:vertical;min-height:80px;transition:border-color .2s,box-shadow .2s"
                    placeholder="Bâtiment A, Bureau 204…"
                    onfocus="this.style.borderColor='var(--c-border-lit)';this.style.boxShadow='0 0 0 3px var(--c-ocean-dim)'"
                    onblur="this.style.borderColor='var(--c-border)';this.style.boxShadow='none'"><?= htmlspecialchars($fournisseur['adresse'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- ── Actions ── -->
      <div style="display:flex;align-items:center;gap:12px;padding-top:8px">
        <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:10px;border:none;cursor:pointer;font-size:.9rem;font-weight:700;font-family:var(--font);background:linear-gradient(135deg,var(--c-ocean),var(--c-ocean-dark));color:#fff;box-shadow:0 3px 16px rgba(26,79,212,.35);transition:all .2s"
                onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 24px rgba(26,79,212,.45)'"
                onmouseout="this.style.transform='none';this.style.boxShadow='0 3px 16px rgba(26,79,212,.35)'">
          <i class="fas fa-<?= $isEditMode ? 'save' : 'user-tie' ?>"></i>
          <?= $isEditMode ? 'Enregistrer les modifications' : 'Ajouter le professeur' ?>
        </button>
        <a href="gestion-fournisseurs.php" style="display:inline-flex;align-items:center;gap:8px;padding:12px 20px;border-radius:10px;text-decoration:none;font-size:.875rem;font-weight:600;font-family:var(--font);color:var(--c-text-soft);background:var(--c-bg);border:1.5px solid var(--c-border);transition:all .2s"
           onmouseover="this.style.borderColor='var(--c-border-lit)';this.style.color='var(--c-text)'"
           onmouseout="this.style.borderColor='var(--c-border)';this.style.color='var(--c-text-soft)'">
          <i class="fas fa-times"></i> Annuler
        </a>
      </div>

    </form>
  </div>
</div>