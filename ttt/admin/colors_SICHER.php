<?php
/**
 * Sichere Colors.php - Keine PHP-Warnungen
 */

session_start();
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Sichere Eingabe-Sanitization
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

$db = getDB();
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['color_theme'])) {
        $color_theme = sanitizeInput($_POST['color_theme']);
        
        // Validiere Hex-Farbe
        if (preg_match('/^#[a-f0-9]{6}$/i', $color_theme)) {
            try {
                $stmt = $db->prepare("UPDATE homepage SET color_theme = ? WHERE id = '1'");
                if ($stmt->execute([$color_theme])) {
                    $message = 'Farbthema wurde erfolgreich aktualisiert!';
                } else {
                    $error = 'Fehler beim Aktualisieren des Farbthemas.';
                }
            } catch (Exception $e) {
                $error = 'Datenbankfehler: ' . $e->getMessage();
            }
        } else {
            $error = 'Ungültige Farbwerte eingegeben.';
        }
    }
}

// Sichere Theme-Konfiguration
$themes = [
    'green' => [
        'name' => 'Grün (Natur)',
        'primary' => '#10b981',
        'secondary' => '#059669',
        'bg' => 'green-100',
        'text' => 'green-800',
        'desc' => 'Natürlich und beruhigend - perfekt für Gartenbau'
    ],
    'blue' => [
        'name' => 'Blau (Vertrauen)', 
        'primary' => '#3b82f6',
        'secondary' => '#1d4ed8',
        'bg' => 'blue-100',
        'text' => 'blue-800',
        'desc' => 'Vertrauensvoll und professionell'
    ],
    'purple' => [
        'name' => 'Lila (Kreativ)',
        'primary' => '#8b5cf6', 
        'secondary' => '#7c3aed',
        'bg' => 'purple-100',
        'text' => 'purple-800',
        'desc' => 'Kreativ und modern'
    ],
    'red' => [
        'name' => 'Rot (Energie)',
        'primary' => '#ef4444',
        'secondary' => '#dc2626', 
        'bg' => 'red-100',
        'text' => 'red-800',
        'desc' => 'Energisch und auffällig'
    ],
    'orange' => [
        'name' => 'Orange (Warm)',
        'primary' => '#f59e0b',
        'secondary' => '#d97706',
        'bg' => 'orange-100', 
        'text' => 'orange-800',
        'desc' => 'Warm und einladend'
    ],
    'gray' => [
        'name' => 'Grau (Elegant)',
        'primary' => '#6b7280',
        'secondary' => '#374151',
        'bg' => 'gray-100',
        'text' => 'gray-800', 
        'desc' => 'Elegant und zeitlos'
    ]
];

// Sichere Theme-Bestimmung
$current_theme = 'green'; // Sicherer Fallback
$primary_color = '#10b981'; // Sicherer Fallback

try {
    $homepage = $db->query("SELECT color_theme FROM homepage WHERE id = '1'")->fetch();
    if ($homepage && isset($homepage['color_theme'])) {
        $primary_color = $homepage['color_theme'];
        
        // Map hex colors to theme names
        $color_to_theme = [
            '#10b981' => 'green',
            '#3b82f6' => 'blue', 
            '#8b5cf6' => 'purple',
            '#ef4444' => 'red', 
            '#f59e0b' => 'orange',
            '#6b7280' => 'gray'
        ];
        
        // Sichere Theme-Zuordnung
        if (isset($color_to_theme[$primary_color])) {
            $current_theme = $color_to_theme[$primary_color];
        }
    }
} catch (Exception $e) {
    $error = 'Fehler beim Laden der aktuellen Farben: ' . $e->getMessage();
}

