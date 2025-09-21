<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/PageContent.php';

// Simple authentication check
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$pageTitle = 'Universal Admin Panel - Hohmann Bau';
$db = new Database();
$pageContent = new PageContent($db);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'save_page_content':
            $page_name = $_POST['page_name'];
            $content = json_decode($_POST['content'], true);
            $result = $pageContent->saveContent($page_name, $content);
            echo json_encode(['success' => $result]);
            exit();
            
        case 'get_page_content':
            $page_name = $_POST['page_name'];
            $content = $pageContent->getContent($page_name);
            echo json_encode($content ?: []);
            exit();
            
        case 'save_design_settings':
            $setting_type = $_POST['setting_type'];
            $settings = json_decode($_POST['settings'], true);
            $result = $pageContent->saveDesignSettings($setting_type, $settings);
            echo json_encode(['success' => $result]);
            exit();
            
        case 'get_design_settings':
            $setting_type = $_POST['setting_type'];
            $settings = $pageContent->getDesignSettings($setting_type);
            echo json_encode($settings ?: []);
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

                    <!-- Content Management -->
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

            <!-- Other sections would be here (navigation-editor, content-manager, projects, messages, settings) -->
            <div id="navigation-editor-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">🧭 Navigation Editor</h1>
                    <p class="text-gray-600">Bearbeiten Sie das Hauptmenü und die Navigation Ihrer Website</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <p class="text-center text-gray-500 py-12">Navigation Editor wird implementiert...</p>
                </div>
            </div>

            <div id="content-manager-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">📝 Content Manager</h1>
                    <p class="text-gray-600">Verwalten Sie alle Inhalte und Texte</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <p class="text-center text-gray-500 py-12">Content Manager wird implementiert...</p>
                </div>
            </div>

            <div id="projects-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">🏗️ Projekte</h1>
                    <p class="text-gray-600">Verwalten Sie Ihr Projekt-Portfolio</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <p class="text-center text-gray-500 py-12">Projekt-Manager wird implementiert...</p>
                </div>
            </div>

            <div id="messages-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">💬 Nachrichten</h1>
                    <p class="text-gray-600">Verwalten Sie Kontaktanfragen und Nachrichten</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <p class="text-center text-gray-500 py-12">Nachrichten-Manager wird implementiert...</p>
                </div>
            </div>

            <div id="settings-section" class="section hidden">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">⚙️ Einstellungen</h1>
                    <p class="text-gray-600">System-Einstellungen und Konfiguration</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <p class="text-center text-gray-500 py-12">Einstellungen werden implementiert...</p>
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
            const formData = new FormData(form);
            
            // Convert form data to object
            const content = {};
            for (let [key, value] of formData.entries()) {
                content[key] = value;
            }
            
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
                body: `action=save_page_content&page_name=${pageName}&content=${encodeURIComponent(JSON.stringify(content))}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Inhalte erfolgreich gespeichert!', 'success');
                } else {
                    showNotification('Fehler beim Speichern!', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving content:', error);
                showNotification('Fehler beim Speichern!', 'error');
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
            fetch('universal_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=save_design_settings&setting_type=${settingType}&settings=${encodeURIComponent(JSON.stringify(settings))}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`${settingType} Einstellungen gespeichert!`, 'success');
                } else {
                    showNotification('Fehler beim Speichern!', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving design settings:', error);
                showNotification('Fehler beim Speichern!', 'error');
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
    </script>
</body>
</html>