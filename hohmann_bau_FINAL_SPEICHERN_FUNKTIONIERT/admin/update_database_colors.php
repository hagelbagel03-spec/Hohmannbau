<?php
/**
 * Database Update Script für erweiterte Farb-Funktionen
 */

require_once '../config/database.php';

try {
    $db = getDB();
    
    echo "<h2>🔧 Datenbank-Update für erweiterte Farb-Funktionen</h2>\n";
    
    // Check if columns exist
    $columns_to_add = [
        'footer_bg_color' => 'VARCHAR(20) DEFAULT "#1f2937"',
        'footer_text_color' => 'VARCHAR(20) DEFAULT "#ffffff"',
        'header_bg_color' => 'VARCHAR(20) DEFAULT "#ffffff"',
        'header_text_color' => 'VARCHAR(20) DEFAULT "#1f2937"',
        'button_primary_color' => 'VARCHAR(20) DEFAULT "#10b981"',
        'button_secondary_color' => 'VARCHAR(20) DEFAULT "#6b7280"',
        'accent_color' => 'VARCHAR(20) DEFAULT "#3b82f6"',
        'body_text_color' => 'VARCHAR(20) DEFAULT "#374151"'
    ];
    
    foreach ($columns_to_add as $column => $definition) {
        try {
            // Try to select the column
            $db->query("SELECT $column FROM homepage LIMIT 1");
            echo "✅ Spalte '$column' existiert bereits<br>\n";
        } catch (PDOException $e) {
            // Column doesn't exist, add it
            try {
                $db->exec("ALTER TABLE homepage ADD COLUMN $column $definition");
                echo "✅ Spalte '$column' wurde hinzugefügt<br>\n";
            } catch (PDOException $e2) {
                echo "❌ Fehler beim Hinzufügen der Spalte '$column': " . $e2->getMessage() . "<br>\n";
            }
        }
    }
    
    // Ensure there's a homepage record
    $homepage = $db->query("SELECT COUNT(*) as count FROM homepage WHERE id = '1'")->fetch();
    if ($homepage['count'] == 0) {
        $db->exec("INSERT INTO homepage (id) VALUES ('1')");
        echo "✅ Standard-Homepage-Eintrag erstellt<br>\n";
    }
    
    echo "<br><h3>✅ Datenbank-Update abgeschlossen!</h3>\n";
    echo "<p><a href='colors_advanced.php'>🎨 Zur erweiterten Farb-Verwaltung</a></p>\n";
    
} catch (Exception $e) {
    echo "❌ Fehler: " . $e->getMessage() . "\n";
}
?>