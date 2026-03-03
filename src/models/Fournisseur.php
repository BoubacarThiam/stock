<?php
require_once __DIR__ . '/../config/database.php';

function getAllFournisseurs() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM fournisseurs ORDER BY nom_fournisseur');
    return $stmt->fetchAll();
}

function getFournisseurById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM fournisseurs WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addFournisseur($data) {
    global $pdo;
    $sql = "INSERT INTO fournisseurs (nom_fournisseur, contact_personne, telephone, email, adresse) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nom_fournisseur'],
        $data['contact_personne'],
        $data['telephone'],
        $data['email'],
        $data['adresse']
    ]);
}

function updateFournisseur($id, $data) {
    global $pdo;
    $sql = "UPDATE fournisseurs SET nom_fournisseur = ?, contact_personne = ?, telephone = ?, email = ?, adresse = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nom_fournisseur'],
        $data['contact_personne'],
        $data['telephone'],
        $data['email'],
        $data['adresse'],
        $id
    ]);
}

function deleteFournisseur($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM fournisseurs WHERE id = ?');
    return $stmt->execute([$id]);
}