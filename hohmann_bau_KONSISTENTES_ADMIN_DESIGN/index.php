<?php
/**
 * Professional Homepage - Hohmann Bau
 */

require_once 'config/database.php';

// Get dynamic data
try {
    $db = getDB();
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
    $services_data = $db->query("SELECT * FROM services WHERE published = 1 ORDER BY sort_order ASC LIMIT 4")->fetchAll();
    $team_data = $db->query("SELECT * FROM team WHERE published = 1 ORDER BY sort_order ASC LIMIT 3")->fetchAll();
    $news_data = $db->query("SELECT * FROM news WHERE published = 1 ORDER BY created_at DESC LIMIT 3")->fetchAll();
} catch (Exception $e) {
    // Fallback data if database fails
    $homepage = [
        'hero_title' => 'Ihr Experte für Garten- und Landschaftsbau',
        'hero_subtitle' => 'Professionelle Gartengestaltung seit über 20 Jahren',
        'hero_image' => '',
        'phone_number' => '+49 123 456-789',
        'email' => 'info@hohmann-bau.de'
    ];
    
    $services_data = [
        ['title' => 'Gartenplanung', 'description' => 'Professionelle Planung und Design für Ihren Traumgarten', 'icon' => 'fas fa-seedling'],
        ['title' => 'Landschaftsbau', 'description' => 'Umfassende Gestaltung von Außenanlagen und Landschaften', 'icon' => 'fas fa-mountain'],
        ['title' => 'Pflanzarbeiten', 'description' => 'Fachgerechte Bepflanzung mit hochwertigen Pflanzen', 'icon' => 'fas fa-leaf'],
        ['title' => 'Gartenpflege', 'description' => 'Regelmäßige Pflege und Instandhaltung Ihrer Gartenanlage', 'icon' => 'fas fa-cut']
    ];
    
    $team_data = [
        ['name' => 'Klaus Hohmann', 'position' => 'Geschäftsführer & Gartenbaumeister', 'description' => 'Über 25 Jahre Erfahrung im Garten- und Landschaftsbau'],
        ['name' => 'Sarah Müller', 'position' => 'Gartendesignerin', 'description' => 'Spezialistin für moderne Gartenplanung'],
        ['name' => 'Thomas Weber', 'position' => 'Bauleiter', 'description' => 'Experte für Landschaftsbauprojekte']
    ];
    
    $news_data = [
        ['id' => 'news-1', 'title' => 'Neue Gartensaison 2024', 'excerpt' => 'Frühjahrsrabatt auf alle Planungsleistungen'],
        ['id' => 'news-2', 'title' => 'Erweiterte Öffnungszeiten', 'excerpt' => 'Mehr Service für unsere Kunden'],
        ['id' => 'news-3', 'title' => 'Auszeichnung erhalten', 'excerpt' => 'Qualität wird belohnt']
    ];
}

$title = 'Hohmann Bau - Ihr Experte für Garten- und Landschaftsbau';
$description = 'Professioneller Garten- und Landschaftsbau in höchster Qualität. Planung, Gestaltung und Pflege Ihrer Außenanlagen.';

include 'includes/header.php';
?>

<!-- Professional Navigation -->
<nav class="navbar fixed top-0 left-0 right-0 z-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Hohmann Bau</h1>
                    <p class="text-xs text-gray-600">Garten & Landschaftsbau</p>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden lg:flex items-center space-x-2">
                <a href="index.php" class="nav-link active">Home</a>
                <a href="about.php" class="nav-link">Über uns</a>
                <a href="services.php" class="nav-link">Leistungen</a>
                <a href="team.php" class="nav-link">Team</a>
                <a href="careers.php" class="nav-link">Karriere</a>
                <a href="news.php" class="nav-link">Aktuelles</a>
                <a href="contact.php" class="nav-link">Kontakt</a>
            </div>
            
            <!-- CTA Button -->
            <div class="flex items-center space-x-4">
                <a href="tel:<?php echo $homepage['phone_number']; ?>" class="hidden sm:flex items-center space-x-2 text-primary-600 font-semibold">
                    <i class="fas fa-phone"></i>
                    <span><?php echo $homepage['phone_number']; ?></span>
                </a>
                <a href="contact.php" class="btn-primary-pro">
                    <i class="fas fa-envelope"></i>
                    <span>Kontakt</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Professional Hero Section -->
