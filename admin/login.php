<?php
session_start();
// Set session cookie parameters for better compatibility
ini_set('session.cookie_lifetime', 86400); // 24 hours
require_once __DIR__ . '/../config/config.php';

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: universal_admin.php');
    exit();
}

$error = '';
$success = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $success = 'Login erfolgreich! Weiterleitung...';
        // JavaScript redirect instead of PHP header
        header('Location: universal_admin.php');
        exit();
    } else {
        $error = 'Ungültige Anmeldedaten';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hohmann Bau</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50 flex items-center justify-center">
    <div class="max-w-md w-full">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-blue-600 px-8 py-6 text-center text-white">
                <div class="flex items-center justify-center mb-2">
                    <i data-lucide="shield-check" class="w-8 h-8 mr-3"></i>
                    <h1 class="text-2xl font-bold">Hohmann Bau</h1>
                </div>
                <p class="text-green-100">Universal PHP Admin Panel</p>
            </div>
            
            <!-- Login Form -->
            <div class="px-8 py-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Admin-Anmeldung</h2>
                <p class="text-gray-600 text-center mb-6">Melden Sie sich an, um ALLES zu bearbeiten</p>
                
                <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i data-lucide="alert-circle" class="w-5 h-5 mr-3 flex-shrink-0"></i>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-3 flex-shrink-0"></i>
                        <span><?= htmlspecialchars($success) ?></span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                            Benutzername
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                            placeholder="Benutzername eingeben"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        >
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i data-lucide="lock" class="w-4 h-4 inline mr-2"></i>
                            Passwort
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                            placeholder="Passwort eingeben"
                        >
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-3 px-6 rounded-lg hover:from-green-700 hover:to-blue-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 font-medium text-lg"
                    >
                        <i data-lucide="log-in" class="w-5 h-5 inline mr-2"></i>
                        Anmelden
                    </button>
                </form>
                
                <!-- Demo Credentials -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                        Standard-Zugangsdaten:
                    </h3>
                    <div class="text-blue-800 space-y-1">
                        <p><strong>Benutzername:</strong> admin</p>
                        <p><strong>Passwort:</strong> admin123</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Features Preview -->
        <div class="mt-8 bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg">
            <h3 class="font-bold text-gray-900 mb-4 text-center">🌟 Universal Admin Panel Features</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="flex items-center text-gray-700">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Alle Seiten bearbeiten</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Design System</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Media Manager</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Navigation Editor</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Farben & Fonts</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Content Manager</span>
                </div>
            </div>
        </div>
        
        <!-- Back to Website -->
        <div class="mt-6 text-center">
            <a href="<?= BASE_URL ?>" class="text-gray-600 hover:text-gray-800 transition-colors inline-flex items-center">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Zurück zur Website
            </a>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
        
        // Auto-fill demo credentials on click
        function fillDemoCredentials() {
            document.getElementById('username').value = 'admin';
            document.getElementById('password').value = 'admin123';
        }
        
        // Add click handler to demo credentials
        document.querySelector('.bg-blue-50').addEventListener('click', fillDemoCredentials);
    </script>
</body>
</html>