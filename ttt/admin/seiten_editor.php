<?php
/**
 * Vollständiger Seiten-Editor - ALLES bearbeitbar für jede Seite
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

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['upload_file'])) {
    $category = $_POST['upload_category'] ?? 'general';
    $file = $_FILES['upload_file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($file['type'], $allowedTypes)) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $category . '_' . time() . '.' . $extension;
            $targetDir = "../uploads/$category/";
            $targetPath = $targetDir . $filename;
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $message = "Bild erfolgreich hochgeladen: /uploads/$category/$filename";
            } else {
                $error = 'Fehler beim Hochladen der Datei.';
            }
        } else {
            $error = 'Nur Bild-Dateien sind erlaubt (JPEG, PNG, GIF, WebP).';
        }
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_FILES['upload_file'])) {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'update_page_content':
                // Dynamisches Update je nach Seite
                $page_key = str_replace('.php', '', $current_page);
                $stmt = $db->prepare("UPDATE homepage SET 
                    {$page_key}_title = ?, 
                    {$page_key}_subtitle = ?, 
                    {$page_key}_description = ?,
                    {$page_key}_background_color = ?,
                    {$page_key}_text_color = ?,
                    {$page_key}_button_color = ?
                    WHERE id = '1'");
                $stmt->execute([
                    $_POST['page_title'],
                    $_POST['page_subtitle'],
                    $_POST['page_description'],
                    $_POST['background_color'],
                    $_POST['text_color'],
                    $_POST['button_color']
                ]);
                $message = 'Seite erfolgreich aktualisiert!';
                break;
                
            case 'update_colors':
                $stmt = $db->prepare("UPDATE homepage SET color_theme = ? WHERE id = '1'");
                $stmt->execute([$_POST['color_theme']]);
                $message = 'Farben erfolgreich aktualisiert!';
                break;
                
            case 'update_fonts':
                $stmt = $db->prepare("UPDATE homepage SET 
                    font_family = ?, 
                    font_size = ?, 
                    heading_color = ?, 
                    text_color = ? 
                    WHERE id = '1'");
                $stmt->execute([
                    $_POST['font_family'],
                    $_POST['font_size'],
                    $_POST['heading_color'],
                    $_POST['text_color']
                ]);
                $message = 'Schriftarten erfolgreich aktualisiert!';
                break;
        }
    } catch (Exception $e) {
        $error = 'Fehler beim Speichern: ' . $e->getMessage();
    }
}

// Get current data
try {
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
    $services = $db->query("SELECT * FROM services ORDER BY `order`, title")->fetchAll();
    $team = $db->query("SELECT * FROM team ORDER BY `order`, name")->fetchAll();
} catch (Exception $e) {
    $homepage = [];
    $services = [];
    $team = [];
}

// Seiten-spezifische Inhalte laden
$page_content = [];
if (file_exists('../' . $current_page)) {
    $page_source = file_get_contents('../' . $current_page);
    // Extrahiere Texte aus der Seite
    preg_match_all('/<h1[^>]*>(.*?)<\/h1>/i', $page_source, $h1_matches);
    preg_match_all('/<h2[^>]*>(.*?)<\/h2>/i', $page_source, $h2_matches);
    preg_match_all('/<p[^>]*>(.*?)<\/p>/i', $page_source, $p_matches);
    
    $page_content['h1'] = $h1_matches[1] ?? [];
    $page_content['h2'] = $h2_matches[1] ?? [];
    $page_content['p'] = $p_matches[1] ?? [];
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
    <title><?php echo $config['name']; ?> Editor - Vollständig</title>
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
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .form-textarea {
            min-height: 80px;
            resize: vertical;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .preview-section {
            background: #f9fafb;
            border-radius: 8px;
            padding: 2rem;
            border: 1px solid #e5e7eb;
            margin: 1rem 0;
        }
        
        .upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-zone:hover {
            border-color: #10b981;
            background: #f0fdf4;
        }
        
        .color-picker-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .color-preview {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }
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
            <p class="text-sm opacity-90">Vollständiger Editor</p>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="seiten_manager.php" class="block p-2 rounded hover:bg-gray-100">← Alle Seiten</a></li>
                <li><a href="../<?php echo $current_page; ?>" target="_blank" class="block p-2 rounded hover:bg-gray-100">👀 Vorschau</a></li>
                <li><hr class="my-2"></li>
                <li><a href="#texte" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('texte')">📝 Texte</a></li>
                <li><a href="#farben" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('farben')">🎨 Farben</a></li>
                <li><a href="#schriften" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('schriften')">🔤 Schriften</a></li>
                <li><a href="#bilder" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('bilder')">🖼️ Bilder</a></li>
                <li><a href="#layout" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('layout')">📐 Layout</a></li>
                <li><a href="#vorschau" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('vorschau')">👁️ Live-Vorschau</a></li>
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
                                
                                <!-- Linke Spalte: Eingabefelder -->
                                <div>
                                    <h3 class="text-lg font-bold mb-4">Seiten-Inhalte</h3>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Haupttitel der Seite</label>
                                        <input type="text" name="page_title" id="page_title" value="<?php echo htmlspecialchars(getArrayValue($homepage, 'hero_title')); ?>" class="form-input">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Untertitel</label>
                                        <textarea name="page_subtitle" id="page_subtitle" class="form-input form-textarea"><?php echo htmlspecialchars(getArrayValue($homepage, 'hero_subtitle')); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Beschreibung</label>
                                        <textarea name="page_description" id="page_description" class="form-input" style="min-height: 120px;"><?php echo htmlspecialchars(getArrayValue($homepage, 'about_description', 'Beschreibung der Seite')); ?></textarea>
                                    </div>
                                    
                                    <!-- Gefundene Texte aus der Seite -->
                                    <div class="form-group">
                                        <label class="form-label">Gefundene Überschriften auf der Seite</label>
                                        <div class="space-y-2">
                                            <?php foreach (array_slice($page_content['h1'], 0, 3) as $i => $h1): ?>
                                                <input type="text" name="h1_<?php echo $i; ?>" value="<?php echo htmlspecialchars(strip_tags($h1)); ?>" class="form-input text-sm" placeholder="Überschrift <?php echo $i + 1; ?>">
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Rechte Spalte: Live-Vorschau -->
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
                            <input type="hidden" name="action" value="update_page_content">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                
                                <div class="form-group">
                                    <label class="form-label">Hintergrundfarbe</label>
                                    <div class="color-picker-container">
                                        <input type="color" name="background_color" id="bg_color" value="<?php echo getArrayValue($homepage, 'color_theme', '#ffffff'); ?>" class="form-input h-12 w-20">
                                        <div class="color-preview" id="bg_preview" style="background-color: <?php echo getArrayValue($homepage, 'color_theme', '#ffffff'); ?>"></div>
                                        <input type="text" id="bg_hex" value="<?php echo getArrayValue($homepage, 'color_theme', '#ffffff'); ?>" class="form-input flex-1" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Textfarbe</label>
                                    <div class="color-picker-container">
                                        <input type="color" name="text_color" id="text_color" value="#333333" class="form-input h-12 w-20">
                                        <div class="color-preview" id="text_preview" style="background-color: #333333"></div>
                                        <input type="text" id="text_hex" value="#333333" class="form-input flex-1" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Button-Farbe</label>
                                    <div class="color-picker-container">
                                        <input type="color" name="button_color" id="button_color" value="#10b981" class="form-input h-12 w-20">
                                        <div class="color-preview" id="button_preview" style="background-color: #10b981"></div>
                                        <input type="text" id="button_hex" value="#10b981" class="form-input flex-1" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Akzentfarbe</label>
                                    <div class="color-picker-container">
                                        <input type="color" name="accent_color" id="accent_color" value="#059669" class="form-input h-12 w-20">
                                        <div class="color-preview" id="accent_preview" style="background-color: #059669"></div>
                                        <input type="text" id="accent_hex" value="#059669" class="form-input flex-1" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Link-Farbe</label>
                                    <div class="color-picker-container">
                                        <input type="color" name="link_color" id="link_color" value="#3b82f6" class="form-input h-12 w-20">
                                        <div class="color-preview" id="link_preview" style="background-color: #3b82f6"></div>
                                        <input type="text" id="link_hex" value="#3b82f6" class="form-input flex-1" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Border-Farbe</label>
                                    <div class="color-picker-container">
                                        <input type="color" name="border_color" id="border_color" value="#e5e7eb" class="form-input h-12 w-20">
                                        <div class="color-preview" id="border_preview" style="background-color: #e5e7eb"></div>
                                        <input type="text" id="border_hex" value="#e5e7eb" class="form-input flex-1" readonly>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <!-- Farb-Vorschau -->
                            <div class="form-group mt-8">
                                <label class="form-label">Farb-Vorschau</label>
                                <div class="preview-section" id="color_preview_section">
                                    <div class="p-6 rounded-lg" style="background-color: #ffffff; color: #333333; border: 1px solid #e5e7eb;">
                                        <h3 class="text-2xl font-bold mb-4">Beispiel-Überschrift</h3>
                                        <p class="mb-4">Dies ist ein Beispiel-Text um zu sehen, wie die Farben wirken.</p>
                                        <a href="#" class="inline-block mr-4" style="color: #3b82f6;">Beispiel-Link</a>
                                        <button class="px-4 py-2 rounded" style="background-color: #10b981; color: white;">Beispiel-Button</button>
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

            <!-- Schriften bearbeiten -->
            <div id="schriften-section" class="section hidden">
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-font mr-2"></i>
                        Schriftarten und Textfarben
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_fonts">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                
                                <div>
                                    <h3 class="text-lg font-bold mb-4">Schriftart-Einstellungen</h3>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Schriftart wählen</label>
                                        <select name="font_family" id="font_family" class="form-input">
                                            <option value="Arial, sans-serif">Arial</option>
                                            <option value="Georgia, serif">Georgia</option>
                                            <option value="Helvetica, sans-serif">Helvetica</option>
                                            <option value="'Times New Roman', serif">Times New Roman</option>
                                            <option value="Verdana, sans-serif">Verdana</option>
                                            <option value="'Open Sans', sans-serif">Open Sans (Google Font)</option>
                                            <option value="'Roboto', sans-serif">Roboto (Google Font)</option>
                                            <option value="'Montserrat', sans-serif">Montserrat (Google Font)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Basis-Schriftgröße</label>
                                        <select name="font_size" id="font_size" class="form-input">
                                            <option value="14px">14px (Klein)</option>
                                            <option value="16px" selected>16px (Standard)</option>
                                            <option value="18px">18px (Groß)</option>
                                            <option value="20px">20px (Sehr groß)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Überschrift-Farbe</label>
                                        <div class="color-picker-container">
                                            <input type="color" name="heading_color" id="heading_color" value="#1f2937" class="form-input h-12 w-20">
                                            <div class="color-preview" style="background-color: #1f2937"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Text-Farbe</label>
                                        <div class="color-picker-container">
                                            <input type="color" name="text_color" id="body_text_color" value="#374151" class="form-input h-12 w-20">
                                            <div class="color-preview" style="background-color: #374151"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-bold mb-4">Schrift-Vorschau</h3>
                                    <div class="preview-section" id="font_preview">
                                        <h1 style="font-family: Arial, sans-serif; font-size: 2rem; color: #1f2937; margin-bottom: 1rem;">Große Überschrift (H1)</h1>
                                        <h2 style="font-family: Arial, sans-serif; font-size: 1.5rem; color: #1f2937; margin-bottom: 1rem;">Mittlere Überschrift (H2)</h2>
                                        <h3 style="font-family: Arial, sans-serif; font-size: 1.25rem; color: #1f2937; margin-bottom: 1rem;">Kleine Überschrift (H3)</h3>
                                        <p style="font-family: Arial, sans-serif; font-size: 16px; color: #374151; line-height: 1.6;">
                                            Dies ist ein Beispieltext, um zu zeigen, wie die gewählte Schriftart und -farbe auf der Website aussehen wird. 
                                            Der Text sollte gut lesbar sein und zur Gesamtgestaltung der Seite passen.
                                        </p>
                                        <p style="font-family: Arial, sans-serif; font-size: 14px; color: #6b7280; margin-top: 1rem;">
                                            Kleinerer Text (z.B. für Hinweise oder Footer)
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <button type="submit" class="btn-save">
                                <i class="fas fa-font mr-2"></i>
                                Schriften speichern
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
                        Bilder für <?php echo $config['name']; ?>
                    </div>
                    <div class="p-6">
                        
                        <!-- Upload-Bereich -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-4">Neues Bild hochladen</h3>
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
                                        <label class="form-label">Alt-Text</label>
                                        <input type="text" name="alt_text" class="form-input" placeholder="Beschreibung für SEO">
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-upload mr-2"></i>
                                    Hochladen
                                </button>
                            </form>
                        </div>
                        
                        <!-- Aktuelle Bilder -->
                        <div>
                            <h3 class="text-lg font-bold mb-4">Aktuelle Bilder verwalten</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                
                                <!-- Hero-Bild -->
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold mb-3">Hero-Hintergrundbild</h4>
                                    <?php if (getArrayValue($homepage, 'hero_background_image')): ?>
                                        <img src="<?php echo htmlspecialchars(getArrayValue($homepage, 'hero_background_image')); ?>" class="w-full h-32 object-cover rounded mb-3">
                                        <p class="text-sm text-green-600">✓ Zugewiesen</p>
                                    <?php else: ?>
                                        <div class="w-full h-32 bg-gray-100 flex items-center justify-center rounded mb-3">
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-sm text-gray-500">Kein Bild zugewiesen</p>
                                    <?php endif; ?>
                                    <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm mt-2 w-full">Ändern</button>
                                </div>
                                
                                <!-- Seiten-spezifisches Bild -->
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold mb-3"><?php echo $config['name']; ?>-Bild</h4>
                                    <div class="w-full h-32 bg-gray-100 flex items-center justify-center rounded mb-3">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">Kein Bild zugewiesen</p>
                                    <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm mt-2 w-full">Zuweisen</button>
                                </div>
                                
                                <!-- Icon/Logo -->
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold mb-3">Seiten-Icon</h4>
                                    <div class="w-full h-32 bg-gray-100 flex items-center justify-center rounded mb-3">
                                        <i class="<?php echo $config['icon']; ?> text-<?php echo $config['color']; ?>-600 text-4xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">Aktuelles Icon</p>
                                    <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm mt-2 w-full">Icon ändern</button>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Layout bearbeiten -->
            <div id="layout-section" class="section hidden">
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-th-large mr-2"></i>
                        Layout-Einstellungen
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div>
                                <h3 class="text-lg font-bold mb-4">Layout-Optionen</h3>
                                
                                <div class="form-group">
                                    <label class="form-label">Seitenbreite</label>
                                    <select class="form-input">
                                        <option value="container">Standard (1200px)</option>
                                        <option value="container-fluid">Vollbreite</option>
                                        <option value="container-narrow">Schmal (800px)</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Abstände</label>
                                    <select class="form-input">
                                        <option value="normal">Normal</option>
                                        <option value="tight">Eng</option>
                                        <option value="loose">Weit</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Header-Stil</label>
                                    <select class="form-input">
                                        <option value="standard">Standard</option>
                                        <option value="minimal">Minimal</option>
                                        <option value="bold">Fett</option>
                                    </select>
                                </div>
                                
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-bold mb-4">Layout-Vorschau</h3>
                                <div class="preview-section">
                                    <div class="border-2 border-dashed border-gray-300 p-4 mb-4">
                                        <span class="text-sm text-gray-500">Header-Bereich</span>
                                    </div>
                                    <div class="border-2 border-dashed border-gray-300 p-8 mb-4">
                                        <span class="text-sm text-gray-500">Haupt-Inhaltsbereich</span>
                                    </div>
                                    <div class="border-2 border-dashed border-gray-300 p-4">
                                        <span class="text-sm text-gray-500">Footer-Bereich</span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live-Vorschau -->
            <div id="vorschau-section" class="section hidden">
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-eye mr-2"></i>
                        Live-Vorschau von <?php echo $config['name']; ?>
                    </div>
                    <div class="p-6">
                        <div class="mb-4 flex space-x-4">
                            <a href="../<?php echo $current_page; ?>" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                In neuem Tab öffnen
                            </a>
                            <button onclick="refreshPreview()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                <i class="fas fa-sync mr-2"></i>
                                Aktualisieren
                            </button>
                        </div>
                        
                        <div class="border rounded-lg overflow-hidden" style="height: 600px;">
                            <iframe src="../<?php echo $current_page; ?>" class="w-full h-full" id="preview-iframe"></iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Sektion wechseln
function showSection(sectionName) {
    document.querySelectorAll('.section').forEach(section => {
        section.classList.add('hidden');
    });
    
    const targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) {
        targetSection.classList.remove('hidden');
    }
    
    // Update active nav
    document.querySelectorAll('nav a').forEach(link => {
        link.classList.remove('bg-gray-100');
    });
    
    const activeLink = document.querySelector(`nav a[href="#${sectionName}"]`);
    if (activeLink) {
        activeLink.classList.add('bg-gray-100');
    }
}

// Live-Updates
document.addEventListener('DOMContentLoaded', function() {
    showSection('texte');
    
    // Text-Vorschau Updates
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
    
    // Farb-Updates
    setupColorPickers();
    
    // Schrift-Updates
    setupFontPreview();
    
    // Upload-Zone
    setupFileUpload();
});

function setupColorPickers() {
    const colors = ['bg', 'text', 'button', 'accent', 'link', 'border'];
    
    colors.forEach(color => {
        const colorInput = document.getElementById(color + '_color');
        const hexInput = document.getElementById(color + '_hex');
        const preview = document.getElementById(color + '_preview');
        
        if (colorInput && hexInput && preview) {
            colorInput.addEventListener('input', function() {
                const value = this.value;
                hexInput.value = value;
                preview.style.backgroundColor = value;
                updateColorPreview();
            });
        }
    });
}

function updateColorPreview() {
    const previewSection = document.getElementById('color_preview_section');
    if (!previewSection) return;
    
    const bgColor = document.getElementById('bg_color')?.value || '#ffffff';
    const textColor = document.getElementById('text_color')?.value || '#333333';
    const buttonColor = document.getElementById('button_color')?.value || '#10b981';
    const linkColor = document.getElementById('link_color')?.value || '#3b82f6';
    const borderColor = document.getElementById('border_color')?.value || '#e5e7eb';
    
    const previewDiv = previewSection.querySelector('div');
    if (previewDiv) {
        previewDiv.style.backgroundColor = bgColor;
        previewDiv.style.color = textColor;
        previewDiv.style.borderColor = borderColor;
        
        const link = previewDiv.querySelector('a');
        if (link) link.style.color = linkColor;
        
        const button = previewDiv.querySelector('button');
        if (button) button.style.backgroundColor = buttonColor;
    }
}

function setupFontPreview() {
    const fontFamily = document.getElementById('font_family');
    const fontSize = document.getElementById('font_size');
    const headingColor = document.getElementById('heading_color');
    const bodyTextColor = document.getElementById('body_text_color');
    
    function updateFontPreview() {
        const preview = document.getElementById('font_preview');
        if (!preview) return;
        
        const family = fontFamily?.value || 'Arial, sans-serif';
        const size = fontSize?.value || '16px';
        const hColor = headingColor?.value || '#1f2937';
        const tColor = bodyTextColor?.value || '#374151';
        
        preview.querySelectorAll('h1, h2, h3').forEach(h => {
            h.style.fontFamily = family;
            h.style.color = hColor;
        });
        
        preview.querySelectorAll('p').forEach(p => {
            p.style.fontFamily = family;
            p.style.fontSize = size;
            p.style.color = tColor;
        });
    }
    
    [fontFamily, fontSize, headingColor, bodyTextColor].forEach(element => {
        if (element) {
            element.addEventListener('change', updateFontPreview);
            element.addEventListener('input', updateFontPreview);
        }
    });
}

function setupFileUpload() {
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    
    if (uploadZone && fileInput) {
        uploadZone.addEventListener('click', () => fileInput.click());
        
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.style.borderColor = '#10b981';
            uploadZone.style.backgroundColor = '#f0fdf4';
        });
        
        uploadZone.addEventListener('dragleave', () => {
            uploadZone.style.borderColor = '#d1d5db';
            uploadZone.style.backgroundColor = '';
        });
        
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.style.borderColor = '#d1d5db';
            uploadZone.style.backgroundColor = '';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                showFilePreview(files[0]);
            }
        });
        
        fileInput.addEventListener('change', function() {
            if (this.files[0]) {
                showFilePreview(this.files[0]);
            }
        });
    }
}

function showFilePreview(file) {
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const uploadZone = document.getElementById('uploadZone');
            if (uploadZone) {
                uploadZone.innerHTML = `
                    <img src="${e.target.result}" class="max-w-full max-h-32 mx-auto mb-2 rounded">
                    <p class="text-sm text-green-600">Bereit zum Upload: ${file.name}</p>
                `;
            }
        };
        reader.readAsDataURL(file);
    }
}

function refreshPreview() {
    const iframe = document.getElementById('preview-iframe');
    if (iframe) {
        iframe.src = iframe.src;
    }
}

console.log('🚀 Vollständiger Seiten-Editor geladen für: <?php echo $current_page; ?>');
</script>

</body>
</html>