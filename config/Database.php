<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    private $conn;

    public function __construct()
    {
        // Charger la configuration depuis App.php
        $config = require __DIR__ . '/App.php';

        $this->host = $config['database']['host'];
        $this->db_name = $config['database']['name'];
        $this->username = $config['database']['username'];
        $this->password = $config['database']['password'];
        $this->charset = $config['database']['charset'];
    }

    public function getConnection()
    {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names " . $this->charset);
        } catch (PDOException $e) {
            echo "Erreur de connexion: " . $e->getMessage();
        }
        return $this->conn;
    }
}
