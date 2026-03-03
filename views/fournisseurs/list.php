<h1>Gestion des Fournisseurs</h1>
<a href="form-fournisseur.php" class="btn btn-success mb-3">Ajouter un Fournisseur</a>
<table class="table table-striped">
    <thead><tr><th>Nom</th><th>Contact</th><th>Téléphone</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($fournisseurs as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['nom_fournisseur']) ?></td>
            <td><?= htmlspecialchars($f['contact_personne']) ?></td>
            <td><?= htmlspecialchars($f['telephone']) ?></td>
            <td>
                <a href="form-fournisseur.php?action=edit&id=<?= $f['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                <a href="traitement-fournisseur.php?action=delete&id=<?= $f['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>