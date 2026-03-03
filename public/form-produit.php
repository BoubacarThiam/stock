<?php
// Vérification de sécurité : l'utilisateur doit être connecté et être un admin
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Produit.php';
require_once __DIR__ . '/../src/models/Fournisseur.php';
require_once __DIR__ . '/../views/templates/header.php';

// Pour l'instant, on n'a pas de modèle pour les catégories, on les met en dur.
// Dans un projet plus avancé, on les récupérerait depuis la BDD.
 $categories = [
    1 => 'Boissons',
    2 => 'Épicerie',
    3 => 'Produits Ménagers',
    4 => 'Fruits et Légumes',
    5 => 'Boulangerie',
    6 => 'Produits Frais',
    7 => 'Viandes et Poissons',
    8 => 'denrées alimentaires diverses',
    9 => 'Hygiène et Beauté',
    10 => 'Autres'
];
// Récupérer la liste des fournisseurs pour le select
 $fournisseurs = getAllFournisseurs();
// On détermine si on est en mode "ajout" ou "modification"
 $isEditMode = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']);
 $produit = [];
if ($isEditMode) {
    $produit = getProduitById($_GET['id']);
    if (!$produit) {
        $_SESSION['error'] = "Produit non trouvé.";
        header('Location: index.php');
        exit;
    }
}

// On affiche la vue du formulaire
require_once __DIR__ . '/../views/produits/form.php';

require_once __DIR__ . '/../views/templates/footer.php';

