<?php
/**
 * Admin Services Management
 */

require_once '../config/database.php';
require_once '../config/auth.php';

requireAuth();

$db = getDB();
$current_admin = getCurrentAdmin();

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $stmt = $db->prepare("INSERT INTO services (id, title, description, icon, `order`, active) VALUES (?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    generateUUID(),
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['description']),
                    sanitizeInput($_POST['icon']),
                    intval($_POST['order']),
                    isset($_POST['active']) ? 1 : 0
                ]);
                $message = $result ? "Service erfolgreich erstellt!" : "Fehler beim Erstellen.";
                break;
                
            case 'update':
                $stmt = $db->prepare("UPDATE services SET title = ?, description = ?, icon = ?, `order` = ?, active = ? WHERE id = ?");
                $result = $stmt->execute([
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['description']),
                    sanitizeInput($_POST['icon']),
                    intval($_POST['order']),
                    isset($_POST['active']) ? 1 : 0,
                    sanitizeInput($_POST['id'])
                ]);
                $message = $result ? "Service erfolgreich aktualisiert!" : "Fehler beim Aktualisieren.";
                break;
                
            case 'delete':
                $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
                $result = $stmt->execute([sanitizeInput($_POST['id'])]);
                $message = $result ? "Service erfolgreich gelöscht!" : "Fehler beim Löschen.";
                break;
        }
    }
}

// Get all services
$services = $db->query("SELECT * FROM services ORDER BY `order`, title")->fetchAll();

$pageTitle = 'Leistungen verwalten - Admin';
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
                <h1 class="text-2xl font-bold text-gray-900">Leistungen verwalten</h1>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-blue-600 hover:text-blue-800">← Zurück zum Dashboard</a>
                    <span class="text-sm text-gray-500"><?php echo htmlspecialchars($current_admin['username']); ?></span>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <?php if (isset($message)): ?>
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <!-- Add New Service -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Neuen Service hinzufügen</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titel *</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Service-Titel">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon *</label>
                        <select name="icon" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Shield">Shield (Schild)</option>
                            <option value="Search">Search (Suche)</option>
                            <option value="Users">Users (Benutzer)</option>
                            <option value="Car">Car (Auto)</option>
                            <option value="Phone">Phone (Telefon)</option>
                            <option value="Mail">Mail (E-Mail)</option>
                            <option value="Clock">Clock (Uhr)</option>
                            <option value="Settings">Settings (Einstellungen)</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung *</label>
                    <textarea name="description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Service-Beschreibung"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reihenfolge</label>
                        <input type="number" name="order" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center pt-6">
                        <input type="checkbox" name="active" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Aktiv</span>
                    </div>
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Service hinzufügen
                </button>
            </form>
        </div>

        <!-- Services List -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Alle Services</h2>
            
            <?php if (empty($services)): ?>
            <p class="text-gray-500 text-center py-8">Keine Services vorhanden.</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($services as $service): ?>
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-<?php 
                                    echo $service['icon'] === 'Shield' ? 'shield-alt' : 
                                         ($service['icon'] === 'Search' ? 'search' : 
                                         ($service['icon'] === 'Users' ? 'users' : 
                                         ($service['icon'] === 'Car' ? 'car' : strtolower($service['icon'])))); 
                                ?> text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($service['description']); ?></p>
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span>Reihenfolge: <?php echo $service['order']; ?></span>
                                    <span><?php echo $service['active'] ? 'Aktiv' : 'Inaktiv'; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editService('<?php echo $service['id']; ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-edit"></i> Bearbeiten
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Service wirklich löschen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash"></i> Löschen
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Edit Form (hidden by default) -->
                    <div id="edit-<?php echo $service['id']; ?>" class="hidden mt-4 pt-4 border-t">
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Titel</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($service['title']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                                    <select name="icon" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="Shield" <?php echo $service['icon'] === 'Shield' ? 'selected' : ''; ?>>Shield</option>
                                        <option value="Search" <?php echo $service['icon'] === 'Search' ? 'selected' : ''; ?>>Search</option>
                                        <option value="Users" <?php echo $service['icon'] === 'Users' ? 'selected' : ''; ?>>Users</option>
                                        <option value="Car" <?php echo $service['icon'] === 'Car' ? 'selected' : ''; ?>>Car</option>
                                        <option value="Phone" <?php echo $service['icon'] === 'Phone' ? 'selected' : ''; ?>>Phone</option>
                                        <option value="Mail" <?php echo $service['icon'] === 'Mail' ? 'selected' : ''; ?>>Mail</option>
                                        <option value="Clock" <?php echo $service['icon'] === 'Clock' ? 'selected' : ''; ?>>Clock</option>
                                        <option value="Settings" <?php echo $service['icon'] === 'Settings' ? 'selected' : ''; ?>>Settings</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                                <textarea name="description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($service['description']); ?></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Reihenfolge</label>
                                    <input type="number" name="order" value="<?php echo $service['order']; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="flex items-center pt-6">
                                    <input type="checkbox" name="active" <?php echo $service['active'] ? 'checked' : ''; ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Aktiv</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    <i class="fas fa-save mr-2"></i>Speichern
                                </button>
                                <button type="button" onclick="cancelEdit('<?php echo $service['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                    Abbrechen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function editService(id) {
        document.getElementById('edit-' + id).classList.remove('hidden');
    }
    
    function cancelEdit(id) {
        document.getElementById('edit-' + id).classList.add('hidden');
    }
    </script>
    
</body>
</html>