<?php
/**
 * Moderne Hohmann Bau Homepage mit bearbeitbaren Texten
 */

require_once 'config/database.php';

// Get dynamic data with fallbacks
try {
    $db = getDBConnection();
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
} catch (Exception $e) {
    $homepage = null;
}

// Fallback-Daten falls Datenbank nicht verfügbar
$default_data = [
    'company_name' => 'Hohmann Bau',
    'company_tagline' => 'Garten & Landschaftsbau',
    'hero_title' => 'Ihr Experte für Garten- und Landschaftsbau',
    'hero_subtitle' => 'Professionelle Gartengestaltung seit über 20 Jahren mit Leidenschaft für die Natur',
    'nav_home_text' => 'Home',
    'nav_about_text' => 'Über uns',
    'nav_services_text' => 'Leistungen',
    'nav_team_text' => 'Team',
    'nav_careers_text' => 'Karriere',
    'nav_news_text' => 'Aktuelles',
    'nav_contact_text' => 'Kontakt',
    'services_section_title' => 'Unsere Leistungen',
    'services_section_description' => 'Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten',
    'team_section_title' => 'Unser Expertenteam',
    'team_section_description' => 'Erfahrene Fachkräfte mit Leidenschaft für Garten und Landschaft',
    'news_section_title' => 'Aktuelles',
    'news_section_description' => 'Neuigkeiten und Informationen rund um Garten und Landschaftsbau',
    'cta_section_title' => 'Bereit für Ihren Traumgarten?',
    'cta_section_description' => 'Lassen Sie uns gemeinsam Ihre Vorstellungen in die Realität umsetzen. Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.',
    'cta_button_text' => 'Beratungstermin vereinbaren',
    'cta_phone_button_text' => 'Jetzt anrufen',
    'trust_indicator_1_value' => '25+',
    'trust_indicator_1_label' => 'Jahre Erfahrung',
    'trust_indicator_2_value' => '150+',
    'trust_indicator_2_label' => 'Projekte/Jahr',
    'trust_indicator_3_value' => '98%',
    'trust_indicator_3_label' => 'Zufriedenheit',
    'phone_number' => '+49 123 456-789',
    'email' => 'info@hohmann-bau.de',
    'hero_background_image' => '',
    'gallery_image_1' => '',
    'gallery_image_2' => '',
    'gallery_image_3' => '',
    'gallery_image_4' => ''
];

// Merge database data with defaults
foreach ($default_data as $key => $value) {
    if (!isset($homepage[$key]) || empty($homepage[$key])) {
        $homepage[$key] = $value;
    }
}

// Services data
$services_data = [
    [
        'title' => 'Gartenplanung & Design',
        'description' => 'Professionelle Planung und kreative Gestaltung für Ihren individuellen Traumgarten mit modernster 3D-Visualisierung',
        'icon' => 'fas fa-seedling',
        'image' => '/uploads/services/gartenplanung.jpg'
    ],
    [
        'title' => 'Landschaftsbau',
        'description' => 'Umfassende Gestaltung von Außenanlagen und Landschaften mit hochwertigen Materialien und nachhaltigen Lösungen',
        'icon' => 'fas fa-mountain',
        'image' => '/uploads/services/landschaftsbau.jpg'
    ],
    [
        'title' => 'Pflanzarbeiten',
        'description' => 'Fachgerechte Bepflanzung mit sorgfältig ausgewählten, regionalen und klimaresistenten Pflanzen',
        'icon' => 'fas fa-leaf',
        'image' => '/uploads/services/pflanzarbeiten.jpg'
    ],
    [
        'title' => 'Gartenpflege & Wartung',
        'description' => 'Regelmäßige professionelle Pflege und Instandhaltung für die dauerhafte Schönheit Ihrer Gartenanlage',
        'icon' => 'fas fa-cut',
        'image' => '/uploads/services/gartenpflege.jpg'
    ]
];

