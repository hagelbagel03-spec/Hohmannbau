<?php
/**
 * Simple JSON-based database for text colors
 * Falls MySQL nicht verfügbar ist
 */

function getDB() {
    // Try MySQL first, fallback to JSON if not available
    try {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=hohmann_ewsdfa;charset=utf8mb4",
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        return $pdo;
    } catch(PDOException $e) {
        // MySQL not available, use JSON database
        return null;
    }
}

// JSON Database Functions
function getHomepageData() {
    $file = __DIR__ . '/../data/homepage.json';
    if (!file_exists($file)) {
        // Create default data
        $default = [
            'id' => '1',
            'hero_title' => 'Ihr Experte für Garten- und Landschaftsbau',
            'hero_subtitle' => 'Professionelle Gartengestaltung seit über 20 Jahren',
            'phone_number' => '+49 123 456-789',
            'email' => 'info@hohmann-bau.de',
            'color_theme' => 'green',
            'footer_bg_color' => '#1f2937',
            'footer_text_color' => '#ffffff',
            'header_bg_color' => '#ffffff',
            'header_text_color' => '#1f2937',
            'button_primary_color' => '#10b981',
            'button_secondary_color' => '#6b7280',
            'accent_color' => '#3b82f6',
            'body_text_color' => '#374151',
            'heading_color' => '#1f2937',
            'subheading_color' => '#374151',
            'link_color' => '#2563eb',
            'highlight_color' => '#059669',
            'service_description_color' => '#374151'
        ];
        
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($file, json_encode($default, JSON_PRETTY_PRINT));
        return $default;
    }
    
    $data = json_decode(file_get_contents($file), true);
    
    // Add missing service_description_color if not exists
    if (!isset($data['service_description_color'])) {
        $data['service_description_color'] = '#374151';
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    return $data;
}

function updateHomepageData($data) {
    $file = __DIR__ . '/../data/homepage.json';
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) !== false;
}

// Wrapper class to simulate PDO behavior
class JsonDB {
    private $data;
    
    public function __construct() {
        $this->data = getHomepageData();
    }
    
    public function query($sql) {
        // Simple simulation for our use case
        if (strpos($sql, "SELECT * FROM homepage WHERE id = '1'") !== false) {
            return new JsonResult($this->data);
        }
        return new JsonResult([]);
    }
    
    public function prepare($sql) {
        return new JsonStatement($sql, $this->data);
    }
}

class JsonResult {
    private $data;
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    public function fetch() {
        return $this->data;
    }
    
    public function fetchAll() {
        return [$this->data];
    }
}

class JsonStatement {
    private $sql;
    private $data;
    
    public function __construct($sql, $data) {
        $this->sql = $sql;
        $this->data = $data;
    }
    
    public function execute($params = []) {
        if (strpos($this->sql, 'UPDATE homepage SET') !== false) {
            // Update operation
            $newData = $this->data;
            
            // Map parameters to fields based on the SQL structure
            if (count($params) >= 12) {
                $newData['footer_bg_color'] = $params[0];
                $newData['footer_text_color'] = $params[1];
                $newData['header_bg_color'] = $params[2];
                $newData['header_text_color'] = $params[3];
                $newData['button_primary_color'] = $params[4];
                $newData['button_secondary_color'] = $params[5];
                $newData['accent_color'] = $params[6];
                $newData['body_text_color'] = $params[7];
                $newData['heading_color'] = $params[8];
                $newData['subheading_color'] = $params[9];
                $newData['link_color'] = $params[10];
                $newData['highlight_color'] = $params[11];
                if (isset($params[12])) {
                    $newData['service_description_color'] = $params[12];
                }
            }
            
            return updateHomepageData($newData);
        }
        
        return true;
    }
}

// Main function that determines which DB to use
function getDBConnection() {
    $pdo = getDB();
    if ($pdo) {
        return $pdo;
    } else {
        return new JsonDB();
    }
}
?>