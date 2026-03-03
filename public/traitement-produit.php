<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Produit.php';

$action = $_GET['action'] ?? '';

// Sécurisation id_categorie (double sécurité)
if (isset($_POST['id_categorie'])) {
    if ($_POST['id_categorie'] === '' || $_POST['id_categorie'] === '0') {
        $_POST['id_categorie'] = null;
    }
}

switch ($action) {

    case 'add':
        if (addProduit($_POST)) {
            $_SESSION['message'] = "Produit ajouté avec succès !";
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du produit.";
        }
        break;

    case 'edit':
        $id = $_GET['id'] ?? null;
        if ($id && updateProduit($id, $_POST)) {
            $_SESSION['message'] = "Produit mis à jour avec succès !";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du produit.";
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id && deleteProduit($id)) {
            $_SESSION['message'] = "Produit supprimé avec succès !";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du produit.";
        }
        break;
}

header('Location: index.php');
exit;
