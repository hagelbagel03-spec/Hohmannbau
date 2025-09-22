<?php
$title = 'Kontakt - Hohmann Bau';
$description = 'Kontaktieren Sie Hohmann Bau für professionelle Garten- und Landschaftsbau-Dienstleistungen.';

// Contact Info
$contact_info = [
    'phone_number' => '+49 123 456-789',
    'email' => 'info@hohmann-bau.de',
    'address' => "Hohmann Garten & Landschaftsbau\nMeisterstraße 45\n12345 Gartenstadt",
    'opening_hours' => "Mo-Fr: 7:00-17:00\nSa: 8:00-14:00\nSo: Notdienst",
    'emergency_number' => '+49 123 456-999'
];

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
                <a href="tel:<?php echo $contact_info['phone_number']; ?>" class="hidden sm:flex items-center space-x-2 text-primary-600 font-semibold">
                    <i class="fas fa-phone"></i>
                    <span><?php echo $contact_info['phone_number']; ?></span>
                </a>
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
            Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten
        </p>
    </div>
</section>

<!-- Contact Content -->
<section class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div class="space-y-6">
                <h2 class="heading-2 font-heading text-gray-900 mb-6">Kontaktinformationen</h2>
                
                <div class="card-professional p-6 space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-red-100 p-3 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <h2 class="heading-2 font-heading text-gray-900 mb-6">Nachricht senden</h2>
                
                <div class="card-professional p-6" id="contact-form">
                    <form method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label-professional">Vorname *</label>
                                <input type="text" name="first_name" required class="input-professional">
                            </div>
                            <div>
                                <label class="label-professional">Nachname *</label>
                                <input type="text" name="last_name" required class="input-professional">
                            </div>
                        </div>
                        
                        <div>
                            <label class="label-professional">E-Mail *</label>
                            <input type="email" name="email" required class="input-professional">
                        </div>
                        
                        <div>
                            <label class="label-professional">Telefon</label>
                            <input type="tel" name="phone" class="input-professional">
                        </div>
                        
                        <div>
                            <label class="label-professional">Betreff *</label>
                            <select name="subject" required class="input-professional">
                                <option value="">Bitte wählen...</option>
                                <option value="beratung">Kostenlose Beratung</option>
                                <option value="kostenvoranschlag">Kostenvoranschlag</option>
                                <option value="gartenplanung">Gartenplanung</option>
                                <option value="landschaftsbau">Landschaftsbau</option>
                                <option value="pflege">Gartenpflege</option>
                                <option value="notfall">Notfall/Sturm</option>
                                <option value="sonstiges">Sonstiges</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="label-professional">Nachricht *</label>
                            <textarea name="message" required rows="6" class="input-professional" placeholder="Beschreiben Sie Ihr Projekt oder Ihre Anfrage..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn-primary-pro w-full justify-center">
                            <i class="fas fa-paper-plane"></i>
                            <span>Nachricht senden</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</content>