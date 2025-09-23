<?php
/**
 * SQL Update ausführen
 */

require_once 'config/database.php';

try {
    $db = getDB();
    
    // SQL-Inhalt lesen
    $sql = file_get_contents('database_update_TEXTE_BILDER.sql');
    
    // SQL-Statements aufteilen und ausführen
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^(USE|SET|START|COMMIT)/i', $statement)) {
            try {
                $db->exec($statement);
                echo "✓ Statement ausgeführt: " . substr($statement, 0, 50) . "...\n";
            } catch (Exception $e) {
                echo "⚠ Übersprungen: " . $e->getMessage() . "\n";
                // Fehler ignorieren, da Spalten möglicherweise schon existieren
            }
        }
    }
    
    echo "\n✅ Datenbank-Update abgeschlossen!\n";
    
} catch (Exception $e) {
    echo "❌ Fehler: " . $e->getMessage() . "\n";
}
?>