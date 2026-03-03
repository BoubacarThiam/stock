<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>État du Stock</h1>
    <a href="export/stock-csv.php" class="btn btn-outline-success">
        <i class="fas fa-download"></i> Exporter en CSV
    </a>
    <!-- Champ de recherche pour la fonctionnalité AJAX -->
    <input type="text" id="search-input" class="form-control w-50" placeholder="Rechercher un produit...">
</div>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php 
        echo $_SESSION['message']; 
        unset($_SESSION['message']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Prix de Vente</th>
            <th>Quantité</th>
            <th>Statut</th>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody id="product-table-body">
        <?php if (empty($produits)): ?>
            <tr>
                <td colspan="6" class="text-center">Aucun produit trouvé.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($produits as $produit): ?>
                <tr class="<?php if ($produit['quantite_en_stock'] < $produit['seuil_alerte']) echo 'table-danger'; ?>">
                    <td><?= htmlspecialchars($produit['nom_produit']) ?></td>
                    <td><?= htmlspecialchars($produit['nom_categorie'] ?? 'Non catégorisé') ?></td>
                    <td><?= number_format($produit['prix_vente'], 0, ',', ' ') ?> FCFA</td>
                    <td><?= $produit['quantite_en_stock'] ?></td>
                    <td>
                        <?php if ($produit['quantite_en_stock'] < $produit['seuil_alerte']): ?>
                            <span class="badge bg-danger">À réapprovisionner</span>
                        <?php else: ?>
                            <span class="badge bg-success">OK</span>
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <td>
                        <a href="form-produit.php?action=edit&id=<?= $produit['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="traitement-produit.php?action=delete&id=<?= $produit['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>