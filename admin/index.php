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
                        <a href="<?= BASE_URL ?>" class="font-bold text-2xl text-green-800">Hohmann Bau</a>
                        <span class="ml-4 text-gray-500">Admin Panel</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Willkommen, Admin</span>
                        <a href="<?= BASE_URL ?>" class="text-sm text-green-600 hover:text-green-700">Zur Website</a>
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
                            <button onclick="showQuoteDetails('${quote.id}')" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Details ansehen</button>
                            <button onclick="createOffer('${quote.id}')" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 ml-2">Angebot erstellen</button>
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
                            <button onclick="showApplicationDetails('${app.id}')" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Details ansehen</button>
                            <button onclick="downloadCV('${app.cv_filename}')" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 ml-2">CV herunterladen</button>
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

        // Content Management Functions
        function editContent(pageType) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-gray-900">Inhalt bearbeiten: ${pageType.charAt(0).toUpperCase() + pageType.slice(1)}</h3>
                            <button onclick="closeModal(this)" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="contentForm">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Titel</label>
                                    <input type="text" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Seitentitel eingeben">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Untertitel</label>
                                    <input type="text" id="subtitle" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Untertitel eingeben">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                                    <textarea id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Beschreibung eingeben"></textarea>
                                </div>
                                ${pageType === 'home' ? `
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Bild URL</label>
                                    <input type="url" id="heroImage" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="https://example.com/image.jpg">
                                </div>
                                ` : ''}
                            </div>
                            <div class="mt-6 flex gap-4">
                                <button type="button" onclick="saveContent('${pageType}')" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                                    <i data-lucide="save" class="w-4 h-4 mr-2 inline"></i>Speichern
                                </button>
                                <button type="button" onclick="closeModal(this)" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50">Abbrechen</button>
                            </div>
                        </form>
                        <div id="saveMessage" class="hidden mt-4 p-4 rounded-lg"></div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            
            // Load existing content
            fetch(`<?= BASE_URL ?>api/index.php/content/${pageType}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.content) {
                        document.getElementById('title').value = data.content.title || '';
                        document.getElementById('subtitle').value = data.content.subtitle || '';
                        document.getElementById('description').value = data.content.description || '';
                        if (pageType === 'home' && data.content.hero_image) {
                            document.getElementById('heroImage').value = data.content.hero_image;
                        }
                    }
                })
                .catch(error => console.log('Could not load existing content'));
            
            lucide.createIcons();
        }

        function saveContent(pageType) {
            const title = document.getElementById('title').value;
            const subtitle = document.getElementById('subtitle').value;
            const description = document.getElementById('description').value;
            
            const content = {
                title: title,
                subtitle: subtitle,
                description: description
            };
            
            if (pageType === 'home') {
                const heroImage = document.getElementById('heroImage').value;
                if (heroImage) {
                    content.hero_image = heroImage;
                    content.hero_title = title;
                    content.hero_subtitle = subtitle;
                }
            }
            
            fetch(`<?= BASE_URL ?>api/index.php/content`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    page_name: pageType,
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('saveMessage');
                messageDiv.className = 'mt-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800';
                messageDiv.innerHTML = '<i data-lucide="check-circle" class="w-5 h-5 mr-2 inline"></i>Inhalt erfolgreich gespeichert!';
                messageDiv.classList.remove('hidden');
                lucide.createIcons();
                
                setTimeout(() => {
                    closeModal(messageDiv);
                }, 2000);
            })
            .catch(error => {
                const messageDiv = document.getElementById('saveMessage');
                messageDiv.className = 'mt-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800';
                messageDiv.innerHTML = '<i data-lucide="alert-circle" class="w-5 h-5 mr-2 inline"></i>Fehler beim Speichern!';
                messageDiv.classList.remove('hidden');
                lucide.createIcons();
            });
        }

        // Media Management Functions
        function manageMedia(type) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-gray-900">${type === 'images' ? 'Bilder' : 'Dokumente'} verwalten</h3>
                            <button onclick="closeModal(this)" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-6">
                            <i data-lucide="upload" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Dateien hochladen</h4>
                            <p class="text-gray-600 mb-4">Ziehen Sie Dateien hierher oder klicken Sie zum Auswählen</p>
                            <input type="file" id="fileUpload" multiple class="hidden" accept="${type === 'images' ? 'image/*' : '.pdf,.doc,.docx'}">
                            <button onclick="document.getElementById('fileUpload').click()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                Dateien auswählen
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="mediaGrid">
                            ${type === 'images' ? `
                                <div class="border rounded-lg p-4 text-center">
                                    <img src="https://via.placeholder.com/150" class="w-full h-24 object-cover rounded mb-2">
                                    <p class="text-sm text-gray-600">beispiel.jpg</p>
                                    <button class="text-red-600 text-xs hover:underline mt-1">Löschen</button>
                                </div>
                            ` : `
                                <div class="border rounded-lg p-4 text-center">
                                    <i data-lucide="file" class="w-12 h-12 mx-auto text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">dokument.pdf</p>
                                    <button class="text-red-600 text-xs hover:underline mt-1">Löschen</button>
                                </div>
                            `}
                        </div>
                        
                        <div class="mt-6">
                            <button onclick="closeModal(this)" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">Schließen</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            lucide.createIcons();
        }

        // Quote Management Functions
        function showQuoteDetails(quoteId) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-gray-900">Angebots-Details</h3>
                            <button onclick="closeModal(this)" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Name</label>
                                    <p class="text-gray-900">Thomas Weber</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">E-Mail</label>
                                    <p class="text-gray-900">thomas@weber-family.de</p>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Projekttyp</label>
                                <p class="text-gray-900">Neubau</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Budget</label>
                                <p class="text-gray-900">250.000€ - 500.000€</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Beschreibung</label>
                                <p class="text-gray-900">Wir möchten ein Einfamilienhaus mit ca. 150m² Wohnfläche bauen. Das Haus soll energieeffizient sein und über einen Keller verfügen.</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Status</label>
                                <select class="mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option>Neu</option>
                                    <option>In Bearbeitung</option>
                                    <option>Angebot erstellt</option>
                                    <option>Abgeschlossen</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <button onclick="createOffer('${quoteId}')" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                                <i data-lucide="calculator" class="w-4 h-4 mr-2 inline"></i>Angebot erstellen
                            </button>
                            <button onclick="closeModal(this)" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50">Schließen</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            lucide.createIcons();
        }

        function createOffer(quoteId) {
            alert('Angebots-Erstellung wird geöffnet...\n\nHier würde normalerweise ein Angebots-Editor geöffnet werden, wo Sie ein detailliertes Angebot erstellen können.');
        }

        // Application Management Functions
        function showApplicationDetails(appId) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-gray-900">Bewerbungs-Details</h3>
                            <button onclick="closeModal(this)" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Name</label>
                                    <p class="text-gray-900">Michael Klein</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">E-Mail</label>
                                    <p class="text-gray-900">michael.klein@email.de</p>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Position</label>
                                <p class="text-gray-900">Bauleiter (m/w/d)</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Anschreiben</label>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-900">Sehr geehrte Damen und Herren, hiermit bewerbe ich mich um die ausgeschriebene Stelle als Bauleiter. Mit über 8 Jahren Berufserfahrung bringe ich umfassende Kenntnisse im Projektmanagement mit...</p>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Status</label>
                                <select class="mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option>Neu</option>
                                    <option>Geprüft</option>
                                    <option>Einladung verschickt</option>
                                    <option>Abgelehnt</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <button onclick="downloadCV('michael_klein_cv.pdf')" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                <i data-lucide="download" class="w-4 h-4 mr-2 inline"></i>CV herunterladen
                            </button>
                            <button onclick="closeModal(this)" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50">Schließen</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            lucide.createIcons();
        }

        function downloadCV(filename) {
            if (filename && filename !== 'null') {
                window.open(`<?= BASE_URL ?>uploads/cv/${filename}`, '_blank');
            } else {
                alert('Kein CV verfügbar');
            }
        }

        // Modal Management
        function closeModal(element) {
            const modal = element.closest('.fixed.inset-0') || element.closest('div[class*="fixed inset-0"]');
            if (modal) {
                document.body.removeChild(modal);
                document.body.style.overflow = 'auto';
            }
        }
    </script>
</body>
</html>