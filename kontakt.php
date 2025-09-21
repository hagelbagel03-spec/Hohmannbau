<?php
require_once __DIR__ . '/config/config.php';

$currentPage = 'contact';
$pageTitle = 'Kontakt - Hohmann Bau';
$pageDescription = 'Kontaktieren Sie Hohmann Bau für Ihre Bauvorhaben. Wir beraten Sie gerne kostenlos und unverbindlich.';

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen pt-16">
    <!-- Header Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 id="contactTitle" class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Kontakt</h1>
                <p id="contactSubtitle" class="text-xl text-gray-600 max-w-3xl mx-auto">Lassen Sie uns über Ihr Projekt sprechen</p>
                <p id="contactDescription" class="text-lg text-gray-500 mt-4 max-w-4xl mx-auto">Haben Sie Fragen zu unseren Leistungen oder möchten Sie ein Projekt mit uns besprechen? Wir freuen uns auf Ihre Nachricht und melden uns schnellstmöglich bei Ihnen.</p>
            </div>
        </div>
    </section>

    <!-- Contact Form & Info Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Nachricht senden</h2>
                    <form id="contactForm" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-Mail *</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Nachricht *</label>
                            <textarea id="message" name="message" rows="5" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Beschreiben Sie Ihr Anliegen..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full btn-primary justify-center py-3">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            Nachricht senden
                        </button>
                        
                        <div id="formMessage" class="hidden p-4 rounded-lg"></div>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="space-y-8">
                    <div class="bg-green-50 rounded-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Kontaktinformationen</h2>
                        <div id="contactInfo" class="space-y-4">
                            <!-- Contact info wird hier per JavaScript geladen -->
                            <div class="flex items-start">
                                <i data-lucide="map-pin" class="w-6 h-6 text-green-700 mr-3 mt-1"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Adresse</div>
                                    <div class="text-gray-600">Bahnhofstraße 123, 12345 Musterstadt</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i data-lucide="phone" class="w-6 h-6 text-green-700 mr-3 mt-1"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Telefon</div>
                                    <div class="text-gray-600">+49 123 456 789</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i data-lucide="mail" class="w-6 h-6 text-green-700 mr-3 mt-1"></i>
                                <div>
                                    <div class="font-medium text-gray-900">E-Mail</div>
                                    <div class="text-gray-600">info@hohmann-bau.de</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i data-lucide="clock" class="w-6 h-6 text-green-700 mr-3 mt-1"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Öffnungszeiten</div>
                                    <div class="text-gray-600">Mo-Fr: 08:00-17:00 Uhr</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 rounded-lg p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Schnelle Kontaktaufnahme</h3>
                        <div class="space-y-3">
                            <a href="tel:+4912345678" class="flex items-center p-4 bg-white rounded-lg hover:shadow-md transition-shadow">
                                <i data-lucide="phone" class="w-6 h-6 text-green-700 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Direkt anrufen</div>
                                    <div class="text-sm text-gray-600">Sofortige Beratung</div>
                                </div>
                            </a>
                            
                            <a href="<?= BASE_URL ?>angebot.php" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <i data-lucide="calculator" class="w-6 h-6 text-green-700 mr-3"></i>
                                <div>
                                    <div class="font-medium text-green-700">Angebot anfordern</div>
                                    <div class="text-sm text-green-600">Kostenlos & unverbindlich</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section (optional - placeholder) -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">So finden Sie uns</h2>
                <p class="text-gray-600">Besuchen Sie uns in unserer Zentrale</p>
            </div>
            <div class="bg-gray-300 rounded-lg h-96 flex items-center justify-center">
                <div class="text-center text-gray-600">
                    <i data-lucide="map" class="w-16 h-16 mx-auto mb-4"></i>
                    <p class="text-lg">Karte wird hier angezeigt</p>
                    <p class="text-sm">Integration mit Google Maps oder OpenStreetMap möglich</p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Load contact content
fetch('<?= BASE_URL ?>api/index.php/content/contact')
    .then(response => response.json())
    .then(data => {
        if (data && data.content) {
            document.getElementById('contactTitle').textContent = data.content.title || 'Kontakt';
            document.getElementById('contactSubtitle').textContent = data.content.subtitle || 'Lassen Sie uns über Ihr Projekt sprechen';
            document.getElementById('contactDescription').textContent = data.content.description || 'Haben Sie Fragen zu unseren Leistungen oder möchten Sie ein Projekt mit uns besprechen?';
        }
    })
    .catch(error => console.log('Using default contact content'));

// Load contact info
fetch('<?= BASE_URL ?>api/index.php/contact-info')
    .then(response => response.json())
    .then(data => {
        if (data && data.address) {
            const contactInfo = document.getElementById('contactInfo');
            contactInfo.innerHTML = `
                <div class="flex items-start">
                    <i data-lucide="map-pin" class="w-6 h-6 text-green-700 mr-3 mt-1"></i>
                    <div>
                        <div class="font-medium text-gray-900">Adresse</div>
                        <div class="text-gray-600">${data.address}</div>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <i data-lucide="phone" class="w-6 h-6 text-green-700 mr-3 mt-1"></i>
                    <div>
                        <div class="font-medium text-gray-900">Telefon</div>
                        <div class="text-gray-600">${data.phone}</div>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <i data-lucide="mail" class="w-6 h-6 text-green-700 mr-3 mt-1"></i>
                    <div>
                        <div class="font-medium text-gray-900">E-Mail</div>
                        <div class="text-gray-600">${data.email}</div>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <i data-lucide="clock" class="w-6 h-6 text-green-700 mr-3 mt-1"></i>
                    <div>
                        <div class="font-medium text-gray-900">Öffnungszeiten</div>
                        <div class="text-gray-600">${data.opening_hours}</div>
                    </div>
                </div>
            `;
            lucide.createIcons();
        }
    })
    .catch(error => console.log('Using default contact info'));

// Handle contact form submission
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('formMessage');
    
    fetch('<?= BASE_URL ?>api/index.php/contact', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: formData.get('name'),
            email: formData.get('email'),
            message: formData.get('message')
        })
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.className = 'p-4 rounded-lg bg-green-50 border border-green-200 text-green-800';
        messageDiv.textContent = 'Vielen Dank für Ihre Nachricht! Wir melden uns schnellstmöglich bei Ihnen.';
        messageDiv.classList.remove('hidden');
        
        // Reset form
        document.getElementById('contactForm').reset();
        
        // Hide message after 5 seconds
        setTimeout(() => {
            messageDiv.classList.add('hidden');
        }, 5000);
    })
    .catch(error => {
        messageDiv.className = 'p-4 rounded-lg bg-red-50 border border-red-200 text-red-800';
        messageDiv.textContent = 'Fehler beim Senden der Nachricht. Bitte versuchen Sie es erneut.';
        messageDiv.classList.remove('hidden');
    });
});

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>