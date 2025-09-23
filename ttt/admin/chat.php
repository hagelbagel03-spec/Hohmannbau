<?php
/**
 * Admin Chat Messages Management
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
            case 'respond':
                $stmt = $db->prepare("UPDATE chat_messages SET status = 'responded', admin_response = ?, responded_at = NOW() WHERE id = ?");
                $result = $stmt->execute([
                    sanitizeInput($_POST['admin_response']),
                    sanitizeInput($_POST['id'])
                ]);
                $message = $result ? "Antwort erfolgreich gesendet!" : "Fehler beim Senden.";
                break;
                
            case 'close':
                $stmt = $db->prepare("UPDATE chat_messages SET status = 'closed' WHERE id = ?");
                $result = $stmt->execute([sanitizeInput($_POST['id'])]);
                $message = $result ? "Chat erfolgreich geschlossen!" : "Fehler beim Schließen.";
                break;
                
            case 'delete':
                $stmt = $db->prepare("DELETE FROM chat_messages WHERE id = ?");
                $result = $stmt->execute([sanitizeInput($_POST['id'])]);
                $message = $result ? "Chat-Nachricht erfolgreich gelöscht!" : "Fehler beim Löschen.";
                break;
        }
    }
}

// Get all chat messages
$chats = $db->query("SELECT * FROM chat_messages ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Kundenanfragen verwalten - Admin';
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
                <h1 class="text-2xl font-bold text-gray-900">Kundenanfragen verwalten</h1>
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

        <!-- Chat Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <?php
            $total_chats = count($chats);
            $new_chats = count(array_filter($chats, function($c) { return $c['status'] === 'new'; }));
            $responded_chats = count(array_filter($chats, function($c) { return $c['status'] === 'responded'; }));
            ?>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-comments text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Gesamt</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $total_chats; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-red-100 rounded-full p-3 mr-4">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Neue</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $new_chats; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Beantwortet</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $responded_chats; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Messages List -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Alle Chat-Nachrichten</h2>
            
            <?php if (empty($chats)): ?>
            <p class="text-gray-500 text-center py-8">Keine Chat-Nachrichten vorhanden.</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($chats as $chat): ?>
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($chat['visitor_name']); ?></h3>
                                <span class="bg-<?php echo $chat['status'] === 'new' ? 'red' : ($chat['status'] === 'responded' ? 'green' : 'gray'); ?>-100 text-<?php echo $chat['status'] === 'new' ? 'red' : ($chat['status'] === 'responded' ? 'green' : 'gray'); ?>-800 text-xs px-2 py-1 rounded-full">
                                    <?php echo $chat['status'] === 'new' ? 'Neu' : ($chat['status'] === 'responded' ? 'Beantwortet' : 'Geschlossen'); ?>
                                </span>
                            </div>
                            <p class="text-gray-600 mb-2"><strong>E-Mail:</strong> <?php echo htmlspecialchars($chat['visitor_email']); ?></p>
                            <div class="bg-gray-50 p-3 rounded mb-3">
                                <p class="text-sm font-medium text-gray-700 mb-1">Nachricht:</p>
                                <p class="text-gray-800"><?php echo htmlspecialchars($chat['message']); ?></p>
                            </div>
                            
                            <?php if ($chat['admin_response']): ?>
                            <div class="bg-blue-50 p-3 rounded mb-3">
                                <p class="text-sm font-medium text-blue-900 mb-1">Ihre Antwort:</p>
                                <p class="text-blue-800"><?php echo htmlspecialchars($chat['admin_response']); ?></p>
                                <?php if ($chat['responded_at']): ?>
                                <p class="text-xs text-blue-600 mt-1">Geantwortet: <?php echo date('d.m.Y H:i', strtotime($chat['responded_at'])); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="text-sm text-gray-500">
                                <p><strong>Eingegangen:</strong> <?php echo date('d.m.Y H:i', strtotime($chat['created_at'])); ?></p>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <?php if ($chat['status'] !== 'closed'): ?>
                            <button onclick="respondToChat('<?php echo $chat['id']; ?>')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-reply"></i> Antworten
                            </button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="close">
                                <input type="hidden" name="id" value="<?php echo $chat['id']; ?>">
                                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-times"></i> Schließen
                                </button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Chat-Nachricht wirklich löschen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $chat['id']; ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash"></i> Löschen
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Response Form (hidden by default) -->
                    <div id="respond-<?php echo $chat['id']; ?>" class="hidden mt-4 pt-4 border-t">
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="respond">
                            <input type="hidden" name="id" value="<?php echo $chat['id']; ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Antwort an <?php echo htmlspecialchars($chat['visitor_name']); ?></label>
                                <textarea name="admin_response" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ihre Antwort auf die Chat-Nachricht..."><?php echo htmlspecialchars($chat['admin_response']); ?></textarea>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    <i class="fas fa-paper-plane mr-2"></i>Antwort senden
                                </button>
                                <button type="button" onclick="cancelRespond('<?php echo $chat['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
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
    function respondToChat(id) {
        document.getElementById('respond-' + id).classList.remove('hidden');
    }
    
    function cancelRespond(id) {
        document.getElementById('respond-' + id).classList.add('hidden');
    }
    </script>
    
</body>
</html>