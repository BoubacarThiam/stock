<?php
// Vérification de sécurité : l'utilisateur doit être connecté
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Produit.php';
require_once __DIR__ . '/../views/templates/header.php';

/*
 * ─────────────────────────────────────────────────────────────
 *  MAPPING des fonctions existantes → contexte universitaire
 *
 *  getValeurTotaleStock()    → nb total d'étudiants inscrits (réutilisation)
 *  getNombreProduitsEnAlerte() → nb de cours sans professeur assigné
 *  getProfitPotentielTotal()  → montant total des frais d'inscription
 *  getTopProduitsVendus()     → top cours les plus demandés
 * ─────────────────────────────────────────────────────────────
 */
$totalInscriptions  = getValeurTotaleStock();       // ← nb inscriptions / valeur stock
$coursNonAssignes   = getNombreProduitsEnAlerte();  // ← cours sans prof / alertes stock
$totalFrais         = getProfitPotentielTotal();    // ← frais scolarité / profit potentiel
$topCours           = getTopProduitsVendus();       // ← top cours / top produits vendus

// On affiche la vue du tableau de bord universitaire
require_once __DIR__ . '/../views/dashboard/dashboard.php';
require_once __DIR__ . '/../views/templates/footer.php';