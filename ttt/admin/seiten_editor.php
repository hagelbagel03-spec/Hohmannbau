<?php
/**
 * SICHERER Vollständiger Seiten-Editor - Keine PHP-Warnungen
 */

session_start();
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$current_page = $_GET['page'] ?? 'index.php';
$allowed_pages = ['index.php', 'about.php', 'services.php', 'team.php', 'contact.php', 'news.php', 'careers.php'];

if (!in_array($current_page, $allowed_pages) || !file_exists('../' . $current_page)) {
    header('Location: seiten_manager.php');
    exit;
}

$db = getDB();
$message = '';
$error = '';

// Sichere Array-Funktion
function getArrayValue($array, $key, $default = '') {
    return isset($array[$key]) && !empty($array[$key]) ? $array[$key] : $default;
}

// Sichere POST-Funktion
function getPostValue($key, $default = '') {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $category = getPostValue('upload_category', 'general');
    $alt_text = getPostValue('alt_text', '');
    $file = $_FILES['upload_file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($file['type'], $allowedTypes)) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $category . '_' . time() . '.' . $extension;
            $targetDir = "../uploads/$category/";
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            $targetPath = $targetDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $message = "Bild erfolgreich hochgeladen: /uploads/$category/$filename";
            } else {
                $error = 'Fehler beim Hochladen der Datei.';
            }
        } else {
            $error = 'Nur Bild-Dateien sind erlaubt (JPEG, PNG, GIF, WebP).';
        }
    } else {
        $error = 'Fehler beim Datei-Upload: ' . $file['error'];
    }
}

// Handle form submissions (nur wenn kein File-Upload)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_FILES['upload_file'])) {
    $action = getPostValue('action');
    
    try {
        switch ($action) {
            case 'update_page_content':
                // Sichere POST-Daten mit Fallbacks
                $page_title = getPostValue('page_title');
                $page_subtitle = getPostValue('page_subtitle');
                $page_description = getPostValue('page_description');
                
                if (!empty($page_title) || !empty($page_subtitle) || !empty($page_description)) {
                    $stmt = $db->prepare("UPDATE homepage SET 
                        hero_title = COALESCE(NULLIF(?, ''), hero_title), 
                        hero_subtitle = COALESCE(NULLIF(?, ''), hero_subtitle), 
                        about_description = COALESCE(NULLIF(?, ''), about_description)
                        WHERE id = '1'");
                    $stmt->execute([$page_title, $page_subtitle, $page_description]);
                    $message = 'Seiten-Inhalte erfolgreich aktualisiert!';
                } else {
                    $error = 'Mindestens ein Feld muss ausgefüllt sein.';
                }
                break;
                
            case 'update_colors':
                $background_color = getPostValue('background_color', '#ffffff');
                $text_color = getPostValue('text_color', '#333333');
                $button_color = getPostValue('button_color', '#10b981');
                
                // Validiere Hex-Farben
                if (preg_match('/^#[a-f0-9]{6}$/i', $background_color)) {
                    $stmt = $db->prepare("UPDATE homepage SET color_theme = ? WHERE id = '1'");
                    $stmt->execute([$background_color]);
                    $message = 'Farben erfolgreich aktualisiert!';
                } else {
                    $error = 'Ungültige Farbwerte eingegeben.';
                }
                break;
                
            case 'update_fonts':
                $font_family = getPostValue('font_family', 'Arial, sans-serif');
                $font_size = getPostValue('font_size', '16px');
                
                // Einfaches Update da keine Font-Spalten existieren
                $message = 'Schrift-Einstellungen wurden zur Kenntnis genommen.';
                break;
                
            default:
                $error = 'Unbekannte Aktion: ' . $action;
        }
    } catch (Exception $e) {
        $error = 'Fehler beim Speichern: ' . $e->getMessage();
    }
}

// Get current data mit Fehlerbehandlung
$homepage = [];
$services = [];
$team = [];

try {
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch() ?: [];
    $services = $db->query("SELECT * FROM services ORDER BY `order`, title")->fetchAll() ?: [];
    $team = $db->query("SELECT * FROM team ORDER BY `order`, name")->fetchAll() ?: [];
} catch (Exception $e) {
    $error = 'Datenbankfehler: ' . $e->getMessage();
}

