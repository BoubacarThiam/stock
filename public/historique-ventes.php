<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/models/Vente.php';
require_once __DIR__ . '/../views/templates/header.php';

// Récupération des filtres de date s'ils existent
 $dateDebut = $_GET['date_debut'] ?? null;
 $dateFin = $_GET['date_fin'] ?? null;

 $ventes = getAllVentes($dateDebut, $dateFin);

require_once __DIR__ . '/../views/ventes/historique.php';
require_once __DIR__ . '/../views/templates/footer.php';