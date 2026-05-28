<?php
require_once 'config/Database.php';

class Review
{
    private $conn;
    private $table = 'reviews';

    public $id;
    public $product_id;
    public $user_id;
    public $rating;
    public $comment;
    public $status;
    public $created_at;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Créer un nouvel avis
     */
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  (product_id, user_id, rating, comment, status) 
                  VALUES (:product_id, :user_id, :rating, :comment, :status)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':comment', $this->comment);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Récupérer tous les avis d'un produit (approuvés uniquement par défaut)
     */
    public function getByProduct($product_id, $status = 'approved')
    {
        $query = "SELECT r.*, u.username 
                  FROM " . $this->table . " r
                  LEFT JOIN users u ON r.user_id = u.id
                  WHERE r.product_id = :product_id";

        if ($status) {
            $query .= " AND r.status = :status";
        }

        $query .= " ORDER BY r.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);

        if ($status) {
            $stmt->bindParam(':status', $status);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer la note moyenne d'un produit
     */
    public function getAverageRating($product_id)
    {
        $query = "SELECT AVG(rating) as average, COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE product_id = :product_id AND status = 'approved'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre d'avis d'un produit
     */
    public function countByProduct($product_id, $status = 'approved')
    {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE product_id = :product_id AND status = :status";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Vérifier si un utilisateur a déjà laissé un avis pour un produit
     */
    public function hasUserReviewed($product_id, $user_id)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE product_id = :product_id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Modérer un avis (approuver ou rejeter)
     */
    public function moderate($id, $status)
    {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);

        return $stmt->execute();
    }

    /**
     * Récupérer tous les avis en attente de modération
     */
    public function getPending()
    {
        $query = "SELECT r.*, u.username, p.name as product_name 
                  FROM " . $this->table . " r
                  LEFT JOIN users u ON r.user_id = u.id
                  LEFT JOIN products p ON r.product_id = p.id
                  WHERE r.status = 'pending'
                  ORDER BY r.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer tous les avis (pour l'admin)
     */
    public function getAll($limit = null)
    {
        $query = "SELECT r.*, u.username, p.name as product_name 
                  FROM " . $this->table . " r
                  LEFT JOIN users u ON r.user_id = u.id
                  LEFT JOIN products p ON r.product_id = p.id
                  ORDER BY r.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->conn->prepare($query);

        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprimer un avis
     */
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
