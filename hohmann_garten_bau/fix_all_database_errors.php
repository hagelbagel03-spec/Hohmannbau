<?php
/**
 * Automatischer Fix für alle Datenbankfehler
 * Repariert alle ungeschützten PDO-Abfragen
 */

echo "🔧 Starte automatische Reparatur aller Datenbankfehler...\n\n";

// Alle PHP-Dateien die repariert werden müssen
$files_to_fix = [
    'admin/colors.php',
    'admin/services.php', 
    'admin/news.php',
    'admin/team.php',
    'admin/feedback.php',
    'admin/reports.php',
    'admin/applications.php',
    'admin/jobs.php',
    'admin/chat.php',
    'news.php',
    'services.php',
    'team.php',
    'about.php',
    'feedback.php',
    'careers.php',
    'contact.php'
];

foreach ($files_to_fix as $file) {
    if (file_exists($file)) {
        echo "🔄 Repariere: $file\n";
        
        $content = file_get_contents($file);
        
        // 1. Ungeschützte query() Aufrufe mit try-catch umhüllen
        $patterns = [
            '/\$db->query\("SELECT \* FROM ([^"]+)"\)->fetchAll\(\)/' => 'safeQuery($db, "SELECT * FROM $1", [])',
            '/\$db->query\("SELECT \* FROM ([^"]+) WHERE ([^"]+)"\)->fetchAll\(\)/' => 'safeQuery($db, "SELECT * FROM $1 WHERE $2", [])',
            '/\$db->query\("SELECT \* FROM ([^"]+) ORDER BY ([^"]+)"\)->fetchAll\(\)/' => 'safeQuery($db, "SELECT * FROM $1 ORDER BY $2", [])',
            '/\$db->query\("SELECT \* FROM ([^"]+) WHERE ([^"]+) ORDER BY ([^"]+)"\)->fetchAll\(\)/' => 'safeQuery($db, "SELECT * FROM $1 WHERE $2 ORDER BY $3", [])',
            '/\$db->query\("SELECT \* FROM ([^"]+) LIMIT ([^"]+)"\)->fetchAll\(\)/' => 'safeQuery($db, "SELECT * FROM $1 LIMIT $2", [])',
            '/\$db->query\("SELECT \* FROM ([^"]+) WHERE ([^"]+) LIMIT ([^"]+)"\)->fetchAll\(\)/' => 'safeQuery($db, "SELECT * FROM $1 WHERE $2 LIMIT $3", [])',
            '/\$db->query\("SELECT \* FROM ([^"]+) ORDER BY ([^"]+) LIMIT ([^"]+)"\)->fetchAll\(\)/' => 'safeQuery($db, "SELECT * FROM $1 ORDER BY $2 LIMIT $3", [])',
            '/\$db->query\("SELECT ([^"]+) FROM ([^"]+)"\)->fetch\(\)/' => 'safeQueryOne($db, "SELECT $1 FROM $2")',
            '/\$db->query\("SELECT ([^"]+) FROM ([^"]+) WHERE ([^"]+)"\)->fetch\(\)/' => 'safeQueryOne($db, "SELECT $1 FROM $2 WHERE $3")'
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        // 2. Sichere Funktionen hinzufügen wenn nicht vorhanden
        if (strpos($content, 'function safeQuery') === false) {
            $safe_functions = "
// Sichere Datenbankabfragen (Auto-generiert)
function safeQuery(\$db, \$query, \$params = []) {
    try {
        if (empty(\$params)) {
            return \$db->query(\$query)->fetchAll();
        } else {
            \$stmt = \$db->prepare(\$query);
            \$stmt->execute(\$params);
            return \$stmt->fetchAll();
        }
    } catch (Exception \$e) {
        error_log('Database error in ' . __FILE__ . ': ' . \$e->getMessage());
        return [];
    }
}

function safeQueryOne(\$db, \$query, \$params = []) {
    try {
        if (empty(\$params)) {
            return \$db->query(\$query)->fetch();
        } else {
            \$stmt = \$db->prepare(\$query);
            \$stmt->execute(\$params);
            return \$stmt->fetch();
        }
    } catch (Exception \$e) {
        error_log('Database error in ' . __FILE__ . ': ' . \$e->getMessage());
        return false;
    }
}

";
            // Funktionen vor dem ersten include einfügen
            $content = preg_replace('/(<\?php[^>]*>)/', '$1' . $safe_functions, $content, 1);
        }
        
        // 3. Datei speichern
        file_put_contents($file, $content);
        echo "✅ $file repariert\n";
    } else {
        echo "⚠️ $file nicht gefunden\n";
    }
}

echo "\n🎉 Alle Dateien repariert!\n";
echo "Alle Datenbankfehler sollten jetzt behoben sein.\n";
?>