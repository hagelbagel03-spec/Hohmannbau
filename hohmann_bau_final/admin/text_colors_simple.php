<?php
// Text-Farben-Editor - NUR TEXTE, NICHT ANDERE ELEMENTE
require_once '../config/database.php';

// Handle form submission
$message = '';
if ($_POST) {
    $action = $_POST['action'] ?? 'single';
    
    if ($action === 'bulk_all') {
        // NUR TEXTFARBEN ÄNDERN - KEINE BUTTONS/HINTERGRÜNDE
        $bulk_color = $_POST['bulk_color'] ?? '#374151';
        
        // Get current data
        $homepage_data = getHomepageData();
        
        // Update ONLY TEXT COLORS
        $homepage_data['body_text_color'] = $bulk_color;
        $homepage_data['heading_color'] = $bulk_color;
        $homepage_data['subheading_color'] = $bulk_color;
        $homepage_data['service_description_color'] = $bulk_color;
        
        if (updateHomepageData($homepage_data)) {
            $message = '🎉 ALLE TEXTE erfolgreich geändert!';
        } else {
            $message = '❌ Fehler beim Speichern!';
        }
    } else {
        // Einzelne Textfarbe ändern
        $service_color = $_POST['service_description_color'] ?? '#374151';
        
        $homepage_data = getHomepageData();
        $homepage_data['service_description_color'] = $service_color;
        
        if (updateHomepageData($homepage_data)) {
            $message = '✅ Textfarbe gespeichert!';
        } else {
            $message = '❌ Fehler beim Speichern!';
        }
    }
}

