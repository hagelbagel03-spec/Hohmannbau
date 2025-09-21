<?php
class Database {
    private $db_path = __DIR__ . '/../data/hohmann_bau.db';
    private $pdo;

    public function __construct() {
        try {
            // Create data directory if it doesn't exist
            $data_dir = dirname($this->db_path);
            if (!is_dir($data_dir)) {
                mkdir($data_dir, 0755, true);
            }

            // Create SQLite database connection
            $this->pdo = new PDO(
                "sqlite:" . $this->db_path,
                null,
                null,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                )
            );
            
            // Enable foreign key support
            $this->pdo->exec('PRAGMA foreign_keys = ON');
            
        } catch(PDOException $e) {
            die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
?>