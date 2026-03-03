<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Récupère un utilisateur par son email.
 * @param string $email L'email de l'utilisateur.
 * @return array|false Les données de l'utilisateur ou false.
 */
function getUtilisateurByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

/**
 * Récupère un utilisateur par son ID.
 * @param int $id L'ID de l'utilisateur.
 * @return array|false Les données de l'utilisateur ou false.
 */
function getUtilisateurById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT id, nom, email, role FROM utilisateurs WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}