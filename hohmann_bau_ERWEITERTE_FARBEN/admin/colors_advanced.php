<?php
/**
 * Advanced Colors & Design Manager
 * Erweiterte Design-Anpassung für Footer, Text, Buttons etc.
 */

require_once '../config/auth.php';
require_once 'includes/functions.php';
requireAuth();

require_once '../config/database.php';

$db = getDB();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_theme':
                $color_theme = sanitizeInput($_POST['color_theme']);
                $stmt = $db->prepare("UPDATE homepage SET color_theme = ? WHERE id = '1'");
                if ($stmt->execute([$color_theme])) {
                    $message = 'Hauptfarbthema wurde aktualisiert!';
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
                
                // Create or update color settings
                $stmt = $db->prepare("
                    INSERT INTO homepage (id, footer_bg_color, footer_text_color, header_bg_color, header_text_color, 
                                        button_primary_color, button_secondary_color, accent_color, body_text_color) 
                    VALUES ('1', ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                    footer_bg_color = VALUES(footer_bg_color),
                    footer_text_color = VALUES(footer_text_color),
                    header_bg_color = VALUES(header_bg_color),
                    header_text_color = VALUES(header_text_color),
                    button_primary_color = VALUES(button_primary_color),
                    button_secondary_color = VALUES(button_secondary_color),
                    accent_color = VALUES(accent_color),
                    body_text_color = VALUES(body_text_color)
                ");
                
                if ($stmt->execute([$footer_bg, $footer_text, $header_bg, $header_text, 
                                  $button_primary, $button_secondary, $accent_color, $body_text])) {
                    $message = 'Individuelle Farben wurden aktualisiert!';
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
                    $message = 'Farben wurden auf Standard zurückgesetzt!';
                }
                break;
        }
    }
}

// Get current settings
$homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
$current_theme = $homepage['color_theme'] ?? 'green';

