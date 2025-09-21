<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'hohmann_bau';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct() {
        try {
            // Try to connect to MySQL server first
            $this->pdo = new PDO(
                "mysql:host={$this->host};charset=utf8mb4", 
                $this->username, 
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                )
            );
            
            // Create database if it doesn't exist
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Connect to the specific database
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4", 
                $this->username, 
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                )
            );
            
            // Run setup if tables don't exist
            $this->setupTables();
            
        } catch(PDOException $e) {
            die("MySQL Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
    
    private function setupTables() {
        try {
            // Check if tables exist
            $stmt = $this->pdo->query("SHOW TABLES LIKE 'page_contents'");
            if ($stmt->rowCount() == 0) {
                // Run the setup SQL
                $setupSQL = file_get_contents(__DIR__ . '/../database/setup.sql');
                
                // Remove CREATE DATABASE and USE statements (we already selected the DB)
                $setupSQL = preg_replace('/CREATE DATABASE.*?;/i', '', $setupSQL);
                $setupSQL = preg_replace('/USE.*?;/i', '', $setupSQL);
                
                // Split and execute each statement
                $statements = explode(';', $setupSQL);
                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    if (!empty($statement) && !preg_match('/^--/', $statement)) {
                        $this->pdo->exec($statement);
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error setting up tables: " . $e->getMessage());
        }
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