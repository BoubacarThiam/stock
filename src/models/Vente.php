<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Récupère toutes les ventes avec les détails du produit et de l'utilisateur.
 * @param string $dateDebut Date de début pour le filtrage (optionnel).
 * @param string $dateFin Date de fin pour le filtrage (optionnel).
 * @return array La liste des ventes.
 */
function getAllVentes($dateDebut = null, $dateFin = null) {
    global $pdo;
    $sql = "
        SELECT v.id, v.date_vente, v.quantite_vendue,
               p.nom_produit,
               u.nom AS nom_utilisateur,
               (p.prix_vente * v.quantite_vendue) AS total_vente
        FROM ventes v
        JOIN produits p ON v.id_produit = p.id
        JOIN utilisateurs u ON v.id_utilisateur = u.id
    ";
    $params = [];

    if ($dateDebut && $dateFin) {
        $sql .= " WHERE v.date_vente BETWEEN ? AND ?";
        $params[] = $dateDebut . ' 00:00:00';
        $params[] = $dateFin . ' 23:59:59';
    }

    $sql .= " ORDER BY v.date_vente DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}