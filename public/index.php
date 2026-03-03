<?php
// Inclusion des fichiers nécessaires (modèle et vue)
require_once __DIR__ . '/../src/models/Produit.php';
require_once __DIR__ . '/../views/templates/header.php';

// Récupération de tous les produits
 $produits = getAllProduits();

// Affichage de la vue qui contient le tableau HTML
require_once __DIR__ . '/../views/produits/list.php';

// Inclusion du pied de page
require_once __DIR__ . '/../views/templates/footer.php';