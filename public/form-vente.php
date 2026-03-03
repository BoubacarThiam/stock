<?php
// On s'assure que l'utilisateur est connecté (admin ou employé peuvent vendre)
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Produit.php';
require_once __DIR__ . '/../views/templates/header.php';

// On récupère la liste de TOUS les produits pour la liste déroulante
 $produits = getAllProduits();

// On affiche la vue qui contient le formulaire de vente
require_once __DIR__ . '/../views/ventes/form-vente.php';

require_once __DIR__ . '/../views/templates/footer.php';