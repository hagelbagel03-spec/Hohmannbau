<?php
/**
 * Professional Services Page - Hohmann Bau
 */

require_once 'config/database.php';

// Get dynamic data
try {
    $db = getDB();
    $services_data = $db->query("SELECT * FROM services WHERE published = 1 ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) {
    // Fallback data if database fails
    $services_data = [
        [
            'title' => 'Gartenplanung & Design',
            'description' => 'Professionelle Planung und 3D-Visualisierung Ihres Traumgartens mit modernster Software.',
            'long_description' => 'Unser erfahrenes Planungsteam entwickelt gemeinsam mit Ihnen individuelle Gartenkonzepte, die perfekt zu Ihren Wünschen und Ihrem Budget passen.',
            'icon' => 'fas fa-seedling',
            'price_range' => 'ab 500€',
            'features' => ['3D-Visualisierung', 'Kostenvoranschlag', 'Beratung vor Ort']
        ],
        [
            'title' => 'Landschaftsbau',
            'description' => 'Umfassende Gestaltung von Außenanlagen, Terrassen, Wegen und Mauern.',
            'long_description' => 'Von der ersten Erdarbeit bis zur finalen Bepflanzung - wir realisieren Ihr komplettes Landschaftsbauprojekt.',
            'icon' => 'fas fa-mountain',
            'price_range' => 'ab 2.000€',
            'features' => ['Erdarbeiten', 'Pflasterarbeiten', 'Mauerbau']
        ],
        [
            'title' => 'Pflanzarbeiten',
            'description' => 'Fachgerechte Bepflanzung mit hochwertigen, standortgerechten Pflanzen.',
            'long_description' => 'Wir wählen die perfekten Pflanzen für jeden Standort und sorgen für eine fachmännische Pflanzung.',
            'icon' => 'fas fa-leaf',
            'price_range' => 'ab 200€',
            'features' => ['Pflanzplan', 'Standortberatung', 'Anwuchsgarantie']
        ],
        [
            'title' => 'Gartenpflege',
            'description' => 'Regelmäßige Pflege und Instandhaltung Ihrer Gartenanlage das ganze Jahr über.',
            'long_description' => 'Damit Ihr Garten immer gepflegt aussieht, bieten wir umfassende Pflegeservices.',
            'icon' => 'fas fa-cut',
            'price_range' => 'ab 80€/Monat',
            'features' => ['Rasenpflege', 'Heckenschnitt', 'Unkrautentfernung']
        ],
        [
            'title' => 'Bewässerungssysteme',
            'description' => 'Installation moderner Bewässerungsanlagen für optimale Wasserversorgung.',
            'long_description' => 'Automatische Bewässerungssysteme sparen Zeit und Wasser bei optimaler Pflanzenversorgung.',
            'icon' => 'fas fa-tint',
            'price_range' => 'ab 1.200€',
            'features' => ['Planung', 'Installation', 'Wartung']
        ],
        [
            'title' => 'Winterdienst',
            'description' => 'Zuverlässiger Räum- und Streudienst für Ihre Wege und Einfahrten.',
            'long_description' => 'Sorgen Sie für sichere Wege in der kalten Jahreszeit mit unserem professionellen Winterdienst.',
            'icon' => 'fas fa-snowflake',
            'price_range' => 'ab 50€/Einsatz',
            'features' => ['24h Bereitschaft', 'Räumung', 'Streumittel']
        ]
    ];
}

$title = 'Unsere Leistungen - Hohmann Bau';
$description = 'Professionelle Garten- und Landschaftsbau-Dienstleistungen: Planung, Gestaltung, Pflege und mehr.';

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
                <a href="index.php" class="nav-link">Home</a>
                <a href="about.php" class="nav-link">Über uns</a>
                <a href="services.php" class="nav-link active">Leistungen</a>
                <a href="team.php" class="nav-link">Team</a>
                <a href="careers.php" class="nav-link">Karriere</a>
                <a href="news.php" class="nav-link">Aktuelles</a>
                <a href="contact.php" class="nav-link">Kontakt</a>
            </div>
            
            <!-- CTA Button -->
            <div class="flex items-center space-x-4">
                <a href="contact.php" class="btn-primary-pro">
                    <i class="fas fa-envelope"></i>
                    <span>Kontakt</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Professional Hero Section -->
<section class="hero-gradient section-professional pt-32 pb-20 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="heading-1 text-white mb-6">Unsere Leistungen</h1>
        <p class="text-large text-gray-100 max-w-3xl mx-auto">
            Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service 
            für Ihren Traumgarten aus einer Hand.
        </p>
    </div>
</section>

<!-- Professional Services Grid -->
<section class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services_data as $service): ?>
                <div class="card-professional p-8 text-center">
                    <div class="service-icon mx-auto mb-6">
                        <i class="<?php echo $service['icon']; ?>"></i>
                    </div>
                    
                    <h3 class="heading-3 text-gray-900 mb-4">
                        <?php echo htmlspecialchars($service['title']); ?>
                    </h3>
                    
                    <p class="text-body text-gray-600 mb-6">
                        <?php echo htmlspecialchars($service['description']); ?>
                    </p>
                    
                    <div class="mb-6">
                        <div class="text-2xl font-bold text-primary-600 mb-2">
                            <?php echo htmlspecialchars($service['price_range']); ?>
                        </div>
                        <div class="text-sm text-gray-500">Richtwert</div>
                    </div>
                    
                    <!-- Features -->
                    <div class="mb-6 space-y-2">
                        <?php foreach ($service['features'] as $feature): ?>
                            <div class="flex items-center justify-center text-sm text-gray-600">
                                <i class="fas fa-check text-primary-500 mr-2"></i>
                                <span><?php echo htmlspecialchars($feature); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="contact.php" class="btn-primary-pro w-full justify-center">
                        <i class="fas fa-envelope"></i>
                        <span>Anfrage stellen</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Professional Process Section -->
