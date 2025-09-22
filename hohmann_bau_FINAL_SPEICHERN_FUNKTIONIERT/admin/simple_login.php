<?php
// Simple Admin Login - Windows Apache Fix
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple hardcoded check for now
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_id'] = '1';
        $_SESSION['admin_username'] = 'admin';
        $_SESSION['admin_email'] = 'admin@stadtwache.de';
        $_SESSION['login_time'] = time();
        
        header('Location: index.php');
        exit;
    } else {
        $error = 'Falscher Benutzername oder Passwort!';
    }
}

// Check if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Stadtwache</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <i class="fas fa-shield-alt text-6xl text-blue-600 mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Stadtwache Admin</h2>
                <p class="text-gray-600">Einfacher Login</p>
            </div>
            
            <?php if (isset($error)): ?>
            <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="bg-white rounded-xl shadow-lg p-8">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 mb-2">Benutzername</label>
                    <input type="text" id="username" name="username" value="admin" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 mb-2">Passwort</label>
                    <input type="password" id="password" name="password" value="admin123" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i>Anmelden
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="../index.php" class="text-blue-600 hover:text-blue-800">← Zurück zur Homepage</a>
            </div>
            
            <div class="bg-yellow-50 rounded p-4 mt-4 text-center">
                <p class="text-sm text-yellow-700">
                    <strong>Demo-Zugang bereits eingegeben:</strong><br>
                    Einfach auf "Anmelden" klicken!
                </p>
            </div>
        </div>
    </div>
    
</body>
</html>