$team_data = [
    [
        'name' => 'Klaus Hohmann',
        'position' => 'Geschäftsführer & Gartenbaumeister',
        'description' => 'Über 25 Jahre Erfahrung im Garten- und Landschaftsbau. Spezialist für nachhaltige Gartenkonzepte.',
        'image' => '/uploads/team/klaus-hohmann.jpg'
    ],
    [
        'name' => 'Sarah Müller',
        'position' => 'Gartendesignerin',
        'description' => 'Kreative Expertin für moderne Gartenplanung und innovative Gestaltungskonzepte.',
        'image' => '/uploads/team/sarah-mueller.jpg'
    ],
    [
        'name' => 'Thomas Weber',
        'position' => 'Bauleiter Landschaftsbau',
        'description' => 'Spezialist für komplexe Landschaftsbauprojekte und technische Umsetzung.',
        'image' => '/uploads/team/thomas-weber.jpg'
    ]
];

$news_data = [
    [
        'id' => 'news-1',
        'title' => 'Frühjahrs-Aktion 2024',
        'excerpt' => '15% Rabatt auf alle Gartenplanungsleistungen bis Ende Mai',
        'image' => '/uploads/news/fruehjahr-2024.jpg'
    ],
    [
        'id' => 'news-2',
        'title' => 'Nachhaltigkeit im Gartenbau',
        'excerpt' => 'Unser Beitrag zum Klimaschutz durch innovative Gartenlösungen',
        'image' => '/uploads/news/nachhaltigkeit.jpg'
    ],
    [
        'id' => 'news-3',
        'title' => 'Auszeichnung erhalten',
        'excerpt' => 'Hohmann Bau wird als "Gartenbaubetrieb des Jahres 2024" ausgezeichnet',
        'image' => '/uploads/news/auszeichnung-2024.jpg'
    ]
];

$title = $homepage['company_name'] . ' - ' . $homepage['hero_title'];
$description = $homepage['hero_subtitle'];

include 'includes/header.php';
?>

