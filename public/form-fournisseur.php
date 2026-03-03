<?php
// Vérification de sécurité : l'utilisateur doit être connecté et être un admin
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Fournisseur.php';
require_once __DIR__ . '/../views/templates/header.php';

// On détermine si on est en mode "ajout" ou "modification"
 $isEditMode = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']);
 $fournisseur = []; // Initialise un tableau vide

if ($isEditMode) {
    // Si on est en mode "édition", on récupère les données du fournisseur à modifier
    $fournisseur = getFournisseurById($_GET['id']);
    if (!$fournisseur) {
        // Si le fournisseur n'existe pas, on redirige avec un message d'erreur
        $_SESSION['error'] = "Fournisseur non trouvé.";
        header('Location: gestion-fournisseurs.php');
        exit;
    }
}

// On affiche la vue qui contient le formulaire HTML
require_once __DIR__ . '/../views/fournisseurs/form.php';

require_once __DIR__ . '/../views/templates/footer.php';