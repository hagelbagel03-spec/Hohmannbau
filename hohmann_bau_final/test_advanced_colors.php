<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erweiterte Farb-Verwaltung - Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .admin-sidebar { background: linear-gradient(135deg, #1f2937 0%, #111827 100%); }
        .admin-content { background: #f8fafc; min-height: 100vh; }
        .btn-primary { background: linear-gradient(135deg, #10b981 0%, #065f46 100%); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
        
        /* Demo-Farben */
        :root {
            --footer-bg: #1f2937;
            --footer-text: #ffffff;
            --header-bg: #ffffff;
            --header-text: #1f2937;
            --button-primary: #10b981;
            --button-secondary: #6b7280;
            --accent-color: #3b82f6;
            --body-text: #374151;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    <!-- Admin Sidebar -->
    <div class="admin-sidebar w-64 shadow-lg">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">Hohmann Bau</h1>
                    <p class="text-gray-300 text-sm">Admin-Panel</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="space-y-2">
                <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-palette w-5"></i>
                    <span>Design (Einfach)</span>
                </a>
                
                <a href="#" class="flex items-center space-x-3 text-white bg-gray-700 px-3 py-2 rounded-lg">
                    <i class="fas fa-sliders-h w-5"></i>
                    <span>Erweiterte Farben</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 admin-content">
        <div class="p-8">
            <div class="admin-header">
                <h1 class="text-3xl font-bold text-gray-900">🎨 Erweiterte Design-Anpassung</h1>
                <p class="text-gray-600">Passen Sie alle Farben und Design-Elemente Ihrer Website an</p>
            </div>

            <!-- Success Message Demo -->
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Demo-Modus - Alle Funktionen verfügbar!
            </div>

            <!-- Live Preview -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-eye mr-2 text-blue-600"></i>
                    Live-Vorschau
                </h2>
                <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                    <!-- Header Preview -->
                    <div class="p-4" style="background-color: var(--header-bg); color: var(--header-text)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold">Header-Bereich</h3>
                            <button class="px-4 py-2 rounded" style="background-color: var(--button-primary); color: white;">
                                Primary Button
                            </button>
                        </div>
                    </div>
                    
                    <!-- Body Preview -->
                    <div class="p-4 bg-white" style="color: var(--body-text)">
                        <h4 class="text-lg font-semibold mb-2">Inhaltsbereiche</h4>
                        <p class="mb-4">Hier ist der normale Text-Bereich Ihrer Website. Lorem ipsum dolor sit amet.</p>
                        <button class="px-4 py-2 rounded mr-2" style="background-color: var(--button-secondary); color: white;">
                            Secondary Button
                        </button>
                        <span class="px-3 py-1 rounded text-sm" style="background-color: var(--accent-color); color: white;">
                            Accent Color
                        </span>
                    </div>
                    
                    <!-- Footer Preview -->
                    <div class="p-4" style="background-color: var(--footer-bg); color: var(--footer-text)">
                        <div class="text-center">
                            <h4 class="font-semibold mb-2">Footer-Bereich</h4>
                            <p class="text-sm opacity-90">© 2024 Hohmann Bau. Alle Rechte vorbehalten.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Schnelle Farbthemen -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
                        <i class="fas fa-palette mr-2 text-purple-600"></i>
                        Schnelle Farbthemen
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Green Theme -->
                        <div class="border-2 border-green-500 rounded-lg p-4">
                            <div class="text-center">
                                <div class="w-full h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-lg mb-2"></div>
                                <h3 class="font-semibold text-gray-900">🌿 Grün</h3>
                                <p class="text-xs text-gray-600">Natur & Garten</p>
                                <span class="inline-block w-4 h-4 rounded-full bg-green-500 mt-2"></span>
                            </div>
                        </div>

                        <!-- Blue Theme -->
                        <div class="border-2 border-gray-200 rounded-lg p-4">
                            <div class="text-center">
                                <div class="w-full h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg mb-2"></div>
                                <h3 class="font-semibold text-gray-900">💙 Blau</h3>
                                <p class="text-xs text-gray-600">Professional</p>
                                <span class="inline-block w-4 h-4 rounded-full border-2 border-gray-300 mt-2"></span>
                            </div>
                        </div>

                        <!-- Purple Theme -->
                        <div class="border-2 border-gray-200 rounded-lg p-4">
                            <div class="text-center">
                                <div class="w-full h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg mb-2"></div>
                                <h3 class="font-semibold text-gray-900">💜 Lila</h3>
                                <p class="text-xs text-gray-600">Elegant</p>
                                <span class="inline-block w-4 h-4 rounded-full border-2 border-gray-300 mt-2"></span>
                            </div>
                        </div>

                        <!-- Red Theme -->
                        <div class="border-2 border-gray-200 rounded-lg p-4">
                            <div class="text-center">
                                <div class="w-full h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-lg mb-2"></div>
                                <h3 class="font-semibold text-gray-900">❤️ Rot</h3>
                                <p class="text-xs text-gray-600">Kraftvoll</p>
                                <span class="inline-block w-4 h-4 rounded-full border-2 border-gray-300 mt-2"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Individuelle Farbanpassung -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
                        <i class="fas fa-sliders-h mr-2 text-orange-600"></i>
                        Individuelle Farbanpassung
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- Footer Colors -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-grip-lines mr-2 text-gray-600"></i>
                                Footer-Farben
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hintergrund</label>
                                    <input type="color" value="#1f2937" class="w-full h-10 border border-gray-300 rounded cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                                    <input type="color" value="#ffffff" class="w-full h-10 border border-gray-300 rounded cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <!-- Header Colors -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-window-maximize mr-2 text-gray-600"></i>
                                Header-Farben
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hintergrund</label>
                                    <input type="color" value="#ffffff" class="w-full h-10 border border-gray-300 rounded cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                                    <input type="color" value="#1f2937" class="w-full h-10 border border-gray-300 rounded cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <!-- Button Colors -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-mouse-pointer mr-2 text-gray-600"></i>
                                Button-Farben
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary</label>
                                    <input type="color" value="#10b981" class="w-full h-10 border border-gray-300 rounded cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Secondary</label>
                                    <input type="color" value="#6b7280" class="w-full h-10 border border-gray-300 rounded cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-undo mr-2"></i>
                            Zurücksetzen
                        </button>
                        <button type="button" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Farben speichern
                        </button>
                    </div>
                </div>
            </div>

            <!-- CSS Export Demo -->
            <div class="mt-6 bg-gray-50 rounded-xl shadow-inner p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-code mr-2 text-indigo-600"></i>
                    Generiertes CSS
                </h2>
                <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
<pre>:root {
    --footer-bg: #1f2937;
    --footer-text: #ffffff;
    --header-bg: #ffffff;
    --header-text: #1f2937;
    --button-primary: #10b981;
    --button-secondary: #6b7280;
    --accent-color: #3b82f6;
    --body-text: #374151;
}

.footer { background-color: var(--footer-bg); color: var(--footer-text); }
.header { background-color: var(--header-bg); color: var(--header-text); }
.btn-primary { background-color: var(--button-primary); }</pre>
                </div>
            </div>

            <!-- Features List -->
            <div class="mt-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                    Neue Funktionen der erweiterten Farb-Verwaltung
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Footer-Farben anpassbar</h3>
                            <p class="text-sm text-gray-600">Hintergrund- und Textfarben des Footers individuell einstellen</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Header-Design</h3>
                            <p class="text-sm text-gray-600">Komplette Header-Farbgebung nach Ihren Wünschen</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Button-Farben</h3>
                            <p class="text-sm text-gray-600">Primary und Secondary Buttons individuell gestalten</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Live-Vorschau</h3>
                            <p class="text-sm text-gray-600">Sofortige Darstellung der Änderungen</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">CSS-Export</h3>
                            <p class="text-sm text-gray-600">Automatisch generiertes CSS für Entwickler</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Reset-Funktion</h3>
                            <p class="text-sm text-gray-600">Zurück zu Standard-Farben mit einem Klick</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>