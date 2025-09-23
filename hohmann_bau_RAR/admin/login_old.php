<?php
/**
 * Simple Admin Login - XAMPP Windows Fix
 */

session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Try database authentication first
    require_once '../config/database.php';
    require_once '../config/auth.php';
    
    if (Auth::login($username, $password)) {
        header('Location: index.php');
        exit;
    } 
    // Fallback to hardcoded authentication
    else if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_id'] = 'admin-1';
        $_SESSION['admin_username'] = 'admin';
        $_SESSION['admin_email'] = 'admin@hohmann-bau.de';
        $_SESSION['login_time'] = time();
        
        header('Location: index.php');
        exit;
    } else {
        $error_message = 'Ungültiger Benutzername oder Passwort.';
    }
}

$pageTitle = 'Admin Login - Hohmann Bau';
?>
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="flex justify-center">
                    <i class="fas fa-shield-alt text-6xl text-blue-600 mb-4"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Hohmann Bau Admin</h2>
                <p class="text-gray-600">Bitte melden Sie sich an</p>
            </div>
            
            <?php if (isset($_GET['timeout'])): ?>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                    <p class="text-yellow-700">Ihre Sitzung ist abgelaufen. Bitte melden Sie sich erneut an.</p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <p class="text-red-700"><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="bg-white rounded-xl shadow-lg p-8 space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Benutzername</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Benutzername eingeben">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Passwort</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Passwort eingeben">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md transition duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Anmelden
                </button>
            </form>
            
            <div class="text-center">
                <a href="../index.php" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Zurück zur Hauptseite
                </a>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-sm text-blue-700">
                    <strong>Demo-Zugang:</strong><br>
                    Benutzername: admin<br>
                    Passwort: admin123
                </p>
            </div>
        </div>
    </div>
    
</body>
</html>