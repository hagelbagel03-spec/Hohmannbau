<?php
/**
 * Admin Feedback Management
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
                $stmt = $db->prepare("UPDATE feedback SET status = ?, admin_response = ? WHERE id = ?");
                $result = $stmt->execute([
                    sanitizeInput($_POST['status']),
                    sanitizeInput($_POST['admin_response']),
                    sanitizeInput($_POST['id'])
                ]);
                $message = $result ? "Feedback erfolgreich aktualisiert!" : "Fehler beim Aktualisieren.";
                break;
                
            case 'delete':
                $stmt = $db->prepare("DELETE FROM feedback WHERE id = ?");
                $result = $stmt->execute([sanitizeInput($_POST['id'])]);
                $message = $result ? "Feedback erfolgreich gelöscht!" : "Fehler beim Löschen.";
                break;
        }
    }
}

// Get all feedback
$feedback = $db->query("SELECT * FROM feedback ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Kundenbewertungen verwalten - Admin';
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
                <h1 class="text-2xl font-bold text-gray-900">Kundenbewertungen verwalten</h1>
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

        <!-- Feedback Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <?php
            $total_feedback = count($feedback);
            $new_feedback = count(array_filter($feedback, function($f) { return $f['status'] === 'new'; }));
            $avg_rating = $total_feedback > 0 ? array_sum(array_column($feedback, 'rating')) / $total_feedback : 0;
            $five_star = count(array_filter($feedback, function($f) { return $f['rating'] == 5; }));
            ?>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-comments text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Gesamt</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $total_feedback; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-full p-3 mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Neu</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $new_feedback; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-star text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ø Bewertung</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo number_format($avg_rating, 1); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-full p-3 mr-4">
                        <i class="fas fa-heart text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">5-Sterne</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $five_star; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Alle Feedback-Einträge</h2>
            
            <?php if (empty($feedback)): ?>
            <p class="text-gray-500 text-center py-8">Kein Feedback vorhanden.</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($feedback as $fb): ?>
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($fb['name']); ?></h3>
                                <div class="flex text-yellow-400">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo $i <= $fb['rating'] ? '' : ' text-gray-300'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="bg-<?php echo $fb['status'] === 'new' ? 'yellow' : 'green'; ?>-100 text-<?php echo $fb['status'] === 'new' ? 'yellow' : 'green'; ?>-800 text-xs px-2 py-1 rounded-full">
                                    <?php echo $fb['status'] === 'new' ? 'Neu' : 'Beantwortet'; ?>
                                </span>
                            </div>
                            <p class="text-blue-600 font-medium mb-2"><?php echo htmlspecialchars($fb['subject']); ?></p>
                            <p class="text-gray-600 mb-2"><strong>E-Mail:</strong> <?php echo htmlspecialchars($fb['email']); ?></p>
                            <p class="text-gray-700 mb-2"><?php echo htmlspecialchars($fb['message']); ?></p>
                            
                            <?php if ($fb['admin_response']): ?>
                            <div class="bg-blue-50 p-3 rounded mt-3">
                                <p class="text-sm font-medium text-blue-900">Admin-Antwort:</p>
                                <p class="text-blue-800"><?php echo htmlspecialchars($fb['admin_response']); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="text-sm text-gray-500 mt-2">
                                <p><strong>Eingegangen:</strong> <?php echo date('d.m.Y H:i', strtotime($fb['created_at'])); ?></p>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="editFeedback('<?php echo $fb['id']; ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-reply"></i> Antworten
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Feedback wirklich löschen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $fb['id']; ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash"></i> Löschen
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Edit Form (hidden by default) -->
                    <div id="edit-<?php echo $fb['id']; ?>" class="hidden mt-4 pt-4 border-t">
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?php echo $fb['id']; ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="new" <?php echo $fb['status'] === 'new' ? 'selected' : ''; ?>>Neu</option>
                                    <option value="reviewed" <?php echo $fb['status'] === 'reviewed' ? 'selected' : ''; ?>>Beantwortet</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Antwort an Benutzer</label>
                                <textarea name="admin_response" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ihre Antwort auf das Feedback..."><?php echo htmlspecialchars($fb['admin_response']); ?></textarea>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    <i class="fas fa-save mr-2"></i>Speichern
                                </button>
                                <button type="button" onclick="cancelEdit('<?php echo $fb['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
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
    function editFeedback(id) {
        document.getElementById('edit-' + id).classList.remove('hidden');
    }
    
    function cancelEdit(id) {
        document.getElementById('edit-' + id).classList.add('hidden');
    }
    </script>
    
</body>
</html>