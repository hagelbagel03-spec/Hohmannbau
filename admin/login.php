<?php
/**
 * Professional Admin Login
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
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error_message = 'Ungültiger Benutzername oder Passwort';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Login - Hohmann Bau</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .login-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b  50%, #334155 100%);
            position: relative;
            overflow: hidden;
        }
        
        .login-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            opacity: 0.3;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .input-professional {
            transition: all 0.3s ease;
        }
        
        .input-professional:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(14, 165, 233, 0.2);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(14, 165, 233, 0.4);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .slide-in {
            animation: slideIn 0.6s ease-out;
        }
        
        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4">

    <div class="login-card rounded-2xl p-8 w-full max-w-md slide-in">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 floating">
                <i class="fas fa-leaf text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Hohmann Bau</h1>
            <p class="text-gray-600">Admin-Panel Anmeldung</p>
        </div>

        <!-- Login Form -->
        <form method="POST" class="space-y-6">
            <?php if ($error_message): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center slide-in">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- Username Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-gray-500"></i>
                    Benutzername
                </label>
                <input type="text" 
                       name="username" 
                       required 
                       class="input-professional w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20"
                       placeholder="Geben Sie Ihren Benutzernamen ein"
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <!-- Password Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-500"></i>
                    Passwort
                </label>
                <div class="relative">
                    <input type="password" 
                           name="password" 
                           id="password"
                           required 
                           class="input-professional w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 pr-12"
                           placeholder="Geben Sie Ihr Passwort ein">
                    <button type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Login Button -->
            <button type="submit" 
                    class="btn-login w-full py-3 px-4 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 flex items-center justify-center space-x-2">
                <i class="fas fa-sign-in-alt"></i>
                <span>Anmelden</span>
            </button>
        </form>

        <!-- Demo Credentials -->
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-sm font-semibold text-blue-900 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Demo-Zugangsdaten
            </h3>
            <div class="text-sm text-blue-800 space-y-1">
                <p><strong>Benutzername:</strong> <code class="bg-blue-100 px-2 py-1 rounded">admin</code></p>
                <p><strong>Passwort:</strong> <code class="bg-blue-100 px-2 py-1 rounded">admin123</code></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 pt-6 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                © 2024 Hohmann Bau. Sicherer Admin-Zugang.
            </p>
        </div>
    </div>

    <!-- Background Decorations -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-20 h-20 bg-blue-500 rounded-full opacity-10 floating"></div>
        <div class="absolute top-32 right-20 w-16 h-16 bg-green-500 rounded-full opacity-10 floating" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 left-32 w-12 h-12 bg-purple-500 rounded-full opacity-10 floating" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-32 right-10 w-24 h-24 bg-yellow-500 rounded-full opacity-10 floating" style="animation-delay: 0.5s;"></div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-fill demo credentials on click
        document.addEventListener('DOMContentLoaded', function() {
            const demoSection = document.querySelector('.bg-blue-50');
            if (demoSection) {
                demoSection.style.cursor = 'pointer';
                demoSection.addEventListener('click', function() {
                    document.querySelector('input[name="username"]').value = 'admin';
                    document.querySelector('input[name="password"]').value = 'admin123';
                    
                    // Visual feedback
                    this.style.background = '#dbeafe';
                    setTimeout(() => {
                        this.style.background = '#eff6ff';
                    }, 200);
                });
            }
        });

        // Enhanced form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Anmeldung läuft...</span>';
            submitBtn.disabled = true;
            
            // Re-enable after 3 seconds as fallback
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 3000);
        });
    </script>

</body>
</html>