<!-- Moderne Navigation -->
<nav class="navbar fixed top-0 left-0 right-0 z-50 py-4 bg-white/95 backdrop-blur-xl border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 editable-text" data-field="company_name">
                        <?php echo htmlspecialchars($homepage['company_name']); ?>
                    </h1>
                    <p class="text-sm text-green-600 font-medium editable-text" data-field="company_tagline">
                        <?php echo htmlspecialchars($homepage['company_tagline']); ?>
                    </p>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden lg:flex items-center space-x-2">
                <a href="index.php" class="nav-link active editable-text" data-field="nav_home_text">
                    <?php echo htmlspecialchars($homepage['nav_home_text']); ?>
                </a>
                <a href="about.php" class="nav-link editable-text" data-field="nav_about_text">
                    <?php echo htmlspecialchars($homepage['nav_about_text']); ?>
                </a>
                <a href="services.php" class="nav-link editable-text" data-field="nav_services_text">
                    <?php echo htmlspecialchars($homepage['nav_services_text']); ?>
                </a>
                <a href="team.php" class="nav-link editable-text" data-field="nav_team_text">
                    <?php echo htmlspecialchars($homepage['nav_team_text']); ?>
                </a>
                <a href="careers.php" class="nav-link editable-text" data-field="nav_careers_text">
                    <?php echo htmlspecialchars($homepage['nav_careers_text']); ?>
                </a>
                <a href="news.php" class="nav-link editable-text" data-field="nav_news_text">
                    <?php echo htmlspecialchars($homepage['nav_news_text']); ?>
                </a>
                <a href="contact.php" class="nav-link editable-text" data-field="nav_contact_text">
                    <?php echo htmlspecialchars($homepage['nav_contact_text']); ?>
                </a>
            </div>
            
            <!-- CTA Button -->
            <div class="flex items-center space-x-4">
                <a href="tel:<?php echo $homepage['phone_number']; ?>" class="hidden sm:flex items-center space-x-2 text-green-600 font-semibold hover:text-green-700 transition-colors">
                    <i class="fas fa-phone text-sm"></i>
                    <span class="text-body"><?php echo $homepage['phone_number']; ?></span>
                </a>
                <a href="contact.php" class="btn-primary-pro bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Beratung</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Moderner Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-green-50 to-emerald-100">
    <!-- Background Image -->
    <?php if (!empty($homepage['hero_background_image'])): ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo htmlspecialchars($homepage['hero_background_image']); ?>" 
                 alt="Hohmann Bau Gartenprojekt" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-green-900/80 to-green-800/60"></div>
        </div>
    <?php else: ?>
        <!-- Fallback gradient background -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-green-700 to-emerald-800"></div>
    <?php endif; ?>
    
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
            <defs>
                <pattern id="garden-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                    <path d="M5,5 L15,15 M15,5 L5,15" stroke="currentColor" stroke-width="0.5" opacity="0.5"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#garden-pattern)" class="text-white"/>
        </svg>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Hero Content -->
        <div class="space-y-8 animate-fade-in-up">
            <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight editable-text" data-field="hero_title">
                <?php echo htmlspecialchars($homepage['hero_title']); ?>
            </h1>
            
            <p class="text-xl md:text-2xl text-green-100 max-w-4xl mx-auto leading-relaxed editable-text" data-field="hero_subtitle">
                <?php echo htmlspecialchars($homepage['hero_subtitle']); ?>
            </p>
            
            <!-- Hero CTAs -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center pt-8">
                <a href="contact.php" class="btn-primary-pro bg-white text-green-600 hover:bg-green-50 px-8 py-4 text-lg">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span class="editable-text" data-field="cta_button_text">
                        <?php echo htmlspecialchars($homepage['cta_button_text']); ?>
                    </span>
                </a>
                <a href="tel:<?php echo $homepage['phone_number']; ?>" class="btn-secondary-pro border-2 border-white text-white hover:bg-white hover:text-green-600 px-8 py-4 text-lg">
                    <i class="fas fa-phone mr-2"></i>
                    <span class="editable-text" data-field="cta_phone_button_text">
                        <?php echo htmlspecialchars($homepage['cta_phone_button_text']); ?>
                    </span>
                </a>
            </div>
            
            <!-- Trust Indicators -->
            <div class="pt-16">
                <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2 editable-text" data-field="trust_indicator_1_value">
                            <?php echo htmlspecialchars($homepage['trust_indicator_1_value']); ?>
                        </div>
                        <div class="text-green-200 font-medium editable-text" data-field="trust_indicator_1_label">
                            <?php echo htmlspecialchars($homepage['trust_indicator_1_label']); ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2 editable-text" data-field="trust_indicator_2_value">
                            <?php echo htmlspecialchars($homepage['trust_indicator_2_value']); ?>
                        </div>
                        <div class="text-green-200 font-medium editable-text" data-field="trust_indicator_2_label">
                            <?php echo htmlspecialchars($homepage['trust_indicator_2_label']); ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2 editable-text" data-field="trust_indicator_3_value">
                            <?php echo htmlspecialchars($homepage['trust_indicator_3_value']); ?>
                        </div>
                        <div class="text-green-200 font-medium editable-text" data-field="trust_indicator_3_label">
                            <?php echo htmlspecialchars($homepage['trust_indicator_3_label']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <div class="w-8 h-12 border-2 border-white/30 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white/60 rounded-full mt-2 animate-bounce"></div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 editable-text" data-field="services_section_title">
                <?php echo htmlspecialchars($homepage['services_section_title']); ?>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed editable-text" data-field="services_section_description">
                <?php echo htmlspecialchars($homepage['services_section_description']); ?>
            </p>
        </div>
        
        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($services_data as $index => $service): ?>
                <div class="group">
                    <div class="relative overflow-hidden rounded-2xl bg-white shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <!-- Service Image -->
                        <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 relative overflow-hidden">
                            <?php if (!empty($service['image'])): ?>
                                <img src="<?php echo htmlspecialchars($service['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($service['title']); ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            <div class="absolute bottom-4 left-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <i class="<?php echo $service['icon']; ?> text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Service Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3">
                                <?php echo htmlspecialchars($service['title']); ?>
                            </h3>
                            <p class="text-gray-600 mb-4 text-body leading-relaxed">
                                <?php echo htmlspecialchars($service['description']); ?>
                            </p>
                            <a href="services.php" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700 transition-colors group">
                                Mehr erfahren
                                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Unsere Referenzen</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Entdecken Sie eine Auswahl unserer schönsten Gartenprojekte</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="group relative overflow-hidden rounded-2xl aspect-square">
                    <?php 
                    $galleryImage = $homepage["gallery_image_$i"];
                    if (!empty($galleryImage)): ?>
                        <img src="<?php echo htmlspecialchars($galleryImage); ?>" 
                             alt="Gartenprojekt <?php echo $i; ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                            <div class="text-white text-center">
                                <i class="fas fa-seedling text-4xl mb-4"></i>
                                <p class="font-semibold">Projekt <?php echo $i; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="font-bold text-lg">Gartenprojekt <?php echo $i; ?></h3>
                            <p class="text-sm text-gray-200">Moderne Gartengestaltung</p>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 editable-text" data-field="team_section_title">
                <?php echo htmlspecialchars($homepage['team_section_title']); ?>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto editable-text" data-field="team_section_description">
                <?php echo htmlspecialchars($homepage['team_section_description']); ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($team_data as $member): ?>
                <div class="group">
                    <div class="relative">
                        <!-- Team Member Image -->
                        <div class="w-64 h-64 mx-auto rounded-2xl overflow-hidden bg-gradient-to-br from-green-400 to-green-600 relative">
                            <?php if (!empty($member['image'])): ?>
                                <img src="<?php echo htmlspecialchars($member['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-6xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Team Member Info -->
                        <div class="text-center mt-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($member['name']); ?>
                            </h3>
                            <p class="text-green-600 font-semibold mb-4">
                                <?php echo htmlspecialchars($member['position']); ?>
                            </p>
                            <p class="text-gray-600 text-body leading-relaxed">
                                <?php echo htmlspecialchars($member['description']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 editable-text" data-field="news_section_title">
                <?php echo htmlspecialchars($homepage['news_section_title']); ?>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto editable-text" data-field="news_section_description">
                <?php echo htmlspecialchars($homepage['news_section_description']); ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($news_data as $article): ?>
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                    <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 relative overflow-hidden">
                        <?php if (!empty($article['image'])): ?>
                            <img src="<?php echo htmlspecialchars($article['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($article['title']); ?>" 
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-newspaper text-white text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </h3>
                        <p class="text-gray-600 mb-4 text-body">
                            <?php echo htmlspecialchars($article['excerpt']); ?>
                        </p>
                        <a href="news.php?id=<?php echo $article['id']; ?>" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700 transition-colors group">
                            Weiterlesen
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-gradient-to-r from-green-600 to-green-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-green-900/20 to-transparent"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 editable-text" data-field="cta_section_title">
            <?php echo htmlspecialchars($homepage['cta_section_title']); ?>
        </h2>
        <p class="text-xl text-green-100 mb-8 leading-relaxed editable-text" data-field="cta_section_description">
            <?php echo htmlspecialchars($homepage['cta_section_description']); ?>
        </p>
        
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="contact.php" class="btn-primary-pro bg-white text-green-600 hover:bg-green-50 px-8 py-4 text-lg">
                <i class="fas fa-calendar-alt mr-2"></i>
                <span class="editable-text" data-field="cta_button_text">
                    <?php echo htmlspecialchars($homepage['cta_button_text']); ?>
                </span>
            </a>
            <a href="tel:<?php echo $homepage['phone_number']; ?>" class="btn-secondary-pro border-2 border-white text-white hover:bg-white hover:text-green-600 px-8 py-4 text-lg">
                <i class="fas fa-phone mr-2"></i>
                <span><?php echo $homepage['phone_number']; ?></span>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- Custom Styles for Modern Design -->
<style>
/* Modern enhancements */
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Glass morphism effects */
.bg-white\/95 {
    background-color: rgba(255, 255, 255, 0.95);
}

/* Custom button styles */
.btn-primary-pro {
    @apply inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300;
}

.btn-secondary-pro {
    @apply inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold border-2 transition-all duration-300 transform hover:-translate-y-0.5;
}

/* Navigation styles */
.nav-link {
    @apply px-4 py-2 rounded-xl font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 transition-all duration-200;
}

.nav-link.active {
    @apply text-green-600 bg-green-50;
}

/* Card hover effects */
.card-hover {
    @apply transition-all duration-300 hover:shadow-lg hover:-translate-y-1;
}

/* Editable text indicator (for admin) */
.editable-text {
    position: relative;
}

.editable-text:hover {
    background-color: rgba(59, 130, 246, 0.1);
    border-radius: 4px;
    cursor: pointer;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .text-5xl {
        font-size: 2.5rem;
    }
    
    .text-7xl {
        font-size: 3rem;
    }
}
</style>