<h1>Tableau de Bord</h1>
<p class="text-muted">Vue d'ensemble de la performance de votre stock.</p>

<div class="row">
    <!-- Carte 1 : Valeur du Stock -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Valeur Totale du Stock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($valeurStock, 0, ',', ' ') ?> FCFA</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte 2 : Produits en Alertes -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Produits en Alerte</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $nbAlertes ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte 3 : Profit Potentiel -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Profit Potentiel</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($profitPotentiel, 0, ',', ' ') ?> FCFA</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Carte pour le Graphique des Ventes -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Évolution des Ventes (12 derniers mois)</h6>
    </div>
    <div class="card-body">
        <div class="chart-container" style="position: relative; height:40vh; width:100%">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>
<!-- Section Top Produits -->
 
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Top 5 des Produits les Plus Vendus</h6>
    </div>
    <div class="card-body">
        <?php if (empty($topProduits)): ?>
            <p>Aucune vente enregistrée pour le moment.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($topProduits as $index => $produit): ?>
                    <div class="col-md-12">
                        <h5><?= $index + 1 ?>. <?= htmlspecialchars($produit['nom_produit']) ?></h5>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: <?= ($produit['total_vendu'] / $topProduits[0]['total_vendu']) * 100 ?>%;" aria-valuenow="<?= $produit['total_vendu'] ?>" aria-valuemin="0" aria-valuemax="<?= $topProduits[0]['total_vendu'] ?>">
                                <?= $produit['total_vendu'] ?> unités vendues
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Dans views/dashboard/dashboard.php, tout à la fin -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const salesCtx = document.getElementById('salesChart').getContext('2d');

    // On appelle notre API pour récupérer les données
    fetch('api/sales-by-month.php')
        .then(response => response.json())
        .then(data => {
            // On prépare les données pour Chart.js
            const labels = data.map(item => {
                const date = new Date(item.mois + '-01');
                return date.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
            });
            const chartData = data.map(item => item.total_ventes);

            // On configure le graphique
            new Chart(salesCtx, {
                type: 'line', // Type de graphique : 'line', 'bar', 'pie', etc.
                data: {
                    labels: labels, // Les étiquettes sur l'axe X (les mois)
                    datasets: [{
                        label: 'Chiffre d\'Affaires (FCFA)',
                        data: chartData, // Les données sur l'axe Y (les ventes)
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.1 // Rend la ligne un peu courbée
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Erreur lors de la récupération des données:', error));
});
</script>