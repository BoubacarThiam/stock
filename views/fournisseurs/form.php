<?php
// Le titre change en fonction du mode (ajout ou modification)
 $pageTitle = $isEditMode ? 'Modifier un Fournisseur' : 'Ajouter un Nouveau Fournisseur';
?>

<h1><?= $pageTitle ?></h1>

<!-- L'action du formulaire pointe vers notre script de traitement -->
<form action="traitement-fournisseur.php?action=<?= $isEditMode ? 'edit&id=' . $fournisseur['id'] : 'add' ?>" method="post">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nom_fournisseur" class="form-label">Nom du Fournisseur *</label>
            <input type="text" class="form-control" id="nom_fournisseur" name="nom_fournisseur" 
                   value="<?= htmlspecialchars($fournisseur['nom_fournisseur'] ?? '') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="contact_personne" class="form-label">Personne à Contacter</label>
            <input type="text" class="form-control" id="contact_personne" name="contact_personne" 
                   value="<?= htmlspecialchars($fournisseur['contact_personne'] ?? '') ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="tel" class="form-control" id="telephone" name="telephone" 
                   value="<?= htmlspecialchars($fournisseur['telephone'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?= htmlspecialchars($fournisseur['email'] ?? '') ?>">
        </div>
    </div>
    <div class="mb-3">
        <label for="adresse" class="form-label">Adresse</label>
        <textarea class="form-control" id="adresse" name="adresse" rows="3"><?= htmlspecialchars($fournisseur['adresse'] ?? '') ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">
        <?= $isEditMode ? 'Mettre à Jour' : 'Ajouter le Fournisseur' ?>
    </button>
    <a href="gestion-fournisseurs.php" class="btn btn-secondary">Annuler</a>
</form>