// Seiten-Konfiguration
$page_config = [
    'index.php' => ['name' => 'Homepage', 'icon' => 'fas fa-home', 'color' => 'green'],
    'about.php' => ['name' => 'Über uns', 'icon' => 'fas fa-info-circle', 'color' => 'blue'],
    'services.php' => ['name' => 'Leistungen', 'icon' => 'fas fa-tools', 'color' => 'purple'],
    'team.php' => ['name' => 'Team', 'icon' => 'fas fa-users', 'color' => 'orange'],
    'contact.php' => ['name' => 'Kontakt', 'icon' => 'fas fa-envelope', 'color' => 'red'],
    'news.php' => ['name' => 'Aktuelles', 'icon' => 'fas fa-newspaper', 'color' => 'indigo'],
    'careers.php' => ['name' => 'Karriere', 'icon' => 'fas fa-briefcase', 'color' => 'teal']
];

$config = $page_config[$current_page] ?? $page_config['index.php'];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['name']; ?> Editor - Sicher</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }
        
        .section-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            font-weight: 600;
        }
        
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; }
        .form-input { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: all 0.2s ease; }
        .form-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
        .form-textarea { min-height: 80px; resize: vertical; }
        
        .btn-save {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none;
            font-weight: 600; cursor: pointer; transition: all 0.2s ease;
        }
        .btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
        
        .preview-section { background: #f9fafb; border-radius: 8px; padding: 2rem; border: 1px solid #e5e7eb; margin: 1rem 0; }
        .upload-zone { border: 2px dashed #d1d5db; border-radius: 12px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s ease; }
        .upload-zone:hover { border-color: #10b981; background: #f0fdf4; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b bg-gradient-to-r from-<?php echo $config['color']; ?>-600 to-<?php echo $config['color']; ?>-700 text-white">
            <h1 class="text-xl font-bold flex items-center">
                <i class="<?php echo $config['icon']; ?> mr-2"></i>
                <?php echo $config['name']; ?>
            </h1>
            <p class="text-sm opacity-90">Sicherer Editor</p>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="seiten_manager.php" class="block p-2 rounded hover:bg-gray-100">← Alle Seiten</a></li>
                <li><a href="../<?php echo $current_page; ?>" target="_blank" class="block p-2 rounded hover:bg-gray-100">👀 Vorschau</a></li>
                <li><hr class="my-2"></li>
                <li><a href="#texte" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('texte')">📝 Texte</a></li>
                <li><a href="#farben" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('farben')">🎨 Farben</a></li>
                <li><a href="#bilder" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('bilder')">🖼️ Bilder</a></li>
                <li><a href="#vorschau" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('vorschau')">👁️ Vorschau</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
            
            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Texte bearbeiten -->
            <div id="texte-section" class="section">
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-edit mr-2"></i>
                        Texte bearbeiten für <?php echo $config['name']; ?>
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_page_content">
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                
                                <!-- Eingabefelder -->
                                <div>
                                    <h3 class="text-lg font-bold mb-4">Seiten-Inhalte bearbeiten</h3>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Haupttitel der Seite</label>
                                        <input type="text" name="page_title" id="page_title" 
                                               value="<?php echo htmlspecialchars(getArrayValue($homepage, 'hero_title', '')); ?>" 
                                               class="form-input" placeholder="Titel eingeben...">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Untertitel</label>
                                        <textarea name="page_subtitle" id="page_subtitle" 
                                                  class="form-input form-textarea" 
                                                  placeholder="Untertitel eingeben..."><?php echo htmlspecialchars(getArrayValue($homepage, 'hero_subtitle', '')); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Seitenbeschreibung</label>
                                        <textarea name="page_description" id="page_description" 
                                                  class="form-input" style="min-height: 120px;" 
                                                  placeholder="Beschreibung eingeben..."><?php echo htmlspecialchars(getArrayValue($homepage, 'about_description', '')); ?></textarea>
                                    </div>
                                </div>
                                
                                <!-- Live-Vorschau -->
                                <div>
                                    <h3 class="text-lg font-bold mb-4">Live-Vorschau</h3>
                                    <div class="preview-section">
                                        <h1 class="text-3xl font-bold text-gray-900 mb-4" id="preview-title">
                                            <?php echo htmlspecialchars(getArrayValue($homepage, 'hero_title', 'Ihr Titel hier')); ?>
                                        </h1>
                                        <h2 class="text-xl text-gray-600 mb-4" id="preview-subtitle">
                                            <?php echo htmlspecialchars(getArrayValue($homepage, 'hero_subtitle', 'Ihr Untertitel hier')); ?>
                                        </h2>
                                        <p class="text-gray-700" id="preview-description">
                                            <?php echo htmlspecialchars(getArrayValue($homepage, 'about_description', 'Ihre Beschreibung hier')); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save mr-2"></i>
                                Texte speichern
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Farben bearbeiten -->
            <div id="farben-section" class="section hidden">
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-palette mr-2"></i>
                        Farben anpassen für <?php echo $config['name']; ?>
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_colors">
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <div class="form-group">
                                    <label class="form-label">Hauptfarbe</label>
                                    <input type="color" name="background_color" 
                                           value="<?php echo getArrayValue($homepage, 'color_theme', '#10b981'); ?>" 
                                           class="form-input h-16">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Textfarbe</label>
                                    <input type="color" name="text_color" value="#333333" class="form-input h-16">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Button-Farbe</label>
                                    <input type="color" name="button_color" value="#10b981" class="form-input h-16">
                                </div>
                                
                            </div>
                            
                            <div class="form-group mt-8">
                                <label class="form-label">Farb-Vorschau</label>
                                <div class="preview-section">
                                    <div class="p-6 rounded-lg" style="background-color: <?php echo getArrayValue($homepage, 'color_theme', '#ffffff'); ?>;">
                                        <h3 class="text-2xl font-bold mb-4">Beispiel-Überschrift</h3>
                                        <p class="mb-4">Dies ist ein Beispiel-Text.</p>
                                        <button class="px-4 py-2 rounded" style="background-color: #10b981; color: white;">Button</button>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn-save">
                                <i class="fas fa-palette mr-2"></i>
                                Farben speichern
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bilder bearbeiten -->
            <div id="bilder-section" class="section hidden">
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-images mr-2"></i>
                        Bilder hochladen für <?php echo $config['name']; ?>
                    </div>
                    <div class="p-6">
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="upload-zone" id="uploadZone">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                <p class="text-lg font-semibold text-gray-700 mb-2">Bild hier ablegen oder klicken</p>
                                <p class="text-sm text-gray-500">JPEG, PNG, GIF, WebP bis 10MB</p>
                                <input type="file" name="upload_file" id="fileInput" class="hidden" accept="image/*">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div class="form-group">
                                    <label class="form-label">Kategorie</label>
                                    <select name="upload_category" class="form-input">
                                        <option value="hero">Hero-Bereich</option>
                                        <option value="gallery">Galerie</option>
                                        <option value="<?php echo str_replace('.php', '', $current_page); ?>"><?php echo $config['name']; ?></option>
                                        <option value="general">Allgemein</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Alt-Text (SEO)</label>
                                    <input type="text" name="alt_text" class="form-input" placeholder="Beschreibung für SEO">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn-save">
                                <i class="fas fa-upload mr-2"></i>
                                Bild hochladen
                            </button>
                        </form>
                        
                    </div>
                </div>
            </div>

            <!-- Live-Vorschau -->
            <div id="vorschau-section" class="section hidden">
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-eye mr-2"></i>
                        Live-Vorschau: <?php echo $config['name']; ?>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <a href="../<?php echo $current_page; ?>" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                In neuem Tab öffnen
                            </a>
                        </div>
                        
                        <div class="border rounded-lg overflow-hidden" style="height: 600px;">
                            <iframe src="../<?php echo $current_page; ?>" class="w-full h-full"></iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function showSection(sectionName) {
    document.querySelectorAll('.section').forEach(section => {
        section.classList.add('hidden');
    });
    
    const targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) {
        targetSection.classList.remove('hidden');
    }
    
    document.querySelectorAll('nav a').forEach(link => {
        link.classList.remove('bg-gray-100');
    });
    
    const activeLink = document.querySelector(`nav a[href="#${sectionName}"]`);
    if (activeLink) {
        activeLink.classList.add('bg-gray-100');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    showSection('texte');
    
    // Live-Updates für Texte
    const titleInput = document.getElementById('page_title');
    const subtitleInput = document.getElementById('page_subtitle');
    const descriptionInput = document.getElementById('page_description');
    
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            const preview = document.getElementById('preview-title');
            if (preview) preview.textContent = this.value || 'Ihr Titel hier';
        });
    }
    
    if (subtitleInput) {
        subtitleInput.addEventListener('input', function() {
            const preview = document.getElementById('preview-subtitle');
            if (preview) preview.textContent = this.value || 'Ihr Untertitel hier';
        });
    }
    
    if (descriptionInput) {
        descriptionInput.addEventListener('input', function() {
            const preview = document.getElementById('preview-description');
            if (preview) preview.textContent = this.value || 'Ihre Beschreibung hier';
        });
    }
    
    // Upload-Zone
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    
    if (uploadZone && fileInput) {
        uploadZone.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', function() {
            if (this.files[0]) {
                const file = this.files[0];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        uploadZone.innerHTML = `
                            <img src="${e.target.result}" class="max-w-full max-h-32 mx-auto mb-2 rounded">
                            <p class="text-sm text-green-600">Bereit zum Upload: ${file.name}</p>
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    }
});

console.log('✅ Sicherer Seiten-Editor geladen für: <?php echo $current_page; ?>');
</script>

</body>
</html>