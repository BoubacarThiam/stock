<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../src/models/Produit.php';

 $term = $_GET['search'] ?? '';

if (empty($term)) {
    // Si la recherche est vide, on retourne tous les produits
    echo json_encode(getAllProduits());
    exit;
}

global $pdo;
 $stmt = $pdo->prepare('
    SELECT p.*, c.nom_categorie 
    FROM produits p 
    LEFT JOIN categories c ON p.id_categorie = c.id 
    WHERE p.nom_produit LIKE ? 
    ORDER BY p.nom_produit
');
 $stmt->execute(["%$term%"]);
 $results = $stmt->fetchAll();

echo json_encode($results);