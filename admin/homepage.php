<?php
/**
 * Admin Homepage Management
 */

require_once '../config/database.php';
require_once '../config/auth.php';
require_once 'includes/functions.php';

requireAuth();

$db = getDB();
$current_admin = getCurrentAdmin();

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_homepage':
                // Handle image upload
                $hero_image = null;
                if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
                    $file_extension = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
                    
                    if (in_array($file_extension, $allowed_types)) {
                        $hero_image = 'hero_' . uniqid() . '.' . $file_extension;
                        $upload_path = '../uploads/' . $hero_image;
                        
                        if (!is_dir('../uploads/')) {
                            mkdir('../uploads/', 0777, true);
                        }
                        
                        if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $upload_path)) {
                            // Image uploaded successfully
                        } else {
                            $hero_image = null;
                        }
                    }
                }
                
                // Get current homepage data
                $current = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
                
                if ($current) {
                    // Update existing
                    $stmt = $db->prepare("UPDATE homepage SET hero_title = ?, hero_subtitle = ?, emergency_number = ?, phone_number = ?, email = ?, address = ?, opening_hours = ?, color_theme = ?" . ($hero_image ? ", hero_image = ?" : "") . " WHERE id = ?");
                    $params = [
                        sanitizeInput($_POST['hero_title']),
                        sanitizeInput($_POST['hero_subtitle']),
                        sanitizeInput($_POST['emergency_number']),
                        sanitizeInput($_POST['phone_number']),
                        sanitizeInput($_POST['email']),
                        sanitizeInput($_POST['address']),
                        sanitizeInput($_POST['opening_hours']),
                        sanitizeInput($_POST['color_theme'])
                    ];
                    if ($hero_image) {
                        $params[] = $hero_image;
                    }
                    $params[] = $current['id'];
                    $result = $stmt->execute($params);
                } else {
                    // Insert new
                    $stmt = $db->prepare("INSERT INTO homepage (id, hero_title, hero_subtitle, emergency_number, phone_number, email, address, opening_hours, color_theme" . ($hero_image ? ", hero_image" : "") . ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?" . ($hero_image ? ", ?" : "") . ")");
                    $params = [
                        '1',
                        sanitizeInput($_POST['hero_title']),
                        sanitizeInput($_POST['hero_subtitle']),
                        sanitizeInput($_POST['emergency_number']),
                        sanitizeInput($_POST['phone_number']),
                        sanitizeInput($_POST['email']),
                        sanitizeInput($_POST['address']),
                        sanitizeInput($_POST['opening_hours']),
                        sanitizeInput($_POST['color_theme'])
                    ];
                    if ($hero_image) {
                        $params[] = $hero_image;
                    }
                    $result = $stmt->execute($params);
                }
                
                $message = $result ? "Homepage erfolgreich aktualisiert!" : "Fehler beim Aktualisieren.";
                break;
        }
    }
}

// Get current homepage data
$homepage = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
if (!$homepage) {
    $homepage = [
        'hero_title' => 'Stadtwache',
        'hero_subtitle' => 'Sicherheit und Schutz für unsere Gemeinschaft. Moderne Polizeiarbeit im Dienste der Bürger.',
        'emergency_number' => '110',
        'phone_number' => '+49 123 456-789',
        'email' => 'info@stadtwache.de',
        'address' => "Stadtwache Hauptrevier\nHauptstraße 123\n12345 Musterstadt",
        'opening_hours' => "Mo-Fr: 8:00-20:00\nSa: 9:00-16:00\nSo: 10:00-14:00",
        'hero_image' => null
    ];
}

