<?php
/**
 * Colors & Design Manager
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

// Get current color theme
$homepage = $db->query("SELECT color_theme FROM homepage WHERE id = '1'")->fetch();
$current_theme = $homepage['color_theme'] ?? 'green';

$pageTitle = 'Farben & Design';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

            <div class="admin-header">
                <h1 class="text-2xl font-bold text-gray-900">🎨 Farben & Design</h1>
                <p class="text-gray-600">Wählen Sie das Farbthema für Ihre Website</p>
            </div>

            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <form method="POST">
                    <h2 class="text-xl font-semibold mb-6">Farbthema auswählen</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Green Theme -->
                        <div class="border-2 <?php echo $current_theme === 'green' ? 'border-green-500' : 'border-gray-200'; ?> rounded-lg p-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="color_theme" value="green" <?php echo $current_theme === 'green' ? 'checked' : ''; ?> class="sr-only peer">
                                <div class="text-center">
                                    <div class="w-full h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-lg mb-3"></div>
                                    <h3 class="font-semibold text-gray-900">🌿 Grün</h3>
                                    <p class="text-sm text-gray-600">Natürlich & frisch</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="inline-block w-4 h-4 rounded-full border-2 <?php echo $current_theme === 'green' ? 'border-green-500 bg-green-500' : 'border-gray-300'; ?> transition-all"></span>
                                </div>
                            </label>
                        </div>

                        <!-- Blue Theme -->
                        <div class="border-2 <?php echo $current_theme === 'blue' ? 'border-blue-500' : 'border-gray-200'; ?> rounded-lg p-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="color_theme" value="blue" <?php echo $current_theme === 'blue' ? 'checked' : ''; ?> class="sr-only peer">
                                <div class="text-center">
                                    <div class="w-full h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg mb-3"></div>
                                    <h3 class="font-semibold text-gray-900">💙 Blau</h3>
                                    <p class="text-sm text-gray-600">Professional & vertrauensvoll</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="inline-block w-4 h-4 rounded-full border-2 <?php echo $current_theme === 'blue' ? 'border-blue-500 bg-blue-500' : 'border-gray-300'; ?> transition-all"></span>
                                </div>
                            </label>
                        </div>

                        <!-- Purple Theme -->
                        <div class="border-2 <?php echo $current_theme === 'purple' ? 'border-purple-500' : 'border-gray-200'; ?> rounded-lg p-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="color_theme" value="purple" <?php echo $current_theme === 'purple' ? 'checked' : ''; ?> class="sr-only peer">
                                <div class="text-center">
                                    <div class="w-full h-20 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg mb-3"></div>
                                    <h3 class="font-semibold text-gray-900">💜 Lila</h3>
                                    <p class="text-sm text-gray-600">Elegant & modern</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="inline-block w-4 h-4 rounded-full border-2 <?php echo $current_theme === 'purple' ? 'border-purple-500 bg-purple-500' : 'border-gray-300'; ?> transition-all"></span>
                                </div>
                            </label>
                        </div>

                        <!-- Red Theme -->
                        <div class="border-2 <?php echo $current_theme === 'red' ? 'border-red-500' : 'border-gray-200'; ?> rounded-lg p-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="color_theme" value="red" <?php echo $current_theme === 'red' ? 'checked' : ''; ?> class="sr-only peer">
                                <div class="text-center">
                                    <div class="w-full h-20 bg-gradient-to-r from-red-500 to-red-600 rounded-lg mb-3"></div>
                                    <h3 class="font-semibold text-gray-900">❤️ Rot</h3>
                                    <p class="text-sm text-gray-600">Kraftvoll & aufmerksamkeitsstark</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="inline-block w-4 h-4 rounded-full border-2 <?php echo $current_theme === 'red' ? 'border-red-500 bg-red-500' : 'border-gray-300'; ?> transition-all"></span>
                                </div>
                            </label>
                        </div>

                        <!-- Orange Theme -->
                        <div class="border-2 <?php echo $current_theme === 'orange' ? 'border-orange-500' : 'border-gray-200'; ?> rounded-lg p-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="color_theme" value="orange" <?php echo $current_theme === 'orange' ? 'checked' : ''; ?> class="sr-only peer">
                                <div class="text-center">
                                    <div class="w-full h-20 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg mb-3"></div>
                                    <h3 class="font-semibold text-gray-900">🧡 Orange</h3>
                                    <p class="text-sm text-gray-600">Energiegeladen & freundlich</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="inline-block w-4 h-4 rounded-full border-2 <?php echo $current_theme === 'orange' ? 'border-orange-500 bg-orange-500' : 'border-gray-300'; ?> transition-all"></span>
                                </div>
                            </label>
                        </div>

                        <!-- Gray Theme -->
                        <div class="border-2 <?php echo $current_theme === 'gray' ? 'border-gray-500' : 'border-gray-200'; ?> rounded-lg p-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="color_theme" value="gray" <?php echo $current_theme === 'gray' ? 'checked' : ''; ?> class="sr-only peer">
                                <div class="text-center">
                                    <div class="w-full h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg mb-3"></div>
                                    <h3 class="font-semibold text-gray-900">🤍 Grau</h3>
                                    <p class="text-sm text-gray-600">Zeitlos & minimalistisch</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="inline-block w-4 h-4 rounded-full border-2 <?php echo $current_theme === 'gray' ? 'border-gray-500 bg-gray-500' : 'border-gray-300'; ?> transition-all"></span>
                                </div>
                            </label>
                        </div>
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
                        </div>
                    </div>
                </div>
            </div>

<?php include 'includes/footer.php'; ?>