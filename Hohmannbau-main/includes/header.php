<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Hohmann Bau - Ihr zuverlässiger Partner' ?></title>
    <meta name="description" content="<?= $pageDescription ?? 'Hohmann Bau - Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen' ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1599995903128-531fc7fb694b?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwyfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-primary {
            background-color: #15803d;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        
        .btn-primary:hover {
            background-color: #166534;
            transform: scale(1.05);
        }
        
        .btn-outline {
            border: 2px solid white;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        
        .btn-outline:hover {
            background-color: white;
            color: #15803d;
        }
        
        .nav-active {
            color: #15803d;
            border-bottom: 2px solid #15803d;
            padding-bottom: 0.25rem;
        }
        
        /* Mobile Menu */
        .mobile-menu {
            display: none;
        }
        
        .mobile-menu.active {
            display: block;
        }
        
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
            .desktop-nav {
                display: none;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-toggle {
                display: none;
            }
            .desktop-nav {
                display: flex;
            }
        }
    </style>
    
    <!-- Icons from Lucide -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="font-bold text-2xl text-green-800 cursor-pointer" onclick="window.location.href='<?= BASE_URL ?>'">
                    Hohmann Bau
                </div>
                
                <!-- Desktop Navigation -->
                <div class="desktop-nav items-center space-x-8">
                    <a href="<?= BASE_URL ?>" class="text-sm font-medium transition-colors hover:text-green-700 <?= ($currentPage ?? '') === 'home' ? 'nav-active' : 'text-gray-600' ?>">Home</a>
                    <a href="<?= BASE_URL ?>leistungen.php" class="text-sm font-medium transition-colors hover:text-green-700 <?= ($currentPage ?? '') === 'services' ? 'nav-active' : 'text-gray-600' ?>">Leistungen</a>
                    <a href="<?= BASE_URL ?>projekte.php" class="text-sm font-medium transition-colors hover:text-green-700 <?= ($currentPage ?? '') === 'projects' ? 'nav-active' : 'text-gray-600' ?>">Projekte</a>
                    <a href="<?= BASE_URL ?>team.php" class="text-sm font-medium transition-colors hover:text-green-700 <?= ($currentPage ?? '') === 'team' ? 'nav-active' : 'text-gray-600' ?>">Team</a>
                    <a href="<?= BASE_URL ?>kontakt.php" class="text-sm font-medium transition-colors hover:text-green-700 <?= ($currentPage ?? '') === 'contact' ? 'nav-active' : 'text-gray-600' ?>">Kontakt</a>
                    <a href="<?= BASE_URL ?>karriere.php" class="text-sm font-medium transition-colors hover:text-green-700 flex items-center <?= ($currentPage ?? '') === 'career' ? 'nav-active' : 'text-gray-600' ?>">
                        <i data-lucide="briefcase" class="w-4 h-4 mr-1"></i>
                        Karriere
                    </a>
                    <a href="<?= BASE_URL ?>angebot.php" class="btn-primary text-sm">
                        <i data-lucide="calculator" class="w-4 h-4 mr-1"></i>
                        Angebot anfordern
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="mobile-toggle">
                    <button class="text-gray-600 hover:text-green-700" onclick="toggleMobileMenu()">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="mobile-menu md:hidden" id="mobileMenu">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                    <a href="<?= BASE_URL ?>" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-green-700">Home</a>
                    <a href="<?= BASE_URL ?>leistungen.php" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-green-700">Leistungen</a>
                    <a href="<?= BASE_URL ?>projekte.php" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-green-700">Projekte</a>
                    <a href="<?= BASE_URL ?>team.php" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-green-700">Team</a>
                    <a href="<?= BASE_URL ?>kontakt.php" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-green-700">Kontakt</a>
                    <a href="<?= BASE_URL ?>karriere.php" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-green-700">Karriere</a>
                    <a href="<?= BASE_URL ?>angebot.php" class="block px-3 py-2 text-base font-medium bg-green-700 text-white rounded-lg mt-2">Angebot anfordern</a>
                </div>
            </div>
        </div>
    </nav>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
        }
        
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>