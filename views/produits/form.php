<?php
// On détermine si on est en mode "ajout" ou "modification"
 $isEditMode = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']);
 $produit = [];
if ($isEditMode) {
    // Récupérer les données du produit à modifier
    $produit = getProduitById($_GET['id']);
    if (!$produit) {
        // Rediriger si le produit n'existe pas
        header('Location: index.php');
        exit;
    }
}
?>

<h1><?= $isEditMode ? 'Modifier un Produit' : 'Ajouter un Nouveau Produit' ?></h1>

<form action="traitement-produit.php?action=<?= $isEditMode ? 'edit&id=' . $produit['id'] : 'add' ?>" method="post">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nom_produit" class="form-label">Nom du Produit</label>
            <input type="text" class="form-control" id="nom_produit" name="nom_produit" value="<?= htmlspecialchars($produit['nom_produit'] ?? '') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="id_categorie" class="form-label">Catégorie</label>
            <select class="form-select" id="id_categorie" name="id_categorie">
                <option value="">Sélectionner une catégorie</option>
                <!-- Exemple statique, à dynamiser -->
                <option value="1" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 1) ? 'selected' : '' ?>>Boissons</option>
                <option value="2" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 2) ? 'selected' : '' ?>>Épicerie</option>
                <option value="3" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 3) ? 'selected' : '' ?>>Produits Ménagers</option>
                <option value="4" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 4) ? 'selected' : '' ?>>Fruits et Légumes</option>
                <option value="5" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 5) ? 'selected' : '' ?>>Boulangerie</option>
                <option value="6" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 6) ? 'selected' : '' ?>>Produits Frais</option>
                <option value="7" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 7) ? 'selected' : '' ?>>Viandes et Poissons</option>
                <option value="8" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 8) ? 'selected' : '' ?>>denrées alimentaires diverses</option>
                <option value="9" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 9) ? 'selected' : '' ?>>Hygiène et Beauté</option>
                <option value="10" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == 10) ? 'selected' : '' ?>>Autres</option>
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
    <label for="id_fournisseur" class="form-label">Fournisseur Principal</label>
    <select class="form-select" id="id_fournisseur" name="id_fournisseur">
        <option value="">-- Aucun / Non spécifié --</option>
        <?php
        // On utilise la variable $fournisseurs passée par le contrôleur
        // Si ce n'est pas fait, ajoutez `global $fournisseurs;` ici
        foreach ($fournisseurs as $fournisseur):
        ?>
            <option value="<?= $fournisseur['id'] ?>" <?= (isset($produit['id_fournisseur']) && $produit['id_fournisseur'] == $fournisseur['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($fournisseur['nom_fournisseur']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($produit['description'] ?? '') ?></textarea>
    </div>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="prix_achat" class="form-label">Prix d'Achat (FCFA)</label>
            <input type="number" class="form-control" id="prix_achat" name="prix_achat" step="0.01" value="<?= htmlspecialchars($produit['prix_achat'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 mb-3">
            <label for="prix_vente" class="form-label">Prix de Vente (FCFA)</label>
            <input type="number" class="form-control" id="prix_vente" name="prix_vente" step="0.01" value="<?= htmlspecialchars($produit['prix_vente'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 mb-3">
            <label for="quantite_en_stock" class="form-label">Quantité en Stock</label>
            <input type="number" class="form-control" id="quantite_en_stock" name="quantite_en_stock" value="<?= htmlspecialchars($produit['quantite_en_stock'] ?? 0) ?>" required>
        </div>
        <div class="col-md-3 mb-3">
            <label for="seuil_alerte" class="form-label">Seuil d'Alerte</label>
            <input type="number" class="form-control" id="seuil_alerte" name="seuil_alerte" value="<?= htmlspecialchars($produit['seuil_alerte'] ?? 10) ?>" required>
        </div>
    </div>
    
    <!-- Affichage de la marge bénéficiaire calculée par JS -->
    <div class="mb-3">
        <label class="form-label">Marge Bénéficiaire Estimée</label>
        <div><span id="marge-display">0</span>%</div>
    </div>

    <button type="submit" class="btn btn-success"><?= $isEditMode ? 'Mettre à Jour' : 'Ajouter le Produit' ?></button>
    <a href="index.php" class="btn btn-secondary">Annuler</a>
</form>