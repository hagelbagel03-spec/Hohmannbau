<?php
/**
 * Advanced Colors & Design Manager - SPEICHERN FUNKTIONIERT
 */

require_once '../config/auth.php';
require_once 'includes/functions.php';
requireAuth();

require_once '../config/database.php';

$db = getDB();
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'update_theme':
                    $color_theme = sanitizeInput($_POST['color_theme']);
                    $stmt = $db->prepare("UPDATE homepage SET color_theme = ? WHERE id = '1'");
                    if ($stmt->execute([$color_theme])) {
                        $message = '✅ Hauptfarbthema wurde aktualisiert!';
                    } else {
                        $error = 'Fehler beim Aktualisieren des Farbthemas.';
                    }
                    break;
                    
                case 'update_colors':
                    // Update individual color settings
                    $footer_bg = sanitizeInput($_POST['footer_bg'] ?? '#1f2937');
                    $footer_text = sanitizeInput($_POST['footer_text'] ?? '#ffffff');
                    $header_bg = sanitizeInput($_POST['header_bg'] ?? '#ffffff');
                    $header_text = sanitizeInput($_POST['header_text'] ?? '#1f2937');
                    $button_primary = sanitizeInput($_POST['button_primary'] ?? '#10b981');
                    $button_secondary = sanitizeInput($_POST['button_secondary'] ?? '#6b7280');
                    $accent_color = sanitizeInput($_POST['accent_color'] ?? '#3b82f6');
                    $body_text = sanitizeInput($_POST['body_text'] ?? '#374151');
                    $heading_color = sanitizeInput($_POST['heading_color'] ?? '#1f2937');
                    $subheading_color = sanitizeInput($_POST['subheading_color'] ?? '#374151');
                    $link_color = sanitizeInput($_POST['link_color'] ?? '#2563eb');
                    $highlight_color = sanitizeInput($_POST['highlight_color'] ?? '#059669');
                    
                    // Einfacher UPDATE-Ansatz
                    $stmt = $db->prepare("
                        UPDATE homepage SET 
                        footer_bg_color = ?,
                        footer_text_color = ?,
                        header_bg_color = ?,
                        header_text_color = ?,
                        button_primary_color = ?,
                        button_secondary_color = ?,
                        accent_color = ?,
                        body_text_color = ?,
                        heading_color = ?,
                        subheading_color = ?,
                        link_color = ?,
                        highlight_color = ?
                        WHERE id = '1'
                    ");
                    
                    if ($stmt->execute([$footer_bg, $footer_text, $header_bg, $header_text, 
                                      $button_primary, $button_secondary, $accent_color, $body_text,
                                      $heading_color, $subheading_color, $link_color, $highlight_color])) {
                        $message = '✅ Individuelle Farben wurden erfolgreich gespeichert!';
                    } else {
                        $error = '❌ Fehler beim Speichern der Farben.';
                    }
                    break;
                    
                case 'reset_colors':
                    $stmt = $db->prepare("
                        UPDATE homepage SET 
                        footer_bg_color = '#1f2937',
                        footer_text_color = '#ffffff', 
                        header_bg_color = '#ffffff',
                        header_text_color = '#1f2937',
                        button_primary_color = '#10b981',
                        button_secondary_color = '#6b7280',
                        accent_color = '#3b82f6',
                        body_text_color = '#374151'
                        WHERE id = '1'
                    ");
                    if ($stmt->execute()) {
                        $message = '✅ Farben wurden auf Standard zurückgesetzt!';
                    } else {
                        $error = '❌ Fehler beim Zurücksetzen der Farben.';
                    }
                    break;
            }
        }
    } catch (Exception $e) {
        $error = '❌ Datenbank-Fehler: ' . $e->getMessage();
    }
}

