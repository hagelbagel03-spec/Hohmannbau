<?php
$title = 'Karriere - Hohmann Bau';
$description = 'Karriere bei Hohmann Bau - Werden Sie Teil unseres Teams im Garten- und Landschaftsbau.';
include 'includes/header.php';
?>

<!-- Navigation -->
<nav class="navbar fixed top-0 left-0 right-0 z-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Hohmann Bau</h1>
                    <p class="text-xs text-gray-600">Garten & Landschaftsbau</p>
                </div>
            </div>
            <div class="hidden lg:flex items-center space-x-2">
                <a href="index.php" class="nav-link">Home</a>
                <a href="about.php" class="nav-link">Über uns</a>
                <a href="services.php" class="nav-link">Leistungen</a>
                <a href="team.php" class="nav-link">Team</a>
                <a href="careers.php" class="nav-link active">Karriere</a>
                <a href="news.php" class="nav-link">Aktuelles</a>
                <a href="contact.php" class="nav-link">Kontakt</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="contact.php" class="btn-primary-pro">
                    <i class="fas fa-envelope"></i>
                    <span>Kontakt</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-gradient section-professional pt-32 pb-20 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="heading-1 text-white mb-6">Karriere</h1>
        <p class="text-large text-gray-100 max-w-3xl mx-auto">
            Werden Sie Teil unseres Teams und gestalten Sie mit uns die Gärten von morgen
        </p>
    </div>
</section>

<!-- Jobs Content -->
<section class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="card-professional p-8">
                <h3 class="heading-3 text-gray-900 mb-4">Gärtner (m/w/d)</h3>
                <p class="text-body text-gray-600 mb-4">
                    Wir suchen einen erfahrenen Gärtner für unser Team. Vollzeit, unbefristet.
                </p>
                <div class="flex items-center space-x-4 mb-4">
                    <span class="bg-primary-100 text-primary-600 px-3 py-1 rounded-full text-sm font-semibold">Vollzeit</span>
                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-semibold">Unbefristet</span>
                </div>
                <a href="contact.php" class="btn-primary-pro w-full justify-center">
                    <i class="fas fa-paper-plane"></i>
                    <span>Jetzt bewerben</span>
                </a>
            </div>
            
            <div class="card-professional p-8">
                <h3 class="heading-3 text-gray-900 mb-4">Landschaftsgärtner (m/w/d)</h3>
                <p class="text-body text-gray-600 mb-4">
                    Für größere Projekte suchen wir einen Landschaftsgärtner mit Erfahrung.
                </p>
                <div class="flex items-center space-x-4 mb-4">
                    <span class="bg-primary-100 text-primary-600 px-3 py-1 rounded-full text-sm font-semibold">Vollzeit</span>
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">Sofort</span>
                </div>
                <a href="contact.php" class="btn-primary-pro w-full justify-center">
                    <i class="fas fa-paper-plane"></i>
                    <span>Jetzt bewerben</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>