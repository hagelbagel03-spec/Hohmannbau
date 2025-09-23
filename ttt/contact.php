<?php 
// Kontakt-Seite mit Header
include 'includes/header.php'; 

// Database connection for contact form
try {
    require_once 'config/database.php';
    $db = getDBConnection();
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
} catch (Exception $e) {
    $homepage = false;
}

$message = '';
if ($_POST && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
    // Handle contact form submission
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $msg = trim($_POST['message']);
    $phone = trim($_POST['phone'] ?? '');
    
    if ($name && $email && $msg) {
        // Here you could save to database or send email
        $message = '✅ Vielen Dank für Ihre Nachricht! Wir melden uns bald bei Ihnen.';
    } else {
        $message = '❌ Bitte füllen Sie alle Pflichtfelder aus.';
    }
}
?>

<main class="flex-1">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-green-600 to-green-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-4">Kontakt</h1>
            <p class="text-xl max-w-3xl mx-auto">
                Haben Sie Fragen zu unseren Dienstleistungen? Wir sind gerne für Sie da!
            </p>
        </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if ($message): ?>
                <div class="mb-8 p-4 rounded-lg <?= strpos($message, '✅') !== false ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Nachricht senden</h2>
                    
                    <form method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-Mail *</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Nachricht *</label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="6" 
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-green-600 text-white py-4 px-8 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            Nachricht senden
                        </button>
                    </form>
                </div>
                
                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Kontaktdaten</h2>
                    
                    <div class="space-y-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Adresse</h3>
                                <p class="text-gray-600">
                                    Musterstraße 123<br>
                                    12345 Musterstadt<br>
                                    Deutschland
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Telefon</h3>
                                <p class="text-gray-600">
                                    <?= $homepage['phone_number'] ?? '+49 123 456-789' ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">E-Mail</h3>
                                <p class="text-gray-600">
                                    <?= $homepage['email'] ?? 'info@hohmann-bau.de' ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Öffnungszeiten</h3>
                                <p class="text-gray-600">
                                    Mo-Fr: 08:00 - 17:00 Uhr<br>
                                    Sa: 09:00 - 14:00 Uhr<br>
                                    So: Nach Vereinbarung
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map placeholder -->
                    <div class="mt-12">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">So finden Sie uns</h3>
                        <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                            <p class="text-gray-600">
                                <i class="fas fa-map text-2xl mb-2"></i><br>
                                Karte wird hier angezeigt
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>