<section class="section-professional bg-gray">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="heading-2 font-heading text-gray-900 mb-4">Unser Arbeitsprozess</h2>
            <p class="text-large text-gray-600 max-w-3xl mx-auto">
                In vier einfachen Schritten zu Ihrem Traumgarten
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-xl">1</span>
                </div>
                <h3 class="heading-3 text-gray-900 mb-2">Beratung</h3>
                <p class="text-body text-gray-600">Kostenloses Erstberatungsgespräch vor Ort</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-xl">2</span>
                </div>
                <h3 class="heading-3 text-gray-900 mb-2">Planung</h3>
                <p class="text-body text-gray-600">Individuelle Planung mit 3D-Visualisierung</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-xl">3</span>
                </div>
                <h3 class="heading-3 text-gray-900 mb-2">Umsetzung</h3>
                <p class="text-body text-gray-600">Professionelle Ausführung durch unser Team</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-xl">4</span>
                </div>
                <h3 class="heading-3 text-gray-900 mb-2">Nachbetreuung</h3>
                <p class="text-body text-gray-600">Langfristige Betreuung und Pflege</p>
            </div>
        </div>
    </div>
</section>

<!-- Professional CTA Section -->
<section class="section-professional hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="heading-2 text-white mb-6">Bereit für Ihr Projekt?</h2>
        <p class="text-large text-gray-100 mb-8 max-w-2xl mx-auto">
            Lassen Sie uns gemeinsam Ihre Gartenträume verwirklichen. 
            Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="contact.php" class="btn-primary-pro bg-white text-primary-600 hover:bg-gray-50">
                <i class="fas fa-calendar-alt"></i>
                <span>Beratungstermin vereinbaren</span>
            </a>
            <a href="tel:+49123456789" class="btn-secondary-pro border-white text-white hover:bg-white hover:text-primary-600">
                <i class="fas fa-phone"></i>
                <span>+49 123 456-789</span>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>