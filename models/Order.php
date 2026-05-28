<?php
require_once 'config/Database.php';

class Order
{
    private $conn;
    private $table = 'orders';

    public $id;
    public $user_id;
    public $total;
    public $status;
    public $payment_method;
    public $shipping_address;
    public $notes;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Créer une commande
    public function create($items)
    {
        try {
            $this->conn->beginTransaction();

            // Vérifier le stock pour chaque item
            foreach ($items as $item) {
                $checkStock = "SELECT stock FROM products WHERE id = :id";
                $checkStmt = $this->conn->prepare($checkStock);
                $checkStmt->bindParam(':id', $item['id']);
                $checkStmt->execute();
                $product = $checkStmt->fetch(PDO::FETCH_ASSOC);

                if (!$product || $product['stock'] < $item['quantity']) {
                    // Lancer une exception au lieu de rollback ici
                    throw new Exception("Stock insuffisant pour le produit: " . $item['name'] . ". Stock disponible: " . ($product['stock'] ?? 0));
                }
            }

            // Insérer la commande
            $query = "INSERT INTO " . $this->table . " 
                      (user_id, total, status, payment_method, shipping_address, notes) 
                      VALUES (:user_id, :total, :status, :payment_method, :shipping_address, :notes)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':total', $this->total);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':payment_method', $this->payment_method);
            $stmt->bindParam(':shipping_address', $this->shipping_address);
            $stmt->bindParam(':notes', $this->notes);

            $stmt->execute();
            $orderId = $this->conn->lastInsertId();

            // Insérer les items de la commande
            $itemQuery = "INSERT INTO order_items 
                          (order_id, product_id, product_name, product_price, quantity, subtotal) 
                          VALUES (:order_id, :product_id, :product_name, :product_price, :quantity, :subtotal)";

            $itemStmt = $this->conn->prepare($itemQuery);

            foreach ($items as $item) {
                $itemStmt->bindParam(':order_id', $orderId);
                $itemStmt->bindParam(':product_id', $item['id']);
                $itemStmt->bindParam(':product_name', $item['name']);
                $itemStmt->bindParam(':product_price', $item['price']);
                $itemStmt->bindParam(':quantity', $item['quantity']);
                $itemStmt->bindParam(':subtotal', $item['subtotal']);
                $itemStmt->execute();

                // Décrémenter le stock
                $updateStock = "UPDATE products SET stock = stock - :quantity WHERE id = :id";
                $stockStmt = $this->conn->prepare($updateStock);
                $stockStmt->bindParam(':quantity', $item['quantity']);
                $stockStmt->bindParam(':id', $item['id']);
                $stockStmt->execute();
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            // Rollback seulement si la transaction est active
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            // Retourner le message d'erreur
            return ['error' => $e->getMessage()];
        }
    }

    // Récupérer les commandes d'un utilisateur
    public function getByUserId($userId)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les commandes d'un utilisateur avec pagination
     * @param int $userId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getByUserIdPaginated($userId, $limit = 20, $offset = 0)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre de commandes d'un utilisateur
     * @param int $userId
     * @return int
     */
    public function countByUserId($userId)
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    // Récupérer toutes les commandes (admin)
    public function getAll()
    {
        $query = "SELECT o.*, u.username, u.email, u.first_name, u.last_name 
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.id
                  ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une commande par ID
    public function getById($id)
    {
        $query = "SELECT o.*, u.username, u.email, u.first_name, u.last_name 
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE o.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer les items d'une commande
    public function getItems($orderId)
    {
        $query = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mettre à jour le statut
    public function updateStatus($orderId, $status)
    {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $orderId);

        return $stmt->execute();
    }

    // Statistiques (admin)
    public function getStatistics()
    {
        $stats = [];

        // Total des commandes
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->query($query);
        $stats['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Chiffre d'affaires total
        $query = "SELECT SUM(total) as revenue FROM " . $this->table;
        $stmt = $this->conn->query($query);
        $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;

        // Commandes par statut
        $query = "SELECT status, COUNT(*) as count FROM " . $this->table . " GROUP BY status";
        $stmt = $this->conn->query($query);
        $stats['by_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    /**
     * Récupérer toutes les commandes avec pagination (admin)
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllPaginated($limit = 20, $offset = 0)
    {
        $query = "SELECT o.*, u.username, u.email, u.first_name, u.last_name 
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.id
                  ORDER BY o.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre total de commandes
     * @return int
     */
    public function countAll()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }
}
