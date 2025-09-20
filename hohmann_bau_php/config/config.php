<?php
// Konfigurationsdatei für Hohmann Bau
define('BASE_URL', '/hohmann_bau_php');
define('SITE_NAME', 'Hohmann Bau');

// Fehler-Anzeige für Entwicklung
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session starten
session_start();

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
$database = new Database();
$pdo = $database->getConnection();
?>