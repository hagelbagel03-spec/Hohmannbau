<?php
/**
 * Admin Dashboard
 * Main admin panel with overview and management features
 */

require_once '../config/database.php';
require_once '../config/auth.php';

requireAuth();

$db = getDB();

// Get statistics
// Safe statistics with error handling
$stats = [
    'reports' => [
        'total' => 0,
        'new' => 0,
        'urgent' => 0
    ],
    'applications' => [
        'total' => 0,
        'pending' => 0
    ],
    'feedback' => [
        'total' => 0,
        'new' => 0
    ],
    'chat' => [
        'total' => 0,
        'new' => 0
    ]
];

// Try to get statistics, but handle missing tables gracefully
try {
    $stats['reports']['total'] = $db->query("SELECT COUNT(*) as count FROM reports")->fetch()['count'];
    $stats['reports']['new'] = $db->query("SELECT COUNT(*) as count FROM reports WHERE status = 'new'")->fetch()['count'];
    $stats['reports']['urgent'] = $db->query("SELECT COUNT(*) as count FROM reports WHERE priority = 'urgent'")->fetch()['count'];
} catch (Exception $e) {
    // Table doesn't exist, keep defaults
}

try {
    $stats['applications']['total'] = $db->query("SELECT COUNT(*) as count FROM applications")->fetch()['count'];
    $stats['applications']['pending'] = $db->query("SELECT COUNT(*) as count FROM applications WHERE status = 'pending'")->fetch()['count'];
} catch (Exception $e) {
    // Table doesn't exist, keep defaults
}

try {
    $stats['feedback']['total'] = $db->query("SELECT COUNT(*) as count FROM feedback")->fetch()['count'];
    $stats['feedback']['new'] = $db->query("SELECT COUNT(*) as count FROM feedback WHERE status = 'new'")->fetch()['count'];
} catch (Exception $e) {
    // Table doesn't exist, keep defaults
}

try {
    $stats['chat']['total'] = $db->query("SELECT COUNT(*) as count FROM chat_messages")->fetch()['count'];
    $stats['chat']['new'] = $db->query("SELECT COUNT(*) as count FROM chat_messages WHERE status = 'new'")->fetch()['count'];
} catch (Exception $e) {
    // Table doesn't exist, keep defaults
}

// Get recent activities with error handling
$recent_reports = [];
$recent_applications = [];
$recent_feedback = [];

try {
    $recent_reports = $db->query("SELECT * FROM reports ORDER BY created_at DESC LIMIT 5")->fetchAll();
} catch (Exception $e) {
    $recent_reports = [];
}

try {
    $recent_applications = $db->query("SELECT * FROM applications ORDER BY created_at DESC LIMIT 5")->fetchAll();
} catch (Exception $e) {
    $recent_applications = [];
}

try {
    $recent_feedback = $db->query("SELECT * FROM feedback ORDER BY created_at DESC LIMIT 5")->fetchAll();
} catch (Exception $e) {
    $recent_feedback = [];
}

$pageTitle = 'Dashboard';
$pageSubtitle = 'Übersicht und Verwaltung';
include 'includes/header.php';
include 'includes/sidebar.php';
?>
            <!-- Main Content Area -->
            <div class="p-8">
                <div class="max-w-7xl mx-auto">
                    
                    <!-- Welcome Section -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
                        <p class="text-gray-600">Willkommen im Admin-Bereich von Hohmann Bau</p>
                    </div>

                    <!-- Website-Management Tools -->
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-tools text-green-600 mr-3"></i>
                            Website-Management
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            
                            <!-- Text Editor -->
                            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-green-100 rounded-full p-3">
                                        <i class="fas fa-edit text-green-600 text-xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-gray-900">📝</div>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Text Editor</h3>
                                <p class="text-gray-600 text-sm mb-4">Alle Texte der Website bearbeiten</p>
                                <a href="text_editor.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium block text-center">
                                    Öffnen →
                                </a>
                            </div>
                            
                            <!-- Bilder Manager -->
                            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-blue-100 rounded-full p-3">
                                        <i class="fas fa-images text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-gray-900">🖼️</div>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Bilder Manager</h3>
                                <p class="text-gray-600 text-sm mb-4">Upload & Bildverwaltung</p>
                                <a href="image_manager.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium block text-center">
                                    Öffnen →
                                </a>
                            </div>
                            
                            <!-- Seiten Editor -->
                            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-purple-100 rounded-full p-3">
                                        <i class="fas fa-palette text-purple-600 text-xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-gray-900">🎨</div>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Seiten Editor</h3>
                                <p class="text-gray-600 text-sm mb-4">Farben & Layout</p>
                                <a href="page_editor.php" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium block text-center">
                                    Öffnen →
                                </a>
                            </div>
                            
                            <!-- Einfacher Upload -->
                            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-orange-100 rounded-full p-3">
                                        <i class="fas fa-upload text-orange-600 text-xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-gray-900">📤</div>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Einfacher Upload</h3>
                                <p class="text-gray-600 text-sm mb-4">Bilder direkt hochladen</p>
                                <a href="simple_upload.php" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium block text-center">
                                    Öffnen →
                                </a>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Statistiken -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">📊 Übersicht</h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    
                    <a href="services.php" class="bg-white rounded-xl shadow-lg p-6 card-hover block hover:no-underline">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Leistungen verwalten</h3>
                                <p class="text-gray-600 text-sm">Services bearbeiten</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-leaf text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </a>
                    
                    <a href="team.php" class="bg-white rounded-xl shadow-lg p-6 card-hover block hover:no-underline">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Team verwalten</h3>
                                <p class="text-gray-600 text-sm">Mitarbeiter bearbeiten</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </a>
                    
                    <a href="jobs.php" class="bg-white rounded-xl shadow-lg p-6 card-hover block hover:no-underline">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Karriere verwalten</h3>
                                <p class="text-gray-600 text-sm">Jobs verwalten</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <i class="fas fa-briefcase text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </a>
                    
                    <a href="news.php" class="bg-white rounded-xl shadow-lg p-6 card-hover block hover:no-underline">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">News verwalten</h3>
                                <p class="text-gray-600 text-sm">Nachrichten bearbeiten</p>
                            </div>
                            <div class="bg-orange-100 rounded-full p-3">
                                <i class="fas fa-newspaper text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Recent Activities Overview -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Aktuelle Aktivitäten</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Neue Berichte</h3>
                            <p class="text-2xl font-bold text-red-600"><?php echo $stats['reports']['new']; ?></p>
                            <a href="reports.php" class="text-sm text-blue-600 hover:text-blue-800">Berichte ansehen →</a>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Ausstehende Bewerbungen</h3>
                            <p class="text-2xl font-bold text-blue-600"><?php echo $stats['applications']['pending']; ?></p>
                            <a href="applications.php" class="text-sm text-blue-600 hover:text-blue-800">Bewerbungen ansehen →</a>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Neues Feedback</h3>
                            <p class="text-2xl font-bold text-green-600"><?php echo $stats['feedback']['new']; ?></p>
                            <a href="feedback.php" class="text-sm text-blue-600 hover:text-blue-800">Feedback ansehen →</a>
                        </div>
                    </div>
                </div>
            </main>
            </main>
        </div>
    </div>
    
</body>
</html>