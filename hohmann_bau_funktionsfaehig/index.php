<?php
/**
 * Stadtwache Homepage
 * Main landing page with all features
 */

// Windows Apache Kompatibilität - Fehlerbehandlung
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    require_once 'config/database.php';
    $db = getDB();
    
    // Get homepage content from database
    $homepage = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
    
    if (!$homepage) {
        // Create default homepage entry if it doesn't exist
        $db->exec("INSERT INTO homepage (id, hero_title, hero_subtitle, emergency_number, phone_number, email, address, opening_hours, color_theme) VALUES ('1', 'Hohmann Bau', 'Ihr Experte für Garten- und Landschaftsbau. Professionelle Gartengestaltung seit über 20 Jahren.', '+49 123 456-999', '+49 123 456-789', 'info@hohmann-bau.de', 'Hohmann Garten & Landschaftsbau\nMeisterstraße 45\n12345 Gartenstadt', 'Mo-Fr: 7:00-17:00\nSa: 8:00-14:00\nSo: Notdienst', 'green')");
        $homepage = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
    }
    
    // Get services
    $services = $db->query("SELECT * FROM services WHERE active = 1 ORDER BY `order`, title")->fetchAll();
    
    // Get team members
    $team = $db->query("SELECT * FROM team WHERE active = 1 ORDER BY `order`, name LIMIT 3")->fetchAll();
    
    // Get statistics
    $statistics = $db->query("SELECT * FROM statistics WHERE active = 1 ORDER BY `order`, title")->fetchAll();
    
    // Get latest news
    $news = $db->query("SELECT * FROM news WHERE published = 1 ORDER BY date DESC LIMIT 3")->fetchAll();
    
} catch (Exception $e) {
    // Fallback wenn Datenbank nicht verfügbar
    $homepage = [
        'hero_title' => 'Hohmann Bau',
        'hero_subtitle' => 'Ihr Experte für Garten- und Landschaftsbau. Professionelle Gartengestaltung seit über 20 Jahren.',
        'emergency_number' => '+49 123 456-999',
        'phone_number' => '+49 123 456-789',
        'email' => 'info@hohmann-bau.de',
        'address' => "Hohmann Garten & Landschaftsbau\nMeisterstraße 45\n12345 Gartenstadt",
        'opening_hours' => "Mo-Fr: 7:00-17:00\nSa: 8:00-14:00\nSo: Notdienst",
        'color_theme' => 'green',
        'hero_image' => null
    ];
    
    $services = [
        ['title' => 'Gartenplanung', 'description' => 'Professionelle Planung und Design für Ihren Traumgarten', 'icon' => 'Leaf'],
        ['title' => 'Landschaftsbau', 'description' => 'Umfassende Gestaltung von Außenanlagen und Landschaften', 'icon' => 'Mountain'],
        ['title' => 'Pflanzarbeiten', 'description' => 'Fachgerechte Bepflanzung mit hochwertigen Pflanzen', 'icon' => 'Seedling'],
        ['title' => 'Gartenpflege', 'description' => 'Regelmäßige Pflege und Instandhaltung Ihrer Gartenanlage', 'icon' => 'Scissors']
    ];
    
    $team = [
        ['name' => 'Meister Klaus Hohmann', 'position' => 'Geschäftsführer & Gartenbaumeister', 'description' => 'Über 25 Jahre Erfahrung im Garten- und Landschaftsbau'],
        ['name' => 'Sarah Müller', 'position' => 'Gartendesignerin', 'description' => 'Spezialistin für moderne Gartenplanung und -gestaltung'],
        ['name' => 'Thomas Weber', 'position' => 'Bauleiter Landschaftsbau', 'description' => 'Experte für komplexe Landschaftsbauprojekte']
    ];
    
    $statistics = [
        ['title' => 'Zufriedene Kunden', 'value' => '98%', 'description' => 'Kundenzufriedenheitsrate', 'icon' => 'TrendingUp', 'color' => 'green'],
        ['title' => 'Projekte pro Jahr', 'value' => '150+', 'description' => 'Erfolgreich abgeschlossene Gartenprojekte', 'icon' => 'Activity', 'color' => 'blue'],
        ['title' => 'Jahre Erfahrung', 'value' => '25+', 'description' => 'Expertise im Garten- und Landschaftsbau', 'icon' => 'Star', 'color' => 'yellow'],
        ['title' => 'Garantie', 'value' => '5 Jahre', 'description' => 'Gewährleistung auf alle Arbeiten', 'icon' => 'Clock', 'color' => 'red']
    ];
    
    $news = [
        ['id' => '1', 'title' => 'Neue Gartensaison 2024', 'content' => 'Die neue Gartensaison beginnt! Jetzt ist die perfekte Zeit für Ihre Gartenplanung...', 'excerpt' => 'Frühjahrsrabatt auf alle Planungsleistungen', 'date' => date('Y-m-d H:i:s'), 'priority' => 'normal'],
        ['id' => '2', 'title' => 'Erweiterte Öffnungszeiten', 'content' => 'Ab sofort sind wir samstags bis 14:00 Uhr für Sie da...', 'excerpt' => 'Mehr Service für unsere Kunden', 'date' => date('Y-m-d H:i:s', strtotime('-1 day')), 'priority' => 'high'],
        ['id' => '3', 'title' => 'Auszeichnung erhalten', 'content' => 'Hohmann Bau wurde als "Gartenbaubetrieb des Jahres" ausgezeichnet...', 'excerpt' => 'Qualität wird belohnt', 'date' => date('Y-m-d H:i:s', strtotime('-2 days')), 'priority' => 'normal']
    ];
}