$pageTitle = 'Farben & Design';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

            <!-- Colors Content -->
            <div class="p-8">
                <div class="max-w-6xl mx-auto">
                    
                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Farben & Design</h1>
                        <p class="text-gray-600">Passen Sie das Farbschema Ihrer Website an</p>
                        
                        <?php if ($current_theme && isset($themes[$current_theme])): ?>
                            <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?php echo $themes[$current_theme]['bg']; ?> text-<?php echo $themes[$current_theme]['text']; ?>">
                                <i class="fas fa-paint-brush mr-2"></i>
                                Aktuell: <?php echo $themes[$current_theme]['name']; ?>
                            </div>
                        <?php else: ?>
                            <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-paint-brush mr-2"></i>
                                Aktuell: Standard (Grün)
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($message): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Theme Selection -->
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-palette mr-3 text-indigo-600"></i>
                            Vordefinierte Farbthemen
                        </h2>
                        
                        <form method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                
                                <?php foreach ($themes as $theme_key => $theme): ?>
                                    <div class="relative">
                                        <input type="radio" 
                                               id="theme_<?php echo $theme_key; ?>" 
                                               name="color_theme" 
                                               value="<?php echo $theme['primary']; ?>" 
                                               class="sr-only" 
                                               <?php echo ($current_theme === $theme_key) ? 'checked' : ''; ?>>
                                        
                                        <label for="theme_<?php echo $theme_key; ?>" 
                                               class="block cursor-pointer rounded-xl border-2 border-gray-200 p-6 hover:border-gray-300 transition-all duration-200 hover:shadow-lg <?php echo ($current_theme === $theme_key) ? 'ring-2 ring-offset-2 ring-' . $theme_key . '-500 border-' . $theme_key . '-300' : ''; ?>">
                                            
                                            <!-- Color Preview -->
                                            <div class="flex space-x-2 mb-4">
                                                <div class="w-8 h-8 rounded-lg shadow-sm" style="background-color: <?php echo $theme['primary']; ?>"></div>
                                                <div class="w-8 h-8 rounded-lg shadow-sm" style="background-color: <?php echo $theme['secondary']; ?>"></div>
                                                <div class="w-8 h-8 rounded-lg shadow-sm bg-<?php echo $theme['bg']; ?>"></div>
                                            </div>
                                            
                                            <!-- Theme Info -->
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo $theme['name']; ?></h3>
                                            <p class="text-sm text-gray-600 mb-3"><?php echo $theme['desc']; ?></p>
                                            
                                            <!-- Colors -->
                                            <div class="space-y-2 text-xs">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Primär:</span>
                                                    <span class="font-mono"><?php echo $theme['primary']; ?></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Sekundär:</span>
                                                    <span class="font-mono"><?php echo $theme['secondary']; ?></span>
                                                </div>
                                            </div>
                                            
                                            <?php if ($current_theme === $theme_key): ?>
                                                <div class="absolute top-3 right-3">
                                                    <div class="bg-<?php echo $theme_key; ?>-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                
                            </div>
                            
                            <div class="flex justify-end pt-6 border-t">
                                <button type="submit" 
                                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 flex items-center shadow-lg hover:shadow-xl">
                                    <i class="fas fa-save mr-2"></i>
                                    Farbthema speichern
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Custom Color Picker -->
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-sliders-h mr-3 text-purple-600"></i>
                            Benutzerdefinierte Farbe
                        </h2>
                        
                        <form method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                
                                <!-- Color Picker -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Wähle eine Farbe
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <input type="color" 
                                               name="color_theme" 
                                               id="customColor"
                                               value="<?php echo htmlspecialchars($primary_color); ?>" 
                                               class="h-16 w-24 rounded-lg border-2 border-gray-300 cursor-pointer">
                                        <div class="flex-1">
                                            <input type="text" 
                                                   id="colorHex"
                                                   value="<?php echo htmlspecialchars($primary_color); ?>"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg font-mono text-sm"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Preview -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Vorschau
                                    </label>
                                    <div id="colorPreview" class="p-6 rounded-lg border-2 border-gray-200" style="background-color: <?php echo $primary_color; ?>20;">
                                        <div class="bg-white p-4 rounded-lg shadow-sm">
                                            <h3 class="text-lg font-semibold mb-2" style="color: <?php echo $primary_color; ?>;">Beispiel-Überschrift</h3>
                                            <p class="text-gray-600 mb-3">Dies ist ein Beispieltext um die Farbwirkung zu demonstrieren.</p>
                                            <button class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: <?php echo $primary_color; ?>;">
                                                Beispiel-Button
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="flex justify-end pt-6 border-t">
                                <button type="submit" 
                                        class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 flex items-center shadow-lg hover:shadow-xl">
                                    <i class="fas fa-palette mr-2"></i>
                                    Benutzerdefinierte Farbe speichern
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

<script>
// Live color preview
document.getElementById('customColor').addEventListener('input', function() {
    const color = this.value;
    document.getElementById('colorHex').value = color;
    
    const preview = document.getElementById('colorPreview');
    const heading = preview.querySelector('h3');
    const button = preview.querySelector('button');
    
    preview.style.backgroundColor = color + '20';
    heading.style.color = color;
    button.style.backgroundColor = color;
});

// Copy color to clipboard
document.getElementById('colorHex').addEventListener('click', function() {
    this.select();
    document.execCommand('copy');
    
    // Visual feedback
    const original = this.value;
    this.value = 'Kopiert!';
    setTimeout(() => {
        this.value = original;
    }, 1000);
});

console.log('✅ Sichere Colors.php geladen ohne PHP-Warnungen');
</script>

<?php include 'includes/footer.php'; ?>