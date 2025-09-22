<?php
// Einfacher Login-Test für Windows Apache
require_once '../config/database.php';
require_once '../config/auth.php';

echo "<h1>Admin Login Test</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    echo "<p>Versuche Login mit: $username</p>";
    
    if (Auth::login($username, $password)) {
        echo "<p style='color: green;'>✅ Login erfolgreich!</p>";
        echo "<p><a href='index.php'>Zum Dashboard</a></p>";
    } else {
        echo "<p style='color: red;'>❌ Login fehlgeschlagen!</p>";
    }
}
?>

<form method="POST" style="margin: 20px; padding: 20px; border: 1px solid #ccc;">
    <h3>Admin Login Tester</h3>
    <p>
        <label>Benutzername:</label><br>
        <input type="text" name="username" value="admin" style="padding: 5px; width: 200px;">
    </p>
    <p>
        <label>Passwort:</label><br>
        <input type="password" name="password" value="admin123" style="padding: 5px; width: 200px;">
    </p>
    <p>
        <button type="submit" style="padding: 10px 20px; background: blue; color: white; border: none;">
            Login testen
        </button>
    </p>
</form>

<p><a href="../index.php">← Zurück zur Hauptseite</a></p>