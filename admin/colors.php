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
    $color_theme = $_POST['color_theme'] ?? 'green';
    
    try {
        $stmt = $db->prepare("UPDATE homepage SET color_theme = ? WHERE id = '1'");
        $stmt->execute([$color_theme]);
        $message = 'Farbthema erfolgreich geändert!';
    } catch (Exception $e) {
        $message = 'Fehler beim Speichern: ' . $e->getMessage();
    }
}

// Get current color theme
$homepage = $db->query("SELECT color_theme FROM homepage WHERE id = '1'")->fetch();
$current_theme = $homepage['color_theme'] ?? 'green';

include 'includes/header.php';
?>

<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>
    
    <!-- Content wird bereits in sidebar.php gestartet -->
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
                                <h3 class="font-semibold text-green-600">🌿 Grün</h3>
                                <p class="text-sm text-gray-600">Perfekt für Gartenbau</p>
                            </div>
                        </label>
                    </div>

                    <!-- Blue Theme -->
                    <div class="border-2 <?php echo $current_theme === 'blue' ? 'border-blue-500' : 'border-gray-200'; ?> rounded-lg p-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="color_theme" value="blue" <?php echo $current_theme === 'blue' ? 'checked' : ''; ?> class="sr-only peer">
                            <div class="text-center">
                                <div class="w-full h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg mb-3"></div>
                                <h3 class="font-semibold text-blue-600">🔵 Blau</h3>
                                <p class="text-sm text-gray-600">Klassisch & vertrauensvoll</p>
                            </div>
                        </label>
                    </div>

                    <!-- Purple Theme -->
                    <div class="border-2 <?php echo $current_theme === 'purple' ? 'border-purple-500' : 'border-gray-200'; ?> rounded-lg p-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="color_theme" value="purple" <?php echo $current_theme === 'purple' ? 'checked' : ''; ?> class="sr-only peer">
                            <div class="text-center">
                                <div class="w-full h-20 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg mb-3"></div>
                                <h3 class="font-semibold text-purple-600">🟣 Lila</h3>
                                <p class="text-sm text-gray-600">Elegant & modern</p>
                            </div>
                        </label>
                    </div>

                    <!-- Red Theme -->
                    <div class="border-2 <?php echo $current_theme === 'red' ? 'border-red-500' : 'border-gray-200'; ?> rounded-lg p-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="color_theme" value="red" <?php echo $current_theme === 'red' ? 'checked' : ''; ?> class="sr-only peer">
                            <div class="text-center">
                                <div class="w-full h-20 bg-gradient-to-r from-red-500 to-red-600 rounded-lg mb-3"></div>
                                <h3 class="font-semibold text-red-600">🔴 Rot</h3>
                                <p class="text-sm text-gray-600">Dynamisch & energisch</p>
                            </div>
                        </label>
                    </div>

                    <!-- Orange Theme -->
                    <div class="border-2 <?php echo $current_theme === 'orange' ? 'border-orange-500' : 'border-gray-200'; ?> rounded-lg p-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="color_theme" value="orange" <?php echo $current_theme === 'orange' ? 'checked' : ''; ?> class="sr-only peer">
                            <div class="text-center">
                                <div class="w-full h-20 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg mb-3"></div>
                                <h3 class="font-semibold text-orange-600">🟠 Orange</h3>
                                <p class="text-sm text-gray-600">Warm & einladend</p>
                            </div>
                        </label>
                    </div>

                    <!-- Gray Theme -->
                    <div class="border-2 <?php echo $current_theme === 'gray' ? 'border-gray-500' : 'border-gray-200'; ?> rounded-lg p-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="color_theme" value="gray" <?php echo $current_theme === 'gray' ? 'checked' : ''; ?> class="sr-only peer">
                            <div class="text-center">
                                <div class="w-full h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg mb-3"></div>
                                <h3 class="font-semibold text-gray-600">⚫ Grau</h3>
                                <p class="text-sm text-gray-600">Neutral & minimalistisch</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-palette mr-2"></i>
                        Farbthema speichern
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview -->
        <div class="mt-8 bg-gray-50 rounded-xl p-6">
            <h3 class="text-lg font-bold mb-4">Aktuelle Farbeinstellungen</h3>
            <p class="text-gray-600">
                Aktuelles Theme: <span class="font-semibold"><?php echo ucfirst($current_theme); ?></span>
            </p>
            <p class="text-sm text-gray-500 mt-2">
                Die Änderungen werden sofort auf der Website sichtbar.
            </p>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>