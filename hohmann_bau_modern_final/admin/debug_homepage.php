<?php
/**
 * Debug Homepage Data
 */

require_once '../config/database.php';

echo "<h1>🔍 Homepage Debug</h1>";

try {
    $db = getDB();
    
    // Check if homepage table exists
    echo "<h2>1. Tabellen-Check</h2>";
    try {
        $result = $db->query("DESCRIBE homepage");
        echo "<p style='color: green;'>✅ Homepage-Tabelle existiert</p>";
        
        while ($row = $result->fetch()) {
            echo "<p>- " . $row['Field'] . " (" . $row['Type'] . ")</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Homepage-Tabelle fehlt: " . $e->getMessage() . "</p>";
        
        // Create homepage table
        echo "<p>Erstelle Homepage-Tabelle...</p>";
        $db->exec("CREATE TABLE homepage (
            id varchar(36) NOT NULL,
            hero_title varchar(255) NOT NULL DEFAULT 'Stadtwache',
            hero_subtitle text NOT NULL DEFAULT 'Sicherheit und Schutz für unsere Gemeinschaft',
            hero_image varchar(255) DEFAULT NULL,
            emergency_number varchar(20) NOT NULL DEFAULT '110',
            phone_number varchar(50) NOT NULL DEFAULT '+49 123 456-789',
            email varchar(255) NOT NULL DEFAULT 'info@stadtwache.de',
            address text NOT NULL DEFAULT 'Stadtwache Hauptrevier\\nHauptstraße 123\\n12345 Musterstadt',
            opening_hours text NOT NULL DEFAULT 'Mo-Fr: 8:00-20:00\\nSa: 9:00-16:00\\nSo: 10:00-14:00',
            updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )");
        
        $db->exec("INSERT INTO homepage (id) VALUES ('1')");
        echo "<p style='color: green;'>✅ Homepage-Tabelle erstellt!</p>";
    }
    
    // Check current data
    echo "<h2>2. Aktuelle Homepage-Daten</h2>";
    $homepage = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
    
    if ($homepage) {
        echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0;'>";
        echo "<strong>ID:</strong> " . $homepage['id'] . "<br>";
        echo "<strong>Titel:</strong> " . htmlspecialchars($homepage['hero_title']) . "<br>";
        echo "<strong>Untertitel:</strong> " . htmlspecialchars($homepage['hero_subtitle']) . "<br>";
        echo "<strong>Hero-Bild:</strong> " . ($homepage['hero_image'] ? htmlspecialchars($homepage['hero_image']) : 'Kein Bild') . "<br>";
        echo "<strong>Notruf:</strong> " . htmlspecialchars($homepage['emergency_number']) . "<br>";
        echo "<strong>Telefon:</strong> " . htmlspecialchars($homepage['phone_number']) . "<br>";
        echo "<strong>E-Mail:</strong> " . htmlspecialchars($homepage['email']) . "<br>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>❌ Keine Homepage-Daten gefunden!</p>";
        
        // Insert default data
        echo "<p>Füge Standard-Daten hinzu...</p>";
        $db->exec("INSERT INTO homepage (id, hero_title, hero_subtitle) VALUES ('1', 'Stadtwache', 'Sicherheit und Schutz für unsere Gemeinschaft')");
        echo "<p style='color: green;'>✅ Standard-Daten hinzugefügt!</p>";
    }
    
    // Test what index.php sees
    echo "<h2>3. Was index.php sieht</h2>";
    $homepage_check = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
    
    if ($homepage_check) {
        echo "<p style='color: green;'>✅ index.php kann Homepage-Daten laden</p>";
        echo "<p><strong>Geladener Titel:</strong> " . htmlspecialchars($homepage_check['hero_title']) . "</p>";
        echo "<p><strong>Geladenes Bild:</strong> " . ($homepage_check['hero_image'] ? htmlspecialchars($homepage_check['hero_image']) : 'Kein Bild') . "</p>";
    } else {
        echo "<p style='color: red;'>❌ index.php kann keine Homepage-Daten laden!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Datenbank-Fehler: " . $e->getMessage() . "</p>";
}

echo "<br><a href='homepage.php'>← Zurück zu Homepage-Editor</a>";
echo "<br><a href='../index.php' target='_blank'>Homepage anzeigen (neuer Tab)</a>";
?>