<?php
// Vérification de sécurité : l'utilisateur doit être connecté
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Produit.php';
require_once __DIR__ . '/../views/templates/header.php';

// On récupère toutes les données pour le tableau de bord
 $valeurStock = getValeurTotaleStock();
 $nbAlertes = getNombreProduitsEnAlerte();
 $profitPotentiel = getProfitPotentielTotal();
 $topProduits = getTopProduitsVendus();

// On affiche la vue du tableau de bord
require_once __DIR__ . '/../views/dashboard/dashboard.php';

require_once __DIR__ . '/../views/templates/footer.php';