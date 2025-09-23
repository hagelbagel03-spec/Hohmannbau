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
            <!-- Dashboard Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="admin-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Gesamte Berichte</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo $stats['reports']['total']; ?></p>
                            <p class="text-sm text-red-600 mt-1">
                                <?php echo $stats['reports']['new']; ?> neue
                            </p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Bewerbungen</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $stats['applications']['total']; ?></p>
                                <p class="text-sm text-blue-600 mt-1">
                                    <?php echo $stats['applications']['pending']; ?> ausstehend
                                </p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-briefcase text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Feedback</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $stats['feedback']['total']; ?></p>
                                <p class="text-sm text-green-600 mt-1">
                                    <?php echo $stats['feedback']['new']; ?> neue
                                </p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-comments text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Chat-Nachrichten</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $stats['chat']['total']; ?></p>
                                <p class="text-sm text-purple-600 mt-1">
                                    <?php echo $stats['chat']['new']; ?> neue
                                </p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <i class="fas fa-message text-purple-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <a href="text_editor.php" class="bg-white rounded-xl shadow-lg p-6 card-hover block hover:no-underline">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">📝 Text Editor</h3>
                                <p class="text-gray-600 text-sm">Alle Texte bearbeiten</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-edit text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </a>
                    
                    <a href="image_manager.php" class="bg-white rounded-xl shadow-lg p-6 card-hover block hover:no-underline">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">🖼️ Bilder verwalten</h3>
                                <p class="text-gray-600 text-sm">Upload & Zuweisungen</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-images text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </a>
                    
                    <a href="page_editor.php" class="bg-white rounded-xl shadow-lg p-6 card-hover block hover:no-underline">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">🎨 Seiten Editor</h3>
                                <p class="text-gray-600 text-sm">Farben & Layout</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <i class="fas fa-palette text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </a>
                    
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