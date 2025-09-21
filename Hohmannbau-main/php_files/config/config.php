<?php
// Konfigurationsdatei für Hohmann Bau
define('BASE_URL', '');
define('SITE_NAME', 'Hohmann Bau');

// Fehler-Anzeige für Entwicklung (nur in Development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader für Klassen
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../classes/' . $class . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Datenbankverbindung einbinden
require_once __DIR__ . '/database.php';

// Globale Datenbank-Instanz
try {
    $database = new Database();
    $pdo = $database->getConnection();
} catch (Exception $e) {
    // Fallback if database fails
    $database = null;
    $pdo = null;
    error_log("Database connection failed: " . $e->getMessage());
}
?>