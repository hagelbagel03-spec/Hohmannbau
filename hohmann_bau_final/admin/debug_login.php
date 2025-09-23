<?php
// Complete Login Debug
session_start();

echo "<h1>🔍 Admin Login Debug</h1>";

// Test database connection
echo "<h2>1. Datenbank-Test</h2>";
try {
    require_once '../config/database.php';
    $db = getDB();
    echo "<p style='color: green;'>✅ Datenbankverbindung OK</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Datenbankfehler: " . $e->getMessage() . "</p>";
    exit;
}

// Check admin table
echo "<h2>2. Admin-Tabelle prüfen</h2>";
try {
    $admins = $db->query("SELECT * FROM admins")->fetchAll();
    echo "<p style='color: green;'>✅ " . count($admins) . " Admin-User gefunden</p>";
    
    foreach ($admins as $admin) {
        echo "<div style='background: #f0f0f0; padding: 10px; margin: 5px 0;'>";
        echo "ID: " . $admin['id'] . "<br>";
        echo "Username: " . $admin['username'] . "<br>";
        echo "Email: " . $admin['email'] . "<br>";
        echo "Password: " . substr($admin['hashed_password'], 0, 20) . "...<br>";
        echo "</div>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Admin-Tabelle Fehler: " . $e->getMessage() . "</p>";
}

// Test login process
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>3. Login-Versuch</h2>";
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    echo "<p><strong>Eingabe:</strong> '$username' / '$password'</p>";
    
    // Direct database login test
    try {
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin) {
            echo "<p style='color: green;'>✅ Admin-User '$username' gefunden</p>";
            echo "<p>Gespeichertes Passwort: " . $admin['hashed_password'] . "</p>";
            
            // Test different password checks
            $checks = [
                'Direct match' => ($admin['hashed_password'] === $password),
                'Password verify' => password_verify($password, $admin['hashed_password']),
                'bcrypt check' => password_verify($password, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
            ];
            
            foreach ($checks as $method => $result) {
                echo "<p>" . ($result ? '✅' : '❌') . " $method: " . ($result ? 'SUCCESS' : 'FAILED') . "</p>";
            }
            
            // Force login for testing
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['login_time'] = time();
                
                echo "<p style='color: green; font-weight: bold;'>🚀 FORCE LOGIN SUCCESSFUL!</p>";
                echo "<p><a href='index.php' style='background: green; color: white; padding: 10px; text-decoration: none;'>→ Zum Dashboard</a></p>";
            }
            
        } else {
            echo "<p style='color: red;'>❌ Admin-User '$username' nicht gefunden</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Login-Test Fehler: " . $e->getMessage() . "</p>";
    }
}

// Show current session
echo "<h2>4. Session-Status</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['admin_id'])) {
    echo "<p style='color: green;'>✅ Sie sind bereits eingeloggt!</p>";
    echo "<p><a href='index.php'>→ Zum Dashboard</a></p>";
}
?>

<!-- Test Login Form -->
<form method="POST" style="background: #f9f9f9; padding: 20px; border: 1px solid #ddd; margin: 20px 0;">
    <h3>🧪 Login Tester</h3>
    <p>
        <label>Username:</label><br>
        <input type="text" name="username" value="admin" style="width: 200px; padding: 5px;">
    </p>
    <p>
        <label>Password:</label><br>
        <input type="text" name="password" value="admin123" style="width: 200px; padding: 5px;">
    </p>
    <p>
        <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none;">
            🔐 Login testen
        </button>
    </p>
</form>

<p><a href="login.php">← Zurück zum normalen Login</a></p>