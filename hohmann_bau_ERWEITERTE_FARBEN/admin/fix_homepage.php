<?php
/**
 * Homepage Fix & Test Script
 */

require_once '../config/database.php';
require_once '../config/auth.php';

requireAuth();

echo "<h1>🔧 Homepage Fix Script</h1>";

try {
    $db = getDB();
    
    // 1. Check if homepage table exists and has correct structure
    echo "<h2>1. Homepage-Tabelle prüfen</h2>";
    
    try {
        // Drop and recreate homepage table with correct structure
        $db->exec("DROP TABLE IF EXISTS homepage");
        
        $db->exec("CREATE TABLE homepage (
            id varchar(36) NOT NULL,
            hero_title varchar(255) NOT NULL DEFAULT 'Stadtwache',
            hero_subtitle text NOT NULL DEFAULT 'Sicherheit und Schutz für unsere Gemeinschaft',
            hero_image varchar(255) DEFAULT NULL,
            color_theme varchar(50) NOT NULL DEFAULT 'blue',
            emergency_number varchar(20) NOT NULL DEFAULT '110',
            phone_number varchar(50) NOT NULL DEFAULT '+49 123 456-789',
            email varchar(255) NOT NULL DEFAULT 'info@stadtwache.de',
            address text NOT NULL DEFAULT 'Stadtwache Hauptrevier\nHauptstraße 123\n12345 Musterstadt',
            opening_hours text NOT NULL DEFAULT 'Mo-Fr: 8:00-20:00\nSa: 9:00-16:00\nSo: 10:00-14:00',
            updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )");
        
        echo "<p style='color: green;'>✅ Homepage-Tabelle neu erstellt!</p>";
        
        // Insert default data
        $db->exec("INSERT INTO homepage (id, hero_title, hero_subtitle, color_theme) VALUES ('1', 'Stadtwache', 'Sicherheit und Schutz für unsere Gemeinschaft. Moderne Polizeiarbeit im Dienste der Bürger.', 'blue')");
        
        echo "<p style='color: green;'>✅ Standard-Daten eingefügt!</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Tabellen-Fehler: " . $e->getMessage() . "</p>";
    }
    
    // 2. Test data retrieval
    echo "<h2>2. Daten-Test</h2>";
    $homepage = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
    
    if ($homepage) {
        echo "<p style='color: green;'>✅ Homepage-Daten erfolgreich geladen!</p>";
        echo "<div style='background: #e7f3ff; padding: 10px; border-left: 4px solid #2196f3; margin: 10px 0;'>";
        echo "<strong>Aktueller Titel:</strong> " . htmlspecialchars($homepage['hero_title']) . "<br>";
        echo "<strong>Aktueller Untertitel:</strong> " . htmlspecialchars($homepage['hero_subtitle']) . "<br>";
        echo "<strong>Aktuelles Farbschema:</strong> " . htmlspecialchars($homepage['color_theme']) . "<br>";
        echo "<strong>Hero-Bild:</strong> " . ($homepage['hero_image'] ? htmlspecialchars($homepage['hero_image']) : 'Kein Bild') . "<br>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>❌ Keine Homepage-Daten gefunden!</p>";
    }
    
    // 3. Simple update test
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<h2>3. Update-Test</h2>";
        
        try {
            $stmt = $db->prepare("UPDATE homepage SET hero_title = ?, hero_subtitle = ?, color_theme = ? WHERE id = '1'");
            $result = $stmt->execute([
                $_POST['test_title'] ?? 'Test Titel',
                $_POST['test_subtitle'] ?? 'Test Untertitel', 
                $_POST['test_theme'] ?? 'green'
            ]);
            
            if ($result) {
                echo "<p style='color: green;'>✅ Update erfolgreich!</p>";
                
                // Verify the update
                $updated = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
                echo "<p><strong>Neuer Titel:</strong> " . htmlspecialchars($updated['hero_title']) . "</p>";
                echo "<p><strong>Neues Theme:</strong> " . htmlspecialchars($updated['color_theme']) . "</p>";
                
            } else {
                echo "<p style='color: red;'>❌ Update fehlgeschlagen!</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Update-Fehler: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>

<!-- Test Form -->
<form method="POST" style="background: #f9f9f9; padding: 20px; margin: 20px 0; border: 1px solid #ddd;">
    <h3>🧪 Homepage Update Tester</h3>
    <p>
        <label>Test Titel:</label><br>
        <input type="text" name="test_title" value="NEUE STADTWACHE" style="width: 300px; padding: 5px;">
    </p>
    <p>
        <label>Test Untertitel:</label><br>
        <input type="text" name="test_subtitle" value="Dies ist ein Test der Bearbeitung" style="width: 400px; padding: 5px;">
    </p>
    <p>
        <label>Test Farbschema:</label><br>
        <select name="test_theme" style="padding: 5px;">
            <option value="blue">Blau</option>
            <option value="green">Grün</option>
            <option value="red">Rot</option>
            <option value="purple">Lila</option>
        </select>
    </p>
    <p>
        <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;">
            ✅ Homepage updaten
        </button>
    </p>
</form>

<p><a href="../index.php" target="_blank" style="background: #2196f3; color: white; padding: 10px 20px; text-decoration: none;">🔗 Homepage anzeigen (neuer Tab)</a></p>
<p><a href="homepage.php">← Zurück zu Homepage-Editor</a></p>
<p><a href="index.php">← Zurück zum Dashboard</a></p>