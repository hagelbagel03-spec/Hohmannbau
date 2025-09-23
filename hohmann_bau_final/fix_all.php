<?php
/**
 * Complete Fix Script für XAMPP Windows
 * Behebt alle Datenbankprobleme und API-Issues
 */

echo "<h1>🔧 Stadtwache Fix Script</h1>";

// 1. Database Connection Test
echo "<h2>1. Datenbank-Test</h2>";
try {
    require_once 'config/database.php';
    $db = getDB();
    echo "<p style='color: green;'>✅ Datenbankverbindung erfolgreich!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Datenbankfehler: " . $e->getMessage() . "</p>";
    echo "<p><strong>Lösung:</strong> Starten Sie MySQL in XAMPP und erstellen Sie die Datenbank 'stadtwache'</p>";
    exit;
}

// 2. Check and Create Tables
echo "<h2>2. Tabellen prüfen und erstellen</h2>";

$tables_to_check = [
    'jobs' => "CREATE TABLE jobs (
        id varchar(36) NOT NULL,
        title varchar(255) NOT NULL,
        department varchar(255) NOT NULL,
        type enum('full-time','part-time','contract','intern') NOT NULL DEFAULT 'full-time',
        location varchar(255) NOT NULL DEFAULT 'Musterstadt',
        description text NOT NULL,
        requirements text DEFAULT NULL,
        benefits text DEFAULT NULL,
        salary_range varchar(100) DEFAULT NULL,
        active boolean NOT NULL DEFAULT true,
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )",
    
    'applications' => "CREATE TABLE applications (
        id varchar(36) NOT NULL,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        phone varchar(50) NOT NULL,
        position varchar(255) NOT NULL,
        message text NOT NULL,
        cv_filename varchar(255) DEFAULT NULL,
        status enum('pending','reviewed','accepted','rejected') NOT NULL DEFAULT 'pending',
        admin_response text DEFAULT NULL,
        admin_email varchar(255) DEFAULT NULL,
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )"
];

foreach ($tables_to_check as $table => $create_sql) {
    try {
        $db->query("SELECT 1 FROM $table LIMIT 1");
        echo "<p style='color: green;'>✅ Tabelle '$table' existiert</p>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ Tabelle '$table' fehlt - wird erstellt...</p>";
        try {
            $db->exec($create_sql);
            echo "<p style='color: green;'>✅ Tabelle '$table' erfolgreich erstellt!</p>";
        } catch (Exception $e2) {
            echo "<p style='color: red;'>❌ Fehler beim Erstellen von '$table': " . $e2->getMessage() . "</p>";
        }
    }
}

// 3. Insert Sample Data
echo "<h2>3. Beispieldaten einfügen</h2>";

// Check if jobs table has data
try {
    $jobCount = $db->query("SELECT COUNT(*) as count FROM jobs")->fetch()['count'];
    if ($jobCount == 0) {
        echo "<p style='color: orange;'>⚠️ Jobs-Tabelle leer - füge Beispieldaten hinzu...</p>";
        
        $jobs = [
            ['job-1', 'Polizeibeamter/in (m/w/d)', 'Streifendienst', 'full-time', 'Wir suchen motivierte Polizeibeamte für den Streifendienst.', 'Abgeschlossene Polizeiausbildung erforderlich.'],
            ['job-2', 'Ermittler/in (m/w/d)', 'Ermittlungsabteilung', 'full-time', 'Für unsere Ermittlungsabteilung suchen wir erfahrene Kriminalbeamte.', 'Spezialisierung in Tatort- und Spurensicherung.'],
            ['job-3', 'Sachbearbeiter/in (m/w/d)', 'Bürgerdienst', 'part-time', 'Unterstützung bei administrativen Aufgaben.', 'Verwaltungsausbildung erwünscht.']
        ];
        
        $stmt = $db->prepare("INSERT INTO jobs (id, title, department, type, description, requirements) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($jobs as $job) {
            $stmt->execute($job);
        }
        echo "<p style='color: green;'>✅ " . count($jobs) . " Beispiel-Jobs hinzugefügt!</p>";
    } else {
        echo "<p style='color: green;'>✅ Jobs-Tabelle hat bereits $jobCount Einträge</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Fehler bei Jobs-Daten: " . $e->getMessage() . "</p>";
}

// 4. Create uploads directory
echo "<h2>4. Upload-Verzeichnis prüfen</h2>";
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
    echo "<p style='color: green;'>✅ Upload-Verzeichnis erstellt</p>";
} else {
    echo "<p style='color: green;'>✅ Upload-Verzeichnis existiert</p>";
}

// 5. Test API Endpoints
echo "<h2>5. API-Test</h2>";
$api_files = ['applications.php', 'reports.php', 'feedback.php', 'news.php'];
foreach ($api_files as $file) {
    if (file_exists("api/$file")) {
        echo "<p style='color: green;'>✅ API-Datei $file vorhanden</p>";
    } else {
        echo "<p style='color: red;'>❌ API-Datei $file fehlt</p>";
    }
}

echo "<h2>✅ Fix Complete!</h2>";
echo "<p><strong>Nächste Schritte:</strong></p>";
echo "<ul>";
echo "<li><a href='careers.php'>Karriere-Seite testen</a></li>";
echo "<li><a href='admin/login.php'>Admin-Login testen (admin/admin123)</a></li>";
echo "<li><a href='admin/jobs.php'>Jobs-Verwaltung testen</a></li>";
echo "</ul>";
?>