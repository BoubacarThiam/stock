// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {

    // --- Fonctionnalité 1: Calcul de la marge bénéficiaire ---
    const prixAchatInput = document.getElementById('prix_achat');
    const prixVenteInput = document.getElementById('prix_vente');
    const margeDisplay = document.getElementById('marge-display');

    function calculerMarge() {
        const prixAchat = parseFloat(prixAchatInput.value) || 0;
        const prixVente = parseFloat(prixVenteInput.value) || 0;

        if (prixAchat > 0) {
            const marge = ((prixVente - prixAchat) / prixAchat) * 100;
            margeDisplay.textContent = marge.toFixed(2);
        } else {
            margeDisplay.textContent = '0';
        }
    }

    if (prixAchatInput && prixVenteInput) {
        prixAchatInput.addEventListener('input', calculerMarge);
        prixVenteInput.addEventListener('input', calculerMarge);
    }


    // --- Fonctionnalité 2: Recherche en temps réel (AJAX) ---
    const searchInput = document.getElementById('search-input');
    const productTableBody = document.getElementById('product-table-body');

    if (searchInput && productTableBody) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value;

            // Utiliser l'API Fetch pour envoyer la requête de recherche
            fetch(`api-search.php?search=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    // Vider le tableau actuel
                    productTableBody.innerHTML = '';

                    if (data.length === 0) {
                        productTableBody.innerHTML = '<tr><td colspan="6" class="text-center">Aucun produit trouvé.</td></tr>';
                        return;
                    }

                    // Reconstruire le tableau avec les résultats
                    data.forEach(produit => {
                        const row = document.createElement('tr');
                        // Ajouter la classe 'table-danger' si le stock est bas
                        if (produit.quantite_en_stock < produit.seuil_alerte) {
                            row.classList.add('table-danger');
                        }
                        
                        // Remplir les cellules
                        // Note: La partie "Actions" est simplifiée ici pour l'exemple
                        row.innerHTML = `
                            <td>${produit.nom_produit}</td>
                            <td>${produit.nom_categorie || 'Non catégorisé'}</td>
                            <td>${Number(produit.prix_vente).toLocaleString('fr-FR')} FCFA</td>
                            <td>${produit.quantite_en_stock}</td>
                            <td>
                                ${produit.quantite_en_stock < produit.seuil_alerte 
                                    ? '<span class="badge bg-danger">À réapprovisionner</span>' 
                                    : '<span class="badge bg-success">OK</span>'}
                            </td>
                            <td>
                                <!-- Les actions devraient être gérées plus dynamiquement si besoin -->
                                <a href="form-produit.php?action=edit&id=${produit.id}" class="btn btn-sm btn-primary">Modifier</a>
                                <a href="traitement-produit.php?action=delete&id=${produit.id}" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
                            </td>
                        `;
                        productTableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Erreur:', error));
        });
    }
});