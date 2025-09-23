<?php
/**
 * Seiten-Manager - Alle Seiten verwalten
 */

session_start();
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Verfügbare Seiten definieren
$available_pages = [
    'index.php' => [
        'name' => 'Homepage',
        'description' => 'Startseite mit Hero-Bereich, Services und Team',
        'icon' => 'fas fa-home',
        'color' => 'green'
    ],
    'about.php' => [
        'name' => 'Über uns',
        'description' => 'Firmengeschichte und Unternehmensinfos',
        'icon' => 'fas fa-info-circle',
        'color' => 'blue'
    ],
    'services.php' => [
        'name' => 'Leistungen',
        'description' => 'Alle angebotenen Services und Dienstleistungen',
        'icon' => 'fas fa-tools',
        'color' => 'purple'
    ],
    'team.php' => [
        'name' => 'Team',
        'description' => 'Mitarbeiter und Teamvorstellung',
        'icon' => 'fas fa-users',
        'color' => 'orange'
    ],
    'contact.php' => [
        'name' => 'Kontakt',
        'description' => 'Kontaktformular und Firmendaten',
        'icon' => 'fas fa-envelope',
        'color' => 'red'
    ],
    'news.php' => [
        'name' => 'Aktuelles',
        'description' => 'News und aktuelle Informationen',
        'icon' => 'fas fa-newspaper',
        'color' => 'indigo'
    ],
    'careers.php' => [
        'name' => 'Karriere',
        'description' => 'Stellenangebote und Bewerbungen',
        'icon' => 'fas fa-briefcase',
        'color' => 'teal'
    ]
];

// Prüfen welche Seiten existieren
$existing_pages = [];
foreach ($available_pages as $filename => $info) {
    if (file_exists('../' . $filename)) {
        $existing_pages[$filename] = $info;
        $existing_pages[$filename]['exists'] = true;
        $existing_pages[$filename]['size'] = filesize('../' . $filename);
        $existing_pages[$filename]['modified'] = filemtime('../' . $filename);
    }
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seiten-Manager - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .page-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .page-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        
        .color-green { @apply bg-green-100 text-green-600; }
        .color-blue { @apply bg-blue-100 text-blue-600; }
        .color-purple { @apply bg-purple-100 text-purple-600; }
        .color-orange { @apply bg-orange-100 text-orange-600; }
        .color-red { @apply bg-red-100 text-red-600; }
        .color-indigo { @apply bg-indigo-100 text-indigo-600; }
        .color-teal { @apply bg-teal-100 text-teal-600; }
        
        .btn-edit {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        
        .btn-edit:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
            text-decoration: none;
            color: white;
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .status-online { @apply bg-green-500; }
        .status-offline { @apply bg-gray-400; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b bg-gradient-to-r from-green-600 to-green-700 text-white">
            <h1 class="text-xl font-bold flex items-center">
                <i class="fas fa-file-alt mr-2"></i>
                Seiten-Manager
            </h1>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="index.php" class="block p-2 rounded hover:bg-gray-100">← Dashboard</a></li>
                <li><a href="text_editor.php" class="block p-2 rounded hover:bg-gray-100">📝 Text Editor</a></li>
                <li><a href="simple_upload.php" class="block p-2 rounded hover:bg-gray-100">📤 Upload</a></li>
                <li><a href="colors.php" class="block p-2 rounded hover:bg-gray-100">🎨 Farben</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto">
        
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Seiten-Manager</h1>
                <p class="text-gray-600">Wählen Sie eine Seite aus, die Sie bearbeiten möchten</p>
            </div>

            <!-- Seiten Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <?php foreach ($existing_pages as $filename => $page): ?>
                    <div class="page-card" onclick="editPage('<?php echo $filename; ?>')">
                        
                        <!-- Page Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-16 h-16 color-<?php echo $page['color']; ?> rounded-full flex items-center justify-center">
                                <i class="<?php echo $page['icon']; ?> text-2xl"></i>
                            </div>
                            <div class="text-right">
                                <div class="status-indicator status-online" title="Seite verfügbar"></div>
                            </div>
                        </div>
                        
                        <!-- Page Info -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo $page['name']; ?></h3>
                        <p class="text-gray-600 text-sm mb-4"><?php echo $page['description']; ?></p>
                        
                        <!-- Page Stats -->
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>Größe: <?php echo number_format($page['size'] / 1024, 1); ?> KB</span>
                            <span>Bearbeitet: <?php echo date('d.m.Y', $page['modified']); ?></span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <button onclick="event.stopPropagation(); editPage('<?php echo $filename; ?>')" 
                                    class="btn-edit flex-1 justify-center">
                                <i class="fas fa-edit mr-2"></i>
                                Bearbeiten
                            </button>
                            <a href="../<?php echo $filename; ?>" 
                               target="_blank" 
                               onclick="event.stopPropagation()"
                               class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        
                    </div>
                <?php endforeach; ?>
                
            </div>

            <!-- Hilfe-Sektion -->
            <div class="mt-12 bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                    Wie funktioniert der Seiten-Manager?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <h3 class="font-semibold mb-2">📄 Seite bearbeiten</h3>
                        <p class="text-gray-600 mb-4">Klicken Sie auf "Bearbeiten" um den spezifischen Editor für diese Seite zu öffnen.</p>
                        
                        <h3 class="font-semibold mb-2">👀 Vorschau anzeigen</h3>
                        <p class="text-gray-600">Mit dem Extern-Link können Sie die Seite in einem neuen Tab öffnen.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">🎨 Was kann bearbeitet werden?</h3>
                        <ul class="text-gray-600 space-y-1">
                            <li>• Texte und Überschriften</li>
                            <li>• Farben und Styling</li>
                            <li>• Bilder und Medien</li>
                            <li>• Layout-Einstellungen</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function editPage(filename) {
    // Lade den spezifischen Editor für die gewählte Seite
    window.location.href = `seiten_editor.php?page=${filename}`;
}

// Smooth hover effects
document.querySelectorAll('.page-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

console.log('🚀 Seiten-Manager geladen - <?php echo count($existing_pages); ?> Seiten gefunden');
</script>

</body>
</html>