<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colors Test - Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-sidebar {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        }
        .admin-content {
            background: #f8fafc;
            min-height: 100vh;
        }
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #065f46 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
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
                    <i class="fas fa-home w-5"></i>
                    <span>Homepage</span>
                </a>
                
                <a href="#" class="flex items-center space-x-3 text-white bg-gray-700 px-3 py-2 rounded-lg">
                    <i class="fas fa-palette w-5"></i>
                    <span>Design</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 admin-content">
        <div class="p-8">
            <div class="admin-header">
                <h1 class="text-2xl font-bold text-gray-900">🎨 Farben & Design</h1>
                <p class="text-gray-600">Wählen Sie das Farbthema für Ihre Website</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold mb-6">Farbthema auswählen</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Green Theme -->
                    <div class="border-2 border-green-500 rounded-lg p-4">
                        <label class="cursor-pointer">
                            <div class="text-center">
                                <div class="w-full h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-lg mb-3"></div>
                                <h3 class="font-semibold text-gray-900">🌿 Grün</h3>
                                <p class="text-sm text-gray-600">Natürlich & frisch</p>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="inline-block w-4 h-4 rounded-full border-2 border-green-500 bg-green-500"></span>
                            </div>
                        </label>
                    </div>

                    <!-- Blue Theme -->
                    <div class="border-2 border-gray-200 rounded-lg p-4">
                        <label class="cursor-pointer">
                            <div class="text-center">
                                <div class="w-full h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg mb-3"></div>
                                <h3 class="font-semibold text-gray-900">💙 Blau</h3>
                                <p class="text-sm text-gray-600">Professional & vertrauensvoll</p>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="inline-block w-4 h-4 rounded-full border-2 border-gray-300"></span>
                            </div>
                        </label>
                    </div>

                    <!-- Purple Theme -->
                    <div class="border-2 border-gray-200 rounded-lg p-4">
                        <label class="cursor-pointer">
                            <div class="text-center">
                                <div class="w-full h-20 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg mb-3"></div>
                                <h3 class="font-semibold text-gray-900">💜 Lila</h3>
                                <p class="text-sm text-gray-600">Elegant & modern</p>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="inline-block w-4 h-4 rounded-full border-2 border-gray-300"></span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="button" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Farbthema speichern
                    </button>
                </div>
            </div>

            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Layout-Test erfolgreich</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>✅ Das Layout funktioniert jetzt korrekt!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>