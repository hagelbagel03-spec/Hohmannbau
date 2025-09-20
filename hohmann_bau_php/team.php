<?php
require_once __DIR__ . '/config/config.php';

$currentPage = 'team';
$pageTitle = 'Team - Hohmann Bau';
$pageDescription = 'Lernen Sie unser erfahrenes Team kennen. Experten für Hoch- und Tiefbau mit jahrzehntelanger Erfahrung.';

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen pt-16">
    <!-- Header Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 id="teamTitle" class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Unser Team</h1>
                <p id="teamSubtitle" class="text-xl text-gray-600 max-w-3xl mx-auto">Erfahrene Fachkräfte für Ihr Projekt</p>
                <p id="teamDescription" class="text-lg text-gray-500 mt-4 max-w-4xl mx-auto">Lernen Sie die Menschen kennen, die hinter unseren erfolgreichen Bauprojekten stehen. Unser erfahrenes Team aus Ingenieuren, Architekten und Baupleitern bringt jahrzehntelange Expertise mit.</p>
            </div>
        </div>
    </section>

    <!-- Team Grid -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="teamGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                <!-- Default Team Members -->
                <div class="text-center">
                    <div class="relative mx-auto w-48 h-48 mb-6">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Thomas Hohmann" class="w-full h-full object-cover rounded-full shadow-lg">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Thomas Hohmann</h3>
                    <p class="text-green-600 font-medium mb-3">Geschäftsführer & Bauleiter</p>
                    <p class="text-gray-600 text-sm">Über 30 Jahre Erfahrung im Baugewerbe. Spezialist für Hochbau und Projektmanagement.</p>
                </div>

                <div class="text-center">
                    <div class="relative mx-auto w-48 h-48 mb-6">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Michael Schmidt" class="w-full h-full object-cover rounded-full shadow-lg">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Michael Schmidt</h3>
                    <p class="text-green-600 font-medium mb-3">Architekt & Planer</p>
                    <p class="text-gray-600 text-sm">Diplom-Architekt mit Fokus auf nachhaltiges und energieeffizientes Bauen.</p>
                </div>

                <div class="text-center">
                    <div class="relative mx-auto w-48 h-48 mb-6">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616c95a8c15?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Sarah Weber" class="w-full h-full object-cover rounded-full shadow-lg">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Sarah Weber</h3>
                    <p class="text-green-600 font-medium mb-3">Bauingenieurin</p>
                    <p class="text-gray-600 text-sm">Expertin für Statik und Bauphysik. Verantwortlich für die technische Planung.</p>
                </div>

                <div class="text-center">
                    <div class="relative mx-auto w-48 h-48 mb-6">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Klaus Müller" class="w-full h-full object-cover rounded-full shadow-lg">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Klaus Müller</h3>
                    <p class="text-green-600 font-medium mb-3">Polier & Qualitätskontrolle</p>
                    <p class="text-gray-600 text-sm">Langjähriger Polier mit Auge fürs Detail. Sorgt für höchste Qualitätsstandards.</p>
                </div>

                <div class="text-center">
                    <div class="relative mx-auto w-48 h-48 mb-6">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Andreas Klein" class="w-full h-full object-cover rounded-full shadow-lg">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Andreas Klein</h3>
                    <p class="text-green-600 font-medium mb-3">Tiefbau-Spezialist</p>
                    <p class="text-gray-600 text-sm">20 Jahre Erfahrung im Tiefbau. Experte für Erdarbeiten und Fundamentbau.</p>
                </div>

                <div class="text-center">
                    <div class="relative mx-auto w-48 h-48 mb-6">
                        <img src="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Jennifer Hoffmann" class="w-full h-full object-cover rounded-full shadow-lg">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Jennifer Hoffmann</h3>
                    <p class="text-green-600 font-medium mb-3">Projektkoordinatorin</p>
                    <p class="text-gray-600 text-sm">Koordiniert alle Gewerke und sorgt für reibungslose Projektabläufe.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Unsere Werte</h2>
                <p class="text-lg text-gray-600">Was uns antreibt und leitet</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="award" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Qualität</h3>
                    <p class="text-gray-600">Höchste Standards in Material und Ausführung - das ist unser Versprechen an Sie.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="handshake" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Vertrauen</h3>
                    <p class="text-gray-600">Langfristige Partnerschaften basieren auf Vertrauen, Ehrlichkeit und Zuverlässigkeit.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="users" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Teamwork</h3>
                    <p class="text-gray-600">Gemeinsam erreichen wir mehr. Unser erfahrenes Team arbeitet Hand in Hand.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Team CTA -->
    <section class="py-20 bg-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Werden Sie Teil unseres Teams</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Wir suchen qualifizierte Fachkräfte, die unsere Leidenschaft für das Bauen teilen.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= BASE_URL ?>/karriere.php" class="bg-white text-green-700 hover:bg-gray-100 px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i data-lucide="briefcase" class="w-5 h-5 mr-2"></i>
                    Aktuelle Stellenangebote
                </a>
                <a href="<?= BASE_URL ?>/kontakt.php" class="border-2 border-white text-white hover:bg-white hover:text-green-700 px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i data-lucide="mail" class="w-5 h-5 mr-2"></i>
                    Initiativbewerbung
                </a>
            </div>
        </div>
    </section>
</div>

<script>
// Load team content
fetch('<?= BASE_URL ?>/api/index.php/content/team')
    .then(response => response.json())
    .then(data => {
        if (data && data.content) {
            document.getElementById('teamTitle').textContent = data.content.title || 'Unser Team';
            document.getElementById('teamSubtitle').textContent = data.content.subtitle || 'Erfahrene Fachkräfte für Ihr Projekt';
            document.getElementById('teamDescription').textContent = data.content.description || 'Lernen Sie die Menschen kennen, die hinter unseren erfolgreichen Bauprojekten stehen.';
        }
    })
    .catch(error => console.log('Using default team content'));

// Load actual team members from API
fetch('<?= BASE_URL ?>/api/index.php/team')
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            const teamGrid = document.getElementById('teamGrid');
            teamGrid.innerHTML = '';
            
            data.forEach(member => {
                const memberCard = document.createElement('div');
                memberCard.className = 'text-center';
                memberCard.innerHTML = `
                    <div class="relative mx-auto w-48 h-48 mb-6">
                        <img src="${member.image_url}" alt="${member.name}" 
                             class="w-full h-full object-cover rounded-full shadow-lg">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">${member.name}</h3>
                    <p class="text-green-600 font-medium mb-3">${member.role}</p>
                    <p class="text-gray-600 text-sm">${member.bio || ''}</p>
                `;
                teamGrid.appendChild(memberCard);
            });
        }
    })
    .catch(error => console.log('Using default team members'));

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>