<h1>Enregistrer une Vente</h1>

<p class="text-muted">Sélectionnez un produit et la quantité vendue. Le stock sera mis à jour automatiquement.</p>

<form action="traitement-vente.php" method="post">
    <div class="row">
        <div class="col-md-8 mb-3">
            <label for="id_produit" class="form-label">Produit Vendu</label>
            <select class="form-select" id="id_produit" name="id_produit" required>
                <option value="">-- Choisissez un produit --</option>
                <?php foreach ($produits as $produit): ?>
                    <option value="<?= $produit['id'] ?>">
                        <?= htmlspecialchars($produit['nom_produit']) ?> (Stock actuel : <?= $produit['quantite_en_stock'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="quantite" class="form-label">Quantité Vendue</label>
            <input type="number" class="form-control" id="quantite" name="quantite" min="1" required>
        </div>
    </div>

    <button type="submit" class="btn btn-success">
        Confirmer la Vente
    </button>
    <a href="index.php" class="btn btn-secondary">Annuler</a>
</form>