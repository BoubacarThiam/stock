<?php
// Ce fichier est une "API" qui renvoie les ventes par mois au format JSON.
header('Content-Type: application/json');

require_once __DIR__ . '/../../src/config/database.php';

// Requête SQL CORRIGÉE : on fait une JOINURE entre les tables 'ventes' et 'produits'
 $sql = "
    SELECT 
        DATE_FORMAT(v.date_vente, '%Y-%m') AS mois,
        SUM(p.prix_vente * v.quantite_vendue) AS total_ventes
    FROM ventes v
    JOIN produits p ON v.id_produit = p.id
    WHERE v.date_vente >= DATE_SUB(NOW(), INTERVAL 1 YEAR)
    GROUP BY mois
    ORDER BY mois ASC
.";

 $stmt = $pdo->query($sql);
 $results = $stmt->fetchAll();

// On renvoie les résultats directement en format JSON
echo json_encode($results);