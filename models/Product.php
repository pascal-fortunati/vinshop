<?php
require_once 'config/Database.php';

class Product
{
    private $conn;
    private $table = 'products';

    public $id;
    public $name;
    public $slug;
    public $description;
    public $price;
    public $image;
    public $category;
    public $condition;
    public $stock;
    public $seller_id;
    public $created_at;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT p.*, u.username as seller_name 
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.seller_id = u.id
                  WHERE p.stock > 0
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer tous les produits avec pagination (admin)
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllPaginated($limit = 20, $offset = 0)
    {
        $query = "SELECT p.*, u.username as seller_name 
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.seller_id = u.id
                  ORDER BY p.created_at DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre total de produits (admin)
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

    public function getByCategory($category)
    {
        $query = "SELECT p.*, u.username as seller_name 
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.seller_id = u.id
                  WHERE p.stock > 0 AND p.category = :category
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT p.*, u.username as seller_name 
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.seller_id = u.id
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBySlug($slug)
    {
        $query = "SELECT p.*, u.username as seller_name 
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.seller_id = u.id
                  WHERE p.slug = :slug";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create()
    {
        // Générer le slug à partir du nom
        $this->slug = slugify($this->name) . '-' . time();

        $query = "INSERT INTO " . $this->table . " 
                  (name, slug, description, price, image, category, `condition`, stock, seller_id) 
                  VALUES (:name, :slug, :description, :price, :image, :category, :condition, :stock, :seller_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':condition', $this->condition);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':seller_id', $this->seller_id);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            // Mettre à jour le slug avec l'ID réel
            $this->slug = slugify($this->name) . '-' . $this->id;
            $updateQuery = "UPDATE " . $this->table . " SET slug = :slug WHERE id = :id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':slug', $this->slug);
            $updateStmt->bindParam(':id', $this->id);
            $updateStmt->execute();
            return true;
        }

        return false;
    }

    public function update()
    {
        // Regénérer le slug si le nom a changé
        $this->slug = slugify($this->name) . '-' . $this->id;

        $query = "UPDATE " . $this->table . " 
                  SET name = :name,
                      slug = :slug, 
                      description = :description, 
                      price = :price, 
                      image = :image, 
                      category = :category, 
                      `condition` = :condition,
                      stock = :stock
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':condition', $this->condition);
        $stmt->bindParam(':stock', $this->stock);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function checkStock($id)
    {
        $query = "SELECT stock FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['stock'] : 0;
    }

    public function search($query, $limit = 10)
    {
        $searchQuery = "SELECT p.*, u.username as seller_name 
                        FROM " . $this->table . " p
                        LEFT JOIN users u ON p.seller_id = u.id
                        WHERE p.stock > 0 
                        AND (p.name LIKE :query 
                            OR p.description LIKE :query 
                            OR p.category LIKE :query)
                        ORDER BY p.created_at DESC
                        LIMIT :limit";

        $stmt = $this->conn->prepare($searchQuery);
        $searchTerm = '%' . $query . '%';
        $stmt->bindParam(':query', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Filtrer les produits avec critères avancés
     */
    public function filter($filters = [])
    {
        $query = "SELECT p.*, u.username as seller_name,
                  (SELECT COUNT(*) FROM order_items oi WHERE oi.product_id = p.id) as popularity
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.seller_id = u.id
                  WHERE 1=1";

        $params = [];

        // Filtre par catégorie
        if (!empty($filters['category'])) {
            $query .= " AND p.category = :category";
            $params[':category'] = $filters['category'];
        }

        // Filtre par prix min
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $query .= " AND p.price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        // Filtre par prix max
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $query .= " AND p.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        // Filtre par stock
        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] === 'in_stock') {
                $query .= " AND p.stock > 0";
            } elseif ($filters['stock_status'] === 'out_of_stock') {
                $query .= " AND p.stock = 0";
            }
        } else {
            // Par défaut, ne montrer que les produits en stock
            $query .= " AND p.stock > 0";
        }

        // Tri
        $orderBy = " ORDER BY ";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $orderBy .= "p.price ASC";
                    break;
                case 'price_desc':
                    $orderBy .= "p.price DESC";
                    break;
                case 'name_asc':
                    $orderBy .= "p.name ASC";
                    break;
                case 'name_desc':
                    $orderBy .= "p.name DESC";
                    break;
                case 'date_asc':
                    $orderBy .= "p.created_at ASC";
                    break;
                case 'popularity':
                    $orderBy .= "popularity DESC, p.created_at DESC";
                    break;
                case 'date_desc':
                default:
                    $orderBy .= "p.created_at DESC";
                    break;
            }
        } else {
            $orderBy .= "p.created_at DESC";
        }

        $query .= $orderBy;

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Filtrer les produits avec pagination
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function filterPaginated($filters = [], $limit = 20, $offset = 0)
    {
        $query = "SELECT p.*, u.username as seller_name,
                  (SELECT COUNT(*) FROM order_items oi WHERE oi.product_id = p.id) as popularity
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.seller_id = u.id
                  WHERE 1=1";

        $params = [];

        if (!empty($filters['category'])) {
            $query .= " AND p.category = :category";
            $params[':category'] = $filters['category'];
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $query .= " AND p.price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $query .= " AND p.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] === 'in_stock') {
                $query .= " AND p.stock > 0";
            } elseif ($filters['stock_status'] === 'out_of_stock') {
                $query .= " AND p.stock = 0";
            }
        } else {
            $query .= " AND p.stock > 0";
        }

        $orderBy = " ORDER BY ";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $orderBy .= "p.price ASC";
                    break;
                case 'price_desc':
                    $orderBy .= "p.price DESC";
                    break;
                case 'name_asc':
                    $orderBy .= "p.name ASC";
                    break;
                case 'name_desc':
                    $orderBy .= "p.name DESC";
                    break;
                case 'date_asc':
                    $orderBy .= "p.created_at ASC";
                    break;
                case 'popularity':
                    $orderBy .= "popularity DESC, p.created_at DESC";
                    break;
                case 'date_desc':
                default:
                    $orderBy .= "p.created_at DESC";
                    break;
            }
        } else {
            $orderBy .= "p.created_at DESC";
        }

        $query .= $orderBy;
        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre de produits correspondant aux filtres
     * @param array $filters
     * @return int
     */
    public function countFiltered($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " p WHERE 1=1";
        $params = [];

        if (!empty($filters['category'])) {
            $query .= " AND p.category = :category";
            $params[':category'] = $filters['category'];
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $query .= " AND p.price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $query .= " AND p.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] === 'in_stock') {
                $query .= " AND p.stock > 0";
            } elseif ($filters['stock_status'] === 'out_of_stock') {
                $query .= " AND p.stock = 0";
            }
        } else {
            $query .= " AND p.stock > 0";
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Obtenir les prix min et max pour le slider
     */
    public function getPriceRange()
    {
        $query = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM " . $this->table . " WHERE stock > 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
