<?php
try {
    require_once __DIR__ . '/src/config/database.php';
    echo "<h1 style='color:green;'>Connexion à la base de données réussie !</h1>";
    echo "Connecté à la base de données : " . $pdo->query('SELECT DATABASE()')->fetchColumn();
} catch (PDOException $e) {
    echo "<h1 style='color:red;'>Échec de la connexion !</h1>";
    echo "Erreur : " . $e->getMessage();
}