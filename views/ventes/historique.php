<h1>Historique des Ventes</h1>

<!-- Formulaire de filtrage par date -->
<form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <label for="date_debut" class="form-label">Date de début</label>
        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= htmlspecialchars($dateDebut) ?>">
    </div>
    <div class="col-md-4">
        <label for="date_fin" class="form-label">Date de fin</label>
        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= htmlspecialchars($dateFin) ?>">
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID Vente</th>
                <th>Date</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Vendeur</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ventes)): ?>
                <tr><td colspan="6" class="text-center">Aucune vente trouvée pour cette période.</td></tr>
            <?php else: ?>
                <?php foreach ($ventes as $vente): ?>
                    <tr>
                        <td><?= $vente['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($vente['date_vente'])) ?></td>
                        <td><?= htmlspecialchars($vente['nom_produit']) ?></td>
                        <td><?= $vente['quantite_vendue'] ?></td>
                        <td><?= htmlspecialchars($vente['nom_utilisateur']) ?></td>
                        <td><strong><?= number_format($vente['total_vente'], 0, ',', ' ') ?> FCFA</strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>