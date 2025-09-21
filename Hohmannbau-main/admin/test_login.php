<?php
session_start();

// Debug info
echo "<h1>Login Debug</h1>";
echo "<h3>POST Data:</h3>";
var_dump($_POST);
echo "<h3>Session Data:</h3>";
var_dump($_SESSION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    echo "<h3>Credentials:</h3>";
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password . "<br>";
    
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        echo "<h3 style='color: green;'>LOGIN SUCCESS!</h3>";
        echo "<a href='universal_admin.php'>Go to Admin Panel</a>";
    } else {
        echo "<h3 style='color: red;'>LOGIN FAILED!</h3>";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="admin" value="admin"><br><br>
    <input type="password" name="password" placeholder="admin123" value="admin123"><br><br>
    <input type="submit" value="Test Login">
</form>