// Set color theme variables
$theme = $homepage['color_theme'] ?? 'blue';
$theme_colors = [
    'blue' => ['primary' => '#667eea', 'secondary' => '#764ba2', 'accent' => '#3b82f6'],
    'green' => ['primary' => '#10b981', 'secondary' => '#065f46', 'accent' => '#059669'],
    'purple' => ['primary' => '#8b5cf6', 'secondary' => '#6d28d9', 'accent' => '#7c3aed'],
    'red' => ['primary' => '#ef4444', 'secondary' => '#dc2626', 'accent' => '#f87171'],
    'gray' => ['primary' => '#6b7280', 'secondary' => '#374151', 'accent' => '#9ca3af'],
    'orange' => ['primary' => '#f59e0b', 'secondary' => '#d97706', 'accent' => '#fbbf24']
];

$current_colors = $theme_colors[$theme];

$pageTitle = 'Hohmann Bau - Ihr Experte für Garten- und Landschaftsbau';
$pageDescription = 'Ihr Experte für Garten- und Landschaftsbau. Professionelle Gartengestaltung seit über 20 Jahren.';

// Additional CSS for dynamic theming
$additionalCSS = "
<style>
:root {
    --theme-primary: {$current_colors['primary']};
    --theme-secondary: {$current_colors['secondary']};
    --theme-accent: {$current_colors['accent']};
}

.hero-section {
    background: linear-gradient(135deg, var(--theme-primary) 0%, var(--theme-secondary) 100%);
}

.btn-primary {
    background: linear-gradient(135deg, var(--theme-primary) 0%, var(--theme-secondary) 100%);
}

.btn-primary:hover {
    box-shadow: 0 10px 20px rgba(" . hexdec(substr($current_colors['primary'], 1, 2)) . ", " . hexdec(substr($current_colors['primary'], 3, 2)) . ", " . hexdec(substr($current_colors['primary'], 5, 2)) . ", 0.4);
}

.text-theme-primary { color: var(--theme-primary); }
.bg-theme-primary { background-color: var(--theme-primary); }
.border-theme-primary { border-color: var(--theme-primary); }
</style>
";

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section min-h-screen flex items-center relative" <?php if (isset($homepage['hero_image']) && $homepage['hero_image']): ?>style="background-image: linear-gradient(rgba(16, 185, 129, 0.8), rgba(6, 95, 70, 0.8)), url('uploads/<?php echo htmlspecialchars($homepage['hero_image']); ?>'); background-size: cover; background-position: center; background-attachment: fixed;"<?php endif; ?>>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    <?php echo htmlspecialchars($homepage['hero_title']); ?>
                </h1>
                <p class="text-xl lg:text-2xl mb-8 text-green-100">
                    <?php echo htmlspecialchars($homepage['hero_subtitle']); ?>
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="contact.php" class="btn-primary inline-flex items-center justify-center">
                        <i class="fas fa-leaf mr-2"></i>
                        Kostenlose Beratung
                    </a>
                    <a href="tel:<?php echo $homepage['phone_number']; ?>" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 inline-flex items-center justify-center">
                        <i class="fas fa-phone mr-2"></i>
                        Anrufen: <?php echo htmlspecialchars($homepage['phone_number']); ?>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-phone mr-2 text-green-300"></i>
                        <span><?php echo htmlspecialchars($homepage['phone_number']); ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-green-300"></i>
                        <span><?php echo htmlspecialchars($homepage['email']); ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2 text-green-300"></i>
                        <span>24/7 Notdienst</span>
                    </div>
                </div>
            </div>
            
            <div class="hidden lg:block">
                <div class="float-animation">
                    <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-3xl p-8 border border-white border-opacity-20">
                        <h3 class="text-2xl font-bold text-white mb-6">Schnellkontakt</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-white">
                                <span>Beratung</span>
                                <a href="tel:<?php echo $homepage['phone_number']; ?>" class="text-2xl font-bold hover:text-green-300 transition duration-300">
                                    <?php echo htmlspecialchars($homepage['phone_number']); ?>
                                </a>
                            </div>
                            <div class="flex items-center justify-between text-white">
                                <span>Notdienst</span>
                                <a href="tel:<?php echo $homepage['emergency_number']; ?>" class="hover:text-green-300 transition duration-300">
                                    <?php echo htmlspecialchars($homepage['emergency_number']); ?>
                                </a>
                            </div>
                            <div class="flex items-center justify-between text-white">
                                <span>E-Mail</span>
                                <a href="mailto:<?php echo $homepage['email']; ?>" class="hover:text-green-300 transition duration-300">
                                    <?php echo htmlspecialchars($homepage['email']); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll down indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <i class="fas fa-chevron-down text-2xl"></i>
    </div>
