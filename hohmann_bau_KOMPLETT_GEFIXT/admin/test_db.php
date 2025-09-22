<?php
/**
 * Database Connection Test
 */

require_once '../config/database.php';

echo "<h1>Datenbank-Test</h1>";

try {
    $db = getDB();
    echo "<p style='color: green;'>✅ Datenbankverbindung erfolgreich!</p>";
    
    // Test tables
    $tables = ['jobs', 'services', 'team', 'applications', 'reports', 'feedback', 'news', 'chat_messages'];
    
    echo "<h3>Tabellen-Test:</h3>";
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch()['count'];
            echo "<p style='color: green;'>✅ $table: $count Einträge</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ $table: " . $e->getMessage() . "</p>";
        }
    }
    
    // Test specific jobs query
    echo "<h3>Jobs-Tabelle Test:</h3>";
    try {
        $jobs = $db->query("SELECT * FROM jobs")->fetchAll();
        echo "<p style='color: green;'>✅ Jobs-Query erfolgreich! " . count($jobs) . " Jobs gefunden.</p>";
        
        if (empty($jobs)) {
            echo "<p style='color: orange;'>⚠️ Keine Jobs in der Tabelle. Füge Beispiel-Jobs hinzu...</p>";
            
            $stmt = $db->prepare("INSERT INTO jobs (id, title, department, type, description, requirements) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                'job-1',
                'Polizeibeamter/in (m/w/d)',
                'Streifendienst',
                'full-time',
                'Wir suchen motivierte Polizeibeamte für den Streifendienst.',
                'Abgeschlossene Polizeiausbildung erforderlich.'
            ]);
            echo "<p style='color: green;'>✅ Beispiel-Job hinzugefügt!</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Jobs-Query Fehler: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Datenbankverbindung fehlgeschlagen: " . $e->getMessage() . "</p>";
    
    echo "<h3>Debug-Informationen:</h3>";
    echo "<p><strong>Host:</strong> localhost</p>";
    echo "<p><strong>Database:</strong> stadtwache</p>";
    echo "<p><strong>Username:</strong> root</p>";
    echo "<p><strong>Password:</strong> [leer]</p>";
}

echo "<br><a href='jobs.php'>← Zurück zu Jobs</a>";
echo "<br><a href='index.php'>← Zurück zum Dashboard</a>";
?>