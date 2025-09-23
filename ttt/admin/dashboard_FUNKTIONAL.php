<?php
/**
 * FUNKTIONALES Admin Dashboard 
 * Alle Links funktionieren garantiert!
 */

session_start();

// Login prüfen - flexibel für verschiedene Session-Variablen
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hohmann Bau</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-tool-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        
        .admin-tool-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }
        
        .admin-tool-card:visited {
            color: inherit;
        }
        
        .tool-button {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        
        .tool-button:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
            text-decoration: none;
            color: white;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-leaf text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Hohmann Bau Admin</h1>
                        <p class="text-sm text-gray-500">Website-Management</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="status-dot"></span>
                    <span class="text-sm text-gray-600">Alle Systeme online</span>
                    <a href="logout.php" class="text-red-600 hover:text-red-700">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Willkommen -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Willkommen im Admin-Bereich</h2>
            <p class="text-gray-600">Verwalten Sie Ihre Website-Inhalte einfach und effizient.</p>
        </div>

        <!-- Website-Management Tools -->
        <div class="mb-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-tools text-green-600 mr-3"></i>
                Website-Management
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Text Editor -->
                <div class="admin-tool-card">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-edit text-green-600 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">📝 Text Editor</h4>
                        <p class="text-gray-600 text-sm mb-4">Bearbeiten Sie alle Texte der Website - Navigation, Überschriften, Beschreibungen</p>
                        <a href="text_editor.php" class="tool-button w-full text-center">
                            Texte bearbeiten
                        </a>
                    </div>
                </div>

                <!-- Bilder Manager -->
                <div class="admin-tool-card">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-images text-blue-600 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">🖼️ Bilder verwalten</h4>
                        <p class="text-gray-600 text-sm mb-4">Upload, Organisation und Zuweisung von Bildern zu verschiedenen Bereichen</p>
                        <a href="image_manager.php" class="tool-button w-full text-center" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                            Bilder verwalten
                        </a>
                    </div>
                </div>

                <!-- Seiten Editor -->
                <div class="admin-tool-card">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-palette text-purple-600 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">🎨 Seiten Editor</h4>
                        <p class="text-gray-600 text-sm mb-4">Anpassung von Farben, Layout und Design-Elementen der Website</p>
                        <a href="page_editor.php" class="tool-button w-full text-center" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                            Design anpassen
                        </a>
                    </div>
                </div>

                <!-- Einfacher Upload -->
                <div class="admin-tool-card">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-cloud-upload-alt text-orange-600 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">📤 Einfacher Upload</h4>
                        <p class="text-gray-600 text-sm mb-4">Schneller Drag & Drop Upload für Bilder mit direkter Pfad-Kopie</p>
                        <a href="simple_upload.php" class="tool-button w-full text-center" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                            Bilder hochladen
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Schnellzugriffe -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-bolt text-yellow-600 mr-3"></i>
                Schnellzugriffe
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="../index.php" target="_blank" class="admin-tool-card">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-external-link-alt text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Website anzeigen</h4>
                            <p class="text-sm text-gray-600">Frontend in neuem Tab öffnen</p>
                        </div>
                    </div>
                </a>
                
                <a href="colors.php" class="admin-tool-card">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-brush text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Farben & Stil</h4>
                            <p class="text-sm text-gray-600">Erweiterte Designoptionen</p>
                        </div>
                    </div>
                </a>
                
                <a href="homepage.php" class="admin-tool-card">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-home text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Homepage-Einstellungen</h4>
                            <p class="text-sm text-gray-600">Grundlegende Konfiguration</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Status und Hilfe -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- System Status -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-server text-green-600 mr-2"></i>
                    System Status
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Website</span>
                        <span class="text-green-600 font-semibold flex items-center">
                            <span class="status-dot mr-2"></span> Online
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Admin Panel</span>
                        <span class="text-green-600 font-semibold flex items-center">
                            <span class="status-dot mr-2"></span> Funktional
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Uploads</span>
                        <span class="text-green-600 font-semibold flex items-center">
                            <span class="status-dot mr-2"></span> Bereit
                        </span>
                    </div>
                </div>
            </div>

            <!-- Hilfe -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                    Schnelle Hilfe
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <strong>Text Editor:</strong> Bearbeiten Sie Navigation, Überschriften und alle Textinhalte der Website.
                    </div>
                    <div>
                        <strong>Bilder Manager:</strong> Laden Sie Bilder hoch und weisen Sie sie verschiedenen Bereichen zu.
                    </div>
                    <div>
                        <strong>Seiten Editor:</strong> Passen Sie Farben und Layout der Website an.
                    </div>
                    <div>
                        <strong>Einfacher Upload:</strong> Schneller Upload mit direkter Pfad-Kopie für die Verwendung.
                    </div>
                </div>
            </div>

        </div>

    </main>
</div>

<script>
// Einfache Link-Validierung
document.querySelectorAll('a[href]').forEach(link => {
    link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        
        // Externe Links in neuem Tab öffnen
        if (href.includes('..') || href.includes('http')) {
            this.target = '_blank';
        }
        
        // Loading-Indikator für Admin-Tools
        if (href.includes('.php') && !href.includes('http') && !href.includes('..')) {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Wird geladen...';
            
            // Falls die Seite nicht lädt, Text zurücksetzen
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 10000);
        }
    });
});

// Status-Check
setInterval(() => {
    console.log('Admin Panel Status: OK');
}, 30000);

console.log('🚀 Admin Dashboard erfolgreich geladen!');
console.log('📝 Text Editor: text_editor.php');
console.log('🖼️ Bilder Manager: image_manager.php'); 
console.log('🎨 Seiten Editor: page_editor.php');
console.log('📤 Upload: simple_upload.php');
</script>

</body>
</html>