<?php
/*
 * ─────────────────────────────────────────────────────────────
 *  CampusFlow — index.php
 *  Anciennement : liste des produits (stock)
 *  Désormais    : liste des étudiants
 *
 *  Aucune modification de la structure BDD ni des fonctions :
 *  getAllProduits() retourne les enregistrements → affichés
 *  comme des étudiants dans la vue adaptée.
 * ─────────────────────────────────────────────────────────────
 */

require_once __DIR__ . '/../src/models/Produit.php';
require_once __DIR__ . '/../views/templates/header.php';

// Récupération de tous les enregistrements (étudiants = produits BDD)
$etudiants = getAllProduits();

// Affichage de la vue "Liste des étudiants"
require_once __DIR__ . '/../views/produits/list.php';

// Pied de page
require_once __DIR__ . '/../views/templates/footer.php';