$pageTitle = 'Homepage verwalten - Admin';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">Homepage verwalten</h1>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-blue-600 hover:text-blue-800">← Zurück zum Dashboard</a>
                    <a href="../index.php" target="_blank" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        Homepage anzeigen
                    </a>
                    <span class="text-sm text-gray-500"><?php echo htmlspecialchars($current_admin['username']); ?></span>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <?php if (isset($message)): ?>
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <!-- Homepage Editor -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-6">Homepage-Inhalte bearbeiten</h2>
            
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="action" value="update_homepage">
                
                <!-- Hero Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Hero-Bereich</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Haupt-Titel</label>
                            <input type="text" name="hero_title" value="<?php echo htmlspecialchars($homepage['hero_title']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="z.B. Stadtwache">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Untertitel</label>
                            <textarea name="hero_subtitle" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Beschreibung der Stadtwache..."><?php echo htmlspecialchars($homepage['hero_subtitle']); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hero-Bild</label>
                            <?php if ($homepage['hero_image']): ?>
                            <div class="mb-3">
                                <img src="../uploads/<?php echo htmlspecialchars($homepage['hero_image']); ?>" alt="Current Hero Image" class="w-32 h-20 object-cover rounded border">
                                <p class="text-sm text-gray-500 mt-1">Aktuelles Bild</p>
                            </div>
                            <?php endif; ?>
                            <input type="file" name="hero_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">JPG, PNG oder WebP (max. 5MB)</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Farbschema</label>
                            <select name="color_theme" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="green" <?php echo ($homepage['color_theme'] ?? 'green') === 'green' ? 'selected' : ''; ?>>
                                    🌿 Grün (Gartenbau)
                                </option>
                                <option value="blue" <?php echo ($homepage['color_theme'] ?? 'green') === 'blue' ? 'selected' : ''; ?>>
                                    🔵 Blau
                                </option>
                                <option value="purple" <?php echo ($homepage['color_theme'] ?? 'green') === 'purple' ? 'selected' : ''; ?>>
                                    🟣 Lila (Elegant)
                                </option>
                                <option value="red" <?php echo ($homepage['color_theme'] ?? 'green') === 'red' ? 'selected' : ''; ?>>
                                    🔴 Rot (Dynamisch)
                                </option>
                                <option value="gray" <?php echo ($homepage['color_theme'] ?? 'green') === 'gray' ? 'selected' : ''; ?>>
                                    ⚫ Grau (Neutral)
                                </option>
                                <option value="orange" <?php echo ($homepage['color_theme'] ?? 'green') === 'orange' ? 'selected' : ''; ?>>
                                    🟠 Orange (Energisch)
                                </option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Bestimmt die Hauptfarbe der gesamten Website</p>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kontaktinformationen</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notruf-Nummer</label>
                            <input type="text" name="emergency_number" value="<?php echo htmlspecialchars($homepage['emergency_number']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="110">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telefonnummer</label>
                            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($homepage['phone_number']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="+49 123 456-789">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">E-Mail-Adresse</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($homepage['email']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="info@stadtwache.de">
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <textarea name="address" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Stadtwache Hauptrevier..."><?php echo htmlspecialchars($homepage['address']); ?></textarea>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Öffnungszeiten</label>
                        <textarea name="opening_hours" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Mo-Fr: 8:00-20:00..."><?php echo htmlspecialchars($homepage['opening_hours']); ?></textarea>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-save mr-2"></i>
                        Änderungen speichern
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Preview -->
        <div class="mt-8 bg-blue-50 rounded-xl p-6">
            <h3 class="text-lg font-bold text-blue-900 mb-4">
                <i class="fas fa-eye mr-2"></i>
                Vorschau der aktuellen Homepage
            </h3>
            <div class="space-y-2 text-sm">
                <p><strong>Titel:</strong> <?php echo htmlspecialchars($homepage['hero_title']); ?></p>
                <p><strong>Untertitel:</strong> <?php echo htmlspecialchars($homepage['hero_subtitle']); ?></p>
                <p><strong>Notruf:</strong> <?php echo htmlspecialchars($homepage['emergency_number']); ?></p>
                <p><strong>Telefon:</strong> <?php echo htmlspecialchars($homepage['phone_number']); ?></p>
                <p><strong>E-Mail:</strong> <?php echo htmlspecialchars($homepage['email']); ?></p>
            </div>
            
            <div class="mt-4">
                <a href="../index.php" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-external-link-alt mr-1"></i>
                    Homepage in neuem Tab öffnen
                </a>
            </div>
        </div>
    </div>
    
</body>
</html>