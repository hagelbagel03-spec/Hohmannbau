<?php
require_once __DIR__ . '/config/config.php';

$currentPage = 'home';
$pageTitle = 'Hohmann Bau - Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen';
$pageDescription = 'Hohmann Bau - Über 25 Jahre Erfahrung im Baugewerbe. Hochbau, Tiefbau, Sanierungen und mehr. Ihr zuverlässiger Partner für alle Bauvorhaben.';

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center hero-bg">
        <div class="relative z-10 text-center text-white max-w-4xl mx-auto px-4">
            <h1 id="heroTitle" class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Bauen mit Vertrauen
            </h1>
            <p id="heroSubtitle" class="text-xl md:text-2xl mb-8 text-gray-200">
                Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= BASE_URL ?>/kontakt.php" class="btn-primary text-lg">
                    Kontakt aufnehmen
                </a>
                <a href="<?= BASE_URL ?>/angebot.php" class="btn-outline text-lg">
                    <i data-lucide="calculator" class="w-5 h-5 mr-2"></i>
                    Angebot anfordern
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Warum Hohmann Bau?</h2>
                <p class="text-xl text-gray-600">Vertrauen Sie auf unsere Expertise und Erfahrung</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="featuresGrid">
                <!-- Features werden hier per JavaScript geladen -->
                <div class="text-center p-6 bg-white rounded-lg shadow-lg card-hover">
                    <div class="text-4xl mb-4">🏗️</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">25+ Jahre Erfahrung</h3>
                    <p class="text-gray-600">Über zwei Jahrzehnte Expertise im Baugewerbe mit hunderten erfolgreich abgeschlossenen Projekten.</p>
                </div>
                <div class="text-center p-6 bg-white rounded-lg shadow-lg card-hover">
                    <div class="text-4xl mb-4">✅</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Qualität & Zuverlässigkeit</h3>
                    <p class="text-gray-600">Höchste Qualitätsstandards und termingerechte Ausführung aller Bauvorhaben.</p>
                </div>
                <div class="text-center p-6 bg-white rounded-lg shadow-lg card-hover">
                    <div class="text-4xl mb-4">🔧</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rundum-Service</h3>
                    <p class="text-gray-600">Von der Planung bis zur Übergabe - alles aus einer Hand für Ihr Bauprojekt.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Links Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Unsere Bereiche</h2>
                <p class="text-xl text-gray-600">Entdecken Sie unser vollständiges Leistungsspektrum</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center card-hover cursor-pointer" onclick="window.location.href='<?= BASE_URL ?>/leistungen.php'">
                    <div class="text-3xl mb-4">🏗️</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Leistungen</h3>
                    <p class="text-gray-600 mb-4">Hochbau, Tiefbau, Sanierung und mehr</p>
                    <i data-lucide="arrow-right" class="w-5 h-5 mx-auto text-green-700"></i>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 text-center card-hover cursor-pointer" onclick="window.location.href='<?= BASE_URL ?>/projekte.php'">
                    <div class="text-3xl mb-4">🏢</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Projekte</h3>
                    <p class="text-gray-600 mb-4">Unsere Referenzen und aktuellen Bauvorhaben</p>
                    <i data-lucide="arrow-right" class="w-5 h-5 mx-auto text-green-700"></i>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 text-center card-hover cursor-pointer" onclick="window.location.href='<?= BASE_URL ?>/team.php'">
                    <div class="text-3xl mb-4">👥</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Team</h3>
                    <p class="text-gray-600 mb-4">Lernen Sie unsere Experten kennen</p>
                    <i data-lucide="arrow-right" class="w-5 h-5 mx-auto text-green-700"></i>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 text-center card-hover cursor-pointer" onclick="window.location.href='<?= BASE_URL ?>/karriere.php'">
                    <div class="text-3xl mb-4">💼</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Karriere</h3>
                    <p class="text-gray-600 mb-4">Werden Sie Teil unseres Teams</p>
                    <i data-lucide="arrow-right" class="w-5 h-5 mx-auto text-green-700"></i>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Load home content
fetch('<?= BASE_URL ?>/api/index.php/content/home')
    .then(response => response.json())
    .then(data => {
        if (data && data.content) {
            document.getElementById('heroTitle').textContent = data.content.hero_title || 'Bauen mit Vertrauen';
            document.getElementById('heroSubtitle').textContent = data.content.hero_subtitle || 'Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen';
            
            // Update background image if provided
            if (data.content.hero_image) {
                const heroSection = document.querySelector('.hero-bg');
                heroSection.style.backgroundImage = `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('${data.content.hero_image}')`;
            }
        }
    })
    .catch(error => console.log('Using default home content'));

// Load features
fetch('<?= BASE_URL ?>/api/index.php/features')
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            const featuresGrid = document.getElementById('featuresGrid');
            featuresGrid.innerHTML = '';
            
            data.forEach(feature => {
                const featureCard = document.createElement('div');
                featureCard.className = 'text-center p-6 bg-white rounded-lg shadow-lg card-hover';
                featureCard.innerHTML = `
                    <div class="text-4xl mb-4">${feature.icon}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">${feature.title}</h3>
                    <p class="text-gray-600">${feature.description}</p>
                `;
                featuresGrid.appendChild(featureCard);
            });
        }
    })
    .catch(error => console.log('Using default features'));

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>