<?php
/**
 * Spezifischer Seiten-Editor für einzelne Seiten
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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'update_homepage_content':
                $stmt = $db->prepare("UPDATE homepage SET hero_title = ?, hero_subtitle = ?, phone_number = ?, email = ? WHERE id = '1'");
                $stmt->execute([
                    $_POST['hero_title'],
                    $_POST['hero_subtitle'],
                    $_POST['phone_number'],
                    $_POST['email']
                ]);
                $message = 'Homepage erfolgreich aktualisiert!';
                break;
                
            case 'update_colors':
                $stmt = $db->prepare("UPDATE homepage SET color_theme = ? WHERE id = '1'");
                $stmt->execute([$_POST['color_theme']]);
                $message = 'Farben erfolgreich aktualisiert!';
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

// Seiten-Konfiguration
$page_config = [
    'index.php' => [
        'name' => 'Homepage',
        'sections' => ['hero', 'services', 'team', 'cta', 'colors'],
        'icon' => 'fas fa-home',
        'color' => 'green'
    ],
    'about.php' => [
        'name' => 'Über uns',
        'sections' => ['content', 'team', 'history', 'colors'],
        'icon' => 'fas fa-info-circle', 
        'color' => 'blue'
    ],
    'services.php' => [
        'name' => 'Leistungen',
        'sections' => ['services', 'pricing', 'colors'],
        'icon' => 'fas fa-tools',
        'color' => 'purple'
    ],
    'contact.php' => [
        'name' => 'Kontakt',
        'sections' => ['contact', 'address', 'colors'],
        'icon' => 'fas fa-envelope',
        'color' => 'red'
    ]
];

$config = $page_config[$current_page] ?? $page_config['index.php'];

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['name']; ?> Editor - Admin</title>
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
            background: linear-gradient(135deg, #<?php echo $config['color'] === 'green' ? '10b981' : '3b82f6'; ?> 0%, #<?php echo $config['color'] === 'green' ? '059669' : '1d4ed8'; ?> 100%);
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
            padding: 1rem;
            border: 1px solid #e5e7eb;
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
                <?php echo $config['name']; ?> Editor
            </h1>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="seiten_manager.php" class="block p-2 rounded hover:bg-gray-100">← Alle Seiten</a></li>
                <li><a href="../<?php echo $current_page; ?>" target="_blank" class="block p-2 rounded hover:bg-gray-100">👀 Vorschau</a></li>
                <li><hr class="my-2"></li>
                <?php foreach ($config['sections'] as $section): ?>
                    <li><a href="#<?php echo $section; ?>" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('<?php echo $section; ?>')">
                        <?php 
                        $section_names = [
                            'hero' => '🎯 Hero-Bereich',
                            'services' => '🛠️ Leistungen',
                            'team' => '👥 Team',
                            'cta' => '📞 Handlungsaufruf',
                            'colors' => '🎨 Farben',
                            'content' => '📄 Inhalte',
                            'contact' => '📧 Kontakt',
                            'address' => '📍 Adresse'
                        ];
                        echo $section_names[$section] ?? $section;
                        ?>
                    </a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-4xl mx-auto">
            
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

            <!-- Hero Section (nur für Homepage) -->
            <?php if ($current_page === 'index.php'): ?>
                <div id="hero-section" class="section">
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-star mr-2"></i>
                            Hero-Bereich bearbeiten
                        </div>
                        <div class="p-6">
                            <form method="POST">
                                <input type="hidden" name="action" value="update_homepage_content">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label class="form-label">Haupttitel</label>
                                        <input type="text" name="hero_title" value="<?php echo htmlspecialchars(getArrayValue($homepage, 'hero_title')); ?>" class="form-input">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Untertitel</label>
                                        <textarea name="hero_subtitle" class="form-input form-textarea"><?php echo htmlspecialchars(getArrayValue($homepage, 'hero_subtitle')); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Telefonnummer</label>
                                        <input type="text" name="phone_number" value="<?php echo htmlspecialchars(getArrayValue($homepage, 'phone_number')); ?>" class="form-input">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">E-Mail</label>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars(getArrayValue($homepage, 'email')); ?>" class="form-input">
                                    </div>
                                </div>
                                
                                <!-- Live Preview -->
                                <div class="form-group">
                                    <label class="form-label">Vorschau</label>
                                    <div class="preview-section">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-2" id="preview-title">
                                            <?php echo htmlspecialchars(getArrayValue($homepage, 'hero_title', 'Ihr Titel hier')); ?>
                                        </h3>
                                        <p class="text-gray-600" id="preview-subtitle">
                                            <?php echo htmlspecialchars(getArrayValue($homepage, 'hero_subtitle', 'Ihr Untertitel hier')); ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save mr-2"></i>
                                    Hero-Bereich speichern
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Colors Section (für alle Seiten) -->
            <div id="colors-section" class="section <?php echo $current_page !== 'index.php' ? '' : 'hidden'; ?>">
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-palette mr-2"></i>
                        Farben anpassen
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_colors">
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="form-group">
                                    <label class="form-label">Primärfarbe</label>
                                    <input type="color" name="color_theme" value="<?php echo getArrayValue($homepage, 'color_theme', '#10b981'); ?>" class="form-input h-12">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Vorschau Primärfarbe</label>
                                    <div class="h-12 rounded border" style="background-color: <?php echo getArrayValue($homepage, 'color_theme', '#10b981'); ?>"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Farbcode</label>
                                    <input type="text" value="<?php echo getArrayValue($homepage, 'color_theme', '#10b981'); ?>" class="form-input" readonly>
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

            <!-- Services Section (nur für Services und Homepage) -->
            <?php if (in_array($current_page, ['index.php', 'services.php'])): ?>
                <div id="services-section" class="section hidden">
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-tools mr-2"></i>
                            Leistungen verwalten
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Services werden über den <a href="services.php" class="text-blue-600 hover:underline">Services-Manager</a> bearbeitet.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach (array_slice($services, 0, 4) as $service): ?>
                                    <div class="border rounded-lg p-4">
                                        <h4 class="font-semibold"><?php echo htmlspecialchars($service['title']); ?></h4>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars(substr($service['description'], 0, 80)); ?>...</p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
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

// Live preview updates
document.addEventListener('DOMContentLoaded', function() {
    // Show first section by default
    <?php if ($current_page === 'index.php'): ?>
        showSection('hero');
    <?php else: ?>
        showSection('colors');
    <?php endif; ?>
    
    // Live preview for hero section
    const titleInput = document.querySelector('input[name="hero_title"]');
    const subtitleInput = document.querySelector('textarea[name="hero_subtitle"]');
    const previewTitle = document.getElementById('preview-title');
    const previewSubtitle = document.getElementById('preview-subtitle');
    
    if (titleInput && previewTitle) {
        titleInput.addEventListener('input', function() {
            previewTitle.textContent = this.value || 'Ihr Titel hier';
        });
    }
    
    if (subtitleInput && previewSubtitle) {
        subtitleInput.addEventListener('input', function() {
            previewSubtitle.textContent = this.value || 'Ihr Untertitel hier';
        });
    }
});

console.log('🚀 Seiten-Editor geladen für: <?php echo $current_page; ?>');
</script>

</body>
</html>