<?php
/**
 * Universal Page Editor - Alle Seiten bearbeiten
 */

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$db = getDB();
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_homepage':
            $stmt = $db->prepare("UPDATE homepage SET hero_title = ?, hero_subtitle = ?, emergency_number = ?, phone_number = ?, email = ?, address = ?, opening_hours = ?, color_theme = ? WHERE id = '1'");
            $stmt->execute([
                $_POST['hero_title'],
                $_POST['hero_subtitle'], 
                $_POST['emergency_number'],
                $_POST['phone_number'],
                $_POST['email'],
                $_POST['address'],
                $_POST['opening_hours'],
                $_POST['color_theme']
            ]);
            $message = 'Homepage erfolgreich aktualisiert!';
            break;
            
        case 'update_service':
            $stmt = $db->prepare("UPDATE services SET title = ?, description = ?, icon = ? WHERE id = ?");
            $stmt->execute([
                $_POST['service_title'],
                $_POST['service_description'],
                $_POST['service_icon'],
                $_POST['service_id']
            ]);
            $message = 'Service erfolgreich aktualisiert!';
            break;
            
        case 'update_team_member':
            $stmt = $db->prepare("UPDATE team SET name = ?, position = ?, description = ? WHERE id = ?");
            $stmt->execute([
                $_POST['team_name'],
                $_POST['team_position'],
                $_POST['team_description'],
                $_POST['team_id']
            ]); 
            $message = 'Team-Mitglied erfolgreich aktualisiert!';
            break;
    }
}

