<?php
/**
 * Colors & Design Manager - Mit verbesserter visueller Anzeige
 */

require_once '../config/auth.php';
require_once 'includes/functions.php';
requireAuth();

require_once '../config/database.php';

$db = getDB();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['color_theme'])) {
        $color_theme = sanitizeInput($_POST['color_theme']);
        
        $stmt = $db->prepare("UPDATE homepage SET color_theme = ? WHERE id = '1'");
        if ($stmt->execute([$color_theme])) {
            $message = 'Farbthema wurde erfolgreich aktualisiert!';
        } else {
            $message = 'Fehler beim Aktualisieren des Farbthemas.';
        }
    }
}

// Get current color theme with safe mapping
try {
    $homepage = $db->query("SELECT color_theme FROM homepage WHERE id = '1'")->fetch();
    $primary_color = $homepage['color_theme'] ?? '#10b981';
    
    // Map hex colors to theme names
    $color_to_theme = [
        '#10b981' => 'green',
        '#3b82f6' => 'blue', 
        '#8b5cf6' => 'purple',
        '#ef4444' => 'red',
        '#f59e0b' => 'orange',
        '#6b7280' => 'gray'
    ];
    
    // Safe theme mapping with fallback
    $current_theme = $color_to_theme[$primary_color] ?? 'green';
    
} catch (Exception $e) {
    $current_theme = 'green'; // Safe fallback
    $primary_color = '#10b981';
}

$pageTitle = 'Farben & Design';
include 'includes/header.php';
include 'includes/sidebar.php';

// Farbthemen definieren
$themes = [
    'green' => ['name' => '🌿 Grün', 'desc' => 'Natürlich & frisch', 'from' => 'green-500', 'to' => 'green-600', 'border' => 'green-500', 'bg' => 'green-50', 'text' => 'green-700'],
    'blue' => ['name' => '💙 Blau', 'desc' => 'Professional & vertrauensvoll', 'from' => 'blue-500', 'to' => 'blue-600', 'border' => 'blue-500', 'bg' => 'blue-50', 'text' => 'blue-700'],
    'purple' => ['name' => '💜 Lila', 'desc' => 'Elegant & modern', 'from' => 'purple-500', 'to' => 'purple-600', 'border' => 'purple-500', 'bg' => 'purple-50', 'text' => 'purple-700'],
    'red' => ['name' => '❤️ Rot', 'desc' => 'Kraftvoll & aufmerksamkeitsstark', 'from' => 'red-500', 'to' => 'red-600', 'border' => 'red-500', 'bg' => 'red-50', 'text' => 'red-700'],
    'orange' => ['name' => '🧡 Orange', 'desc' => 'Energiegeladen & freundlich', 'from' => 'orange-500', 'to' => 'orange-600', 'border' => 'orange-500', 'bg' => 'orange-50', 'text' => 'orange-700'],
    'gray' => ['name' => '🤍 Grau', 'desc' => 'Zeitlos & minimalistisch', 'from' => 'gray-500', 'to' => 'gray-600', 'border' => 'gray-500', 'bg' => 'gray-50', 'text' => 'gray-700']
];
?>

            <div class="admin-header">
                <h1 class="text-2xl font-bold text-gray-900">🎨 Farben & Design</h1>
                <p class="text-gray-600">Wählen Sie das Farbthema für Ihre Website</p>
                
                <?php if ($current_theme): ?>
                    <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?php echo $themes[$current_theme]['bg']; ?> text-<?php echo $themes[$current_theme]['text']; ?>">
                        <i class="fas fa-paint-brush mr-2"></i>
                        Aktuell: <?php echo $themes[$current_theme]['name']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <form method="POST">
                    <h2 class="text-xl font-semibold mb-6">Farbthema auswählen</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($themes as $theme_key => $theme_data): ?>
                            <div class="border-2 <?php echo $current_theme === $theme_key ? 'border-' . $theme_data['border'] . ' bg-' . $theme_data['bg'] . ' shadow-lg' : 'border-gray-200 hover:border-' . $theme_data['border'] . ' hover:shadow-md'; ?> rounded-lg p-4 transition-all cursor-pointer">
                                <label class="cursor-pointer">
                                    <input type="radio" name="color_theme" value="<?php echo $theme_key; ?>" 
                                           <?php echo $current_theme === $theme_key ? 'checked' : ''; ?> 
                                           class="sr-only peer" onchange="this.form.submit()">
                                    <div class="text-center">
                                        <div class="w-full h-20 bg-gradient-to-r from-<?php echo $theme_data['from']; ?> to-<?php echo $theme_data['to']; ?> rounded-lg mb-3 relative">
                                            <?php if ($current_theme === $theme_key): ?>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg">
                                                        <i class="fas fa-check text-<?php echo $theme_data['border']; ?> text-lg"></i>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="font-semibold <?php echo $current_theme === $theme_key ? 'text-' . $theme_data['text'] : 'text-gray-900'; ?>">
                                            <?php echo $theme_data['name']; ?>
                                        </h3>
                                        <p class="text-sm text-gray-600"><?php echo $theme_data['desc']; ?></p>
                                        <?php if ($current_theme === $theme_key): ?>
                                            <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-bold text-<?php echo $theme_data['text']; ?> bg-<?php echo $theme_data['bg']; ?>">
                                                <i class="fas fa-star mr-1"></i>
                                                AKTIV
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <span class="inline-block w-4 h-4 rounded-full border-2 <?php echo $current_theme === $theme_key ? 'border-' . $theme_data['border'] . ' bg-' . $theme_data['border'] : 'border-gray-300'; ?> transition-all"></span>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Farbthema speichern
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Hinweis</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Das gewählte Farbthema wird sofort auf der gesamten Website angewendet. Die Änderungen sind für alle Besucher sichtbar.</p>
                            <p class="mt-1">Für erweiterte Farbanpassungen nutzen Sie die <a href="colors_advanced.php" class="underline font-medium">Erweiterte Farb-Verwaltung</a>.</p>
                        </div>
                    </div>
                </div>
            </div>

<?php include 'includes/footer.php'; ?>