// Check if color columns exist, if not add them
try {
    $footer_bg = $homepage['footer_bg_color'] ?? '#1f2937';
    $footer_text = $homepage['footer_text_color'] ?? '#ffffff';
    $header_bg = $homepage['header_bg_color'] ?? '#ffffff';
    $header_text = $homepage['header_text_color'] ?? '#1f2937';
    $button_primary = $homepage['button_primary_color'] ?? '#10b981';
    $button_secondary = $homepage['button_secondary_color'] ?? '#6b7280';
    $accent_color = $homepage['accent_color'] ?? '#3b82f6';
    $body_text = $homepage['body_text_color'] ?? '#374151';
} catch (Exception $e) {
    // Add columns if they don't exist
    $db->exec("ALTER TABLE homepage ADD COLUMN footer_bg_color VARCHAR(20) DEFAULT '#1f2937'");
    $db->exec("ALTER TABLE homepage ADD COLUMN footer_text_color VARCHAR(20) DEFAULT '#ffffff'");
    $db->exec("ALTER TABLE homepage ADD COLUMN header_bg_color VARCHAR(20) DEFAULT '#ffffff'");
    $db->exec("ALTER TABLE homepage ADD COLUMN header_text_color VARCHAR(20) DEFAULT '#1f2937'");
    $db->exec("ALTER TABLE homepage ADD COLUMN button_primary_color VARCHAR(20) DEFAULT '#10b981'");
    $db->exec("ALTER TABLE homepage ADD COLUMN button_secondary_color VARCHAR(20) DEFAULT '#6b7280'");
    $db->exec("ALTER TABLE homepage ADD COLUMN accent_color VARCHAR(20) DEFAULT '#3b82f6'");
    $db->exec("ALTER TABLE homepage ADD COLUMN body_text_color VARCHAR(20) DEFAULT '#374151'");
    
    // Set default values
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
include 'includes/header.php';
include 'includes/sidebar.php';
?>

            <div class="admin-header">
                <h1 class="text-3xl font-bold text-gray-900">🎨 Erweiterte Design-Anpassung</h1>
                <p class="text-gray-600">Passen Sie alle Farben und Design-Elemente Ihrer Website an</p>
            </div>

            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Live Preview -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
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

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Schnelle Farbthemen -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
                        <i class="fas fa-palette mr-2 text-purple-600"></i>
                        Schnelle Farbthemen
                    </h2>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="update_theme">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Green Theme -->
                            <div class="border-2 <?php echo $current_theme === 'green' ? 'border-green-500' : 'border-gray-200'; ?> rounded-lg p-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="color_theme" value="green" <?php echo $current_theme === 'green' ? 'checked' : ''; ?> 
                                           class="sr-only peer" onchange="this.form.submit()">
                                    <div class="text-center">
                                        <div class="w-full h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-lg mb-2"></div>
                                        <h3 class="font-semibold text-gray-900">🌿 Grün</h3>
                                        <p class="text-xs text-gray-600">Natur & Garten</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Blue Theme -->
                            <div class="border-2 <?php echo $current_theme === 'blue' ? 'border-blue-500' : 'border-gray-200'; ?> rounded-lg p-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="color_theme" value="blue" <?php echo $current_theme === 'blue' ? 'checked' : ''; ?> 
                                           class="sr-only peer" onchange="this.form.submit()">
                                    <div class="text-center">
                                        <div class="w-full h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg mb-2"></div>
                                        <h3 class="font-semibold text-gray-900">💙 Blau</h3>
                                        <p class="text-xs text-gray-600">Professional</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Purple Theme -->
                            <div class="border-2 <?php echo $current_theme === 'purple' ? 'border-purple-500' : 'border-gray-200'; ?> rounded-lg p-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="color_theme" value="purple" <?php echo $current_theme === 'purple' ? 'checked' : ''; ?> 
                                           class="sr-only peer" onchange="this.form.submit()">
                                    <div class="text-center">
                                        <div class="w-full h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg mb-2"></div>
                                        <h3 class="font-semibold text-gray-900">💜 Lila</h3>
                                        <p class="text-xs text-gray-600">Elegant</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Red Theme -->
                            <div class="border-2 <?php echo $current_theme === 'red' ? 'border-red-500' : 'border-gray-200'; ?> rounded-lg p-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="color_theme" value="red" <?php echo $current_theme === 'red' ? 'checked' : ''; ?> 
                                           class="sr-only peer" onchange="this.form.submit()">
                                    <div class="text-center">
                                        <div class="w-full h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-lg mb-2"></div>
                                        <h3 class="font-semibold text-gray-900">❤️ Rot</h3>
                                        <p class="text-xs text-gray-600">Kraftvoll</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Individuelle Farbanpassung -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
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
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="footer_bg" value="<?php echo $footer_bg; ?>" 
                                                   class="w-12 h-8 border border-gray-300 rounded cursor-pointer">
                                            <input type="text" value="<?php echo $footer_bg; ?>" 
                                                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="footer_text" value="<?php echo $footer_text; ?>" 
                                                   class="w-12 h-8 border border-gray-300 rounded cursor-pointer">
                                            <input type="text" value="<?php echo $footer_text; ?>" 
                                                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                        </div>
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
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="header_bg" value="<?php echo $header_bg; ?>" 
                                                   class="w-12 h-8 border border-gray-300 rounded cursor-pointer">
                                            <input type="text" value="<?php echo $header_bg; ?>" 
                                                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="header_text" value="<?php echo $header_text; ?>" 
                                                   class="w-12 h-8 border border-gray-300 rounded cursor-pointer">
                                            <input type="text" value="<?php echo $header_text; ?>" 
                                                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                        </div>
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
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="button_primary" value="<?php echo $button_primary; ?>" 
                                                   class="w-12 h-8 border border-gray-300 rounded cursor-pointer">
                                            <input type="text" value="<?php echo $button_primary; ?>" 
                                                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Secondary</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="button_secondary" value="<?php echo $button_secondary; ?>" 
                                                   class="w-12 h-8 border border-gray-300 rounded cursor-pointer">
                                            <input type="text" value="<?php echo $button_secondary; ?>" 
                                                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                        </div>
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
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="body_text" value="<?php echo $body_text; ?>" 
                                                   class="w-12 h-8 border border-gray-300 rounded cursor-pointer">
                                            <input type="text" value="<?php echo $body_text; ?>" 
                                                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Akzent</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="accent_color" value="<?php echo $accent_color; ?>" 
                                                   class="w-12 h-8 border border-gray-300 rounded cursor-pointer">
                                            <input type="text" value="<?php echo $accent_color; ?>" 
                                                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="button" onclick="resetColors()" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
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
            </div>

            <!-- CSS Export -->
            <div class="mt-6 bg-gray-50 rounded-xl shadow-inner p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-code mr-2 text-indigo-600"></i>
                    Generiertes CSS
                </h2>
                <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
<pre>:root {
    --footer-bg: <?php echo $footer_bg; ?>;
    --footer-text: <?php echo $footer_text; ?>;
    --header-bg: <?php echo $header_bg; ?>;
    --header-text: <?php echo $header_text; ?>;
    --button-primary: <?php echo $button_primary; ?>;
    --button-secondary: <?php echo $button_secondary; ?>;
    --accent-color: <?php echo $accent_color; ?>;
    --body-text: <?php echo $body_text; ?>;
}

.footer { background-color: var(--footer-bg); color: var(--footer-text); }
.header { background-color: var(--header-bg); color: var(--header-text); }
.btn-primary { background-color: var(--button-primary); }
.btn-secondary { background-color: var(--button-secondary); }
.accent { color: var(--accent-color); }
body { color: var(--body-text); }</pre>
                </div>
            </div>

<script>
function resetColors() {
    if (confirm('Alle Farben auf Standard zurücksetzen? Diese Aktion kann nicht rückgängig gemacht werden.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="action" value="reset_colors">';
        document.body.appendChild(form);
        form.submit();
    }
}

// Live color preview update
document.querySelectorAll('input[type="color"]').forEach(function(input) {
    input.addEventListener('change', function() {
        const textInput = this.nextElementSibling;
        textInput.value = this.value;
        updatePreview();
    });
});

function updatePreview() {
    // This could update the live preview in real-time
    console.log('Preview updated');
}
</script>

<?php include 'includes/footer.php'; ?>