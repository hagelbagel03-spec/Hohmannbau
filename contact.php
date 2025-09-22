<?php
/**
 * Contact Page
 * Contact information and form
 */

// Windows Apache Kompatibilität - Fehlerbehandlung
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    require_once 'config/database.php';
} catch (Exception $e) {
    $db_error = true;
}

$pageTitle = 'Kontakt - Hohmann Bau';
$pageDescription = 'Kontaktieren Sie Hohmann Bau - Ihr Experte für Garten- und Landschaftsbau';

// Standardwerte falls Datenbank nicht verfügbar
$contact_info = [
    'phone_number' => '+49 123 456-789',
    'email' => 'info@hohmann-bau.de',
    'address' => "Hohmann Garten & Landschaftsbau\nMeisterstraße 45\n12345 Gartenstadt",
    'opening_hours' => "Mo-Fr: 7:00-17:00\nSa: 8:00-14:00\nSo: Notdienst",
    'emergency_number' => '+49 123 456-999'
];

// Versuche Datenbank zu laden, falls verfügbar
if (!isset($db_error)) {
    try {
        $db = getDB();
        $homepage = $db->query("SELECT * FROM homepage LIMIT 1")->fetch();
        if ($homepage) {
            $contact_info = $homepage;
        }
    } catch (Exception $e) {
        $db_error = true;
    }
}

$title = 'Kontakt - Hohmann Bau';
$description = 'Kontaktieren Sie Hohmann Bau für professionelle Garten- und Landschaftsbau-Dienstleistungen.';

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
                <a href="careers.php" class="nav-link">Karriere</a>
                <a href="news.php" class="nav-link">Aktuelles</a>
                <a href="contact.php" class="nav-link active">Kontakt</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#contact-form" class="btn-primary-pro">
                    <i class="fas fa-envelope"></i>
                    <span>Nachricht</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-gradient section-professional pt-32 pb-20 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="heading-1 text-white mb-6">Kontakt</h1>
        <p class="text-large text-gray-100 max-w-3xl mx-auto">
            Nehmen Sie Kontakt mit uns auf - wir sind für Sie da!
        </p>
    </div>
</section>

<!-- Contact Content -->
<div class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Kontaktinformationen</h2>
                
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-phone text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Garten-Notdienst</h3>
                            <p class="text-gray-600 text-lg font-bold"><?php echo htmlspecialchars($contact_info['emergency_number']); ?></p>
                            <p class="text-sm text-gray-500">Bei Sturm, Wasserschäden, Baum-Notfällen - 24/7 erreichbar</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-phone text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Beratung & Kostenvoranschlag</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($contact_info['phone_number']); ?></p>
                            <p class="text-sm text-gray-500">Für Gartenplanung, Kostenvoranschläge und Beratung</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-envelope text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">E-Mail</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($contact_info['email']); ?></p>
                            <p class="text-sm text-gray-500">Für Projektanfragen und Kostenvoranschläge</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-map-marker-alt text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Adresse</h3>
                            <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($contact_info['address'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Öffnungszeiten</h3>
                            <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($contact_info['opening_hours'])); ?></p>
                            <p class="text-sm text-green-600 font-semibold mt-1">Garten-Notdienst: 24/7 verfügbar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Contact Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Kostenvoranschlag anfragen</h2>
                
                <form id="contactForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                            <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ihr Name">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-Mail *</label>
                            <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ihre.email@beispiel.de">
                        </div>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Betreff *</label>
                        <select id="subject" name="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Wählen Sie eine Leistung</option>
                            <option value="Gartenplanung">Gartenplanung & Design</option>
                            <option value="Landschaftsbau">Landschaftsbau</option>
                            <option value="Pflanzarbeiten">Pflanzarbeiten</option>
                            <option value="Gartenpflege">Gartenpflege</option>
                            <option value="Terrassen">Terrassen & Wege</option>
                            <option value="Bewässerung">Bewässerungssysteme</option>
                            <option value="Sonstiges">Sonstiges</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Nachricht *</label>
                        <textarea id="message" name="message" rows="5" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Beschreiben Sie Ihr Gartenprojekt..."></textarea>
                    </div>
                    
                    <div class="flex justify-center">
                        <button type="submit" id="submitBtn" class="btn-primary px-8 py-3 text-lg">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Nachricht senden
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Hinweis:</strong> Kostenvoranschläge sind für Sie kostenlos und unverbindlich. 
                        Anfragen werden innerhalb von 24 Stunden bearbeitet.
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="services.php" class="bg-green-600 text-white p-6 rounded-xl text-center hover:bg-green-700 transition duration-300">
                <i class="fas fa-leaf text-3xl mb-3"></i>
                <h3 class="font-semibold mb-2">Alle Leistungen</h3>
                <p class="text-sm text-green-100">Unsere Gartenbau-Services</p>
            </a>
            
            <a href="careers.php" class="bg-blue-600 text-white p-6 rounded-xl text-center hover:bg-blue-700 transition duration-300">
                <i class="fas fa-briefcase text-3xl mb-3"></i>
                <h3 class="font-semibold mb-2">Karriere</h3>
                <p class="text-sm text-blue-100">Bei uns arbeiten</p>
            </a>
            
            <a href="feedback.php" class="bg-yellow-600 text-white p-6 rounded-xl text-center hover:bg-yellow-700 transition duration-300">
                <i class="fas fa-star text-3xl mb-3"></i>
                <h3 class="font-semibold mb-2">Bewertung</h3>
                <p class="text-sm text-yellow-100">Bewerten Sie uns</p>
            </a>
            
            <a href="about.php" class="bg-purple-600 text-white p-6 rounded-xl text-center hover:bg-purple-700 transition duration-300">
                <i class="fas fa-info-circle text-3xl mb-3"></i>
                <h3 class="font-semibold mb-2">Über uns</h3>
                <p class="text-sm text-purple-100">Unsere Geschichte</p>
            </a>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Validate form
    if (!validateForm('contactForm')) {
        showMessage('Bitte füllen Sie alle Pflichtfelder aus.', 'error');
        return;
    }
    
    setLoading(submitBtn, true);
    
    try {
        // Simulate sending (in real implementation, this would go to an API)
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        showMessage('Ihre Kostenvoranschlag-Anfrage wurde erfolgreich gesendet. Wir werden uns schnellstmöglich bei Ihnen melden.', 'success');
        this.reset();
    } catch (error) {
        showMessage('Fehler beim Senden der Nachricht. Bitte versuchen Sie es erneut.', 'error');
    } finally {
        setLoading(submitBtn, false);
        submitBtn.innerHTML = originalText;
    }
});
</script>

<?php include 'includes/footer.php'; ?>