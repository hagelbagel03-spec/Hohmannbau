<?php
/**
 * Team Page
 * Display all team members
 */

// Windows Apache Kompatibilität - Fehlerbehandlung
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    require_once 'config/database.php';
} catch (Exception $e) {
    $db_error = true;
}

$pageTitle = 'Unser Team - Hohmann Bau';
$pageDescription = 'Lernen Sie unser engagiertes Team kennen';

// Standardwerte falls Datenbank nicht verfügbar
$team = [
    ['name' => 'Kommissar Schmidt', 'position' => 'Leiter der Stadtwache', 'description' => 'Über 20 Jahre Erfahrung im Polizeidienst', 'email' => 'schmidt@stadtwache.de', 'phone' => '+49 123 456-701'],
    ['name' => 'Hauptmeister Müller', 'position' => 'Stellvertretender Leiter', 'description' => 'Spezialist für Ermittlungsverfahren', 'email' => 'mueller@stadtwache.de', 'phone' => '+49 123 456-702'],
    ['name' => 'Meisterin Weber', 'position' => 'Leiterin Bürgerdienst', 'description' => 'Expertin für Bürgerberatung und Prävention', 'email' => 'weber@stadtwache.de', 'phone' => '+49 123 456-703'],
    ['name' => 'Oberkommissar Fischer', 'position' => 'Leiter Verkehrssicherheit', 'description' => 'Experte für Verkehrsunfälle und Prävention', 'email' => 'fischer@stadtwache.de', 'phone' => '+49 123 456-704'],
    ['name' => 'Kommissarin Schneider', 'position' => 'Ermittlungsabteilung', 'description' => 'Spezialistin für Cyberkriminalität', 'email' => 'schneider@stadtwache.de', 'phone' => '+49 123 456-705'],
    ['name' => 'Hauptmeister Wagner', 'position' => 'Streifendienst', 'description' => 'Langjährige Erfahrung im Außendienst', 'email' => 'wagner@stadtwache.de', 'phone' => '+49 123 456-706']
];

// Versuche Datenbank zu laden, falls verfügbar
if (!isset($db_error)) {
    try {
        $db = getDB();
        $team_db = $db->query("SELECT * FROM team WHERE active = 1 ORDER BY `order`, name")->fetchAll();
        if (!empty($team_db)) {
            $team = $team_db;
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
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Unser Team</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Lernen Sie die erfahrenen Garten- und Landschaftsbau-Experten kennen, die Ihre grünen Träume verwirklichen
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($team as $member): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                <div class="p-6 text-center">
                    <div class="w-32 h-32 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-seedling text-white text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($member['name']); ?></h3>
                    <p class="text-green-600 font-semibold mb-3"><?php echo htmlspecialchars($member['position']); ?></p>
                    
                    <?php if ($member['description']): ?>
                    <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($member['description']); ?></p>
                    <?php endif; ?>
                    
                    <?php if (isset($member['email']) || isset($member['phone'])): ?>
                    <div class="flex justify-center space-x-3 mt-4">
                        <?php if (isset($member['email']) && $member['email']): ?>
                        <a href="mailto:<?php echo $member['email']; ?>" 
                           class="bg-blue-100 p-3 rounded-full text-blue-600 hover:bg-blue-200 transition duration-300">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (isset($member['phone']) && $member['phone']): ?>
                        <a href="tel:<?php echo $member['phone']; ?>" 
                           class="bg-green-100 p-3 rounded-full text-green-600 hover:bg-green-200 transition duration-300">
                            <i class="fas fa-phone"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Team Statistics -->
        <div class="mt-16 bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Unser Team in Zahlen</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2"><?php echo count($team); ?></div>
                    <p class="text-gray-600">Gartenbau-Experten</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2">75+</div>
                    <p class="text-gray-600">Jahre Gesamterfahrung</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600 mb-2">365</div>
                    <p class="text-gray-600">Tage Gartenpflege</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600 mb-2">98%</div>
                    <p class="text-gray-600">Kundenzufriedenheit</p>
                </div>
            </div>
        </div>

        <!-- Join Our Team -->
        <div class="mt-16 bg-green-600 rounded-xl shadow-lg p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">Werden Sie Teil unseres grünen Teams!</h2>
            <p class="text-green-100 mb-6">
                Wir suchen leidenschaftliche Gartenbau-Fachkräfte, die mit uns die schönsten Gärten der Region gestalten möchten.
            </p>
            <a href="careers.php" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-green-50 transition duration-300">
                <i class="fas fa-leaf mr-2"></i>
                Gartenbau-Jobs ansehen
            </a>
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