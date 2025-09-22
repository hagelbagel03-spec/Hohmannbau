<?php
/**
 * Admin Jobs Management (Karriere)
 */

require_once '../config/database.php';
require_once '../config/auth.php';
require_once 'includes/functions.php';

requireAuth();

// Windows Apache Kompatibilität - Fehlerbehandlung
try {
    $db = getDB();
} catch (Exception $e) {
    die("Datenbankfehler: " . $e->getMessage() . "<br><a href='test_db.php'>Datenbank testen</a>");
}
$current_admin = getCurrentAdmin();

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $stmt = $db->prepare("INSERT INTO jobs (id, title, department, type, location, description, requirements, benefits, salary_range, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    'job-' . uniqid(),
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['department']),
                    sanitizeInput($_POST['type']),
                    sanitizeInput($_POST['location']),
                    sanitizeInput($_POST['description']),
                    sanitizeInput($_POST['requirements']),
                    sanitizeInput($_POST['benefits']),
                    sanitizeInput($_POST['salary_range']),
                    isset($_POST['active']) ? 1 : 0
                ]);
                $message = $result ? "Stellenausschreibung erfolgreich erstellt!" : "Fehler beim Erstellen.";
                break;
                
            case 'update':
                $stmt = $db->prepare("UPDATE jobs SET title = ?, department = ?, type = ?, location = ?, description = ?, requirements = ?, benefits = ?, salary_range = ?, active = ? WHERE id = ?");
                $result = $stmt->execute([
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['department']),
                    sanitizeInput($_POST['type']),
                    sanitizeInput($_POST['location']),
                    sanitizeInput($_POST['description']),
                    sanitizeInput($_POST['requirements']),
                    sanitizeInput($_POST['benefits']),
                    sanitizeInput($_POST['salary_range']),
                    isset($_POST['active']) ? 1 : 0,
                    sanitizeInput($_POST['id'])
                ]);
                $message = $result ? "Stellenausschreibung erfolgreich aktualisiert!" : "Fehler beim Aktualisieren.";
                break;
                
            case 'delete':
                $stmt = $db->prepare("DELETE FROM jobs WHERE id = ?");
                $result = $stmt->execute([sanitizeInput($_POST['id'])]);
                $message = $result ? "Stellenausschreibung erfolgreich gelöscht!" : "Fehler beim Löschen.";
                break;
        }
    }
}

// Get all jobs
$jobs = $db->query("SELECT * FROM jobs ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Gartenbau-Stellen verwalten - Admin';
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
                <h1 class="text-2xl font-bold text-gray-900">Karriere/Stellenausschreibungen verwalten</h1>
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

        <!-- Add New Job -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Neue Stellenausschreibung hinzufügen</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stellentitel *</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="z.B. Polizeibeamter/in (m/w/d)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Abteilung *</label>
                        <input type="text" name="department" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="z.B. Streifendienst">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Beschäftigungsart *</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="full-time">Vollzeit</option>
                            <option value="part-time">Teilzeit</option>
                            <option value="contract">Befristet</option>
                            <option value="intern">Praktikum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Standort</label>
                        <input type="text" name="location" value="Musterstadt" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gehaltsrahmen</label>
                        <input type="text" name="salary_range" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="z.B. 35.000 - 45.000 €">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stellenbeschreibung *</label>
                    <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Beschreibung der Stelle und Aufgaben..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Anforderungen</label>
                    <textarea name="requirements" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Qualifikationen und Anforderungen..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Benefits/Vorteile</label>
                    <textarea name="benefits" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Vorteile und Benefits für Mitarbeiter..."></textarea>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="active" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Aktiv (für Bewerber sichtbar)</span>
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Stellenausschreibung hinzufügen
                </button>
            </form>
        </div>

        <!-- Jobs List -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Alle Stellenausschreibungen</h2>
            
            <?php if (empty($jobs)): ?>
            <p class="text-gray-500 text-center py-8">Keine Stellenausschreibungen vorhanden.</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($jobs as $job): ?>
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($job['title']); ?></h3>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    <?php 
                                    echo $job['type'] === 'full-time' ? 'Vollzeit' : 
                                         ($job['type'] === 'part-time' ? 'Teilzeit' : 
                                         ($job['type'] === 'contract' ? 'Befristet' : 'Praktikum')); 
                                    ?>
                                </span>
                                <?php if (!$job['active']): ?>
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Inaktiv</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-blue-600 font-medium"><?php echo htmlspecialchars($job['department']); ?></p>
                            <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars(substr($job['description'], 0, 120)) . '...'; ?></p>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                <span><i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($job['location']); ?></span>
                                <?php if ($job['salary_range']): ?>
                                <span><i class="fas fa-euro-sign mr-1"></i><?php echo htmlspecialchars($job['salary_range']); ?></span>
                                <?php endif; ?>
                                <span><i class="fas fa-calendar mr-1"></i><?php echo date('d.m.Y', strtotime($job['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editJob('<?php echo $job['id']; ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-edit"></i> Bearbeiten
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Stellenausschreibung wirklich löschen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $job['id']; ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash"></i> Löschen
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Edit Form (hidden by default) -->
                    <div id="edit-<?php echo $job['id']; ?>" class="hidden mt-4 pt-4 border-t">
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $job['id']; ?>">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stellentitel</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Abteilung</label>
                                    <input type="text" name="department" value="<?php echo htmlspecialchars($job['department']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Beschäftigungsart</label>
                                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="full-time" <?php echo $job['type'] === 'full-time' ? 'selected' : ''; ?>>Vollzeit</option>
                                        <option value="part-time" <?php echo $job['type'] === 'part-time' ? 'selected' : ''; ?>>Teilzeit</option>
                                        <option value="contract" <?php echo $job['type'] === 'contract' ? 'selected' : ''; ?>>Befristet</option>
                                        <option value="intern" <?php echo $job['type'] === 'intern' ? 'selected' : ''; ?>>Praktikum</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Standort</label>
                                    <input type="text" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gehaltsrahmen</label>
                                    <input type="text" name="salary_range" value="<?php echo htmlspecialchars($job['salary_range']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stellenbeschreibung</label>
                                <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($job['description']); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Anforderungen</label>
                                <textarea name="requirements" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($job['requirements']); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Benefits/Vorteile</label>
                                <textarea name="benefits" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($job['benefits']); ?></textarea>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="active" <?php echo $job['active'] ? 'checked' : ''; ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Aktiv (für Bewerber sichtbar)</span>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    <i class="fas fa-save mr-2"></i>Speichern
                                </button>
                                <button type="button" onclick="cancelEdit('<?php echo $job['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
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
    function editJob(id) {
        document.getElementById('edit-' + id).classList.remove('hidden');
    }
    
    function cancelEdit(id) {
        document.getElementById('edit-' + id).classList.add('hidden');
    }
    </script>
    
</body>
</html>