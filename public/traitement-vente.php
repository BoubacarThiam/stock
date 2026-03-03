<?php
// Vérification de sécurité : l'utilisateur doit être connecté
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Produit.php';

// Récupération des données du formulaire
 $idProduit = $_POST['id_produit'] ?? null;
 $quantite = $_POST['quantite'] ?? null;
 $idUtilisateur = $_SESSION['user']['id']; // On récupère l'ID de l'utilisateur connecté

// Validation simple des données
if ($idProduit && $quantite && $quantite > 0) {
    // On appelle la fonction qui gère la vente (définie dans Produit.php)
    $success = vendreProduit($idProduit, $quantite, $idUtilisateur);

    if ($success) {
        $_SESSION['message'] = "Vente de $quantité unité(s) enregistrée avec succès !";
    } else {
        $_SESSION['error'] = "Erreur : Stock insuffisant pour cette vente.";
    }
} else {
    $_SESSION['error'] = "Erreur : Veuillez remplir correctement tous les champs.";
}

// Dans tous les cas, on redirige vers la page d'accueil pour voir l'état mis à jour du stock
header('Location: index.php');
exit;