// Get current data
$homepage = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
$services = $db->query("SELECT * FROM services ORDER BY `order`, title")->fetchAll();
$team = $db->query("SELECT * FROM team ORDER BY `order`, name")->fetchAll();
$statistics = $db->query("SELECT * FROM statistics ORDER BY `order`, title")->fetchAll();

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Editor - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b">
            <h1 class="text-xl font-bold text-gray-800">Page Editor</h1>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="#homepage" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('homepage')">🏠 Homepage</a></li>
                <li><a href="#services" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('services')">🛠️ Leistungen</a></li>
                <li><a href="#team" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('team')">👥 Team</a></li>
                <li><a href="#statistics" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('statistics')">📊 Statistiken</a></li>
                <li><a href="#colors" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('colors')">🎨 Farben & Design</a></li>
                <li><a href="index.php" class="block p-2 rounded hover:bg-gray-100">← Zurück zum Dashboard</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto">
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Homepage Section -->
        <div id="homepage-section" class="section">
            <h2 class="text-2xl font-bold mb-6">Homepage bearbeiten</h2>
            <form method="POST" class="bg-white p-6 rounded-lg shadow-lg">
                <input type="hidden" name="action" value="update_homepage">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Haupttitel</label>
                        <input type="text" name="hero_title" value="<?php echo htmlspecialchars($homepage['hero_title']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Farbthema</label>
                        <select name="color_theme" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="green" <?php echo ($homepage['color_theme'] ?? 'green') == 'green' ? 'selected' : ''; ?>>Grün (Gartenbau)</option>
                            <option value="blue" <?php echo ($homepage['color_theme'] ?? '') == 'blue' ? 'selected' : ''; ?>>Blau</option>
                            <option value="purple" <?php echo ($homepage['color_theme'] ?? '') == 'purple' ? 'selected' : ''; ?>>Lila</option>
                            <option value="red" <?php echo ($homepage['color_theme'] ?? '') == 'red' ? 'selected' : ''; ?>>Rot</option>
                            <option value="orange" <?php echo ($homepage['color_theme'] ?? '') == 'orange' ? 'selected' : ''; ?>>Orange</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Untertitel</label>
                    <textarea name="hero_subtitle" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($homepage['hero_subtitle']); ?></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($homepage['phone_number']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notdienst</label>
                        <input type="text" name="emergency_number" value="<?php echo htmlspecialchars($homepage['emergency_number']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">E-Mail</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($homepage['email']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($homepage['address']); ?></textarea>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Öffnungszeiten</label>
                    <textarea name="opening_hours" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($homepage['opening_hours']); ?></textarea>
                </div>
                
                <button type="submit" class="mt-6 bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                    Homepage speichern
                </button>
            </form>
        </div>

        <!-- Services Section -->
        <div id="services-section" class="section hidden">
            <h2 class="text-2xl font-bold mb-6">Leistungen bearbeiten</h2>
            <?php foreach ($services as $service): ?>
                <div class="bg-white p-6 rounded-lg shadow-lg mb-4">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_service">
                        <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titel</label>
                                <input type="text" name="service_title" value="<?php echo htmlspecialchars($service['title']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                                <select name="service_icon" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="Leaf" <?php echo $service['icon'] == 'Leaf' ? 'selected' : ''; ?>>🌿 Blatt</option>
                                    <option value="Mountain" <?php echo $service['icon'] == 'Mountain' ? 'selected' : ''; ?>>🏔️ Berg</option>
                                    <option value="Seedling" <?php echo $service['icon'] == 'Seedling' ? 'selected' : ''; ?>>🌱 Setzling</option>
                                    <option value="Scissors" <?php echo $service['icon'] == 'Scissors' ? 'selected' : ''; ?>>✂️ Schere</option>
                                    <option value="Tree" <?php echo $service['icon'] == 'Tree' ? 'selected' : ''; ?>>🌳 Baum</option>
                                    <option value="Flower" <?php echo $service['icon'] == 'Flower' ? 'selected' : ''; ?>>🌸 Blume</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Speichern
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                            <textarea name="service_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($service['description']); ?></textarea>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Team Section -->
        <div id="team-section" class="section hidden">
            <h2 class="text-2xl font-bold mb-6">Team bearbeiten</h2>
            <?php foreach ($team as $member): ?>
                <div class="bg-white p-6 rounded-lg shadow-lg mb-4">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_team_member">
                        <input type="hidden" name="team_id" value="<?php echo $member['id']; ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" name="team_name" value="<?php echo htmlspecialchars($member['name']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                <input type="text" name="team_position" value="<?php echo htmlspecialchars($member['position']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                            <textarea name="team_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($member['description']); ?></textarea>
                        </div>
                        
                        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Speichern
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Statistics Section -->
        <div id="statistics-section" class="section hidden">
            <h2 class="text-2xl font-bold mb-6">Statistiken bearbeiten</h2>
            <p class="text-gray-600 mb-4">Die Zahlen auf der Homepage</p>
            
            <?php foreach ($statistics as $stat): ?>
                <div class="bg-white p-6 rounded-lg shadow-lg mb-4">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_statistic">
                        <input type="hidden" name="stat_id" value="<?php echo $stat['id']; ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titel</label>
                                <input type="text" name="stat_title" value="<?php echo htmlspecialchars($stat['title']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Wert</label>
                                <input type="text" name="stat_value" value="<?php echo htmlspecialchars($stat['value']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Farbe</label>
                                <select name="stat_color" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="green" <?php echo $stat['color'] == 'green' ? 'selected' : ''; ?>>Grün</option>
                                    <option value="blue" <?php echo $stat['color'] == 'blue' ? 'selected' : ''; ?>>Blau</option>
                                    <option value="yellow" <?php echo $stat['color'] == 'yellow' ? 'selected' : ''; ?>>Gelb</option>
                                    <option value="red" <?php echo $stat['color'] == 'red' ? 'selected' : ''; ?>>Rot</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                            <textarea name="stat_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($stat['description']); ?></textarea>
                        </div>
                        
                        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Speichern
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Colors Section -->
        <div id="colors-section" class="section hidden">
            <h2 class="text-2xl font-bold mb-6">Farben & Design</h2>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold mb-4">Farbvorschau</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-lg bg-green-600 text-white text-center">
                        <div class="font-bold">Grün</div>
                        <div class="text-sm">#10b981</div>
                    </div>
                    
                    <div class="p-4 rounded-lg bg-blue-600 text-white text-center">
                        <div class="font-bold">Blau</div>
                        <div class="text-sm">#667eea</div>
                    </div>
                    
                    <div class="p-4 rounded-lg bg-purple-600 text-white text-center">
                        <div class="font-bold">Lila</div>
                        <div class="text-sm">#8b5cf6</div>
                    </div>
                    
                    <div class="p-4 rounded-lg bg-red-600 text-white text-center">
                        <div class="font-bold">Rot</div>
                        <div class="text-sm">#ef4444</div>
                    </div>
                    
                    <div class="p-4 rounded-lg bg-orange-600 text-white text-center">
                        <div class="font-bold">Orange</div>
                        <div class="text-sm">#f59e0b</div>
                    </div>
                    
                    <div class="p-4 rounded-lg bg-gray-600 text-white text-center">
                        <div class="font-bold">Grau</div>
                        <div class="text-sm">#6b7280</div>
                    </div>
                </div>
                
                <p class="mt-4 text-gray-600">Das Farbthema kann in der Homepage-Sektion geändert werden.</p>
            </div>
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
    document.getElementById(sectionName + '-section').classList.remove('hidden');
}

// Show homepage section by default
document.addEventListener('DOMContentLoaded', function() {
    showSection('homepage');
});
</script>

</body>
</html>