</section>

<!-- Statistics Section -->
<?php if (!empty($statistics)): ?>
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($statistics as $stat): ?>
            <div class="stats-card rounded-xl p-6 text-center card-hover">
                <div class="text-4xl mb-4">
                    <i class="fas fa-<?php 
                        echo $stat['icon'] === 'TrendingUp' ? 'chart-line' : 
                             ($stat['icon'] === 'Activity' ? 'activity' : 
                             ($stat['icon'] === 'Star' ? 'star' : 
                             ($stat['icon'] === 'Clock' ? 'clock' : 'chart-bar'))); 
                    ?> text-<?php echo $stat['color']; ?>-600"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($stat['value']); ?></h3>
                <p class="text-lg font-semibold text-gray-800 mb-1"><?php echo htmlspecialchars($stat['title']); ?></p>
                <?php if ($stat['description']): ?>
                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($stat['description']); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Services Section -->
<?php if (!empty($services)): ?>
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Unsere Leistungen</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Wir bieten umfassende Garten- und Landschaftsbaudienstleistungen
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($services as $service): ?>
            <div class="bg-white rounded-xl p-6 card-hover">
                <div class="text-4xl mb-4 text-green-600">
                    <i class="fas fa-<?php 
                        echo $service['icon'] === 'Leaf' ? 'leaf' : 
                             ($service['icon'] === 'Mountain' ? 'mountain' : 
                             ($service['icon'] === 'Seedling' ? 'seedling' : 
                             ($service['icon'] === 'Scissors' ? 'cut' : 'leaf'))); 
                    ?>"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($service['title']); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($service['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Team Section -->
<?php if (!empty($team)): ?>
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Unser Team</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Erfahrene Garten- und Landschaftsbau-Experten
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($team as $member): ?>
            <div class="bg-gray-50 rounded-xl p-6 text-center card-hover">
                <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($member['name']); ?></h3>
                <p class="text-blue-600 font-semibold mb-3"><?php echo htmlspecialchars($member['position']); ?></p>
                <?php if ($member['description']): ?>
                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($member['description']); ?></p>
                <?php endif; ?>
                
                <?php if ($member['email'] || $member['phone']): ?>
                <div class="mt-4 flex justify-center space-x-3">
                    <?php if ($member['email']): ?>
                    <a href="mailto:<?php echo $member['email']; ?>" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($member['phone']): ?>
                    <a href="tel:<?php echo $member['phone']; ?>" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-phone"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Removed team link -->
    </div>
</section>
<?php endif; ?>

<!-- News Section -->
<?php if (!empty($news)): ?>
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Aktuelles</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Neuigkeiten und Informationen rund um Garten und Landschaftsbau
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($news as $article): ?>
            <article class="bg-white rounded-xl overflow-hidden card-hover">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            <?php echo $article['priority'] === 'high' ? 'Wichtig' : ($article['priority'] === 'urgent' ? 'Eilmeldung' : 'Aktuell'); ?>
                        </span>
                        <time class="text-sm text-gray-500">
                            <?php echo date('d.m.Y', strtotime($article['date'])); ?>
                        </time>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($article['title']); ?></h3>
                    <p class="text-gray-600 mb-4">
                        <?php echo htmlspecialchars($article['excerpt'] ?: substr(strip_tags($article['content']), 0, 120) . '...'); ?>
                    </p>
                    <a href="news.php?id=<?php echo $article['id']; ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Weiterlesen <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="news.php" class="btn-primary">
                Alle Meldungen anzeigen
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Kontaktieren Sie uns</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Wir sind für Sie da. Kontaktieren Sie uns bei Fragen oder Anliegen.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-phone text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Telefon</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($homepage['phone_number']); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-envelope text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">E-Mail</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($homepage['email']); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Adresse</h3>
                            <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($homepage['address'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-clock text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Öffnungszeiten</h3>
                            <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($homepage['opening_hours'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Schnellzugriff</h3>
                <div class="grid grid-cols-1 gap-4">
                    <a href="services.php" class="flex items-center justify-between p-4 bg-white rounded-lg hover:bg-green-50 transition duration-300">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-leaf text-green-600"></i>
                            <span class="font-semibold">Alle Leistungen</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </a>
                    
                    <a href="careers.php" class="flex items-center justify-between p-4 bg-white rounded-lg hover:bg-green-50 transition duration-300">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-briefcase text-blue-600"></i>
                            <span class="font-semibold">Karriere</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </a>
                    
                    <a href="feedback.php" class="flex items-center justify-between p-4 bg-white rounded-lg hover:bg-green-50 transition duration-300">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-comments text-green-600"></i>
                            <span class="font-semibold">Bewertung abgeben</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </a>
                    
                    <a href="contact.php" class="flex items-center justify-between p-4 bg-white rounded-lg hover:bg-green-50 transition duration-300">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-purple-600"></i>
                            <span class="font-semibold">Kostenvoranschlag</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>