// Get current data
$homepage_data = getHomepageData();
$current_color = $homepage_data['service_description_color'] ?? '#374151';
$body_text_color = $homepage_data['body_text_color'] ?? '#374151';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌈 Text-Farben - Hohmann Bau Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 250px;
            background: #1f2937;
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }
        .admin-content {
            margin-left: 250px;
            min-height: 100vh;
            background: #f3f4f6;
        }
        .sidebar-item {
            display: block;
            padding: 12px 20px;
            color: #d1d5db;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        .sidebar-item:hover, .sidebar-item.active {
            background: #374151;
            border-left-color: #10b981;
            color: white;
        }
        .preview-text {
            font-size: 18px;
            font-weight: 500;
            margin: 15px 0;
            padding: 15px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .color-input {
            width: 100px;
            height: 50px;
            border: 2px solid #ccc;
            border-radius: 8px;
            cursor: pointer;
        }
        .bulk-section {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- ADMIN SIDEBAR - BLEIBT IMMER DA -->
    <div class="admin-sidebar">
        <div class="p-6 border-b border-gray-600">
            <h2 class="text-xl font-bold">Admin Panel</h2>
            <p class="text-sm text-gray-300">Hohmann Bau</p>
        </div>
        
        <nav class="py-4">
            <a href="index.php" class="sidebar-item">
                📊 Dashboard
            </a>
            <a href="homepage.php" class="sidebar-item">
                🏠 Homepage verwalten
            </a>
            <a href="text_colors_simple.php" class="sidebar-item active">
                🌈 Text-Farben
            </a>
            <a href="colors.php" class="sidebar-item">
                🎨 Design & Farben
            </a>
            <a href="services.php" class="sidebar-item">
                🔧 Dienstleistungen
            </a>
            <a href="team.php" class="sidebar-item">
                👥 Team
            </a>
            <a href="news.php" class="sidebar-item">
                📰 Neuigkeiten
            </a>
            <a href="contact.php" class="sidebar-item">
                📞 Kontakt
            </a>
            <a href="jobs.php" class="sidebar-item">
                💼 Stellenanzeigen
            </a>
            <a href="reports.php" class="sidebar-item">
                📈 Berichte
            </a>
            <div class="border-t border-gray-600 my-4"></div>
            <a href="../" target="_blank" class="sidebar-item">
                🔗 Website ansehen
            </a>
            <a href="logout.php" class="sidebar-item">
                🚪 Abmelden
            </a>
        </nav>
    </div>
    
    <!-- MAIN CONTENT -->
    <div class="admin-content">
        <div class="p-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">🌈 Text-Farben-Editor</h1>
                <p class="text-gray-600">Ändern Sie alle Texte gleichzeitig oder einzeln</p>
            </div>
            
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?= strpos($message, '🎉') !== false || strpos($message, '✅') !== false ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                    <div class="text-lg font-medium"><?= $message ?></div>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                
                <!-- ALLE TEXTE GLEICHZEITIG -->
                <div class="bulk-section">
                    <h2 class="text-2xl font-bold mb-4">⚡ ALLE TEXTE ÄNDERN</h2>
                    <p class="mb-6 opacity-90">Alle Texte auf der Website in einer Farbe</p>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="bulk_all">
                        
                        <div>
                            <label class="block text-lg font-medium mb-3">🎨 Farbe für alle Texte</label>
                            
                            <div class="flex items-center gap-4">
                                <input type="color" 
                                       name="bulk_color" 
                                       value="<?= $body_text_color ?>" 
                                       class="color-input"
                                       id="bulkColorPicker">
                                
                                <input type="text" 
                                       value="<?= $body_text_color ?>" 
                                       class="px-3 py-2 border border-white rounded-lg text-gray-800"
                                       id="bulkColorDisplay"
                                       readonly>
                            </div>
                        </div>
                        
                        <!-- Vorschau -->
                        <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                            <h4 class="font-bold mb-3">👁️ Vorschau:</h4>
                            <div class="space-y-2 text-sm">
                                <p id="bulk-preview-1" style="color: <?= $body_text_color ?>">✓ Professionelle Planung und Design für Ihren Traumgarten</p>
                                <p id="bulk-preview-2" style="color: <?= $body_text_color ?>">✓ Von der Planung bis zur Pflege - kompletter Service</p>
                                <p id="bulk-preview-3" style="color: <?= $body_text_color ?>">✓ Erfahrene Experten mit Leidenschaft für Garten</p>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-white text-green-700 py-3 px-6 rounded-lg hover:bg-gray-100 font-bold">
                                ⚡ ALLE TEXTE ÄNDERN
                        </button>
                    </form>
                    
                    <!-- Schnell-Farben -->
                    <div class="mt-4">
                        <h4 class="font-medium mb-3">🚀 Schnell-Farben:</h4>
                        <div class="flex gap-2 flex-wrap">
                            <button onclick="setBulkColor('#1f2937')" class="w-10 h-10 rounded border-2 border-white" style="background: #1f2937"></button>
                            <button onclick="setBulkColor('#374151')" class="w-10 h-10 rounded border-2 border-white" style="background: #374151"></button>
                            <button onclick="setBulkColor('#6b7280')" class="w-10 h-10 rounded border-2 border-white" style="background: #6b7280"></button>
                            <button onclick="setBulkColor('#2563eb')" class="w-10 h-10 rounded border-2 border-white" style="background: #2563eb"></button>
                            <button onclick="setBulkColor('#059669')" class="w-10 h-10 rounded border-2 border-white" style="background: #059669"></button>
                            <button onclick="setBulkColor('#dc2626')" class="w-10 h-10 rounded border-2 border-white" style="background: #dc2626"></button>
                        </div>
                    </div>
                </div>
                
                <!-- EINZELNER TEXT -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">🎯 Einzelnen Text ändern</h2>
                    <p class="text-gray-600 mb-6">Nur "Von der Planung bis zur Pflege..." Text</p>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="single">
                        
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-3">Service-Text Farbe</label>
                            
                            <div class="flex items-center gap-4">
                                <input type="color" 
                                       name="service_description_color" 
                                       value="<?= $current_color ?>" 
                                       class="color-input"
                                       id="singleColorPicker">
                                
                                <input type="text" 
                                       value="<?= $current_color ?>" 
                                       class="px-3 py-2 border border-gray-300 rounded-lg"
                                       id="singleColorDisplay"
                                       readonly>
                            </div>
                        </div>
                        
                        <!-- Vorschau -->
                        <div class="preview-text" id="singlePreviewText" style="color: <?= $current_color ?>">
                            Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 font-medium">
                                💾 Diesen Text speichern
                        </button>
                    </form>
                    
                    <!-- Schnell-Farben -->
                    <div class="mt-4">
                        <h4 class="font-medium text-gray-700 mb-3">Beliebte Farben:</h4>
                        <div class="flex gap-2 flex-wrap">
                            <button onclick="setSingleColor('#2d5016')" class="w-8 h-8 rounded border" style="background: #2d5016"></button>
                            <button onclick="setSingleColor('#059669')" class="w-8 h-8 rounded border" style="background: #059669"></button>
                            <button onclick="setSingleColor('#374151')" class="w-8 h-8 rounded border" style="background: #374151"></button>
                            <button onclick="setSingleColor('#dc2626')" class="w-8 h-8 rounded border" style="background: #dc2626"></button>
                            <button onclick="setSingleColor('#2563eb')" class="w-8 h-8 rounded border" style="background: #2563eb"></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Info -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-bold text-yellow-800 mb-2">ℹ️ Info</h3>
                <p class="text-yellow-700"><strong>ALLE TEXTE:</strong> Ändert nur die Textfarben, keine Buttons oder Hintergründe.</p>
            </div>
        </div>
    </div>
    
    <script>
        // BULK COLOR PICKER
        const bulkColorPicker = document.getElementById('bulkColorPicker');
        const bulkColorDisplay = document.getElementById('bulkColorDisplay');
        const bulkPreviews = ['bulk-preview-1', 'bulk-preview-2', 'bulk-preview-3'];
        
        bulkColorPicker.addEventListener('input', function() {
            const newColor = this.value;
            bulkColorDisplay.value = newColor;
            bulkPreviews.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.style.color = newColor;
            });
        });
        
        // SINGLE COLOR PICKER
        const singleColorPicker = document.getElementById('singleColorPicker');
        const singleColorDisplay = document.getElementById('singleColorDisplay');
        const singlePreviewText = document.getElementById('singlePreviewText');
        
        singleColorPicker.addEventListener('input', function() {
            const newColor = this.value;
            singleColorDisplay.value = newColor;
            singlePreviewText.style.color = newColor;
        });
        
        // Quick color functions
        function setBulkColor(color) {
            bulkColorPicker.value = color;
            bulkColorDisplay.value = color;
            bulkPreviews.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.style.color = color;
            });
        }
        
        function setSingleColor(color) {
            singleColorPicker.value = color;
            singleColorDisplay.value = color;
            singlePreviewText.style.color = color;
        }
    </script>
</body>
</html>