// Get current settings
try {
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
    $current_theme = $homepage['color_theme'] ?? 'green';
    
    // Farben laden
    $footer_bg = $homepage['footer_bg_color'] ?? '#1f2937';
    $footer_text = $homepage['footer_text_color'] ?? '#ffffff';
    $header_bg = $homepage['header_bg_color'] ?? '#ffffff';
    $header_text = $homepage['header_text_color'] ?? '#1f2937';
    $button_primary = $homepage['button_primary_color'] ?? '#10b981';
    $button_secondary = $homepage['button_secondary_color'] ?? '#6b7280';
    $accent_color = $homepage['accent_color'] ?? '#3b82f6';
    $body_text = $homepage['body_text_color'] ?? '#374151';
    $heading_color = $homepage['heading_color'] ?? '#1f2937';
    $subheading_color = $homepage['subheading_color'] ?? '#374151';
    $link_color = $homepage['link_color'] ?? '#2563eb';
    $highlight_color = $homepage['highlight_color'] ?? '#059669';
} catch (Exception $e) {
    $error = '❌ Fehler beim Laden der Daten: ' . $e->getMessage();
    // Fallback-Werte
    $current_theme = 'green';
    $footer_bg = '#1f2937';
    $footer_text = '#ffffff';
    $header_bg = '#ffffff';
    $header_text = '#1f2937';
    $button_primary = '#10b981';
    $button_secondary = '#6b7280';
    $accent_color = '#3b82f6';
    $body_text = '#374151';
}

$pageTitle = 'Erweiterte Design-Anpassung';
$pageSubtitle = 'Passen Sie alle Farben und Design-Elemente Ihrer Website an';
include 'includes/header.php';
include 'includes/sidebar.php';

// Flash messages
if ($message) {
    echo '<div class="alert alert-success slide-in">';
    echo '<i class="fas fa-check-circle"></i>';
    echo '<span>' . htmlspecialchars($message) . '</span>';
    echo '</div>';
}

