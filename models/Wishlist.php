<?php
require_once 'config/Database.php';

class Wishlist
{
    private $conn;
    private $table = 'wishlist';

    public $id;
    public $user_id;
    public $product_id;
    public $created_at;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Ajouter un produit à la wishlist
     */
    public function add($user_id, $product_id)
    {
        // Vérifier si le produit existe déjà dans la wishlist
        if ($this->isInWishlist($user_id, $product_id)) {
            return false; // Déjà dans la wishlist
        }

        $query = "INSERT INTO " . $this->table . " (user_id, product_id) 
                  VALUES (:user_id, :product_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);

        return $stmt->execute();
    }

    /**
     * Retirer un produit de la wishlist
     */
    public function remove($user_id, $product_id)
    {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE user_id = :user_id AND product_id = :product_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);

        return $stmt->execute();
    }

    /**
     * Vérifier si un produit est dans la wishlist
     */
    public function isInWishlist($user_id, $product_id)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE user_id = :user_id AND product_id = :product_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Récupérer tous les produits de la wishlist d'un utilisateur
     */
    public function getByUser($user_id)
    {
        $query = "SELECT p.*, w.created_at as added_at, u.username as seller_name
                  FROM " . $this->table . " w
                  INNER JOIN products p ON w.product_id = p.id
                  LEFT JOIN users u ON p.seller_id = u.id
                  WHERE w.user_id = :user_id
                  ORDER BY w.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre de produits dans la wishlist d'un utilisateur
     */
    public function count($user_id)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    /**
     * Récupérer les IDs des produits dans la wishlist (pour marquage rapide)
     */
    public function getUserWishlistIds($user_id)
    {
        $query = "SELECT product_id FROM " . $this->table . " 
                  WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $results ?: [];
    }

    /**
     * Vider complètement la wishlist d'un utilisateur
     */
    public function clear($user_id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
