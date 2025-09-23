<?php
// Einfacher Test für Windows Apache
echo "<h1>PHP funktioniert!</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";

// Teste Dateien
$files = ['index.php', 'config/database.php', 'includes/header.php'];
echo "<h3>Datei-Check:</h3>";
foreach ($files as $file) {
    echo "<p>$file: " . (file_exists($file) ? '✅ Vorhanden' : '❌ Fehlt') . "</p>";
}
?>