if ($error) {
    echo '<div class="alert alert-error slide-in">';
    echo '<i class="fas fa-exclamation-circle"></i>';
    echo '<span>' . htmlspecialchars($error) . '</span>';
    echo '</div>';
}
?>

            <!-- Live Preview -->
            <div class="admin-card p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center text-gray-800">
                    <i class="fas fa-eye mr-2 text-blue-600"></i>
                    Live-Vorschau
                </h2>
                <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                    <!-- Header Preview -->
                    <div class="p-4" style="background-color: <?php echo $header_bg; ?>; color: <?php echo $header_text; ?>">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold">Header-Bereich</h3>
                            <button class="px-4 py-2 rounded" style="background-color: <?php echo $button_primary; ?>; color: white;">
                                Primary Button
                            </button>
                        </div>
                    </div>
                    
                    <!-- Body Preview -->
                    <div class="p-4 bg-white" style="color: <?php echo $body_text; ?>">
                        <h4 class="text-lg font-semibold mb-2">Inhaltsbereiche</h4>
                        <p class="mb-4">Hier ist der normale Text-Bereich Ihrer Website. Lorem ipsum dolor sit amet.</p>
                        <button class="px-4 py-2 rounded mr-2" style="background-color: <?php echo $button_secondary; ?>; color: white;">
                            Secondary Button
                        </button>
                        <span class="px-3 py-1 rounded text-sm" style="background-color: <?php echo $accent_color; ?>; color: white;">
                            Accent Color
                        </span>
                    </div>
                    
                    <!-- Footer Preview -->
                    <div class="p-4" style="background-color: <?php echo $footer_bg; ?>; color: <?php echo $footer_text; ?>">
                        <div class="text-center">
                            <h4 class="font-semibold mb-2">Footer-Bereich</h4>
                            <p class="text-sm opacity-90">© 2024 Hohmann Bau. Alle Rechte vorbehalten.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Individuelle Farbanpassung -->
            <div class="admin-card p-6">
                <h2 class="text-xl font-semibold mb-6 flex items-center text-gray-800">
                    <i class="fas fa-sliders-h mr-2 text-orange-600"></i>
                    Individuelle Farbanpassung
                </h2>
                
                <form method="POST">
                    <input type="hidden" name="action" value="update_colors">
                    
                    <div class="space-y-4">
                        <!-- Footer Colors -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-grip-lines mr-2 text-gray-600"></i>
                                Footer-Farben
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hintergrund</label>
                                    <input type="color" name="footer_bg" value="<?php echo $footer_bg; ?>" 
                                           class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" name="footer_bg_display" value="<?php echo $footer_bg; ?>" 
                                           class="w-full mt-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                                    <input type="color" name="footer_text" value="<?php echo $footer_text; ?>" 
                                           class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" name="footer_text_display" value="<?php echo $footer_text; ?>" 
                                           class="w-full mt-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Header Colors -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-window-maximize mr-2 text-gray-600"></i>
                                Header-Farben
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hintergrund</label>
                                    <input type="color" name="header_bg" value="<?php echo $header_bg; ?>" 
                                           class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" name="header_bg_display" value="<?php echo $header_bg; ?>" 
                                           class="w-full mt-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                                    <input type="color" name="header_text" value="<?php echo $header_text; ?>" 
                                           class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" name="header_text_display" value="<?php echo $header_text; ?>" 
                                           class="w-full mt-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Button Colors -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-mouse-pointer mr-2 text-gray-600"></i>
                                Button-Farben
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary</label>
                                    <input type="color" name="button_primary" value="<?php echo $button_primary; ?>" 
                                           class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" name="button_primary_display" value="<?php echo $button_primary; ?>" 
                                           class="w-full mt-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Secondary</label>
                                    <input type="color" name="button_secondary" value="<?php echo $button_secondary; ?>" 
                                           class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" name="button_secondary_display" value="<?php echo $button_secondary; ?>" 
                                           class="w-full mt-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Text & Accent Colors -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-font mr-2 text-gray-600"></i>
                                Text & Akzent-Farben
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Haupttext</label>
                                    <input type="color" name="body_text" value="<?php echo $body_text; ?>" 
                                           class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" name="body_text_display" value="<?php echo $body_text; ?>" 
                                           class="w-full mt-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Akzent</label>
                                    <input type="color" name="accent_color" value="<?php echo $accent_color; ?>" 
                                           class="w-full h-12 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" name="accent_color_display" value="<?php echo $accent_color; ?>" 
                                           class="w-full mt-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button type="submit" name="action" value="reset_colors" 
                                class="btn-secondary"
                                onclick="return confirm('Alle Farben auf Standard zurücksetzen?')">
                            <i class="fas fa-undo mr-2"></i>
                            Zurücksetzen
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Farben speichern
                        </button>
                    </div>
                </form>
            </div>

            <!-- Test-Anzeige -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Debug-Informationen:</h3>
                <div class="text-sm text-blue-700 grid grid-cols-2 gap-2">
                    <p><strong>Footer BG:</strong> <?php echo $footer_bg; ?></p>
                    <p><strong>Footer Text:</strong> <?php echo $footer_text; ?></p>
                    <p><strong>Header BG:</strong> <?php echo $header_bg; ?></p>
                    <p><strong>Header Text:</strong> <?php echo $header_text; ?></p>
                    <p><strong>Button Primary:</strong> <?php echo $button_primary; ?></p>
                    <p><strong>Button Secondary:</strong> <?php echo $button_secondary; ?></p>
                    <p><strong>Accent:</strong> <?php echo $accent_color; ?></p>
                    <p><strong>Body Text:</strong> <?php echo $body_text; ?></p>
                </div>
            </div>

<script>
// Update display values when color changes
document.querySelectorAll('input[type="color"]').forEach(function(input) {
    input.addEventListener('change', function() {
        const displayInput = document.querySelector('input[name="' + this.name + '_display"]');
        if (displayInput) {
            displayInput.value = this.value;
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>