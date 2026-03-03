<?php
// Configuration de la connexion à la base de données
 $host = 'localhost';
 $db   = 'gestion_stock'; // Le nom de votre BDD
 $user = 'root';          // Votre utilisateur MySQL (par défaut dans XAMPP/MAMP)
 $pass = 'root123';              // Votre mot de passe MySQL (vide par défaut)
 $charset = 'utf8mb4';

// Data Source Name (DSN)
 $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options PDO pour une meilleure gestion des erreurs et de la sécurité
 $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lancer des exceptions en cas d'erreur
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Récupérer les résultats sous forme de tableaux associatifs
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Utiliser les requêtes préparées natives
];

try {
    // Tentative de création de l'objet PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En cas d'échec, on arrête le script et on affiche un message d'erreur
    // En production, ne jamais afficher les détails de l'erreur !
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}