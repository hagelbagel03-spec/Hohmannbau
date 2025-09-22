<?php
$title = 'Über uns - Hohmann Bau';
$description = 'Erfahren Sie mehr über Hohmann Bau - Ihr Experte für Garten- und Landschaftsbau seit über 25 Jahren.';
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
                <a href="about.php" class="nav-link active">Über uns</a>
                <a href="services.php" class="nav-link">Leistungen</a>
                <a href="team.php" class="nav-link">Team</a>
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
        <h1 class="heading-1 text-white mb-6">Über uns</h1>
        <p class="text-large text-gray-100 max-w-3xl mx-auto">
            Seit über 25 Jahren Ihr zuverlässiger Partner für Garten- und Landschaftsbau
        </p>
    </div>
</section>

<!-- Content -->
<section class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="heading-2 font-heading text-gray-900 mb-6">Unsere Geschichte</h2>
                <p class="text-body text-gray-600 mb-6">
                    Was 1998 als kleiner Familienbetrieb begann, ist heute ein erfolgreiches Unternehmen 
                    mit über 15 Mitarbeitern. Unsere Leidenschaft für Gärten und Landschaften treibt 
                    uns jeden Tag an, für unsere Kunden das Beste zu erreichen.
                </p>
                <p class="text-body text-gray-600 mb-8">
                    Mit jahrzehntelanger Erfahrung, modernster Technik und einem Team aus qualifizierten 
                    Garten- und Landschaftsbauern verwirklichen wir Ihre Gartenträume.
                </p>
                <a href="contact.php" class="btn-primary-pro">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Beratungstermin vereinbaren</span>
                </a>
            </div>
            <div>
                <div class="card-professional p-8">
                    <div class="text-center">
                        <i class="fas fa-award text-primary-500 text-5xl mb-6"></i>
                        <h3 class="heading-3 text-gray-900 mb-4">Ausgezeichnete Qualität</h3>
                        <p class="text-body text-gray-600">
                            Mehrfach ausgezeichnet für hervorragende Leistungen im Garten- und Landschaftsbau
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>