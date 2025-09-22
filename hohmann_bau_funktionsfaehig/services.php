<?php
/**
 * Services Page
 * Display all services offered by Stadtwache
 */

// Windows Apache Kompatibilität - Fehlerbehandlung
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    require_once 'config/database.php';
} catch (Exception $e) {
    $db_error = true;
}

$pageTitle = 'Unsere Leistungen - Hohmann Bau';
$pageDescription = 'Umfassende Garten- und Landschaftsbau-Dienstleistungen für Ihre grünen Träume';

// Standardwerte falls Datenbank nicht verfügbar
$services = [
    ['title' => 'Gartenplanung', 'description' => 'Professionelle Planung und Design für Ihren Traumgarten', 'icon' => 'Leaf'],
    ['title' => 'Landschaftsbau', 'description' => 'Umfassende Gestaltung von Außenanlagen und Landschaften', 'icon' => 'Mountain'],
    ['title' => 'Pflanzarbeiten', 'description' => 'Fachgerechte Bepflanzung mit hochwertigen Pflanzen', 'icon' => 'Seedling'],
    ['title' => 'Gartenpflege', 'description' => 'Regelmäßige Pflege und Instandhaltung Ihrer Gartenanlage', 'icon' => 'Scissors']
];

// Versuche Datenbank zu laden, falls verfügbar
if (!isset($db_error)) {
    try {
        $db = getDB();
        $services_db = $db->query("SELECT * FROM services WHERE active = 1 ORDER BY `order`, title")->fetchAll();
        if (!empty($services_db)) {
            $services = $services_db;
        }
    } catch (Exception $e) {
        $db_error = true;
    }
}

include 'includes/header.php';
?>

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Unsere Dienste</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Umfassende Sicherheitsdienste für unsere Gemeinschaft. Wir sind für Sie da.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $service): ?>
            <div class="bg-white rounded-xl shadow-lg p-8 text-center card-hover">
                <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-<?php 
                        echo $service['icon'] === 'Shield' ? 'shield-alt' : 
                             ($service['icon'] === 'Search' ? 'search' : 
                             ($service['icon'] === 'Users' ? 'users' : 
                             ($service['icon'] === 'Car' ? 'car' : 'shield-alt'))); 
                    ?> text-blue-600 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($service['title']); ?></h3>
                <p class="text-gray-600 leading-relaxed"><?php echo htmlspecialchars($service['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Additional Service Info -->
        <div class="mt-16 bg-white rounded-xl shadow-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">24/7 Verfügbarkeit</h2>
                    <p class="text-gray-600 mb-4">
                        Unsere Dienste sind rund um die Uhr verfügbar. In Notfällen erreichen Sie uns unter der 110.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Sofortige Notfallhilfe</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Professionelle Beratung</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Schnelle Reaktionszeiten</li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Kontakt</h2>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-600 mr-3"></i>
                            <span>Notruf: 110</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-600 mr-3"></i>
                            <span>Allgemein: +49 123 456-789</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 mr-3"></i>
                            <span>info@hohmann-bau.de</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}
</style>

<?php include 'includes/footer.php'; ?>