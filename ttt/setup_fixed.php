<?php
/**
 * Hohmann Bau - Automatisches Setup & Installation (FIXED)
 */

// Handle AJAX requests first (before any HTML output)
if (isset($_GET['step']) && $_GET['step'] === 'process') {
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
            // Remove comments and split by semicolon
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement) && !preg_match('/^--/', $statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (Exception $e) {
                        // Log but continue
                        error_log("SQL Error: " . $e->getMessage());
                    }
                }
            }
        }

        // 4. Admin-Benutzer erstellen oder aktualisieren
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        
        // Erst Tabelle erstellen falls sie nicht existiert
        try {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS admins (
                    id VARCHAR(36) PRIMARY KEY,
                    username VARCHAR(100) NOT NULL UNIQUE,
                    email VARCHAR(255) NOT NULL,
                    hashed_password VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
        } catch (Exception $e) {
            error_log("Table creation error: " . $e->getMessage());
        }
        
        // Prüfen ob Admin bereits existiert
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
        $stmt->execute([$admin_user]);
        $existing_admin = $stmt->fetch();
        
        if ($existing_admin) {
            // Admin existiert bereits - aktualisieren
            $stmt = $pdo->prepare("UPDATE admins SET email = ?, hashed_password = ? WHERE username = ?");
            $stmt->execute([$admin_email, $hashed_password, $admin_user]);
        } else {
            // Neuen Admin erstellen
            $admin_id = 'admin-' . uniqid();
            $stmt = $pdo->prepare("INSERT INTO admins (id, username, email, hashed_password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$admin_id, $admin_user, $admin_email, $hashed_password]);
        }

        // 5. Konfigurationsdatei erstellen
        if (!is_dir('config')) {
            mkdir('config', 0755, true);
        }
        
        $config_content = "<?php
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
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
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

        // 6. Installation als abgeschlossen markieren
        file_put_contents('config/installed.lock', date('Y-m-d H:i:s') . " - Admin: {$admin_user}");

        // Start session and store admin info
        session_start();
        $_SESSION['setup_admin_user'] = $admin_user;

        echo json_encode([
            'success' => true,
            'message' => 'Installation erfolgreich!',
            'admin_user' => $admin_user
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// Check if already installed
session_start();
if (file_exists('config/installed.lock')) {
    header('Location: index.php');
    exit;
}

$step = $_GET['step'] ?? 'start';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hohmann Bau - Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .progress-bar { transition: width 0.5s ease-in-out; }
        .setup-container { background: linear-gradient(135deg, #10b981 0%, #065f46 100%); min-height: 100vh; }
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
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-green-800 mb-4">🌿 Setup-Assistent</h2>
            <p class="text-green-700 mb-4">Installiert automatisch Ihre Gartenbau-Website:</p>
            <ul class="text-green-700 space-y-2">
                <li><i class="fas fa-check text-green-600 mr-2"></i> Datenbank konfigurieren</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Admin-Benutzer erstellen</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Standard-Inhalte importieren</li>
                <li><i class="fas fa-check text-green-600 mr-2"></i> Website bereit in 2 Minuten</li>
            </ul>
        </div>

        <div class="text-center">
            <a href="setup_fixed.php?step=database" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition duration-300 inline-block">
                <i class="fas fa-rocket mr-2"></i>Setup starten
            </a>
        </div>
    </div>
</div>

<?php elseif ($step === 'database'): ?>
<!-- Datenbank-Konfiguration -->
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-database text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Datenbank & Admin</h1>
        </div>

        <form id="setupForm">
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Datenbank-Host</label>
                        <input type="text" name="db_host" value="localhost" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Datenbank-Name</label>
                        <input type="text" name="db_name" value="hohmann_bau" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">DB-Benutzer</label>
                        <input type="text" name="db_user" value="root" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">DB-Passwort</label>
                        <input type="password" name="db_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin-Benutzer</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Benutzername</label>
                            <input type="text" name="admin_user" value="admin" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passwort</label>
                            <input type="password" name="admin_password" value="admin123" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">E-Mail</label>
                        <input type="email" name="admin_email" value="admin@hohmann-bau.de" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition duration-300">
                    <i class="fas fa-cog mr-2"></i>Installation starten
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('setupForm').onsubmit = function(e) {
    e.preventDefault();
    
    // Show installation progress
    document.body.innerHTML = `
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cog fa-spin text-white text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Installation läuft...</h1>
                </div>

                <div class="mb-8">
                    <div class="bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div id="progressBar" class="bg-gradient-to-r from-green-500 to-green-600 h-full rounded-full" style="width: 0%"></div>
                    </div>
                    <div class="text-center mt-2">
                        <span id="progressText">Starte Installation...</span>
                    </div>
                </div>

                <div id="status" class="text-center text-gray-600">
                    Bitte warten...
                </div>
            </div>
        </div>
    `;
    
    // Start installation
    runInstallation(new FormData(this));
};

async function runInstallation(formData) {
    const steps = [
        'Datenbank-Verbindung...',
        'Tabellen erstellen...',
        'Admin-Benutzer anlegen...',
        'Konfiguration speichern...',
        'Installation abschließen...'
    ];
    
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const status = document.getElementById('status');
    
    // Animate progress
    for (let i = 0; i < steps.length; i++) {
        const progress = ((i + 1) / steps.length) * 100;
        progressBar.style.width = progress + '%';
        progressText.textContent = steps[i];
        
        await new Promise(resolve => setTimeout(resolve, 800));
    }
    
    try {
        // Send installation request
        const response = await fetch('setup_fixed.php?step=process', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            status.innerHTML = '<div class="text-green-600 font-bold">✅ Installation erfolgreich!</div>';
            setTimeout(() => {
                window.location.href = 'setup_fixed.php?step=complete&admin=' + encodeURIComponent(result.admin_user);
            }, 2000);
        } else {
            status.innerHTML = '<div class="text-red-600 font-bold">❌ Fehler: ' + result.error + '</div>';
        }
    } catch (error) {
        status.innerHTML = '<div class="text-red-600 font-bold">❌ Installation fehlgeschlagen: ' + error.message + '</div>';
    }
}
</script>

<?php elseif ($step === 'complete'): ?>
<!-- Installation abgeschlossen -->
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full text-center">
        <div class="w-24 h-24 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check text-white text-4xl"></i>
        </div>
        <h1 class="text-4xl font-bold text-green-600 mb-4">🎉 Installation erfolgreich!</h1>
        <p class="text-xl text-gray-600 mb-8">Ihre Hohmann Bau Website ist einsatzbereit!</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-lg font-semibold transition duration-300 block">
                🌐 Website besuchen
            </a>
            <a href="admin/login.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-lg font-semibold transition duration-300 block">
                ⚙️ Admin Panel
            </a>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h3 class="font-bold text-gray-800 mb-2">Login-Daten:</h3>
            <p class="text-gray-700 text-sm">
                <strong>Benutzername:</strong> <?php echo htmlspecialchars($_GET['admin'] ?? 'admin'); ?><br>
                <strong>Passwort:</strong> Das von Ihnen gewählte Passwort
            </p>
        </div>
    </div>
</div>

<?php endif; ?>

</body>
</html>