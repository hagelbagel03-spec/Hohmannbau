<?php
require_once __DIR__ . '/../config/config.php';

// Simple admin panel - in production, add proper authentication
$pageTitle = 'Admin Panel - Hohmann Bau';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="<?= BASE_URL ?>/" class="font-bold text-2xl text-green-800">Hohmann Bau</a>
                        <span class="ml-4 text-gray-500">Admin Panel</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Willkommen, Admin</span>
                        <a href="<?= BASE_URL ?>/" class="text-sm text-green-600 hover:text-green-700">Zur Website</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex">
            <!-- Sidebar -->
            <nav class="w-64 bg-white shadow-md min-h-screen">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Navigation</h2>
                    <ul class="space-y-2">
                        <li>
                            <a href="#dashboard" onclick="showSection('dashboard')" class="nav-link flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#content" onclick="showSection('content')" class="nav-link flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                                <i data-lucide="file-text" class="w-5 h-5 mr-3"></i>
                                Inhalte verwalten
                            </a>
                        </li>
                        <li>
                            <a href="#messages" onclick="showSection('messages')" class="nav-link flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                                <i data-lucide="mail" class="w-5 h-5 mr-3"></i>
                                Nachrichten
                            </a>
                        </li>
                        <li>
                            <a href="#quotes" onclick="showSection('quotes')" class="nav-link flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                                <i data-lucide="calculator" class="w-5 h-5 mr-3"></i>
                                Angebots-Anfragen
                            </a>
                        </li>
                        <li>
                            <a href="#applications" onclick="showSection('applications')" class="nav-link flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                                <i data-lucide="briefcase" class="w-5 h-5 mr-3"></i>
                                Bewerbungen
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 p-8">
                <!-- Dashboard Section -->
                <div id="dashboard-section" class="section">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i data-lucide="mail" class="w-6 h-6 text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Nachrichten</p>
                                    <p id="messages-count" class="text-2xl font-bold text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <i data-lucide="calculator" class="w-6 h-6 text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Angebots-Anfragen</p>
                                    <p id="quotes-count" class="text-2xl font-bold text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <i data-lucide="briefcase" class="w-6 h-6 text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Bewerbungen</p>
                                    <p id="applications-count" class="text-2xl font-bold text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <i data-lucide="building" class="w-6 h-6 text-yellow-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Projekte</p>
                                    <p id="projects-count" class="text-2xl font-bold text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Willkommen im Admin Panel</h2>
                        <p class="text-gray-600 mb-4">
                            Hier können Sie alle Aspekte Ihrer Website verwalten. Nutzen Sie die Navigation links, um zwischen den verschiedenen Bereichen zu wechseln.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">Schnellzugriff</h3>
                                <ul class="space-y-2">
                                    <li><a href="#messages" onclick="showSection('messages')" class="text-blue-600 hover:underline">Neue Nachrichten anzeigen</a></li>
                                    <li><a href="#quotes" onclick="showSection('quotes')" class="text-blue-600 hover:underline">Angebots-Anfragen bearbeiten</a></li>
                                    <li><a href="#content" onclick="showSection('content')" class="text-blue-600 hover:underline">Inhalte bearbeiten</a></li>
                                </ul>
                            </div>
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">System Info</h3>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li>PHP Version: <?= phpversion() ?></li>
                                    <li>MySQL: Verbunden</li>
                                    <li>Letztes Update: <?= date('d.m.Y H:i') ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Management Section -->
                <div id="content-section" class="section hidden">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Inhalte verwalten</h1>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-gray-600 mb-4">
                            <i data-lucide="info" class="w-5 h-5 inline mr-2"></i>
                            Content Management System in Entwicklung. Hier können Sie später alle Texte und Bilder der Website bearbeiten.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Seiten</h3>
                                <ul class="space-y-2">
                                    <li class="flex justify-between items-center">
                                        <span>Homepage</span>
                                        <button onclick="editContent('home')" class="text-blue-600 hover:underline text-sm">Bearbeiten</button>
                                    </li>
                                    <li class="flex justify-between items-center">
                                        <span>Leistungen</span>
                                        <button onclick="editContent('services')" class="text-blue-600 hover:underline text-sm">Bearbeiten</button>
                                    </li>
                                    <li class="flex justify-between items-center">
                                        <span>Projekte</span>
                                        <button onclick="editContent('projects')" class="text-blue-600 hover:underline text-sm">Bearbeiten</button>
                                    </li>
                                    <li class="flex justify-between items-center">
                                        <span>Team</span>
                                        <button onclick="editContent('team')" class="text-blue-600 hover:underline text-sm">Bearbeiten</button>
                                    </li>
                                    <li class="flex justify-between items-center">
                                        <span>Kontakt</span>
                                        <button onclick="editContent('contact')" class="text-blue-600 hover:underline text-sm">Bearbeiten</button>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Medien</h3>
                                <ul class="space-y-2">
                                    <li class="flex justify-between items-center">
                                        <span>Bilder verwalten</span>
                                        <button onclick="manageMedia('images')" class="text-blue-600 hover:underline text-sm">Öffnen</button>
                                    </li>
                                    <li class="flex justify-between items-center">
                                        <span>Dokumente</span>
                                        <button onclick="manageMedia('documents')" class="text-blue-600 hover:underline text-sm">Öffnen</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Section -->
                <div id="messages-section" class="section hidden">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Kontaktnachrichten</h1>
                    
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-900">Eingegangene Nachrichten</h2>
                                <button onclick="loadMessages()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2 inline"></i>
                                    Aktualisieren
                                </button>
                            </div>
                        </div>
                        <div id="messages-list" class="divide-y divide-gray-200">
                            <div class="p-6 text-center text-gray-500">
                                <i data-lucide="mail" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>Nachrichten werden geladen...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quotes Section -->
                <div id="quotes-section" class="section hidden">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Angebots-Anfragen</h1>
                    
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-900">Angebots-Anfragen</h2>
                                <button onclick="loadQuotes()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2 inline"></i>
                                    Aktualisieren
                                </button>
                            </div>
                        </div>
                        <div id="quotes-list" class="divide-y divide-gray-200">
                            <div class="p-6 text-center text-gray-500">
                                <i data-lucide="calculator" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>Angebots-Anfragen werden geladen...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Section -->
                <div id="applications-section" class="section hidden">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Bewerbungen</h1>
                    
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-900">Eingegangene Bewerbungen</h2>
                                <button onclick="loadApplications()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2 inline"></i>
                                    Aktualisieren
                                </button>
                            </div>
                        </div>
                        <div id="applications-list" class="divide-y divide-gray-200">
                            <div class="p-6 text-center text-gray-500">
                                <i data-lucide="briefcase" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>Bewerbungen werden geladen...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        let currentSection = 'dashboard';

        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Show selected section
            document.getElementById(sectionName + '-section').classList.remove('hidden');
            
            // Update navigation
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('bg-green-100', 'text-green-700');
                link.classList.add('text-gray-700');
            });
            
            event.target.closest('a').classList.add('bg-green-100', 'text-green-700');
            event.target.closest('a').classList.remove('text-gray-700');
            
            currentSection = sectionName;
            
            // Load section data
            if (sectionName === 'messages') {
                loadMessages();
            } else if (sectionName === 'quotes') {
                loadQuotes();
            } else if (sectionName === 'applications') {
                loadApplications();
            }
        }

        function loadDashboardStats() {
            // This would normally fetch real data from the API
            // For demo purposes, we'll use placeholder data
            setTimeout(() => {
                document.getElementById('messages-count').textContent = '12';
                document.getElementById('quotes-count').textContent = '8';
                document.getElementById('applications-count').textContent = '5';
                document.getElementById('projects-count').textContent = '24';
            }, 500);
        }

        function loadMessages() {
            const messagesList = document.getElementById('messages-list');
            messagesList.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <i data-lucide="loader" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                    <p>Nachrichten werden geladen...</p>
                </div>
            `;
            
            // Simulate API call
            setTimeout(() => {
                messagesList.innerHTML = `
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">Max Mustermann</h3>
                                <p class="text-sm text-gray-600">max@example.com</p>
                            </div>
                            <span class="text-sm text-gray-500">vor 2 Stunden</span>
                        </div>
                        <p class="text-gray-700">Hallo, ich interessiere mich für eine Badsanierung und hätte gerne ein Angebot...</p>
                        <div class="mt-4">
                            <button class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Antworten</button>
                            <button class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 ml-2">Als erledigt markieren</button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">Anna Schmidt</h3>
                                <p class="text-sm text-gray-600">anna.schmidt@email.de</p>
                            </div>
                            <span class="text-sm text-gray-500">gestern</span>
                        </div>
                        <p class="text-gray-700">Guten Tag, wir planen einen Anbau und suchen einen zuverlässigen Partner...</p>
                        <div class="mt-4">
                            <button class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Antworten</button>
                            <button class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 ml-2">Als erledigt markieren</button>
                        </div>
                    </div>
                `;
                lucide.createIcons();
            }, 1000);
        }

        function loadQuotes() {
            const quotesList = document.getElementById('quotes-list');
            quotesList.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <i data-lucide="loader" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                    <p>Angebots-Anfragen werden geladen...</p>
                </div>
            `;
            
            setTimeout(() => {
                quotesList.innerHTML = `
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">Einfamilienhaus Neubau</h3>
                                <p class="text-sm text-gray-600">Thomas Weber • thomas@weber-family.de</p>
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Neu</span>
                        </div>
                        <p class="text-gray-700 mb-2">Projekttyp: Neubau | Budget: 250.000€ - 500.000€</p>
                        <p class="text-gray-600 text-sm">Wir möchten ein Einfamilienhaus mit ca. 150m² Wohnfläche bauen...</p>
                        <div class="mt-4">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Details ansehen</button>
                            <button class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 ml-2">Angebot erstellen</button>
                        </div>
                    </div>
                `;
                lucide.createIcons();
            }, 1000);
        }

        function loadApplications() {
            const applicationsList = document.getElementById('applications-list');
            applicationsList.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <i data-lucide="loader" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                    <p>Bewerbungen werden geladen...</p>
                </div>
            `;
            
            setTimeout(() => {
                applicationsList.innerHTML = `
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">Michael Klein</h3>
                                <p class="text-sm text-gray-600">Bewerbung als Bauleiter (m/w/d)</p>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Neu</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-2">michael.klein@email.de • +49 123 456789</p>
                        <p class="text-gray-700 text-sm">Sehr geehrte Damen und Herren, hiermit bewerbe ich mich um die ausgeschriebene Stelle...</p>
                        <div class="mt-4">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Details ansehen</button>
                            <button class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 ml-2">CV herunterladen</button>
                        </div>
                    </div>
                `;
                lucide.createIcons();
            }, 1000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            loadDashboardStats();
        });
    </script>
</body>
</html>