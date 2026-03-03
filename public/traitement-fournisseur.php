<?php
// Vérification de sécurité : l'utilisateur doit être connecté et être un admin
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Fournisseur.php';

// On récupère l'action depuis l'URL (ex: ?action=add)
 $action = $_GET['action'] ?? '';

if ($action === 'add') {
    // Si on ajoute un nouveau fournisseur
    if (addFournisseur($_POST)) {
        $_SESSION['message'] = "Fournisseur ajouté avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout du fournisseur.";
    }
} elseif ($action === 'edit') {
    // Si on modifie un fournisseur existant
    $id = $_GET['id'];
    if (updateFournisseur($id, $_POST)) {
        $_SESSION['message'] = "Fournisseur mis à jour avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du fournisseur.";
    }
} elseif ($action === 'delete') {
    // Si on supprime un fournisseur
    $id = $_GET['id'];
    if (deleteFournisseur($id)) {
        $_SESSION['message'] = "Fournisseur supprimé avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression du fournisseur.";
    }
}

// Dans tous les cas, on redirige vers la page de la liste des fournisseurs
header('Location: gestion-fournisseurs.php');
exit;