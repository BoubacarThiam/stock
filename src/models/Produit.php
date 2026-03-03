<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Récupère tous les produits de la base de données, avec leur catégorie.
 * @return array La liste des produits.
 */
function getAllProduits() {
    global $pdo;
    $stmt = $pdo->query('
        SELECT p.*, c.nom_categorie 
        FROM produits p 
        LEFT JOIN categories c ON p.id_categorie = c.id 
        ORDER BY p.nom_produit
    ');
    return $stmt->fetchAll();
}

/**
 * Récupère un produit spécifique par son ID.
 * @param int $id L'ID du produit.
 * @return array|false Les données du produit ou false si non trouvé.
 */
function getProduitById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM produits WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Ajoute un nouveau produit dans la base de données.
 * @param array $data Les données du produit (nom, description, prix, etc.).
 * @return bool True en cas de succès, false en cas d'échec.
 */
function addProduit($data) {
    global $pdo;
    $sql = "INSERT INTO produits (nom_produit, description, prix_achat, prix_vente, quantite_en_stock, seuil_alerte, id_categorie) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nom_produit'],
        $data['description'],
        $data['prix_achat'],
        $data['prix_vente'],
        $data['quantite_en_stock'],
        $data['seuil_alerte'],
        $data['id_categorie']
    ]);
}

/**
 * Met à jour un produit existant.
 * @param int $id L'ID du produit à mettre à jour.
 * @param array $data Les nouvelles données du produit.
 * @return bool True en cas de succès.
 */
function updateProduit($id, $data) {
    global $pdo;
    $sql = "UPDATE produits SET nom_produit = ?, description = ?, prix_achat = ?, prix_vente = ?, quantite_en_stock = ?, seuil_alerte = ?, id_categorie = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nom_produit'],
        $data['description'],
        $data['prix_achat'],
        $data['prix_vente'],
        $data['quantite_en_stock'],
        $data['seuil_alerte'],
        $data['id_categorie'],
        $id
    ]);
}

/**
 * Supprime un produit de la base de données.
 * @param int $id L'ID du produit à supprimer.
 * @return bool True en cas de succès.
 */
function deleteProduit($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM produits WHERE id = ?');
    return $stmt->execute([$id]);
}

/**
 * Enregistre une vente : décrémente le stock et ajoute une entrée dans la table des ventes.
 * @param int $idProduit L'ID du produit vendu.
 * @param int $quantite La quantité vendue.
 * @param int $idUtilisateur L'ID de l'utilisateur qui enregistre la vente.
 * @return bool True en cas de succès.
 */
function vendreProduit($idProduit, $quantite, $idUtilisateur) {
    global $pdo;
    
    // On utilise une transaction pour s'assurer que les deux opérations (mise à jour du stock et ajout de la vente) réussissent ensemble.
    $pdo->beginTransaction();
    try {
        // 1. Mettre à jour la quantité en stock
        $sqlUpdate = 'UPDATE produits SET quantite_en_stock = quantite_en_stock - ? WHERE id = ? AND quantite_en_stock >= ?';
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$quantite, $idProduit, $quantite]);

        // Vérifier si une ligne a bien été affectée (sinon, stock insuffisant)
        if ($stmtUpdate->rowCount() == 0) {
            throw new Exception("Stock insuffisant ou produit non trouvé.");
        }

        // 2. Ajouter la vente à l'historique
        $sqlVente = 'INSERT INTO ventes (id_produit, quantite_vendue, id_utilisateur) VALUES (?, ?, ?)';
        $stmtVente = $pdo->prepare($sqlVente);
        $stmtVente->execute([$idProduit, $quantite, $idUtilisateur]);
        
        // Si tout s'est bien passé, on valide la transaction
        $pdo->commit();
        return true;

    } catch (Exception $e) {
        // En cas d'erreur, on annule la transaction
        $pdo->rollBack();
        // Ici, vous pourriez loguer l'erreur : error_log($e->getMessage());
        return false;
    }
}
/**
 * Calcule la valeur totale du stock (somme de (prix_achat * quantite) pour tous les produits).
 * @return float La valeur totale du stock.
 */
function getValeurTotaleStock() {
    global $pdo;
    $stmt = $pdo->query('SELECT SUM(prix_achat * quantite_en_stock) AS total FROM produits');
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

/**
 * Compte le nombre de produits dont le stock est bas.
 * @return int Le nombre de produits en alerte.
 */
function getNombreProduitsEnAlerte() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT COUNT(*) AS count FROM produits WHERE quantite_en_stock < seuil_alerte');
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['count'] ?? 0;
}

/**
 * Calcule le profit potentiel total si tout le stock était vendu.
 * @return float Le profit potentiel total.
 */
function getProfitPotentielTotal() {
    global $pdo;
    $stmt = $pdo->query('SELECT SUM((prix_vente - prix_achat) * quantite_en_stock) AS profit FROM produits');
    $result = $stmt->fetch();
    return $result['profit'] ?? 0;
}

/**
 * Récupère les 5 produits les plus vendus (basé sur la table des ventes).
 * @return array La liste des top 5 produits.
 */
function getTopProduitsVendus($limit = 5) {
    global $pdo;
    $sql = "
        SELECT p.nom_produit, SUM(v.quantite_vendue) AS total_vendu
        FROM ventes v
        JOIN produits p ON v.id_produit = p.id
        GROUP BY p.id, p.nom_produit
        ORDER BY total_vendu DESC
        LIMIT ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}