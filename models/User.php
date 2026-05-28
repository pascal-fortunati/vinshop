<?php
require_once 'config/Database.php';

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $phone;
    public $address;
    public $role;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Inscription
    public function register()
    {
        $query = "INSERT INTO " . $this->table . " 
                  (username, email, password, first_name, last_name, phone, address, role) 
                  VALUES (:username, :email, :password, :first_name, :last_name, :phone, :address, :role)";

        $stmt = $this->conn->prepare($query);

        // Hash du mot de passe
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute();
    }

    // Connexion
    public function login($email, $password)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    // Récupérer un utilisateur par ID
    public function getById($id)
    {
        $query = "SELECT id, username, email, first_name, last_name, phone, address, role, created_at 
                  FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour le profil
    public function updateProfile()
    {
        $query = "UPDATE " . $this->table . " 
                  SET first_name = :first_name, 
                      last_name = :last_name, 
                      phone = :phone, 
                      address = :address 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);

        return $stmt->execute();
    }

    // Changer le mot de passe
    public function changePassword($newPassword)
    {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Vérifier si l'email existe
    public function emailExists($email)
    {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Vérifier si le username existe
    public function usernameExists($username)
    {
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Récupérer tous les utilisateurs (admin)
    public function getAll()
    {
        $query = "SELECT id, username, email, first_name, last_name, role, created_at 
                  FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les utilisateurs avec pagination
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllPaginated($limit = 20, $offset = 0)
    {
        $query = "SELECT id, username, email, first_name, last_name, role, created_at 
                  FROM " . $this->table . " 
                  ORDER BY created_at DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre total d'utilisateurs
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
