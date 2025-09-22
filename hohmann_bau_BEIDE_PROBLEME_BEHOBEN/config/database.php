<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'hohmann_ewsdfa';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function getConnection() {
        $this->pdo = null;
        try {
            $this->pdo = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch(PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
        return $this->pdo;
    }
}

function getDB() {
    $database = new Database();
    return $database->getConnection();
}
?>