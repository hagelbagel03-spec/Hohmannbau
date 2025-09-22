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

$pageTitle = 'Admin Dashboard - Hohmann Bau';
$current_admin = getCurrentAdmin();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .sidebar-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <div class="flex items-center space-x-2 mb-8">
                    <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
                    <span class="font-bold text-xl text-gray-900">Hohmann Bau</span>
                </div>
                
                <nav class="space-y-2">
                    <a href="index.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="homepage.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-home"></i>
                        <span>Homepage bearbeiten</span>
                    </a>
                    
                    <a href="colors.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-palette"></i>
                        <span>Farben & Design</span>
                    </a>
                    <a href="reports.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-leaf"></i>
                        <span>Schadensmeldungen</span>
                        <?php if ($stats['reports']['new'] > 0): ?>
                        <span class="bg-green-500 text-white text-xs rounded-full px-2 py-1 ml-auto"><?php echo $stats['reports']['new']; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="applications.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-briefcase"></i>
                        <span>Bewerbungen</span>
                        <?php if ($stats['applications']['pending'] > 0): ?>
                        <span class="bg-blue-500 text-white text-xs rounded-full px-2 py-1 ml-auto"><?php echo $stats['applications']['pending']; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="feedback.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-star"></i>
                        <span>Kundenbewertungen</span>
                        <?php if ($stats['feedback']['new'] > 0): ?>
                        <span class="bg-yellow-500 text-white text-xs rounded-full px-2 py-1 ml-auto"><?php echo $stats['feedback']['new']; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="news.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-newspaper"></i>
                        <span>Garten-News</span>
                    </a>
                    <a href="services.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-seedling"></i>
                        <span>Leistungen verwalten</span>
                    </a>
                    <a href="team.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-users"></i>
                        <span>Team verwalten</span>
                    </a>
                    <a href="jobs.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-briefcase"></i>
                        <span>Karriere/Jobs verwalten</span>
                    </a>
                    <a href="chat.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-envelope"></i>
                        <span>Kundenanfragen</span>
                        <?php if ($stats['chat']['new'] > 0): ?>
                        <span class="bg-blue-500 text-white text-xs rounded-full px-2 py-1 ml-auto"><?php echo $stats['chat']['new']; ?></span>
                        <?php endif; ?>
                    </a>
                </nav>
            </div>
            
            <div class="absolute bottom-0 w-64 p-6 border-t">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($current_admin['username']); ?></p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <a href="logout.php" class="text-red-600 hover:text-red-800" title="Abmelden">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">Willkommen, <?php echo htmlspecialchars($current_admin['username']); ?></span>
                            <a href="../index.php" target="_blank" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Website anzeigen
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <main class="p-6">
            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
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
                    <a href="page_editor.php" class="bg-white rounded-xl shadow-lg p-6 card-hover block hover:no-underline">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">🎨 Seiten Editor</h3>
                                <p class="text-gray-600 text-sm">Alle Seiten bearbeiten</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-edit text-green-600 text-xl"></i>
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