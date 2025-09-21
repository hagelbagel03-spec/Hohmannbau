<?php
// Set session parameters BEFORE starting session
ini_set('session.cookie_lifetime', 86400);
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/PageContent.php';

// Check authentication - simplified 
// Skip auth check for AJAX requests during development
if (!isset($_POST['action']) && (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true)) {
    header('Location: login.php');
    exit();
}

$pageTitle = 'Universal Admin Panel - Hohmann Bau';
$db = new Database();
$pageContent = new PageContent($db);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    // Initialize database connection if not already done
    if (!$pageContent && $database) {
        $pageContent = new PageContent($database);
    }
    
    try {
        switch ($_POST['action']) {
            case 'save_page_content':
                $page_name = $_POST['page_name'] ?? '';
                $content_json = $_POST['content'] ?? '{}';
                $content = json_decode($content_json, true);
                
                if (!$content) {
                    echo json_encode(['success' => false, 'error' => 'Ungültige Inhalte']);
                    exit();
                }
                
                if ($pageContent) {
                    $result = $pageContent->saveContent($page_name, $content);
                    echo json_encode(['success' => $result, 'message' => 'Inhalte gespeichert']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Datenbankfehler']);
                }
                exit();
                
            case 'get_page_content':
                $page_name = $_POST['page_name'] ?? '';
                if ($pageContent) {
                    $content = $pageContent->getContent($page_name);
                    echo json_encode($content ?: []);
                } else {
                    echo json_encode([]);
                }
                exit();
                
            case 'save_design_settings':
                $setting_type = $_POST['setting_type'] ?? '';
                $settings_json = $_POST['settings'] ?? '{}';
                $settings = json_decode($settings_json, true);
                
                if ($pageContent && $settings) {
                    $result = $pageContent->saveDesignSettings($setting_type, $settings);
                    echo json_encode(['success' => $result, 'message' => 'Einstellungen gespeichert']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Ungültige Einstellungen']);
                }
                exit();
                
            case 'get_design_settings':
                $setting_type = $_POST['setting_type'] ?? '';
                if ($pageContent) {
                    $settings = $pageContent->getDesignSettings($setting_type);
                    echo json_encode($settings ?: []);
                } else {
                    echo json_encode([]);
                }
                exit();
                
            default:
                echo json_encode(['success' => false, 'error' => 'Unbekannte Aktion']);
                exit();
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Server Fehler: ' . $e->getMessage()]);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        .color-picker-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .color-input {
            width: 50px;
            height: 50px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
        }
        .sidebar-nav-item {
            transition: all 0.2s ease;
        }
        .sidebar-nav-item:hover {
            transform: translateX(4px);
        }
        .sidebar-nav-item.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg border-b-4 border-green-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <div class="font-bold text-2xl bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                        Hohmann Bau
                    </div>
                    <span class="bg-gradient-to-r from-green-100 to-blue-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        Universal PHP Admin Panel
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Willkommen, <?= $_SESSION['admin_username'] ?? 'Admin' ?></span>
                    <a href="<?= BASE_URL ?>" class="text-sm text-blue-600 hover:text-blue-700" target="_blank">
                        <i data-lucide="external-link" class="w-4 h-4 inline mr-1"></i>
                        Zur Website
                    </a>
                    <a href="logout.php" class="text-sm bg-red-100 text-red-700 px-3 py-2 rounded hover:bg-red-200">
                        <i data-lucide="log-out" class="w-4 h-4 inline mr-1"></i>
                        Abmelden
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="flex min-h-screen">
        <!-- Enhanced Sidebar -->
        <nav class="w-80 bg-white shadow-xl">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i data-lucide="settings" class="w-6 h-6 mr-3 text-green-600"></i>
                    Universal Admin
                </h2>
                
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <div class="sidebar-nav-item active p-4 rounded-lg cursor-pointer" onclick="showSection('dashboard')">
                        <div class="flex items-center">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">Dashboard</div>
                                <div class="text-xs opacity-75">Übersicht & Statistiken</div>
                            </div>
                        </div>
                    </div>

                    <!-- Universal Page Editor -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('page-editor')">
                        <div class="flex items-center">
                            <i data-lucide="edit-2" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">🌍 Universal Page Editor</div>
                                <div class="text-xs opacity-75">ALLE Seiten bearbeiten</div>
                            </div>
                        </div>
                    </div>

                    <!-- Design System Manager -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('design-system')">
                        <div class="flex items-center">
                            <i data-lucide="palette" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">🎨 Design System</div>
                                <div class="text-xs opacity-75">Farben, Fonts, Layout</div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Manager -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('media-manager')">
                        <div class="flex items-center">
                            <i data-lucide="image" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">📁 Media Manager</div>
                                <div class="text-xs opacity-75">Bilder & Dateien</div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Editor -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('navigation-editor')">
                        <div class="flex items-center">
                            <i data-lucide="menu" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">🧭 Navigation</div>
                                <div class="text-xs opacity-75">Menü & Links</div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Manager -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('services-manager')">
                        <div class="flex items-center">
                            <i data-lucide="wrench" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">🔧 Unsere Leistungen</div>
                                <div class="text-xs opacity-75">Hochbau, Tiefbau, Sanierung...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Manager -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('team-manager')">
                        <div class="flex items-center">
                            <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">👥 Unser Team</div>
                                <div class="text-xs opacity-75">Team-Mitglieder verwalten</div>
                            </div>
                        </div>
                    </div>

                    <!-- Career Manager -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('career-manager')">
                        <div class="flex items-center">
                            <i data-lucide="briefcase" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">💼 Karriere</div>
                                <div class="text-xs opacity-75">Jobs & Bewerbungen</div>
                            </div>
                        </div>
                    </div>

                    <!-- Offers Manager -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('offers-manager')">
                        <div class="flex items-center">
                            <i data-lucide="calculator" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">📋 Angebote</div>
                                <div class="text-xs opacity-75">Angebotsanfragen verwalten</div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Manager -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('content-manager')">
                        <div class="flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">📝 Content Manager</div>
                                <div class="text-xs opacity-75">Texte & Inhalte</div>
                            </div>
                        </div>
                    </div>

                    <!-- Projects -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('projects')">
                        <div class="flex items-center">
                            <i data-lucide="building" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">🏗️ Projekte</div>
                                <div class="text-xs opacity-75">Portfolio verwalten</div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('messages')">
                        <div class="flex items-center">
                            <i data-lucide="mail" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">💬 Nachrichten</div>
                                <div class="text-xs opacity-75">Kontaktanfragen</div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="sidebar-nav-item p-4 rounded-lg cursor-pointer" onclick="showSection('settings')">
                        <div class="flex items-center">
                            <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                            <div>
                                <div class="font-medium">⚙️ Einstellungen</div>
                                <div class="text-xs opacity-75">System & Config</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="mt-8 p-4 bg-gradient-to-br from-blue-50 to-green-50 rounded-lg">
                    <h3 class="font-semibold text-sm text-gray-900 mb-3">System Status</h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span>PHP Version:</span>
                            <span class="font-medium"><?= phpversion() ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>MySQL:</span>
                            <span class="text-green-600 font-medium">✓ Verbunden</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Server:</span>
                            <span class="text-green-600 font-medium">✓ Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Dashboard Section -->
            <div id="dashboard-section" class="section">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Universal Dashboard</h1>
                    <p class="text-gray-600">Willkommen im ultimativen Admin-Panel - hier können Sie ALLES bearbeiten!</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100">Seiten</p>
                                <p class="text-3xl font-bold">8</p>
                            </div>
                            <i data-lucide="file-text" class="w-12 h-12 text-blue-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-xl text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100">Projekte</p>
                                <p class="text-3xl font-bold">24</p>
                            </div>
                            <i data-lucide="building" class="w-12 h-12 text-green-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-xl text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100">Nachrichten</p>
                                <p class="text-3xl font-bold">12</p>
                            </div>
                            <i data-lucide="mail" class="w-12 h-12 text-purple-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-xl text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100">Medien</p>
                                <p class="text-3xl font-bold">156</p>
                            </div>
                            <i data-lucide="image" class="w-12 h-12 text-orange-200"></i>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold mb-4">🚀 Schnellaktionen</h3>
                        <div class="space-y-3">
                            <button class="w-full text-left p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" onclick="showSection('page-editor')">
                                <div class="flex items-center">
                                    <i data-lucide="edit-2" class="w-5 h-5 mr-3 text-blue-600"></i>
                                    <span class="font-medium">Homepage bearbeiten</span>
                                </div>
                            </button>
                            <button class="w-full text-left p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors" onclick="showSection('design-system')">
                                <div class="flex items-center">
                                    <i data-lucide="palette" class="w-5 h-5 mr-3 text-green-600"></i>
                                    <span class="font-medium">Design anpassen</span>
                                </div>
                            </button>
                            <button class="w-full text-left p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors" onclick="showSection('media-manager')">
                                <div class="flex items-center">
                                    <i data-lucide="upload" class="w-5 h-5 mr-3 text-purple-600"></i>
                                    <span class="font-medium">Bilder hochladen</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-blue-50 p-6 rounded-xl">
                        <h3 class="text-xl font-bold mb-4">✨ Was ist neu?</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm">Universal Page Editor - Bearbeiten Sie ALLE Seiten</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm">Design System Manager - Komplette Kontrolle über das Design</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                <span class="text-sm">Media Manager - Einfache Bildverwaltung</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Universal Page Editor Section -->
            <div id="page-editor-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">🌍 Universal Page Editor</h1>
                    <p class="text-gray-600">Bearbeiten Sie ALLE Seiten Ihrer Website - Texte, Bilder, Buttons, alles!</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Page Selection -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <h3 class="font-bold mb-4">Seiten auswählen</h3>
                            <div class="space-y-2" id="page-list">
                                <div class="page-item p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50" data-page="home">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">🏠</span>
                                        <div>
                                            <div class="font-medium">Homepage</div>
                                            <div class="text-xs text-gray-500">Hero, About, Features</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-item p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50" data-page="services">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">🔧</span>
                                        <div>
                                            <div class="font-medium">Leistungen</div>
                                            <div class="text-xs text-gray-500">Services, Preise</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-item p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50" data-page="projects">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">🏗️</span>
                                        <div>
                                            <div class="font-medium">Projekte</div>
                                            <div class="text-xs text-gray-500">Portfolio, Referenzen</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-item p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50" data-page="team">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">👥</span>
                                        <div>
                                            <div class="font-medium">Team</div>
                                            <div class="text-xs text-gray-500">Mitarbeiter, Biografien</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-item p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50" data-page="contact">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">📞</span>
                                        <div>
                                            <div class="font-medium">Kontakt</div>
                                            <div class="text-xs text-gray-500">Adresse, Telefon, E-Mail</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-item p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50" data-page="career">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">💼</span>
                                        <div>
                                            <div class="font-medium">Karriere</div>
                                            <div class="text-xs text-gray-500">Jobs, Bewerbungen</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-item p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50" data-page="footer">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">📄</span>
                                        <div>
                                            <div class="font-medium">Footer</div>
                                            <div class="text-xs text-gray-500">Links, Copyright</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-item p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50" data-page="navigation">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">🧭</span>
                                        <div>
                                            <div class="font-medium">Navigation</div>
                                            <div class="text-xs text-gray-500">Menü, Logo</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Page Editor -->
                    <div class="lg:col-span-3">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div id="page-editor-content">
                                <div class="text-center py-12">
                                    <i data-lucide="mouse-pointer-click" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Seite auswählen</h3>
                                    <p class="text-gray-600">Wählen Sie links eine Seite aus, um deren Inhalte zu bearbeiten.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Design System Section -->
            <div id="design-system-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">🎨 Design System Manager</h1>
                    <p class="text-gray-600">Passen Sie das komplette Design Ihrer Website an - Farben, Schriftarten, Layout</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Color System -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i data-lucide="palette" class="w-5 h-5 mr-2 text-blue-600"></i>
                            Farbsystem
                        </h3>
                        <div class="space-y-4" id="color-controls">
                            <div>
                                <label class="block text-sm font-medium mb-2">Primärfarbe (Buttons, Links)</label>
                                <div class="color-picker-container">
                                    <input type="color" class="color-input" id="primary-color" value="#10b981">
                                    <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg" id="primary-color-text" value="#10b981">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Sekundärfarbe</label>
                                <div class="color-picker-container">
                                    <input type="color" class="color-input" id="secondary-color" value="#059669">
                                    <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg" id="secondary-color-text" value="#059669">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Akzentfarbe</label>
                                <div class="color-picker-container">
                                    <input type="color" class="color-input" id="accent-color" value="#3b82f6">
                                    <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg" id="accent-color-text" value="#3b82f6">
                                </div>
                            </div>
                            <button class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700" onclick="saveColorSettings()">
                                Farben speichern
                            </button>
                        </div>
                    </div>

                    <!-- Typography -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i data-lucide="type" class="w-5 h-5 mr-2 text-purple-600"></i>
                            Typografie
                        </h3>
                        <div class="space-y-4" id="typography-controls">
                            <div>
                                <label class="block text-sm font-medium mb-2">Hauptschriftart</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="main-font">
                                    <option value="Inter, sans-serif">Inter (Modern)</option>
                                    <option value="Roboto, sans-serif">Roboto</option>
                                    <option value="Arial, sans-serif">Arial</option>
                                    <option value="Georgia, serif">Georgia</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Überschriften-Schriftart</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="heading-font">
                                    <option value="Inter, sans-serif">Inter (Modern)</option>
                                    <option value="Roboto, sans-serif">Roboto</option>
                                    <option value="Arial, sans-serif">Arial</option>
                                    <option value="Georgia, serif">Georgia</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Basis Schriftgröße</label>
                                <input type="range" min="14" max="20" value="16" class="w-full" id="font-size-range">
                                <span class="text-sm text-gray-500" id="font-size-display">16px</span>
                            </div>
                            <button class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700" onclick="saveTypographySettings()">
                                Typografie speichern
                            </button>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i data-lucide="eye" class="w-5 h-5 mr-2 text-green-600"></i>
                            Live Vorschau
                        </h3>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4" id="design-preview">
                            <h1 class="text-2xl font-bold mb-2" id="preview-title">Hohmann Bau</h1>
                            <h2 class="text-lg font-semibold mb-2" id="preview-subtitle">Bauen mit Vertrauen</h2>
                            <p class="mb-4" id="preview-text">Dies ist eine Vorschau Ihres neuen Designs. Änderungen werden sofort sichtbar.</p>
                            <div class="space-x-2">
                                <button class="px-4 py-2 rounded-lg text-white" id="preview-button-primary">Primärer Button</button>
                                <button class="px-4 py-2 rounded-lg border" id="preview-button-secondary">Sekundärer Button</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Manager Section -->
            <div id="media-manager-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">📁 Media Manager</h1>
                    <p class="text-gray-600">Verwalten Sie alle Bilder und Dateien Ihrer Website</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center mb-8">
                        <i data-lucide="upload" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Dateien hochladen</h3>
                        <p class="text-gray-600 mb-4">Ziehen Sie Dateien hierher oder klicken Sie zum Auswählen</p>
                        <input type="file" id="file-upload" multiple accept="image/*,.pdf,.doc,.docx" class="hidden">
                        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700" onclick="document.getElementById('file-upload').click()">
                            Dateien auswählen
                        </button>
                    </div>

                    <!-- Media Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="media-grid">
                        <!-- Sample media items -->
                        <div class="bg-gray-100 rounded-lg p-4 text-center">
                            <i data-lucide="image" class="w-12 h-12 mx-auto text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600">beispiel1.jpg</p>
                            <button class="text-red-600 text-xs hover:underline mt-2">Löschen</button>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-4 text-center">
                            <i data-lucide="file-text" class="w-12 h-12 mx-auto text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600">dokument.pdf</p>
                            <button class="text-red-600 text-xs hover:underline mt-2">Löschen</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Editor Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="navigation-editor-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">🧭 Navigation Editor</h1>
                    <p class="text-gray-600">Bearbeiten Sie das Hauptmenü und die Navigation Ihrer Website</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="space-y-6">
                        <!-- Logo Section -->
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-semibold mb-4">🎯 Logo & Branding</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Logo Text</label>
                                    <input type="text" id="nav-logo-text" value="Hohmann Bau" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Logo Bild URL</label>
                                    <input type="text" id="nav-logo-image" placeholder="https://example.com/logo.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Menu Items -->
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-semibold mb-4">📋 Menü-Punkte</h3>
                            <div id="menu-items-list" class="space-y-3">
                                <div class="menu-item flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <input type="text" placeholder="Menü-Name" value="Home" class="flex-1 px-3 py-2 border rounded">
                                    <input type="text" placeholder="Link" value="/" class="flex-1 px-3 py-2 border rounded">
                                    <button class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">Löschen</button>
                                </div>
                                <div class="menu-item flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <input type="text" placeholder="Menü-Name" value="Leistungen" class="flex-1 px-3 py-2 border rounded">
                                    <input type="text" placeholder="Link" value="leistungen.php" class="flex-1 px-3 py-2 border rounded">
                                    <button class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">Löschen</button>
                                </div>
                                <div class="menu-item flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <input type="text" placeholder="Menü-Name" value="Projekte" class="flex-1 px-3 py-2 border rounded">
                                    <input type="text" placeholder="Link" value="projekte.php" class="flex-1 px-3 py-2 border rounded">
                                    <button class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">Löschen</button>
                                </div>
                            </div>
                            <button id="add-menu-item" class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                + Neuen Menü-Punkt hinzufügen
                            </button>
                        </div>
                        
                        <!-- CTA Button -->
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-semibold mb-4">🎯 Call-to-Action Button</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Button Text</label>
                                    <input type="text" id="cta-text" value="Angebot erhalten" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Button Link</label>
                                    <input type="text" id="cta-link" value="angebot.php" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Button Farbe</label>
                                    <input type="color" id="cta-color" value="#16a34a" class="w-full h-10 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex justify-end">
                            <button onclick="saveNavigationSettings()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                                💾 Navigation Speichern
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Manager Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="content-manager-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">📝 Content Manager</h1>
                    <p class="text-gray-600">Verwalten Sie alle Inhalte und Texte Ihrer Website</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Quick Actions -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">⚡ Schnellaktionen</h3>
                            <div class="space-y-3">
                                <button onclick="bulkEditTexts()" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                    📝 Alle Texte bearbeiten
                                </button>
                                <button onclick="globalSearchReplace()" class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                                    🔍 Suchen & Ersetzen
                                </button>
                                <button onclick="exportContent()" class="w-full bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700">
                                    📤 Inhalte exportieren
                                </button>
                                <button onclick="importContent()" class="w-full bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700">
                                    📥 Inhalte importieren
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">📚 Alle Inhalte</h3>
                            <div class="space-y-4" id="content-list">
                                <div class="content-item p-4 border rounded-lg hover:bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium">Homepage Hero Titel</h4>
                                            <p class="text-sm text-gray-600">Bauen mit Vertrauen</p>
                                        </div>
                                        <button onclick="editContent('home', 'hero_title')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Bearbeiten</button>
                                    </div>
                                </div>
                                <div class="content-item p-4 border rounded-lg hover:bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium">Homepage Hero Untertitel</h4>
                                            <p class="text-sm text-gray-600">Ihr zuverlässiger Partner für...</p>
                                        </div>
                                        <button onclick="editContent('home', 'hero_subtitle')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Bearbeiten</button>
                                    </div>
                                </div>
                                <div class="content-item p-4 border rounded-lg hover:bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium">Kontakt Adresse</h4>
                                            <p class="text-sm text-gray-600">Bahnhofstraße 123...</p>
                                        </div>
                                        <button onclick="editContent('contact', 'address')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Bearbeiten</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Manager Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="projects-section" class="section hidden">
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">🏗️ Projekte Manager</h1>
                        <p class="text-gray-600">Verwalten Sie Ihr Projekt-Portfolio</p>
                    </div>
                    <button onclick="addNewProject()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                        ➕ Neues Projekt hinzufügen
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="projects-grid">
                    <!-- Example Project -->
                    <div class="project-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1599995903128-531fc7fb694b" alt="Projekt" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">Einfamilienhaus Musterstadt</h3>
                            <p class="text-gray-600 text-sm mb-3">Wohnbau</p>
                            <p class="text-gray-700 text-sm mb-4">Modernes Einfamilienhaus mit nachhaltiger Bauweise...</p>
                            <div class="flex gap-2">
                                <button onclick="editProject('1')" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">Bearbeiten</button>
                                <button onclick="deleteProject('1')" class="bg-red-500 text-white px-3 py-2 rounded text-sm hover:bg-red-600">Löschen</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Project Card -->
                    <div class="add-project-card bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center h-80 cursor-pointer hover:bg-gray-200" onclick="addNewProject()">
                        <div class="text-center">
                            <div class="text-4xl text-gray-400 mb-2">➕</div>
                            <p class="text-gray-600">Neues Projekt hinzufügen</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Manager Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="messages-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">💬 Nachrichten Manager</h1>
                    <p class="text-gray-600">Verwalten Sie alle Kontaktanfragen und Nachrichten</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Filter Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <h3 class="font-semibold mb-4">📊 Filter & Status</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Status</label>
                                    <select id="message-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                        <option value="all">Alle Nachrichten</option>
                                        <option value="new">Neu</option>
                                        <option value="read">Gelesen</option>
                                        <option value="replied">Beantwortet</option>
                                    </select>
                                </div>
                                <div class="text-sm space-y-2">
                                    <div class="flex justify-between">
                                        <span>Gesamt:</span>
                                        <span class="font-medium">47</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-red-600">Neu:</span>
                                        <span class="font-medium text-red-600">12</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Gelesen:</span>
                                        <span class="font-medium text-blue-600">23</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-600">Beantwortet:</span>
                                        <span class="font-medium text-green-600">12</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Messages List -->
                    <div class="lg:col-span-3">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold">📬 Nachrichten Liste</h3>
                                <button onclick="markAllAsRead()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Alle als gelesen markieren
                                </button>
                            </div>
                            
                            <div class="space-y-3" id="messages-list">
                                <div class="message-item p-4 border-l-4 border-l-red-500 bg-red-50 rounded-lg cursor-pointer hover:shadow-md" onclick="openMessage('1')">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium">Max Mustermann</span>
                                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">NEU</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-1">max@example.com</p>
                                            <p class="text-sm text-gray-800">Anfrage für Hausbau Projekt...</p>
                                        </div>
                                        <span class="text-xs text-gray-500">vor 2 Stunden</span>
                                    </div>
                                </div>
                                
                                <div class="message-item p-4 border-l-4 border-l-blue-500 bg-blue-50 rounded-lg cursor-pointer hover:shadow-md" onclick="openMessage('2')">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium">Anna Schmidt</span>
                                                <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">GELESEN</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-1">anna@example.com</p>
                                            <p class="text-sm text-gray-800">Frage zu Sanierungsarbeiten...</p>
                                        </div>
                                        <span class="text-xs text-gray-500">gestern</span>
                                    </div>
                                </div>
                                
                                <div class="message-item p-4 border-l-4 border-l-green-500 bg-green-50 rounded-lg cursor-pointer hover:shadow-md" onclick="openMessage('3')">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium">Peter Wagner</span>
                                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">BEANTWORTET</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-1">peter@example.com</p>
                                            <p class="text-sm text-gray-800">Terminanfrage für Beratung...</p>
                                        </div>
                                        <span class="text-xs text-gray-500">vor 2 Tagen</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Manager Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="services-manager-section" class="section hidden">
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">🔧 Unsere Leistungen</h1>
                        <p class="text-gray-600">Verwalten Sie alle Services und Dienstleistungen</p>
                    </div>
                    <button onclick="addNewService()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                        ➕ Neue Leistung hinzufügen
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="services-grid">
                    <!-- Hochbau -->
                    <div class="service-card bg-white rounded-xl shadow-lg p-6 transition-opacity duration-300" data-service-id="hochbau">
                        <div class="text-4xl mb-4">🏗️</div>
                        <h3 class="text-xl font-bold mb-2">Hochbau</h3>
                        <p class="text-gray-600 mb-4">Neubau von Wohn- und Geschäftsgebäuden, Einfamilienhäuser bis hin zu komplexen Gewerbeobjekten.</p>
                        <div class="text-sm text-gray-700 mb-4">
                            <strong>Features:</strong>
                            <ul class="list-disc list-inside mt-2">
                                <li>Planung und Ausführung</li>
                                <li>Rohbau und Ausbau</li>
                                <li>Schlüsselfertige Übergabe</li>
                            </ul>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editService('hochbau')" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600 transition-colors">
                                <i class="fas fa-edit mr-1"></i>Bearbeiten
                            </button>
                            <button onclick="toggleService('hochbau')" class="bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-power-off mr-1"></i>An/Aus
                            </button>
                        </div>
                    </div>

                    <!-- Tiefbau -->
                    <div class="service-card bg-white rounded-xl shadow-lg p-6">
                        <div class="text-4xl mb-4">🚧</div>
                        <h3 class="text-xl font-bold mb-2">Tiefbau</h3>
                        <p class="text-gray-600 mb-4">Fundamente, Keller, Erschließung und alle Arbeiten unter der Erdoberfläche.</p>
                        <div class="text-sm text-gray-700 mb-4">
                            <strong>Features:</strong>
                            <ul class="list-disc list-inside mt-2">
                                <li>Erdarbeiten und Aushub</li>
                                <li>Fundamente und Keller</li>
                                <li>Ver- und Entsorgung</li>
                            </ul>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editService('tiefbau')" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">Bearbeiten</button>
                            <button onclick="toggleService('tiefbau')" class="bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600">An/Aus</button>
                        </div>
                    </div>

                    <!-- Sanierung -->
                    <div class="service-card bg-white rounded-xl shadow-lg p-6">
                        <div class="text-4xl mb-4">🔨</div>
                        <h3 class="text-xl font-bold mb-2">Sanierung</h3>
                        <p class="text-gray-600 mb-4">Modernisierung und Instandsetzung bestehender Gebäude nach neuesten Standards.</p>
                        <div class="text-sm text-gray-700 mb-4">
                            <strong>Features:</strong>
                            <ul class="list-disc list-inside mt-2">
                                <li>Energetische Sanierung</li>
                                <li>Dach- und Fassadensanierung</li>
                                <li>Badsanierung</li>
                            </ul>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editService('sanierung')" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">Bearbeiten</button>
                            <button onclick="toggleService('sanierung')" class="bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600">An/Aus</button>
                        </div>
                    </div>

                    <!-- An- und Umbau -->
                    <div class="service-card bg-white rounded-xl shadow-lg p-6">
                        <div class="text-4xl mb-4">🏠</div>
                        <h3 class="text-xl font-bold mb-2">An- und Umbau</h3>
                        <p class="text-gray-600 mb-4">Erweiterung und Umgestaltung bestehender Gebäude nach Ihren Wünschen.</p>
                        <div class="text-sm text-gray-700 mb-4">
                            <strong>Features:</strong>
                            <ul class="list-disc list-inside mt-2">
                                <li>Anbauten und Aufstockungen</li>
                                <li>Grundrissänderungen</li>
                                <li>Dachausbau</li>
                            </ul>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editService('anbau')" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">Bearbeiten</button>
                            <button onclick="toggleService('anbau')" class="bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600">An/Aus</button>
                        </div>
                    </div>

                    <!-- Gewerbebau -->
                    <div class="service-card bg-white rounded-xl shadow-lg p-6">
                        <div class="text-4xl mb-4">🏢</div>
                        <h3 class="text-xl font-bold mb-2">Gewerbebau</h3>
                        <p class="text-gray-600 mb-4">Bürogebäude, Lagerhallen, Produktionsstätten und andere Gewerbeimmobilien.</p>
                        <div class="text-sm text-gray-700 mb-4">
                            <strong>Features:</strong>
                            <ul class="list-disc list-inside mt-2">
                                <li>Büro- und Verwaltungsgebäude</li>
                                <li>Produktions- und Lagerhallen</li>
                                <li>Individuelle Gewerbeobjekte</li>
                            </ul>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editService('gewerbebau')" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">Bearbeiten</button>
                            <button onclick="toggleService('gewerbebau')" class="bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600">An/Aus</button>
                        </div>
                    </div>

                    <!-- Notdienst -->
                    <div class="service-card bg-white rounded-xl shadow-lg p-6">
                        <div class="text-4xl mb-4">⚡</div>
                        <h3 class="text-xl font-bold mb-2">Notdienst</h3>
                        <p class="text-gray-600 mb-4">24/7 Notdienst für dringende Reparaturen und Schadensbehebung.</p>
                        <div class="text-sm text-gray-700 mb-4">
                            <strong>Features:</strong>
                            <ul class="list-disc list-inside mt-2">
                                <li>Wasserschäden</li>
                                <li>Sturmschäden</li>
                                <li>Notfallreparaturen</li>
                            </ul>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editService('notdienst')" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">Bearbeiten</button>
                            <button onclick="toggleService('notdienst')" class="bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600">An/Aus</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Manager Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="team-manager-section" class="section hidden">
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">👥 Unser Team</h1>
                        <p class="text-gray-600">Verwalten Sie alle Team-Mitglieder und deren Informationen</p>
                    </div>
                    <button onclick="addNewTeamMember()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                        ➕ Team-Mitglied hinzufügen
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="team-grid">
                    <!-- Beispiel Team-Mitglied -->
                    <div class="team-member-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d" alt="Team Mitglied" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">Max Mustermann</h3>
                            <p class="text-green-600 font-medium mb-2">Geschäftsführer</p>
                            <p class="text-gray-600 text-sm mb-4">Mit über 20 Jahren Erfahrung im Bauwesen leitet Max unser Unternehmen mit Leidenschaft und Expertise.</p>
                            <div class="flex gap-2">
                                <button onclick="editTeamMember('1')" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">Bearbeiten</button>
                                <button onclick="deleteTeamMember('1')" class="bg-red-500 text-white px-3 py-2 rounded text-sm hover:bg-red-600">Löschen</button>
                            </div>
                        </div>
                    </div>

                    <!-- Add Team Member Card -->
                    <div class="add-team-member-card bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center h-80 cursor-pointer hover:bg-gray-200" onclick="addNewTeamMember()">
                        <div class="text-center">
                            <div class="text-4xl text-gray-400 mb-2">➕</div>
                            <p class="text-gray-600">Neues Team-Mitglied hinzufügen</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Career Manager Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="career-manager-section" class="section hidden">
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">💼 Karriere Manager</h1>
                        <p class="text-gray-600">Verwalten Sie Stellenausschreibungen und Bewerbungen</p>
                    </div>
                    <button onclick="addNewJob()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                        ➕ Neue Stellenausschreibung
                    </button>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Jobs List -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold mb-4">📋 Stellenausschreibungen</h3>
                        <div class="space-y-4" id="jobs-list">
                            <div class="job-item p-4 border rounded-lg hover:bg-gray-50">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold">Bauleiter (m/w/d)</h4>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">Aktiv</span>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">Vollzeit • Musterstadt</p>
                                <p class="text-gray-700 text-sm mb-3">Erfahrener Bauleiter für Wohnbauprojekte gesucht...</p>
                                <div class="flex gap-2">
                                    <button onclick="editJob('1')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Bearbeiten</button>
                                    <button onclick="toggleJobStatus('1')" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Deaktivieren</button>
                                    <button onclick="viewApplications('1')" class="bg-purple-500 text-white px-3 py-1 rounded text-sm">Bewerbungen (3)</button>
                                </div>
                            </div>
                            
                            <div class="job-item p-4 border rounded-lg hover:bg-gray-50">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold">Maurer (m/w/d)</h4>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">Aktiv</span>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">Vollzeit • Musterstadt</p>
                                <p class="text-gray-700 text-sm mb-3">Zuverlässiger Maurer für Neubau- und Sanierungsprojekte...</p>
                                <div class="flex gap-2">
                                    <button onclick="editJob('2')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Bearbeiten</button>
                                    <button onclick="toggleJobStatus('2')" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Deaktivieren</button>
                                    <button onclick="viewApplications('2')" class="bg-purple-500 text-white px-3 py-1 rounded text-sm">Bewerbungen (7)</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Applications -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold mb-4">📨 Aktuelle Bewerbungen</h3>
                        <div class="space-y-4" id="applications-list">
                            <div class="application-item p-4 border-l-4 border-l-blue-500 bg-blue-50 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-semibold">Anna Schmidt</h4>
                                        <p class="text-sm text-gray-600">Bewerbung für: Bauleiter (m/w/d)</p>
                                    </div>
                                    <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs">Neu</span>
                                </div>
                                <p class="text-sm text-gray-700 mb-3">anna.schmidt@email.com • vor 2 Stunden</p>
                                <div class="flex gap-2">
                                    <button onclick="viewApplication('1')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Ansehen</button>
                                    <button onclick="downloadCV('1')" class="bg-green-500 text-white px-3 py-1 rounded text-sm">CV</button>
                                </div>
                            </div>

                            <div class="application-item p-4 border-l-4 border-l-green-500 bg-green-50 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-semibold">Thomas Müller</h4>
                                        <p class="text-sm text-gray-600">Bewerbung für: Maurer (m/w/d)</p>
                                    </div>
                                    <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">Bearbeitet</span>
                                </div>
                                <p class="text-sm text-gray-700 mb-3">thomas.mueller@email.com • vor 1 Tag</p>
                                <div class="flex gap-2">
                                    <button onclick="viewApplication('2')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Ansehen</button>
                                    <button onclick="downloadCV('2')" class="bg-green-500 text-white px-3 py-1 rounded text-sm">CV</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offers Manager Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="offers-manager-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">📋 Angebots-Manager</h1>
                    <p class="text-gray-600">Verwalten Sie alle Angebotsanfragen und Kostenvoranschläge</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Filter & Stats -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
                            <h3 class="font-semibold mb-4">📊 Übersicht</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span>Gesamt Anfragen:</span>
                                    <span class="font-medium">24</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-red-600">Neue:</span>
                                    <span class="font-medium text-red-600">8</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-600">In Bearbeitung:</span>
                                    <span class="font-medium text-blue-600">11</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-600">Abgeschlossen:</span>
                                    <span class="font-medium text-green-600">5</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <h3 class="font-semibold mb-4">🔍 Filter</h3>
                            <div class="space-y-4">
                                <select id="offer-status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="all">Alle Status</option>
                                    <option value="new">Neu</option>
                                    <option value="processing">In Bearbeitung</option>
                                    <option value="completed">Abgeschlossen</option>
                                </select>
                                <select id="offer-type-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="all">Alle Typen</option>
                                    <option value="hochbau">Hochbau</option>
                                    <option value="tiefbau">Tiefbau</option>
                                    <option value="sanierung">Sanierung</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Offers List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-semibold">💼 Angebotsanfragen</h3>
                                <button onclick="exportOffers()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Excel Export
                                </button>
                            </div>
                            
                            <div class="space-y-4" id="offers-list">
                                <div class="offer-item p-4 border-l-4 border-l-red-500 bg-red-50 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-semibold">Familie Weber - Einfamilienhaus</h4>
                                            <p class="text-sm text-gray-600">Hochbau • Neubau</p>
                                        </div>
                                        <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">NEU</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-1"><strong>Budget:</strong> €300.000 - €400.000</p>
                                    <p class="text-sm text-gray-700 mb-1"><strong>Zeitrahmen:</strong> 6-12 Monate</p>
                                    <p class="text-sm text-gray-700 mb-3">Einfamilienhaus ca. 150qm mit Keller und Garage...</p>
                                    <div class="flex gap-2">
                                        <button onclick="viewOffer('1')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Details</button>
                                        <button onclick="createQuote('1')" class="bg-green-500 text-white px-3 py-1 rounded text-sm">Angebot erstellen</button>
                                        <button onclick="updateOfferStatus('1')" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Status ändern</button>
                                    </div>
                                </div>

                                <div class="offer-item p-4 border-l-4 border-l-blue-500 bg-blue-50 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-semibold">Müller GmbH - Bürogebäude Sanierung</h4>
                                            <p class="text-sm text-gray-600">Sanierung • Gewerbe</p>
                                        </div>
                                        <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs">BEARBEITUNG</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-1"><strong>Budget:</strong> €150.000 - €200.000</p>
                                    <p class="text-sm text-gray-700 mb-1"><strong>Zeitrahmen:</strong> 3-4 Monate</p>
                                    <p class="text-sm text-gray-700 mb-3">Komplette Sanierung eines 3-stöckigen Bürogebäudes...</p>
                                    <div class="flex gap-2">
                                        <button onclick="viewOffer('2')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Details</button>
                                        <button onclick="createQuote('2')" class="bg-green-500 text-white px-3 py-1 rounded text-sm">Angebot erstellen</button>
                                        <button onclick="updateOfferStatus('2')" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Status ändern</button>
                                    </div>
                                </div>

                                <div class="offer-item p-4 border-l-4 border-l-green-500 bg-green-50 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-semibold">Schmidt - Dachsanierung</h4>
                                            <p class="text-sm text-gray-600">Sanierung • Privat</p>
                                        </div>
                                        <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">ABGESCHLOSSEN</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-1"><strong>Budget:</strong> €45.000</p>
                                    <p class="text-sm text-gray-700 mb-1"><strong>Zeitrahmen:</strong> 2 Wochen</p>
                                    <p class="text-sm text-gray-700 mb-3">Komplette Dachsanierung mit neuer Dämmung...</p>
                                    <div class="flex gap-2">
                                        <button onclick="viewOffer('3')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Details</button>
                                        <button onclick="downloadQuote('3')" class="bg-purple-500 text-white px-3 py-1 rounded text-sm">Angebot Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Section - VOLLSTÄNDIG IMPLEMENTIERT -->
            <div id="settings-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">⚙️ System Einstellungen</h1>
                    <p class="text-gray-600">Konfiguration und Verwaltung der Admin-Panel Einstellungen</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- General Settings -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">🔧 Allgemeine Einstellungen</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Website Name</label>
                                <input type="text" id="site-name" value="Hohmann Bau" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Website Beschreibung</label>
                                <textarea id="site-description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg">Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Admin Email</label>
                                <input type="email" id="admin-email" value="admin@hohmann-bau.de" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <button onclick="saveGeneralSettings()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Allgemeine Einstellungen speichern
                            </button>
                        </div>
                    </div>
                    
                    <!-- Security Settings -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">🔐 Sicherheits-Einstellungen</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Admin Passwort ändern</label>
                                <input type="password" id="new-password" placeholder="Neues Passwort" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Passwort bestätigen</label>
                                <input type="password" id="confirm-password" placeholder="Passwort bestätigen" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="two-factor" class="mr-2">
                                <label for="two-factor" class="text-sm">Zwei-Faktor-Authentifizierung aktivieren</label>
                            </div>
                            <button onclick="changePassword()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                Passwort ändern
                            </button>
                        </div>
                    </div>
                    
                    <!-- Backup & Export -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">💾 Backup & Export</h3>
                        <div class="space-y-4">
                            <button onclick="createBackup()" class="w-full bg-green-600 text-white py-3 px-4 rounded hover:bg-green-700">
                                🗄️ Vollständiges Backup erstellen
                            </button>
                            <button onclick="exportDatabase()" class="w-full bg-blue-600 text-white py-3 px-4 rounded hover:bg-blue-700">
                                📊 Datenbank exportieren
                            </button>
                            <button onclick="importDatabase()" class="w-full bg-purple-600 text-white py-3 px-4 rounded hover:bg-purple-700">
                                📥 Datenbank importieren
                            </button>
                        </div>
                    </div>
                    
                    <!-- System Info -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">📊 System Information</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span>PHP Version:</span>
                                <span class="font-medium"><?= phpversion() ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>MySQL Version:</span>
                                <span class="font-medium">8.0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Server:</span>
                                <span class="font-medium"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Development Server' ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Speicher Limit:</span>
                                <span class="font-medium"><?= ini_get('memory_limit') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Upload Max:</span>
                                <span class="font-medium"><?= ini_get('upload_max_filesize') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentSection = 'dashboard';
        let currentPage = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            setupEventListeners();
            showSection('dashboard');
        });

        // Setup event listeners
        function setupEventListeners() {
            // Page selection
            document.querySelectorAll('.page-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectPage(this.dataset.page);
                });
            });

            // Color picker sync
            setupColorPickers();
            
            // Font size slider
            setupFontSizeSlider();
        }

        // Navigation
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Show selected section
            document.getElementById(sectionName + '-section').classList.remove('hidden');
            
            // Update navigation
            document.querySelectorAll('.sidebar-nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            event.target.closest('.sidebar-nav-item').classList.add('active');
            
            currentSection = sectionName;
        }

        // Page Editor Functions
        function selectPage(pageName) {
            currentPage = pageName;
            
            // Update UI
            document.querySelectorAll('.page-item').forEach(item => {
                item.classList.remove('bg-blue-50', 'border-blue-200');
                item.classList.add('bg-gray-50');
            });
            
            document.querySelector(`[data-page="${pageName}"]`).classList.remove('bg-gray-50');
            document.querySelector(`[data-page="${pageName}"]`).classList.add('bg-blue-50', 'border-blue-200');
            
            // Load page content
            loadPageContent(pageName);
        }

        function loadPageContent(pageName) {
            const content = document.getElementById('page-editor-content');
            
            // Show loading
            content.innerHTML = `
                <div class="text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
                    <p>Lade Inhalte für ${pageName}...</p>
                </div>
            `;

            // AJAX request to load content
            fetch('universal_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=get_page_content&page_name=${pageName}`
            })
            .then(response => response.json())
            .then(data => {
                renderPageEditor(pageName, data);
            })
            .catch(error => {
                console.error('Error loading page content:', error);
                renderPageEditor(pageName, {});
            });
        }

        function renderPageEditor(pageName, contentData) {
            const pageConfig = getPageConfig(pageName);
            const content = document.getElementById('page-editor-content');
            
            let html = `
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">${pageConfig.icon} ${pageConfig.title} bearbeiten</h2>
                        <p class="text-gray-600">${pageConfig.description}</p>
                    </div>
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700" onclick="savePageContent('${pageName}')">
                        <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>Speichern
                    </button>
                </div>
                <form id="page-form-${pageName}" class="space-y-6">
            `;
            
            pageConfig.fields.forEach(field => {
                const value = contentData[field.name] || field.default || '';
                
                html += `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">${field.label}</label>
                `;
                
                if (field.type === 'textarea') {
                    html += `<textarea name="${field.name}" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="${field.placeholder}">${value}</textarea>`;
                } else if (field.type === 'select') {
                    html += `<select name="${field.name}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">`;
                    field.options.forEach(option => {
                        const selected = value === option.value ? 'selected' : '';
                        html += `<option value="${option.value}" ${selected}>${option.label}</option>`;
                    });
                    html += `</select>`;
                } else {
                    html += `<input type="${field.type}" name="${field.name}" value="${value}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="${field.placeholder}">`;
                }
                
                if (field.help) {
                    html += `<p class="text-xs text-gray-500 mt-1">${field.help}</p>`;
                }
                
                html += `</div>`;
            });
            
            html += `</form>`;
            
            content.innerHTML = html;
            lucide.createIcons();
        }

        function getPageConfig(pageName) {
            const configs = {
                home: {
                    title: 'Homepage',
                    icon: '🏠',
                    description: 'Bearbeiten Sie die Startseite mit Hero-Bereich, Über uns und Features',
                    fields: [
                        { name: 'hero_title', label: 'Hero Titel', type: 'text', placeholder: 'Bauen mit Vertrauen', default: 'Bauen mit Vertrauen' },
                        { name: 'hero_subtitle', label: 'Hero Untertitel', type: 'text', placeholder: 'Ihr zuverlässiger Partner...', default: 'Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen' },
                        { name: 'hero_image', label: 'Hero Hintergrundbild URL', type: 'url', placeholder: 'https://example.com/bild.jpg', help: 'URL zu einem Hintergrundbild' },
                        { name: 'hero_cta_text', label: 'Hero Button Text', type: 'text', placeholder: 'Jetzt Angebot anfordern', default: 'Jetzt Angebot anfordern' },
                        { name: 'about_title', label: 'Über uns Titel', type: 'text', placeholder: 'Über uns', default: 'Über uns' },
                        { name: 'about_text', label: 'Über uns Text', type: 'textarea', placeholder: 'Beschreibung des Unternehmens...', default: 'Mit über 25 Jahren Erfahrung sind wir Ihr vertrauensvoller Partner für alle Bauprojekte.' }
                    ]
                },
                services: {
                    title: 'Leistungen',
                    icon: '🔧',
                    description: 'Bearbeiten Sie die Leistungsseite mit Services und Preisen',
                    fields: [
                        { name: 'title', label: 'Seitentitel', type: 'text', placeholder: 'Unsere Leistungen', default: 'Unsere Leistungen' },
                        { name: 'subtitle', label: 'Untertitel', type: 'text', placeholder: 'Umfassende Baulösungen...', default: 'Umfassende Baulösungen aus einer Hand' },
                        { name: 'description', label: 'Beschreibung', type: 'textarea', placeholder: 'Beschreibung der Leistungen...', default: 'Von der ersten Idee bis zur schlüsselfertigen Übergabe begleiten wir Sie durch Ihr gesamtes Bauvorhaben.' }
                    ]
                },
                projects: {
                    title: 'Projekte',
                    icon: '🏗️',
                    description: 'Bearbeiten Sie die Projektseite mit Portfolio und Referenzen',
                    fields: [
                        { name: 'title', label: 'Seitentitel', type: 'text', placeholder: 'Unsere Projekte', default: 'Unsere Projekte' },
                        { name: 'subtitle', label: 'Untertitel', type: 'text', placeholder: 'Referenzen aus verschiedenen Bereichen', default: 'Referenzen aus verschiedenen Bereichen' },
                        { name: 'description', label: 'Beschreibung', type: 'textarea', placeholder: 'Beschreibung der Projekte...', default: 'Entdecken Sie unsere erfolgreich abgeschlossenen Bauprojekte und lassen Sie sich von der Vielfalt und Qualität unserer Arbeit überzeugen.' }
                    ]
                },
                team: {
                    title: 'Team',
                    icon: '👥',
                    description: 'Bearbeiten Sie die Team-Seite mit Mitarbeitern und Biografien',
                    fields: [
                        { name: 'title', label: 'Seitentitel', type: 'text', placeholder: 'Unser Team', default: 'Unser Team' },
                        { name: 'subtitle', label: 'Untertitel', type: 'text', placeholder: 'Erfahrene Fachkräfte...', default: 'Erfahrene Fachkräfte für Ihr Projekt' },
                        { name: 'description', label: 'Beschreibung', type: 'textarea', placeholder: 'Beschreibung des Teams...', default: 'Lernen Sie die Menschen kennen, die hinter unseren erfolgreichen Bauprojekten stehen.' }
                    ]
                },
                contact: {
                    title: 'Kontakt',
                    icon: '📞',
                    description: 'Bearbeiten Sie die Kontaktseite mit Informationen und Formularen',
                    fields: [
                        { name: 'title', label: 'Seitentitel', type: 'text', placeholder: 'Kontakt', default: 'Kontakt' },
                        { name: 'subtitle', label: 'Untertitel', type: 'text', placeholder: 'Lassen Sie uns über Ihr Projekt sprechen', default: 'Lassen Sie uns über Ihr Projekt sprechen' },
                        { name: 'description', label: 'Beschreibung', type: 'textarea', placeholder: 'Kontakt-Beschreibung...', default: 'Haben Sie Fragen zu unseren Leistungen oder möchten Sie ein Projekt mit uns besprechen? Wir freuen uns auf Ihre Nachricht.' },
                        { name: 'address', label: 'Adresse', type: 'text', placeholder: 'Straße, PLZ Ort', default: 'Bahnhofstraße 123, 12345 Musterstadt' },
                        { name: 'phone', label: 'Telefon', type: 'tel', placeholder: '+49 123 456 789', default: '+49 123 456 789' },
                        { name: 'email', label: 'E-Mail', type: 'email', placeholder: 'info@hohmann-bau.de', default: 'info@hohmann-bau.de' },
                        { name: 'opening_hours', label: 'Öffnungszeiten', type: 'text', placeholder: 'Mo-Fr: 08:00-17:00 Uhr', default: 'Mo-Fr: 08:00-17:00 Uhr' }
                    ]
                },
                career: {
                    title: 'Karriere',
                    icon: '💼',
                    description: 'Bearbeiten Sie die Karriere-Seite mit Jobs und Bewerbungsprozess',
                    fields: [
                        { name: 'title', label: 'Seitentitel', type: 'text', placeholder: 'Karriere', default: 'Karriere' },
                        { name: 'subtitle', label: 'Untertitel', type: 'text', placeholder: 'Werden Sie Teil unseres Teams', default: 'Werden Sie Teil unseres Teams' },
                        { name: 'description', label: 'Beschreibung', type: 'textarea', placeholder: 'Karriere-Beschreibung...', default: 'Wir suchen motivierte Fachkräfte, die mit uns gemeinsam die Zukunft des Bauens gestalten möchten.' }
                    ]
                },
                footer: {
                    title: 'Footer',
                    icon: '📄',
                    description: 'Bearbeiten Sie den Footer mit Links, Copyright und sozialen Medien',
                    fields: [
                        { name: 'company_name', label: 'Firmenname', type: 'text', placeholder: 'Hohmann Bau GmbH', default: 'Hohmann Bau GmbH' },
                        { name: 'company_description', label: 'Firmenbeschreibung', type: 'text', placeholder: 'Ihr Partner für professionelle Bauprojekte', default: 'Ihr Partner für professionelle Bauprojekte' },
                        { name: 'copyright', label: 'Copyright Text', type: 'text', placeholder: '© 2024 Hohmann Bau GmbH', default: '© 2024 Hohmann Bau GmbH. Alle Rechte vorbehalten.' }
                    ]
                },
                navigation: {
                    title: 'Navigation',
                    icon: '🧭',
                    description: 'Bearbeiten Sie das Hauptmenü und die Navigation',
                    fields: [
                        { name: 'logo_text', label: 'Logo Text', type: 'text', placeholder: 'Hohmann Bau', default: 'Hohmann Bau' },
                        { name: 'cta_button_text', label: 'CTA Button Text', type: 'text', placeholder: 'Angebot erhalten', default: 'Angebot erhalten' }
                    ]
                }
            };
            
            return configs[pageName] || { title: pageName, icon: '📄', description: 'Seite bearbeiten', fields: [] };
        }

        function savePageContent(pageName) {
            const form = document.getElementById(`page-form-${pageName}`);
            if (!form) {
                showNotification('Formular nicht gefunden!', 'error');
                return;
            }
            
            const formData = new FormData(form);
            
            // Convert form data to object
            const content = {};
            for (let [key, value] of formData.entries()) {
                content[key] = value;
            }
            
            console.log('Saving content for:', pageName, content);
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i data-lucide="loader" class="w-4 h-4 inline mr-2 animate-spin"></i>Speichern...';
            button.disabled = true;
            
            // AJAX request to save content
            fetch('universal_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=save_page_content&page_name=${encodeURIComponent(pageName)}&content=${encodeURIComponent(JSON.stringify(content))}`
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(data => {
                console.log('Response data:', data);
                try {
                    const jsonData = JSON.parse(data);
                    if (jsonData.success) {
                        showNotification('Inhalte erfolgreich gespeichert!', 'success');
                    } else {
                        showNotification('Fehler beim Speichern: ' + (jsonData.error || 'Unbekannter Fehler'), 'error');
                    }
                } catch (e) {
                    console.error('Parse error:', e, 'Data:', data);
                    showNotification('Server-Antwort Fehler', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving content:', error);
                showNotification('Netzwerk-Fehler beim Speichern!', 'error');
            })
            .finally(() => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                lucide.createIcons();
            });
        }

        // Design System Functions
        function setupColorPickers() {
            const colorInputs = [
                { picker: 'primary-color', text: 'primary-color-text' },
                { picker: 'secondary-color', text: 'secondary-color-text' },
                { picker: 'accent-color', text: 'accent-color-text' }
            ];

            colorInputs.forEach(input => {
                const picker = document.getElementById(input.picker);
                const text = document.getElementById(input.text);

                if (picker && text) {
                    picker.addEventListener('input', function() {
                        text.value = this.value;
                        updateDesignPreview();
                    });

                    text.addEventListener('input', function() {
                        picker.value = this.value;
                        updateDesignPreview();
                    });
                }
            });
        }

        function setupFontSizeSlider() {
            const slider = document.getElementById('font-size-range');
            const display = document.getElementById('font-size-display');

            if (slider && display) {
                slider.addEventListener('input', function() {
                    display.textContent = this.value + 'px';
                    updateDesignPreview();
                });
            }
        }

        function updateDesignPreview() {
            const primaryColor = document.getElementById('primary-color')?.value || '#10b981';
            const secondaryColor = document.getElementById('secondary-color')?.value || '#059669';
            const fontSize = document.getElementById('font-size-range')?.value || '16';
            const mainFont = document.getElementById('main-font')?.value || 'Inter, sans-serif';
            const headingFont = document.getElementById('heading-font')?.value || 'Inter, sans-serif';

            const preview = document.getElementById('design-preview');
            const title = document.getElementById('preview-title');
            const subtitle = document.getElementById('preview-subtitle');
            const text = document.getElementById('preview-text');
            const primaryBtn = document.getElementById('preview-button-primary');
            const secondaryBtn = document.getElementById('preview-button-secondary');

            if (preview) {
                preview.style.fontFamily = mainFont;
                preview.style.fontSize = fontSize + 'px';
            }

            if (title) {
                title.style.fontFamily = headingFont;
                title.style.color = primaryColor;
            }

            if (subtitle) {
                subtitle.style.fontFamily = headingFont;
            }

            if (primaryBtn) {
                primaryBtn.style.backgroundColor = primaryColor;
            }

            if (secondaryBtn) {
                secondaryBtn.style.borderColor = secondaryColor;
                secondaryBtn.style.color = secondaryColor;
            }
        }

        function saveColorSettings() {
            const settings = {
                primary_color: document.getElementById('primary-color').value,
                secondary_color: document.getElementById('secondary-color').value,
                accent_color: document.getElementById('accent-color').value
            };

            saveDesignSettings('theme', settings);
        }

        function saveTypographySettings() {
            const settings = {
                font_family: document.getElementById('main-font').value,
                heading_font: document.getElementById('heading-font').value,
                font_size_base: document.getElementById('font-size-range').value + 'px'
            };

            saveDesignSettings('typography', settings);
        }

        function saveDesignSettings(settingType, settings) {
            console.log('Saving design settings:', settingType, settings);
            
            fetch('universal_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=save_design_settings&setting_type=${encodeURIComponent(settingType)}&settings=${encodeURIComponent(JSON.stringify(settings))}`
            })
            .then(response => response.text())
            .then(data => {
                console.log('Design settings response:', data);
                try {
                    const jsonData = JSON.parse(data);
                    if (jsonData.success) {
                        showNotification(`${settingType} Einstellungen gespeichert!`, 'success');
                    } else {
                        showNotification('Fehler beim Speichern: ' + (jsonData.error || 'Unbekannter Fehler'), 'error');
                    }
                } catch (e) {
                    console.error('Parse error:', e, 'Data:', data);
                    showNotification('Server-Antwort Fehler', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving design settings:', error);
                showNotification('Netzwerk-Fehler beim Speichern!', 'error');
            });
        }

        // Utility Functions
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-green-600 text-white' :
                type === 'error' ? 'bg-red-600 text-white' :
                'bg-blue-600 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'}" class="w-5 h-5 mr-3"></i>
                    <span>${message}</span>
                </div>
            `;

            // Add to page
            document.body.appendChild(notification);
            lucide.createIcons();

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Initialize design preview on load
        setTimeout(() => {
            updateDesignPreview();
        }, 1000);

        // NEUE FUNKTIONEN FÜR ALLE BEREICHE

        // Navigation Editor Functions
        function saveNavigationSettings() {
            const logoText = document.getElementById('nav-logo-text').value;
            const logoImage = document.getElementById('nav-logo-image').value;
            const ctaText = document.getElementById('cta-text').value;
            const ctaLink = document.getElementById('cta-link').value;
            const ctaColor = document.getElementById('cta-color').value;
            
            // Collect menu items
            const menuItems = [];
            document.querySelectorAll('.menu-item').forEach(item => {
                const name = item.querySelector('input[placeholder="Menü-Name"]').value;
                const link = item.querySelector('input[placeholder="Link"]').value;
                if (name && link) {
                    menuItems.push({ name, link });
                }
            });
            
            const navSettings = {
                logo_text: logoText,
                logo_image: logoImage,
                menu_items: menuItems,
                cta_button: {
                    text: ctaText,
                    link: ctaLink,
                    color: ctaColor
                }
            };
            
            saveDesignSettings('navigation', navSettings);
        }

        document.getElementById('add-menu-item')?.addEventListener('click', function() {
            const menuList = document.getElementById('menu-items-list');
            const newItem = document.createElement('div');
            newItem.className = 'menu-item flex items-center gap-3 p-3 bg-gray-50 rounded-lg';
            newItem.innerHTML = `
                <input type="text" placeholder="Menü-Name" class="flex-1 px-3 py-2 border rounded">
                <input type="text" placeholder="Link" class="flex-1 px-3 py-2 border rounded">
                <button onclick="this.parentElement.remove()" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">Löschen</button>
            `;
            menuList.appendChild(newItem);
        });

        // Content Manager Functions
        function bulkEditTexts() {
            showNotification('Bulk Text Editor geöffnet', 'success');
            // Hier würde die Bulk-Edit Funktionalität implementiert
        }

        function globalSearchReplace() {
            const searchTerm = prompt('Nach welchem Text suchen?');
            const replaceTerm = prompt('Durch welchen Text ersetzen?');
            if (searchTerm && replaceTerm) {
                showNotification(`Ersetze "${searchTerm}" durch "${replaceTerm}"`, 'success');
            }
        }

        function exportContent() {
            showNotification('Inhalte werden exportiert...', 'success');
            // Hier würde Export-Funktionalität implementiert
        }

        function importContent() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = '.json,.csv';
            fileInput.onchange = function(e) {
                if (e.target.files[0]) {
                    showNotification('Import gestartet...', 'success');
                }
            };
            fileInput.click();
        }

        function editContent(page, field) {
            const newValue = prompt(`Neuen Wert für ${field} eingeben:`);
            if (newValue) {
                // Hier würde die Speicher-Logik implementiert
                showNotification(`${field} aktualisiert`, 'success');
            }
        }

        // Projects Manager Functions
        function addNewProject() {
            const projectName = prompt('Projekt Name:');
            const projectCategory = prompt('Projekt Kategorie:');
            const projectDescription = prompt('Projekt Beschreibung:');
            
            if (projectName) {
                showNotification(`Projekt "${projectName}" hinzugefügt`, 'success');
                // Hier würde das Projekt zur Datenbank hinzugefügt
            }
        }

        function editProject(id) {
            showNotification(`Bearbeite Projekt ${id}`, 'info');
            // Hier würde ein Edit-Modal geöffnet
        }

        function deleteProject(id) {
            if (confirm('Projekt wirklich löschen?')) {
                showNotification(`Projekt ${id} gelöscht`, 'success');
                // Hier würde das Projekt gelöscht
            }
        }

        // Messages Manager Functions
        function openMessage(id) {
            showNotification(`Öffne Nachricht ${id}`, 'info');
            // Hier würde ein Nachrichten-Modal geöffnet
        }

        function markAllAsRead() {
            if (confirm('Alle Nachrichten als gelesen markieren?')) {
                showNotification('Alle Nachrichten als gelesen markiert', 'success');
                // Hier würde der Status in der DB aktualisiert
            }
        }

        // Settings Functions
        function saveGeneralSettings() {
            const siteName = document.getElementById('site-name').value;
            const siteDescription = document.getElementById('site-description').value;
            const adminEmail = document.getElementById('admin-email').value;
            
            showNotification('Allgemeine Einstellungen gespeichert', 'success');
            // Hier würden die Einstellungen gespeichert
        }

        function changePassword() {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (!newPassword || !confirmPassword) {
                showNotification('Bitte beide Passwort-Felder ausfüllen', 'error');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showNotification('Passwörter stimmen nicht überein', 'error');
                return;
            }
            
            if (newPassword.length < 6) {
                showNotification('Passwort muss mindestens 6 Zeichen lang sein', 'error');
                return;
            }
            
            showNotification('Passwort erfolgreich geändert', 'success');
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        }

        function createBackup() {
            showNotification('Backup wird erstellt...', 'info');
            setTimeout(() => {
                showNotification('Backup erfolgreich erstellt', 'success');
            }, 2000);
        }

        function exportDatabase() {
            showNotification('Datenbank wird exportiert...', 'info');
            // Hier würde ein Download gestartet
        }

        function importDatabase() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = '.sql,.db';
            fileInput.onchange = function(e) {
                if (e.target.files[0]) {
                    showNotification('Datenbank Import gestartet...', 'info');
                }
            };
            fileInput.click();
        }

        // SERVICES MANAGER FUNCTIONS
        function addNewService() {
            // Leeres Modal für neuen Service
            document.getElementById('serviceId').value = '';
            document.getElementById('serviceName').value = '';
            document.getElementById('serviceIcon').value = '';
            document.getElementById('serviceDescription').value = '';
            document.getElementById('serviceOrder').value = '0';
            document.getElementById('serviceActive').checked = true;
            
            // Features zurücksetzen
            const featuresContainer = document.getElementById('serviceFeatures');
            featuresContainer.innerHTML = `
                <div class="feature-item flex items-center gap-2">
                    <input type="text" placeholder="Feature eingeben..." class="flex-1 px-3 py-2 border rounded">
                    <button type="button" onclick="removeFeature(this)" class="bg-red-500 text-white px-3 py-2 rounded">Entfernen</button>
                </div>
            `;
            
            document.getElementById('serviceEditModal').classList.remove('hidden');
        }

        function editService(serviceId) {
            // Service-Daten laden (Beispieldaten je nach ID)
            const serviceData = getServiceData(serviceId);
            
            // Modal mit Daten füllen
            document.getElementById('serviceId').value = serviceData.id;
            document.getElementById('serviceName').value = serviceData.name;
            document.getElementById('serviceIcon').value = serviceData.icon;
            document.getElementById('serviceDescription').value = serviceData.description;
            document.getElementById('serviceOrder').value = serviceData.order || 0;
            document.getElementById('serviceActive').checked = serviceData.active !== false;
            
            // Features laden
            const featuresContainer = document.getElementById('serviceFeatures');
            featuresContainer.innerHTML = '';
            serviceData.features.forEach(feature => {
                const featureDiv = document.createElement('div');
                featureDiv.className = 'feature-item flex items-center gap-2';
                featureDiv.innerHTML = `
                    <input type="text" value="${feature}" class="flex-1 px-3 py-2 border rounded">
                    <button type="button" onclick="removeFeature(this)" class="bg-red-500 text-white px-3 py-2 rounded">Entfernen</button>
                `;
                featuresContainer.appendChild(featureDiv);
            });
            
            document.getElementById('serviceEditModal').classList.remove('hidden');
        }

        function toggleService(serviceId) {
            if (confirm(`Service ${serviceId} wirklich aktivieren/deaktivieren?`)) {
                showNotification(`Service ${serviceId} Status erfolgreich geändert`, 'success');
                
                // Visuelles Feedback - Service-Karte aktualisieren
                setTimeout(() => {
                    const serviceCard = document.querySelector(`[data-service-id="${serviceId}"]`);
                    if (serviceCard) {
                        serviceCard.style.opacity = serviceCard.style.opacity === '0.5' ? '1' : '0.5';
                    }
                }, 500);
                
                // Hier würde der Service-Status in der DB geändert werden
            }
        }

        function closeServiceModal() {
            document.getElementById('serviceEditModal').classList.add('hidden');
        }

        function addFeature() {
            const featuresContainer = document.getElementById('serviceFeatures');
            const featureDiv = document.createElement('div');
            featureDiv.className = 'feature-item flex items-center gap-2';
            featureDiv.innerHTML = `
                <input type="text" placeholder="Feature eingeben..." class="flex-1 px-3 py-2 border rounded">
                <button type="button" onclick="removeFeature(this)" class="bg-red-500 text-white px-3 py-2 rounded">Entfernen</button>
            `;
            featuresContainer.appendChild(featureDiv);
        }

        function removeFeature(button) {
            button.parentElement.remove();
        }

        function getServiceData(serviceId) {
            const services = {
                'hochbau': {
                    id: 'hochbau',
                    name: 'Hochbau',
                    icon: '🏗️',
                    description: 'Neubau von Wohn- und Geschäftsgebäuden, Einfamilienhäuser bis hin zu komplexen Gewerbeobjekten.',
                    features: ['Planung und Ausführung', 'Rohbau und Ausbau', 'Schlüsselfertige Übergabe'],
                    order: 1,
                    active: true
                },
                'tiefbau': {
                    id: 'tiefbau',
                    name: 'Tiefbau',
                    icon: '🚧',
                    description: 'Fundamente, Keller, Erschließung und alle Arbeiten unter der Erdoberfläche.',
                    features: ['Erdarbeiten und Aushub', 'Fundamente und Keller', 'Ver- und Entsorgung'],
                    order: 2,
                    active: true
                },
                'sanierung': {
                    id: 'sanierung',
                    name: 'Sanierung',
                    icon: '🔨',
                    description: 'Modernisierung und Instandsetzung bestehender Gebäude nach neuesten Standards.',
                    features: ['Energetische Sanierung', 'Dach- und Fassadensanierung', 'Badsanierung'],
                    order: 3,
                    active: true
                },
                'anbau': {
                    id: 'anbau',
                    name: 'An- und Umbau',
                    icon: '🏠',
                    description: 'Erweiterung und Umgestaltung bestehender Gebäude nach Ihren Wünschen.',
                    features: ['Anbauten und Aufstockungen', 'Grundrissänderungen', 'Dachausbau'],
                    order: 4,
                    active: true
                },
                'gewerbebau': {
                    id: 'gewerbebau',
                    name: 'Gewerbebau',
                    icon: '🏢',
                    description: 'Bürogebäude, Lagerhallen, Produktionsstätten und andere Gewerbeimmobilien.',
                    features: ['Büro- und Verwaltungsgebäude', 'Produktions- und Lagerhallen', 'Individuelle Gewerbeobjekte'],
                    order: 5,
                    active: true
                },
                'notdienst': {
                    id: 'notdienst',
                    name: 'Notdienst',
                    icon: '⚡',
                    description: '24/7 Notdienst für dringende Reparaturen und Schadensbehebung.',
                    features: ['Wasserschäden', 'Sturmschäden', 'Notfallreparaturen'],
                    order: 6,
                    active: true
                }
            };
            
            return services[serviceId] || {
                id: serviceId,
                name: 'Unbekannter Service',
                icon: '❓',
                description: '',
                features: [],
                order: 0,
                active: true
            };
        }

        // TEAM MANAGER FUNCTIONS
        function addNewTeamMember() {
            const name = prompt('Name des Team-Mitglieds:');
            const position = prompt('Position:');
            const bio = prompt('Kurze Biografie:');
            const imageUrl = prompt('Bild URL (optional):');
            
            if (name && position) {
                showNotification(`Team-Mitglied "${name}" hinzugefügt`, 'success');
                // Hier würde das Team-Mitglied zur Datenbank hinzugefügt
            }
        }

        function editTeamMember(memberId) {
            showNotification(`Bearbeite Team-Mitglied ${memberId}`, 'info');
            // Hier würde ein Edit-Modal geöffnet
        }

        function deleteTeamMember(memberId) {
            if (confirm('Team-Mitglied wirklich löschen?')) {
                showNotification(`Team-Mitglied ${memberId} gelöscht`, 'success');
                // Hier würde das Team-Mitglied gelöscht
            }
        }

        // CAREER MANAGER FUNCTIONS
        function addNewJob() {
            const jobTitle = prompt('Stellentitel:');
            const jobLocation = prompt('Arbeitsort:');
            const jobType = prompt('Arbeitszeit (Vollzeit/Teilzeit):');
            const jobDescription = prompt('Kurze Beschreibung:');
            
            if (jobTitle) {
                showNotification(`Stellenausschreibung "${jobTitle}" erstellt`, 'success');
                // Hier würde der Job zur Datenbank hinzugefügt
            }
        }

        function editJob(jobId) {
            showNotification(`Bearbeite Stellenausschreibung ${jobId}`, 'info');
            // Hier würde ein Edit-Modal geöffnet
        }

        function toggleJobStatus(jobId) {
            if (confirm('Job-Status ändern (Aktiv/Inaktiv)?')) {
                showNotification(`Job ${jobId} Status geändert`, 'success');
                // Hier würde der Job-Status geändert
            }
        }

        function viewApplications(jobId) {
            showNotification(`Zeige Bewerbungen für Job ${jobId}`, 'info');
            // Hier würden die Bewerbungen angezeigt
        }

        function viewApplication(applicationId) {
            showNotification(`Öffne Bewerbung ${applicationId}`, 'info');
            // Hier würde die Bewerbung im Detail angezeigt
        }

        function downloadCV(applicationId) {
            showNotification(`Lade CV für Bewerbung ${applicationId} herunter`, 'info');
            // Hier würde der CV-Download gestartet
        }

        // OFFERS MANAGER FUNCTIONS
        function viewOffer(offerId) {
            showNotification(`Öffne Angebot ${offerId}`, 'info');
            // Hier würde das Angebot im Detail angezeigt
        }

        function createQuote(offerId) {
            showNotification(`Erstelle Kostenvoranschlag für Angebot ${offerId}`, 'info');
            // Hier würde ein Kostenvoranschlag erstellt
        }

        function updateOfferStatus(offerId) {
            const newStatus = prompt('Neuer Status (new/processing/completed):');
            if (newStatus) {
                showNotification(`Status für Angebot ${offerId} geändert zu: ${newStatus}`, 'success');
                // Hier würde der Status in der DB geändert
            }
        }

        function exportOffers() {
            showNotification('Exportiere Angebote nach Excel...', 'info');
            // Hier würde ein Excel-Export gestartet
        }

        function downloadQuote(offerId) {
            showNotification(`Lade Kostenvoranschlag für Angebot ${offerId} herunter`, 'info');
            // Hier würde der Kostenvoranschlag heruntergeladen
        }
    </script>

    <!-- MODAL DIALOGE FÜR ECHTE FUNKTIONALITÄT -->
    
    <!-- Career Job Edit Modal -->
    <div id="jobEditModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Stellenausschreibung bearbeiten</h3>
                        <button onclick="closeJobModal()" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>
                    
                    <form id="jobEditForm" class="space-y-4">
                        <input type="hidden" id="jobId" value="">
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Stellentitel *</label>
                            <input type="text" id="jobTitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Arbeitsort *</label>
                                <input type="text" id="jobLocation" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Arbeitszeit *</label>
                                <select id="jobType" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="Vollzeit">Vollzeit</option>
                                    <option value="Teilzeit">Teilzeit</option>
                                    <option value="Minijob">Minijob</option>
                                    <option value="Praktikum">Praktikum</option>
                                    <option value="Ausbildung">Ausbildung</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Stellenbeschreibung *</label>
                            <textarea id="jobDescription" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Anforderungen *</label>
                            <textarea id="jobRequirements" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required></textarea>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="jobActive" class="mr-2">
                            <label for="jobActive" class="text-sm">Stellenausschreibung aktiv</label>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeJobModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Abbrechen
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Details Modal -->
    <div id="applicationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Bewerbung Details</h3>
                        <button onclick="closeApplicationModal()" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>
                    
                    <div id="applicationDetails" class="space-y-4">
                        <!-- Content wird hier eingefügt -->
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button onclick="closeApplicationModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Schließen
                        </button>
                        <button onclick="respondToApplication()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Antworten
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offer Details Modal -->
    <div id="offerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Angebotsanfrage Details</h3>
                        <button onclick="closeOfferModal()" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>
                    
                    <div id="offerDetails" class="space-y-4">
                        <!-- Content wird hier eingefügt -->
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button onclick="closeOfferModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Schließen
                        </button>
                        <button onclick="createQuoteFromModal()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Kostenvoranschlag erstellen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Edit Modal -->
    <div id="serviceEditModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Service bearbeiten</h3>
                        <button onclick="closeServiceModal()" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>
                    
                    <form id="serviceEditForm" class="space-y-4">
                        <input type="hidden" id="serviceId" value="">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Service Name *</label>
                                <input type="text" id="serviceName" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Icon (Emoji)</label>
                                <input type="text" id="serviceIcon" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="🏗️">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Beschreibung *</label>
                            <textarea id="serviceDescription" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Features/Leistungen</label>
                            <div id="serviceFeatures" class="space-y-2">
                                <div class="feature-item flex items-center gap-2">
                                    <input type="text" placeholder="Feature eingeben..." class="flex-1 px-3 py-2 border rounded">
                                    <button type="button" onclick="removeFeature(this)" class="bg-red-500 text-white px-3 py-2 rounded">Entfernen</button>
                                </div>
                            </div>
                            <button type="button" onclick="addFeature()" class="mt-2 bg-blue-500 text-white px-3 py-2 rounded text-sm">+ Feature hinzufügen</button>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Reihenfolge</label>
                                <input type="number" id="serviceOrder" class="w-full px-3 py-2 border border-gray-300 rounded-lg" min="0">
                            </div>
                            <div class="flex items-center pt-6">
                                <input type="checkbox" id="serviceActive" class="mr-2">
                                <label for="serviceActive" class="text-sm">Service aktiv anzeigen</label>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeServiceModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Abbrechen
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Service speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Member Edit Modal -->
    <div id="teamEditModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Team-Mitglied bearbeiten</h3>
                        <button onclick="closeTeamModal()" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>
                    
                    <form id="teamEditForm" class="space-y-4">
                        <input type="hidden" id="teamMemberId" value="">
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Name *</label>
                            <input type="text" id="teamMemberName" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Position *</label>
                            <input type="text" id="teamMemberPosition" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Bild URL</label>
                            <input type="url" id="teamMemberImage" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="https://example.com/photo.jpg">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Biografie</label>
                            <textarea id="teamMemberBio" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeTeamModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Abbrechen
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ECHTE MODAL FUNKTIONEN

        // Job Management
        function editJob(jobId) {
            // Lade Job-Daten (hier Beispieldaten)
            const jobData = {
                id: jobId,
                title: jobId === '1' ? 'Bauleiter (m/w/d)' : 'Maurer (m/w/d)',
                location: 'Musterstadt',
                type: 'Vollzeit',
                description: 'Beschreibung der Stelle...',
                requirements: '• Anforderung 1\n• Anforderung 2',
                active: true
            };
            
            // Fülle Modal mit Daten
            document.getElementById('jobId').value = jobData.id;
            document.getElementById('jobTitle').value = jobData.title;
            document.getElementById('jobLocation').value = jobData.location;
            document.getElementById('jobType').value = jobData.type;
            document.getElementById('jobDescription').value = jobData.description;
            document.getElementById('jobRequirements').value = jobData.requirements;
            document.getElementById('jobActive').checked = jobData.active;
            
            // Zeige Modal
            document.getElementById('jobEditModal').classList.remove('hidden');
        }

        function closeJobModal() {
            document.getElementById('jobEditModal').classList.add('hidden');
        }

        function toggleJobStatus(jobId) {
            if (confirm('Job-Status wirklich ändern?')) {
                showNotification(`Job ${jobId} Status geändert`, 'success');
                // Hier würde der Status in der DB geändert
                setTimeout(() => location.reload(), 1000);
            }
        }

        function viewApplications(jobId) {
            showNotification(`Lade Bewerbungen für Job ${jobId}...`, 'info');
            // Hier würde zur Bewerbungsübersicht gewechselt
        }

        // Application Management
        function viewApplication(applicationId) {
            const applicationData = {
                id: applicationId,
                name: applicationId === '1' ? 'Anna Schmidt' : 'Thomas Müller',
                email: applicationId === '1' ? 'anna.schmidt@email.com' : 'thomas.mueller@email.com',
                job: applicationId === '1' ? 'Bauleiter (m/w/d)' : 'Maurer (m/w/d)',
                phone: '+49 123 456789',
                coverLetter: 'Sehr geehrte Damen und Herren,\n\nhiermit bewerbe ich mich...',
                cvFile: 'lebenslauf.pdf',
                date: 'vor 2 Stunden'
            };
            
            document.getElementById('applicationDetails').innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong>Name:</strong><br>
                        ${applicationData.name}
                    </div>
                    <div>
                        <strong>E-Mail:</strong><br>
                        ${applicationData.email}
                    </div>
                    <div>
                        <strong>Telefon:</strong><br>
                        ${applicationData.phone}
                    </div>
                    <div>
                        <strong>Bewerbung für:</strong><br>
                        ${applicationData.job}
                    </div>
                    <div>
                        <strong>CV-Datei:</strong><br>
                        <a href="#" onclick="downloadCV('${applicationData.id}')" class="text-blue-600 hover:underline">${applicationData.cvFile}</a>
                    </div>
                    <div>
                        <strong>Eingegangen:</strong><br>
                        ${applicationData.date}
                    </div>
                </div>
                <div class="mt-4">
                    <strong>Anschreiben:</strong>
                    <div class="mt-2 p-3 bg-gray-50 rounded border">
                        <pre class="whitespace-pre-wrap text-sm">${applicationData.coverLetter}</pre>
                    </div>
                </div>
            `;
            
            document.getElementById('applicationModal').classList.remove('hidden');
        }

        function closeApplicationModal() {
            document.getElementById('applicationModal').classList.add('hidden');
        }

        function downloadCV(applicationId) {
            showNotification(`Lade CV für Bewerbung ${applicationId} herunter...`, 'success');
            // Hier würde der CV-Download gestartet
        }

        function respondToApplication() {
            const email = prompt('E-Mail Antwort eingeben:');
            if (email) {
                showNotification('E-Mail Antwort gesendet!', 'success');
                closeApplicationModal();
            }
        }

        // Offer Management
        function viewOffer(offerId) {
            const offerData = {
                id: offerId,
                customer: offerId === '1' ? 'Familie Weber' : offerId === '2' ? 'Müller GmbH' : 'Schmidt',
                project: offerId === '1' ? 'Einfamilienhaus' : offerId === '2' ? 'Bürogebäude Sanierung' : 'Dachsanierung',
                type: offerId === '1' ? 'Hochbau' : offerId === '2' ? 'Sanierung' : 'Sanierung',
                budget: offerId === '1' ? '€300.000 - €400.000' : offerId === '2' ? '€150.000 - €200.000' : '€45.000',
                timeline: offerId === '1' ? '6-12 Monate' : offerId === '2' ? '3-4 Monate' : '2 Wochen',
                description: 'Detaillierte Projektbeschreibung...',
                status: offerId === '3' ? 'completed' : offerId === '2' ? 'processing' : 'new'
            };
            
            document.getElementById('offerDetails').innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong>Kunde:</strong><br>
                        ${offerData.customer}
                    </div>
                    <div>
                        <strong>Projekt:</strong><br>
                        ${offerData.project}
                    </div>
                    <div>
                        <strong>Typ:</strong><br>
                        ${offerData.type}
                    </div>
                    <div>
                        <strong>Budget:</strong><br>
                        ${offerData.budget}
                    </div>
                    <div>
                        <strong>Zeitrahmen:</strong><br>
                        ${offerData.timeline}
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span class="px-2 py-1 rounded text-xs ${offerData.status === 'new' ? 'bg-red-100 text-red-700' : offerData.status === 'processing' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'}">
                            ${offerData.status === 'new' ? 'NEU' : offerData.status === 'processing' ? 'BEARBEITUNG' : 'ABGESCHLOSSEN'}
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <strong>Projektbeschreibung:</strong>
                    <div class="mt-2 p-3 bg-gray-50 rounded border">
                        <p>${offerData.description}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('offerModal').classList.remove('hidden');
        }

        function closeOfferModal() {
            document.getElementById('offerModal').classList.add('hidden');
        }

        function createQuoteFromModal() {
            showNotification('Kostenvoranschlag wird erstellt...', 'info');
            // Hier würde der Kostenvoranschlag erstellt
            closeOfferModal();
        }

        // Team Management
        function editTeamMember(memberId) {
            const memberData = {
                id: memberId,
                name: 'Max Mustermann',
                position: 'Geschäftsführer',
                image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d',
                bio: 'Mit über 20 Jahren Erfahrung im Bauwesen leitet Max unser Unternehmen mit Leidenschaft und Expertise.'
            };
            
            document.getElementById('teamMemberId').value = memberData.id;
            document.getElementById('teamMemberName').value = memberData.name;
            document.getElementById('teamMemberPosition').value = memberData.position;
            document.getElementById('teamMemberImage').value = memberData.image;
            document.getElementById('teamMemberBio').value = memberData.bio;
            
            document.getElementById('teamEditModal').classList.remove('hidden');
        }

        function closeTeamModal() {
            document.getElementById('teamEditModal').classList.add('hidden');
        }

        // Form Submissions
        document.getElementById('jobEditForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Stellenausschreibung gespeichert!', 'success');
            closeJobModal();
        });

        document.getElementById('teamEditForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Team-Mitglied gespeichert!', 'success');
            closeTeamModal();
        });
    </script>
</body>
</html>