<section class="hero-gradient section-professional pt-32 pb-20 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Hero Content -->
            <div class="animate-fade-in-up">
                <h1 class="heading-1 text-white mb-6">
                    <?php echo htmlspecialchars($homepage['hero_title']); ?>
                </h1>
                <p class="text-large text-gray-100 mb-8 leading-relaxed">
                    <?php echo htmlspecialchars($homepage['hero_subtitle']); ?>
                </p>
                
                <!-- Hero CTAs -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="contact.php" class="btn-primary-pro bg-white text-primary-600 hover:bg-gray-50">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Beratungstermin</span>
                    </a>
                    <a href="services.php" class="btn-secondary-pro border-white text-white hover:bg-white hover:text-primary-600">
                        <i class="fas fa-tools"></i>
                        <span>Unsere Leistungen</span>
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="mt-12 flex items-center space-x-8">
                    <div class="text-center">
                        <div class="text-2xl font-bold">25+</div>
                        <div class="text-sm text-gray-200">Jahre Erfahrung</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">150+</div>
                        <div class="text-sm text-gray-200">Projekte/Jahr</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">98%</div>
                        <div class="text-sm text-gray-200">Zufriedenheit</div>
                    </div>
                </div>
            </div>
            
            <!-- Hero Image -->
            <div class="animate-slide-in-right">
                <div class="relative">
                    <div class="w-full h-96 bg-white bg-opacity-10 rounded-2xl backdrop-blur-sm border border-white border-opacity-20 flex items-center justify-center">
                        <?php if ($homepage['hero_image']): ?>
                            <img src="<?php echo htmlspecialchars($homepage['hero_image']); ?>" 
                                 alt="Hohmann Bau Gartenprojekt" 
                                 class="w-full h-full object-cover rounded-2xl">
                        <?php else: ?>
                            <div class="text-center text-white">
                                <i class="fas fa-seedling text-6xl mb-4 opacity-50"></i>
                                <p class="text-lg font-semibold">Ihr Traumgarten</p>
                                <p class="text-sm opacity-75">wartet auf Sie</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 w-16 h-16 bg-yellow-400 rounded-full opacity-20 animate-bounce"></div>
                    <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-green-400 rounded-full opacity-20 animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Professional Services Section -->
<section class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="heading-2 font-heading text-gray-900 mb-4">Unsere Leistungen</h2>
            <p class="text-large text-gray-600 max-w-3xl mx-auto">
                Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($services_data as $service): ?>
                <div class="card-professional p-6 text-center">
                    <div class="service-icon mx-auto">
                        <i class="<?php echo $service['icon']; ?>"></i>
                    </div>
                    <h3 class="heading-3 text-gray-900 mb-3">
                        <?php echo htmlspecialchars($service['title']); ?>
                    </h3>
                    <p class="text-body text-gray-600 mb-4">
                        <?php echo htmlspecialchars($service['description']); ?>
                    </p>
                    <a href="services.php" class="text-primary-600 font-semibold hover:text-primary-700 transition-colors inline-flex items-center">
                        Mehr erfahren
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Professional Team Section -->
<section class="section-professional bg-gray">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="heading-2 font-heading text-gray-900 mb-4">Unser Team</h2>
            <p class="text-large text-gray-600 max-w-3xl mx-auto">
                Erfahrene Experten mit Leidenschaft für Garten und Landschaft
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($team_data as $member): ?>
                <div class="card-professional p-6 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                    <h3 class="heading-3 text-gray-900 mb-2">
                        <?php echo htmlspecialchars($member['name']); ?>
                    </h3>
                    <p class="text-primary-600 font-semibold mb-3">
                        <?php echo htmlspecialchars($member['position']); ?>
                    </p>
                    <p class="text-body text-gray-600">
                        <?php echo htmlspecialchars($member['description']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Professional News Section -->
<section class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="heading-2 font-heading text-gray-900 mb-4">Aktuelles</h2>
            <p class="text-large text-gray-600 max-w-3xl mx-auto">
                Neuigkeiten und Informationen rund um Garten und Landschaftsbau
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($news_data as $article): ?>
                <div class="card-professional overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-4xl"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="heading-3 text-gray-900 mb-3">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </h3>
                        <p class="text-body text-gray-600 mb-4">
                            <?php echo htmlspecialchars($article['excerpt']); ?>
                        </p>
                        <a href="news.php?id=<?php echo $article['id']; ?>" class="text-primary-600 font-semibold hover:text-primary-700 transition-colors inline-flex items-center">
                            Weiterlesen
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="news.php" class="btn-secondary-pro">
                <i class="fas fa-newspaper"></i>
                <span>Alle Nachrichten</span>
            </a>
        </div>
    </div>
</section>

<!-- Professional CTA Section -->
<section class="section-professional hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="heading-2 text-white mb-6">Bereit für Ihren Traumgarten?</h2>
        <p class="text-large text-gray-100 mb-8 max-w-2xl mx-auto">
            Lassen Sie uns gemeinsam Ihre Vorstellungen in die Realität umsetzen. 
            Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="contact.php" class="btn-primary-pro bg-white text-primary-600 hover:bg-gray-50">
                <i class="fas fa-calendar-alt"></i>
                <span>Beratungstermin vereinbaren</span>
            </a>
            <a href="tel:<?php echo $homepage['phone_number']; ?>" class="btn-secondary-pro border-white text-white hover:bg-white hover:text-primary-600">
                <i class="fas fa-phone"></i>
                <span><?php echo $homepage['phone_number']; ?></span>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>