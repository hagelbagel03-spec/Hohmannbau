<?php
/**
 * Admin Applications Management
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
            case 'update_status':
                $stmt = $db->prepare("UPDATE applications SET status = ?, admin_response = ?, admin_email = ? WHERE id = ?");
                $result = $stmt->execute([
                    sanitizeInput($_POST['status']),
                    sanitizeInput($_POST['admin_response']),
                    $current_admin['email'],
                    sanitizeInput($_POST['id'])
                ]);
                $message = $result ? "Bewerbung erfolgreich aktualisiert!" : "Fehler beim Aktualisieren.";
                break;
                
            case 'delete':
                $stmt = $db->prepare("DELETE FROM applications WHERE id = ?");
                $result = $stmt->execute([sanitizeInput($_POST['id'])]);
                $message = $result ? "Bewerbung erfolgreich gelöscht!" : "Fehler beim Löschen.";
                break;
        }
    }
}

// Get all applications
$applications = $db->query("SELECT * FROM applications ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Bewerbungen verwalten - Admin';
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
                <h1 class="text-2xl font-bold text-gray-900">Bewerbungen verwalten</h1>
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

        <!-- Applications List -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Alle Bewerbungen</h2>
            
            <?php if (empty($applications)): ?>
            <p class="text-gray-500 text-center py-8">Keine Bewerbungen vorhanden.</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($applications as $application): ?>
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($application['name']); ?></h3>
                                <span class="bg-<?php echo $application['status'] === 'pending' ? 'yellow' : ($application['status'] === 'accepted' ? 'green' : ($application['status'] === 'reviewed' ? 'blue' : 'red')); ?>-100 text-<?php echo $application['status'] === 'pending' ? 'yellow' : ($application['status'] === 'accepted' ? 'green' : ($application['status'] === 'reviewed' ? 'blue' : 'red')); ?>-800 text-xs px-2 py-1 rounded-full">
                                    <?php echo $application['status'] === 'pending' ? 'Ausstehend' : ($application['status'] === 'reviewed' ? 'Geprüft' : ($application['status'] === 'accepted' ? 'Angenommen' : 'Abgelehnt')); ?>
                                </span>
                            </div>
                            <p class="text-blue-600 font-medium mb-2"><?php echo htmlspecialchars($application['position']); ?></p>
                            <p class="text-gray-600 mb-2"><strong>E-Mail:</strong> <?php echo htmlspecialchars($application['email']); ?></p>
                            <p class="text-gray-600 mb-2"><strong>Telefon:</strong> <?php echo htmlspecialchars($application['phone']); ?></p>
                            <p class="text-gray-700 mb-2"><?php echo htmlspecialchars(substr($application['message'], 0, 200)) . '...'; ?></p>
                            <?php if ($application['cv_filename']): ?>
                            <p class="text-sm text-green-600 mb-2">
                                <i class="fas fa-file-pdf mr-1"></i>
                                <a href="../uploads/<?php echo $application['cv_filename']; ?>" target="_blank">CV herunterladen</a>
                            </p>
                            <?php endif; ?>
                            <div class="text-sm text-gray-500">
                                <p><strong>Eingegangen:</strong> <?php echo date('d.m.Y H:i', strtotime($application['created_at'])); ?></p>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="editApplication('<?php echo $application['id']; ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-edit"></i> Bearbeiten
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Bewerbung wirklich löschen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $application['id']; ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash"></i> Löschen
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Edit Form (hidden by default) -->
                    <div id="edit-<?php echo $application['id']; ?>" class="hidden mt-4 pt-4 border-t">
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?php echo $application['id']; ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="pending" <?php echo $application['status'] === 'pending' ? 'selected' : ''; ?>>Ausstehend</option>
                                    <option value="reviewed" <?php echo $application['status'] === 'reviewed' ? 'selected' : ''; ?>>Geprüft</option>
                                    <option value="accepted" <?php echo $application['status'] === 'accepted' ? 'selected' : ''; ?>>Angenommen</option>
                                    <option value="rejected" <?php echo $application['status'] === 'rejected' ? 'selected' : ''; ?>>Abgelehnt</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Antwort an Bewerber</label>
                                <textarea name="admin_response" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Antwort oder Feedback für den Bewerber..."><?php echo htmlspecialchars($application['admin_response']); ?></textarea>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    <i class="fas fa-save mr-2"></i>Speichern
                                </button>
                                <button type="button" onclick="cancelEdit('<?php echo $application['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
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
    function editApplication(id) {
        document.getElementById('edit-' + id).classList.remove('hidden');
    }
    
    function cancelEdit(id) {
        document.getElementById('edit-' + id).classList.add('hidden');
    }
    </script>
    
</body>
</html>