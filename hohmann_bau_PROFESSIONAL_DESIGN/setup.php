<?php
/**
 * Hohmann Bau - Automatisches Setup & Installation
 * Erstellt die Datenbank und konfiguriert die Website
 */

session_start();

// Prüfen ob bereits installiert
if (file_exists('config/installed.lock')) {
    header('Location: index.php');
    exit;
}

$step = $_GET['step'] ?? 'start';
$error = '';
$success = '';

// Handle JSON API requests first
if ($step === 'process') {
    header('Content-Type: application/json');
    
    try {
        $db_host = $_POST['db_host'] ?? 'localhost';
        $db_name = $_POST['db_name'] ?? 'hohmann_bau';
        $db_user = $_POST['db_user'] ?? 'root';
        $db_password = $_POST['db_password'] ?? '';
        $admin_user = $_POST['admin_user'] ?? 'admin';
        $admin_password = $_POST['admin_password'] ?? 'admin123';
        $admin_email = $_POST['admin_email'] ?? 'admin@hohmann-bau.de';

        // 1. Datenbank-Verbindung testen
        $pdo = new PDO("mysql:host={$db_host};charset=utf8mb4", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. Datenbank erstellen
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$db_name}`");

        // 3. Tabellen erstellen
        if (file_exists('database_fix.sql')) {
            $sql = file_get_contents('database_fix.sql');
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement) && !preg_match('/^--/', $statement) && !preg_match('/^\/\*/', $statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (Exception $e) {
                        // Continue on non-critical errors
                        error_log("SQL Warning: " . $e->getMessage());
                    }
                }
            }
        }

        // 4. Admin-Benutzer erstellen
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admins (id, username, email, hashed_password) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE email = ?, hashed_password = ?");
        $admin_id = 'admin-' . uniqid();
        $stmt->execute([$admin_id, $admin_user, $admin_email, $hashed_password, $admin_email, $hashed_password]);

        // 5. Konfigurationsdatei erstellen
        if (!is_dir('config')) {
            mkdir('config', 0755, true);
        }
        
        $config_content = "<?php
// Hohmann Bau - Database Configuration (Auto-generated)
class Database {
    private \$host = '{$db_host}';
    private \$db_name = '{$db_name}';
    private \$username = '{$db_user}';
    private \$password = '{$db_password}';
    private \$pdo;

    public function getConnection() {
        \$this->pdo = null;
        
        try {
            \$this->pdo = new PDO(
                \"mysql:host=\" . \$this->host . \";dbname=\" . \$this->db_name . \";charset=utf8mb4\",
                \$this->username,
                \$this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException \$e) {
            die('Connection failed: ' . \$e->getMessage());
        }
        
        return \$this->pdo;
    }
}

function getDB() {
    \$database = new Database();
    return \$database->getConnection();
}
?>";

        file_put_contents('config/database.php', $config_content);

        // 6. Session für Setup speichern
        $_SESSION['admin_user'] = $admin_user;
        $_SESSION['setup_completed'] = true;

        // 7. Installation als abgeschlossen markieren
        file_put_contents('config/installed.lock', date('Y-m-d H:i:s') . " - Admin: {$admin_user}");

        echo json_encode([
            'success' => true, 
            'message' => 'Installation erfolgreich abgeschlossen!',
            'admin_user' => $admin_user
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'error' => $e->getMessage(),
            'details' => 'Fehler bei der Installation'
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hohmann Bau - Setup & Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
        .setup-container {
            background: linear-gradient(135deg, #10b981 0%, #065f46 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="setup-container">

<?php if ($step === 'start'): ?>
<!-- Willkommensseite -->
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-seedling text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Hohmann Bau</h1>
            <p class="text-xl text-gray-600">Garten & Landschaftsbau</p>
            <div class="w-24 h-1 bg-green-500 mx-auto mt-4"></div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-green-800 mb-4">🌿 Willkommen zum Setup!</h2>
            <p class="text-green-700 mb-4">
                Dieses Setup wird automatisch Ihre Hohmann Bau Website installieren und konfigurieren.
            </p>
            <ul class="text-green-700 space-y-2">
                <li><i class="fas fa-check text-green-600 mr-2"></i> Datenbank erstellen und konfigurieren</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Admin-Benutzer anlegen</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Standard-Inhalte importieren</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Website-Konfiguration</li>
            </ul>
        </div>

        <div class="text-center">
            <a href="setup.php?step=database" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition duration-300 inline-block">
                <i class="fas fa-rocket mr-2"></i>
                Installation starten
            </a>
        </div>
    </div>
</div>

<?php elseif ($step === 'database'): ?>
<!-- Datenbankeinstellungen -->
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-database text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Datenbank konfigurieren</h1>
            <p class="text-gray-600">Geben Sie Ihre Datenbank-Zugangsdaten ein</p>
        </div>

        <form id="dbForm" method="POST" action="setup.php?step=install">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-server mr-2 text-green-600"></i>
                        Datenbank-Host
                    </label>
                    <input type="text" name="db_host" value="localhost" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-database mr-2 text-green-600"></i>
                        Datenbank-Name
                    </label>
                    <input type="text" name="db_name" value="hohmann_bau" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-green-600"></i>
                        Datenbank-Benutzer
                    </label>
                    <input type="text" name="db_user" value="root" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-green-600"></i>
                        Datenbank-Passwort
                    </label>
                    <input type="password" name="db_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-user-shield mr-2 text-green-600"></i>
                        Admin-Benutzer
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin-Benutzername</label>
                            <input type="text" name="admin_user" value="admin" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin-Passwort</label>
                            <input type="password" name="admin_password" value="admin123" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin-E-Mail</label>
                        <input type="email" name="admin_email" value="admin@hohmann-bau.de" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <a href="setup.php?step=start" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold text-center transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Zurück
                </a>
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition duration-300">
                    <i class="fas fa-cog mr-2"></i>
                    Installation beginnen
                </button>
            </div>
        </form>
    </div>
</div>

<?php elseif ($step === 'install'): ?>
<!-- Installation Progress -->
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-cog fa-spin text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Installation läuft...</h1>
            <p class="text-gray-600">Bitte warten Sie, während Ihre Website eingerichtet wird</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="bg-gray-200 rounded-full h-4 overflow-hidden">
                <div id="progressBar" class="progress-bar bg-gradient-to-r from-green-500 to-green-600 h-full rounded-full" style="width: 0%"></div>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mt-2">
                <span>0%</span>
                <span id="progressText">Starte Installation...</span>
                <span>100%</span>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="space-y-4" id="progressSteps">
            <div class="flex items-center text-gray-500" id="step1">
                <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3">
                    <i class="fas fa-database text-sm"></i>
                </div>
                <span>Datenbank-Verbindung testen...</span>
            </div>
            
            <div class="flex items-center text-gray-500" id="step2">
                <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3">
                    <i class="fas fa-table text-sm"></i>
                </div>
                <span>Tabellen erstellen...</span>
            </div>
            
            <div class="flex items-center text-gray-500" id="step3">
                <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3">
                    <i class="fas fa-user-plus text-sm"></i>
                </div>
                <span>Admin-Benutzer anlegen...</span>
            </div>
            
            <div class="flex items-center text-gray-500" id="step4">
                <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3">
                    <i class="fas fa-seedling text-sm"></i>
                </div>
                <span>Standard-Inhalte importieren...</span>
            </div>
            
            <div class="flex items-center text-gray-500" id="step5">
                <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <span>Installation abschließen...</span>
            </div>
        </div>

        <div class="mt-8 text-center">
            <div class="animate-pulse">
                <i class="fas fa-leaf text-green-500 text-2xl mr-2"></i>
                <span class="text-green-600 font-semibold">Hohmann Bau wird installiert...</span>
                <i class="fas fa-leaf text-green-500 text-2xl ml-2"></i>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    installWebsite();
});

async function installWebsite() {
    const steps = [
        { id: 'step1', text: 'Datenbank-Verbindung testen...', progress: 20 },
        { id: 'step2', text: 'Tabellen erstellen...', progress: 40 },
        { id: 'step3', text: 'Admin-Benutzer anlegen...', progress: 60 },
        { id: 'step4', text: 'Standard-Inhalte importieren...', progress: 80 },
        { id: 'step5', text: 'Installation abschließen...', progress: 100 }
    ];

    for (let i = 0; i < steps.length; i++) {
        await new Promise(resolve => setTimeout(resolve, 1500)); // 1.5s delay
        
        const step = steps[i];
        const stepElement = document.getElementById(step.id);
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        
        // Update progress bar
        progressBar.style.width = step.progress + '%';
        progressText.textContent = step.text;
        
        // Mark step as complete
        stepElement.classList.remove('text-gray-500');
        stepElement.classList.add('text-green-600');
        
        const circle = stepElement.querySelector('div');
        circle.classList.remove('border-gray-300');
        circle.classList.add('border-green-500', 'bg-green-500');
        
        const icon = stepElement.querySelector('i');
        icon.classList.add('text-white');
        
        if (step.progress === 100) {
            // Installation complete
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // Send installation data to server
            const formData = new FormData();
            const params = new URLSearchParams(window.location.search);
            
            // Get form data from POST request
            const formElements = document.querySelectorAll('input[name], select[name]');
            
            // If we came from database form, get values from URL parameters or form
            const urlParams = new URLSearchParams(window.location.search);
            const formData = new FormData();
            
            // Add form data from previous step
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <?php foreach ($_POST as $key => $value): ?>
            formData.append('<?php echo htmlspecialchars($key); ?>', '<?php echo htmlspecialchars($value); ?>');
            <?php endforeach; ?>
            <?php else: ?>
            // Fallback values if no POST data
            formData.append('db_host', 'localhost');
            formData.append('db_name', 'hohmann_bau');
            formData.append('db_user', 'root');
            formData.append('db_password', '');
            formData.append('admin_user', 'admin');
            formData.append('admin_password', 'admin123');
            formData.append('admin_email', 'admin@hohmann-bau.de');
            <?php endif; ?>
            
            try {
                const response = await fetch('setup.php?step=process', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    progressText.textContent = '🎉 Installation erfolgreich abgeschlossen!';
                    
                    // Show completion message
                    setTimeout(() => {
                        window.location.href = 'setup.php?step=complete';
                    }, 2000);
                } else {
                    progressText.textContent = '❌ Fehler: ' + result.error;
                }
            } catch (error) {
                progressText.textContent = '❌ Installationsfehler: ' + error.message;
            }
        }
    }
}
</script>

<?php <?php elseif ($step === 'complete'): ?>
<!-- Installation abgeschlossen -->
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full text-center">
        <div class="mb-8">
            <div class="w-24 h-24 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-green-600 mb-4">🎉 Installation erfolgreich!</h1>
            <p class="text-xl text-gray-600 mb-8">
                Ihre Hohmann Bau Website ist jetzt einsatzbereit!
            </p>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-green-800 mb-4">Was wurde installiert:</h2>
            <ul class="text-green-700 space-y-2 text-left">
                <li><i class="fas fa-check text-green-600 mr-2"></i> Datenbank mit allen Tabellen</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Admin-Benutzer angelegt</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Standard-Inhalte importiert</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Gartenbau-spezifische Konfiguration</li>
            </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-bold text-blue-800 mb-2">🌐 Website besuchen</h3>
                <p class="text-blue-700 text-sm mb-3">Schauen Sie sich Ihre neue Website an</p>
                <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300 inline-block">
                    Zur Website
                </a>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-bold text-green-800 mb-2">⚙️ Admin Panel</h3>
                <p class="text-green-700 text-sm mb-3">Verwalten Sie Ihre Website</p>
                <a href="admin/login.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300 inline-block">
                    Admin Login
                </a>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-left">
            <h3 class="font-bold text-gray-800 mb-2">📋 Wichtige Informationen:</h3>
            <ul class="text-gray-700 text-sm space-y-1">
                <li><strong>Admin-Login:</strong> <?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'admin'); ?></li>
                <li><strong>Admin-Passwort:</strong> (Das von Ihnen gewählte Passwort)</li>
                <li><strong>Setup-Datei:</strong> Diese Datei können Sie jetzt löschen</li>
            </ul>
        </div>

        <div class="mt-8">
            <p class="text-gray-500 text-sm">
                Vielen Dank, dass Sie sich für Hohmann Bau entschieden haben! 🌿
            </p>
        </div>
    </div>
</div>

<?php endif; ?>

</body>
</html>