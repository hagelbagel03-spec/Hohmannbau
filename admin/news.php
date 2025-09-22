<?php
/**
 * Admin News Management
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
                $stmt = $db->prepare("INSERT INTO news (id, title, content, excerpt, priority, published) VALUES (?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    generateUUID(),
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['content']),
                    sanitizeInput($_POST['excerpt']),
                    sanitizeInput($_POST['priority']),
                    isset($_POST['published']) ? 1 : 0
                ]);
                $message = $result ? "Nachricht erfolgreich erstellt!" : "Fehler beim Erstellen.";
                break;
                
            case 'update':
                $stmt = $db->prepare("UPDATE news SET title = ?, content = ?, excerpt = ?, priority = ?, published = ? WHERE id = ?");
                $result = $stmt->execute([
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['content']),
                    sanitizeInput($_POST['excerpt']),
                    sanitizeInput($_POST['priority']),
                    isset($_POST['published']) ? 1 : 0,
                    sanitizeInput($_POST['id'])
                ]);
                $message = $result ? "Nachricht erfolgreich aktualisiert!" : "Fehler beim Aktualisieren.";
                break;
                
            case 'delete':
                $stmt = $db->prepare("DELETE FROM news WHERE id = ?");
                $result = $stmt->execute([sanitizeInput($_POST['id'])]);
                $message = $result ? "Nachricht erfolgreich gelöscht!" : "Fehler beim Löschen.";
                break;
        }
    }
}

// Get all news
$news = $db->query("SELECT * FROM news ORDER BY date DESC")->fetchAll();

$pageTitle = 'Garten-News verwalten - Admin';
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
                <h1 class="text-2xl font-bold text-gray-900">Garten-News verwalten</h1>
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

        <!-- Add New News -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Neue Nachricht hinzufügen</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titel *</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nachrichtentitel">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kurze Zusammenfassung</label>
                    <textarea name="excerpt" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Kurze Zusammenfassung für die Übersicht..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bild hochladen (optional)</label>
                    <input type="file" name="news_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-sm text-gray-500 mt-1">Unterstützte Formate: JPG, PNG, GIF (max. 5MB)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vollständiger Inhalt *</label>
                    <textarea name="content" rows="8" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Vollständiger Nachrichteninhalt..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priorität</label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="normal">Normal</option>
                            <option value="high">Wichtig</option>
                            <option value="urgent">Eilmeldung</option>
                        </select>
                    </div>
                    <div class="flex items-center pt-6">
                        <input type="checkbox" name="published" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Veröffentlicht</span>
                    </div>
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Nachricht hinzufügen
                </button>
            </form>
        </div>

        <!-- News List -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Alle Nachrichten</h2>
            
            <?php if (empty($news)): ?>
            <p class="text-gray-500 text-center py-8">Keine Nachrichten vorhanden.</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($news as $article): ?>
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($article['title']); ?></h3>
                                <span class="bg-<?php echo $article['priority'] === 'urgent' ? 'red' : ($article['priority'] === 'high' ? 'orange' : 'blue'); ?>-100 text-<?php echo $article['priority'] === 'urgent' ? 'red' : ($article['priority'] === 'high' ? 'orange' : 'blue'); ?>-800 text-xs px-2 py-1 rounded-full">
                                    <?php echo $article['priority'] === 'urgent' ? 'Eilmeldung' : ($article['priority'] === 'high' ? 'Wichtig' : 'Normal'); ?>
                                </span>
                                <?php if (!$article['published']): ?>
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Entwurf</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($article['excerpt']): ?>
                            <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            <?php endif; ?>
                            
                            <p class="text-gray-700 mb-2"><?php echo htmlspecialchars(substr($article['content'], 0, 200)) . '...'; ?></p>
                            
                            <div class="text-sm text-gray-500">
                                <p><strong>Veröffentlicht:</strong> <?php echo date('d.m.Y H:i', strtotime($article['date'])); ?></p>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="editNews('<?php echo $article['id']; ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-edit"></i> Bearbeiten
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Nachricht wirklich löschen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash"></i> Löschen
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Edit Form (hidden by default) -->
                    <div id="edit-<?php echo $article['id']; ?>" class="hidden mt-4 pt-4 border-t">
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titel</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kurze Zusammenfassung</label>
                                <textarea name="excerpt" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($article['excerpt']); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vollständiger Inhalt</label>
                                <textarea name="content" rows="8" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($article['content']); ?></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Priorität</label>
                                    <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="normal" <?php echo $article['priority'] === 'normal' ? 'selected' : ''; ?>>Normal</option>
                                        <option value="high" <?php echo $article['priority'] === 'high' ? 'selected' : ''; ?>>Wichtig</option>
                                        <option value="urgent" <?php echo $article['priority'] === 'urgent' ? 'selected' : ''; ?>>Eilmeldung</option>
                                    </select>
                                </div>
                                <div class="flex items-center pt-6">
                                    <input type="checkbox" name="published" <?php echo $article['published'] ? 'checked' : ''; ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Veröffentlicht</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    <i class="fas fa-save mr-2"></i>Speichern
                                </button>
                                <button type="button" onclick="cancelEdit('<?php echo $article['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
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
    function editNews(id) {
        document.getElementById('edit-' + id).classList.remove('hidden');
    }
    
    function cancelEdit(id) {
        document.getElementById('edit-' + id).classList.add('hidden');
    }
    </script>
    
</body>
</html>