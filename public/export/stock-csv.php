<?php
// Vérification de sécurité
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../../src/models/Produit.php';

 $produits = getAllProduits();

// Définit les en-têtes pour forcer le téléchargement du fichier
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="etat-stock-' . date('Y-m-d') . '.csv"');

// Ouvre un flux de sortie vers le navigateur
 $output = fopen('php://output', 'w');

// Ajoute la ligne d'en-tête (noms des colonnes)
fputcsv($output, ['ID', 'Nom du Produit', 'Catégorie', 'Prix Achat', 'Prix Vente', 'Quantité', 'Valeur Totale']);

// Ajoute les données de chaque produit
foreach ($produits as $produit) {
    fputcsv($output, [
        $produit['id'],
        $produit['nom_produit'],
        $produit['nom_categorie'],
        $produit['prix_achat'],
        $produit['prix_vente'],
        $produit['quantite_en_stock'],
        $produit['quantite_en_stock'] * $produit['prix_achat'] // Calcul de la valeur
    ]);
}

// Ferme le flux
fclose($output);
exit;