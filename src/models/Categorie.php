<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Récupère toutes les catégories
 * @return array
 */
function getAllCategories() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT id, nom_categorie 
        FROM categories 
        ORDER BY nom_categorie
    ");
    return $stmt->fetchAll();
}
