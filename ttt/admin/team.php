<?php
/**
 * Admin Team Management
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
            case 'create':
                $stmt = $db->prepare("INSERT INTO team (id, name, position, description, email, phone, `order`, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    generateUUID(),
                    sanitizeInput($_POST['name']),
                    sanitizeInput($_POST['position']),
                    sanitizeInput($_POST['description']),
                    sanitizeInput($_POST['email']),
                    sanitizeInput($_POST['phone']),
                    intval($_POST['order']),
                    isset($_POST['active']) ? 1 : 0
                ]);
                $message = $result ? "Team-Mitglied erfolgreich erstellt!" : "Fehler beim Erstellen.";
                break;
                
            case 'update':
                $stmt = $db->prepare("UPDATE team SET name = ?, position = ?, description = ?, email = ?, phone = ?, `order` = ?, active = ? WHERE id = ?");
                $result = $stmt->execute([
                    sanitizeInput($_POST['name']),
                    sanitizeInput($_POST['position']),
                    sanitizeInput($_POST['description']),
                    sanitizeInput($_POST['email']),
                    sanitizeInput($_POST['phone']),
                    intval($_POST['order']),
                    isset($_POST['active']) ? 1 : 0,
                    sanitizeInput($_POST['id'])
                ]);
                $message = $result ? "Team-Mitglied erfolgreich aktualisiert!" : "Fehler beim Aktualisieren.";
                break;
                
            case 'delete':
                $stmt = $db->prepare("DELETE FROM team WHERE id = ?");
                $result = $stmt->execute([sanitizeInput($_POST['id'])]);
                $message = $result ? "Team-Mitglied erfolgreich gelöscht!" : "Fehler beim Löschen.";
                break;
        }
    }
}

// Get all team members
$team = $db->query("SELECT * FROM team ORDER BY `order`, name")->fetchAll();

$pageTitle = 'Gartenbau-Team verwalten - Admin';
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
                <h1 class="text-2xl font-bold text-gray-900">Gartenbau-Team verwalten</h1>
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

        <!-- Add New Team Member -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Neues Team-Mitglied hinzufügen</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Vollständiger Name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                        <input type="text" name="position" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Stellenbezeichnung">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Kurze Beschreibung der Person"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">E-Mail</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="email@stadtwache.de">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                        <input type="tel" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="+49 123 456-789">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reihenfolge</label>
                        <input type="number" name="order" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="active" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Aktiv</span>
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-user-plus mr-2"></i>Team-Mitglied hinzufügen
                </button>
            </form>
        </div>

        <!-- Team Members List -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Alle Team-Mitglieder</h2>
            
            <?php if (empty($team)): ?>
            <p class="text-gray-500 text-center py-8">Keine Team-Mitglieder vorhanden.</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($team as $member): ?>
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($member['name']); ?></h3>
                                <p class="text-blue-600 font-medium"><?php echo htmlspecialchars($member['position']); ?></p>
                                <?php if ($member['description']): ?>
                                <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($member['description']); ?></p>
                                <?php endif; ?>
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <?php if ($member['email']): ?>
                                    <span><i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($member['email']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($member['phone']): ?>
                                    <span><i class="fas fa-phone mr-1"></i><?php echo htmlspecialchars($member['phone']); ?></span>
                                    <?php endif; ?>
                                    <span>Reihenfolge: <?php echo $member['order']; ?></span>
                                    <span><?php echo $member['active'] ? 'Aktiv' : 'Inaktiv'; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editMember('<?php echo $member['id']; ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-edit"></i> Bearbeiten
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Team-Mitglied wirklich löschen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash"></i> Löschen
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Edit Form (hidden by default) -->
                    <div id="edit-<?php echo $member['id']; ?>" class="hidden mt-4 pt-4 border-t">
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                    <input type="text" name="position" value="<?php echo htmlspecialchars($member['position']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($member['description']); ?></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">E-Mail</label>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Reihenfolge</label>
                                    <input type="number" name="order" value="<?php echo $member['order']; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="active" <?php echo $member['active'] ? 'checked' : ''; ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Aktiv</span>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    <i class="fas fa-save mr-2"></i>Speichern
                                </button>
                                <button type="button" onclick="cancelEdit('<?php echo $member['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
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
    function editMember(id) {
        document.getElementById('edit-' + id).classList.remove('hidden');
    }
    
    function cancelEdit(id) {
        document.getElementById('edit-' + id).classList.add('hidden');
    }
    </script>
    
</body>
</html>