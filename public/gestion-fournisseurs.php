<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../src/models/Fournisseur.php';
require_once __DIR__ . '/../views/templates/header.php';
 $fournisseurs = getAllFournisseurs();
require_once __DIR__ . '/../views/fournisseurs/list.php';
require_once __DIR__ . '/../views/templates/footer.php';