<?php
$title = 'Team - Hohmann Bau';
$description = 'Lernen Sie unser erfahrenes Team kennen - Experten für Garten- und Landschaftsbau.';
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
                <a href="team.php" class="nav-link active">Team</a>
                <a href="careers.php" class="nav-link">Karriere</a>
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
        <h1 class="heading-1 text-white mb-6">Unser Team</h1>
        <p class="text-large text-gray-100 max-w-3xl mx-auto">
            Lernen Sie die Experten kennen, die Ihre Gartenträume verwirklichen
        </p>
    </div>
</section>

<!-- Team Content -->
<section class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card-professional p-8 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <h3 class="heading-3 text-gray-900 mb-2">Klaus Hohmann</h3>
                <p class="text-primary-600 font-semibold mb-3">Geschäftsführer & Gartenbaumeister</p>
                <p class="text-body text-gray-600">Über 25 Jahre Erfahrung im Garten- und Landschaftsbau</p>
            </div>
            
            <div class="card-professional p-8 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <h3 class="heading-3 text-gray-900 mb-2">Sarah Müller</h3>
                <p class="text-primary-600 font-semibold mb-3">Gartendesignerin</p>
                <p class="text-body text-gray-600">Spezialistin für moderne Gartenplanung und 3D-Design</p>
            </div>
            
            <div class="card-professional p-8 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <h3 class="heading-3 text-gray-900 mb-2">Thomas Weber</h3>
                <p class="text-primary-600 font-semibold mb-3">Bauleiter</p>
                <p class="text-body text-gray-600">Experte für komplexe Landschaftsbauprojekte</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>