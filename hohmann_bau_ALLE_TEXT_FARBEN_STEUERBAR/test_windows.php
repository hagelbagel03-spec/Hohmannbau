<?php
/**
 * Windows Test Script für Stadtwache
 * Dieses Script testet alle wichtigen Komponenten
 */
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stadtwache - Windows Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    
    <div class="container mx-auto py-8 px-4">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-600 mb-4">
                <i class="fas fa-shield-alt mr-2"></i>
                Stadtwache - Windows Test
            </h1>
            <p class="text-xl text-gray-600">Testen Sie alle Funktionen der PHP-Anwendung</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Homepage Test -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-green-600">
                    <i class="fas fa-home mr-2"></i>
                    Homepage
                </h2>
                <p class="text-gray-600 mb-4">Hauptseite mit allen Features</p>
                <div class="space-y-2">
                    <a href="index.php" class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">
                        Homepage öffnen
                    </a>
                    <div class="text-sm text-gray-500">
                        ✅ Status: <?php echo file_exists('index.php') ? 'Verfügbar' : 'Fehlt'; ?>
                    </div>
                </div>
            </div>

            <!-- Report Test -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-red-600">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Vorfall melden
                </h2>
                <p class="text-gray-600 mb-4">Incident-Report Formular</p>
                <div class="space-y-2">
                    <a href="report.php" class="block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-center">
                        Formular öffnen
                    </a>
                    <div class="text-sm text-gray-500">
                        ✅ Status: <?php echo file_exists('report.php') ? 'Verfügbar' : 'Fehlt'; ?>
                    </div>
                </div>
            </div>

            <!-- Careers Test -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-purple-600">
                    <i class="fas fa-briefcase mr-2"></i>
                    Bewerbungen
                </h2>
                <p class="text-gray-600 mb-4">Karriere mit CV-Upload</p>
                <div class="space-y-2">
                    <a href="careers.php" class="block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 text-center">
                        Bewerbung öffnen
                    </a>
                    <div class="text-sm text-gray-500">
                        ✅ Status: <?php echo file_exists('careers.php') ? 'Verfügbar' : 'Fehlt'; ?>
                    </div>
                </div>
            </div>

            <!-- Feedback Test -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-yellow-600">
                    <i class="fas fa-star mr-2"></i>
                    Feedback
                </h2>
                <p class="text-gray-600 mb-4">Bewertungen mit Sternen</p>
                <div class="space-y-2">
                    <a href="feedback.php" class="block bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 text-center">
                        Feedback öffnen
                    </a>
                    <div class="text-sm text-gray-500">
                        ✅ Status: <?php echo file_exists('feedback.php') ? 'Verfügbar' : 'Fehlt'; ?>
                    </div>
                </div>
            </div>

            <!-- News Test -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-blue-600">
                    <i class="fas fa-newspaper mr-2"></i>
                    Aktuelles
                </h2>
                <p class="text-gray-600 mb-4">News und Meldungen</p>
                <div class="space-y-2">
                    <a href="news.php" class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">
                        News öffnen
                    </a>
                    <div class="text-sm text-gray-500">
                        ✅ Status: <?php echo file_exists('news.php') ? 'Verfügbar' : 'Fehlt'; ?>
                    </div>
                </div>
            </div>

            <!-- Admin Test -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-600">
                    <i class="fas fa-user-shield mr-2"></i>
                    Admin-Panel
                </h2>
                <p class="text-gray-600 mb-4">Dashboard und Verwaltung</p>
                <div class="space-y-2">
                    <a href="admin/login.php" class="block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-center">
                        Admin-Login
                    </a>
                    <div class="text-sm text-gray-500">
                        ✅ Status: <?php echo file_exists('admin/login.php') ? 'Verfügbar' : 'Fehlt'; ?>
                    </div>
                    <div class="text-xs bg-gray-100 p-2 rounded">
                        <strong>Demo:</strong> admin / admin123
                    </div>
                </div>
            </div>

        </div>

        <!-- System Info -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">
                <i class="fas fa-info-circle mr-2"></i>
                System-Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">PHP-Konfiguration:</h3>
                    <ul class="text-sm space-y-1">
                        <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
                        <li><strong>PDO MySQL:</strong> <?php echo extension_loaded('pdo_mysql') ? '✅ Aktiviert' : '❌ Nicht verfügbar'; ?></li>
                        <li><strong>File Uploads:</strong> <?php echo ini_get('file_uploads') ? '✅ Aktiviert' : '❌ Deaktiviert'; ?></li>
                        <li><strong>Max Upload:</strong> <?php echo ini_get('upload_max_filesize'); ?></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-2">Dateien-Status:</h3>
                    <ul class="text-sm space-y-1">
                        <li><strong>config/database.php:</strong> <?php echo file_exists('config/database.php') ? '✅ Vorhanden' : '❌ Fehlt'; ?></li>
                        <li><strong>includes/header.php:</strong> <?php echo file_exists('includes/header.php') ? '✅ Vorhanden' : '❌ Fehlt'; ?></li>
                        <li><strong>api/ Ordner:</strong> <?php echo is_dir('api') ? '✅ Vorhanden' : '❌ Fehlt'; ?></li>
                        <li><strong>uploads/ Ordner:</strong> 
                            <?php 
                            if (!is_dir('uploads')) {
                                mkdir('uploads', 0777, true);
                                echo '✅ Erstellt';
                            } else {
                                echo is_writable('uploads') ? '✅ Beschreibbar' : '⚠️ Nicht beschreibbar';
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Database Test -->
        <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">
                <i class="fas fa-database mr-2"></i>
                Datenbank-Test
            </h2>
            
            <?php
            try {
                require_once 'config/database.php';
                $db = getDB();
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">';
                echo '<strong>✅ Datenbankverbindung erfolgreich!</strong>';
                echo '</div>';
                
                // Test some tables
                $tables = ['admins', 'applications', 'feedback', 'news', 'reports'];
                echo '<div class="mt-4"><h3 class="font-semibold mb-2">Tabellen-Status:</h3><ul class="text-sm space-y-1">';
                foreach ($tables as $table) {
                    try {
                        $stmt = $db->query("SELECT COUNT(*) as count FROM $table");
                        $count = $stmt->fetch()['count'];
                        echo "<li><strong>$table:</strong> ✅ $count Einträge</li>";
                    } catch (Exception $e) {
                        echo "<li><strong>$table:</strong> ❌ Fehlt oder leer</li>";
                    }
                }
                echo '</ul></div>';
                
            } catch (Exception $e) {
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">';
                echo '<strong>❌ Datenbankverbindung fehlgeschlagen:</strong><br>';
                echo htmlspecialchars($e->getMessage());
                echo '<br><br><strong>Lösung:</strong>';
                echo '<ol class="list-decimal list-inside mt-2">';
                echo '<li>MySQL/MariaDB starten</li>';
                echo '<li>Datenbank "stadtwache" erstellen</li>';
                echo '<li>database.sql importieren</li>';
                echo '<li>config/database.php anpassen</li>';
                echo '</ol>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- Quick Links -->
        <div class="mt-6 bg-blue-50 rounded-lg p-6 text-center">
            <h2 class="text-xl font-bold mb-4">🚀 Schnellstart für Windows</h2>
            <div class="space-y-2 text-sm">
                <p><strong>1.</strong> Alle PHP-Dateien in Apache DocumentRoot kopieren</p>
                <p><strong>2.</strong> MySQL starten und database.sql importieren</p>
                <p><strong>3.</strong> config/database.php anpassen</p>
                <p><strong>4.</strong> mod_rewrite aktivieren (optional für saubere URLs)</p>
                <p><strong>5.</strong> Apache neustarten</p>
            </div>
            
            <div class="mt-4">
                <a href="WINDOWS_INSTALLATION.md" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    📋 Vollständige Windows-Anleitung
                </a>
            </div>
        </div>
    </div>
    
</body>
</html>