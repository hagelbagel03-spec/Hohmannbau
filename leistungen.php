<?php
require_once __DIR__ . '/config/config.php';

$currentPage = 'services';
$pageTitle = 'Leistungen - Hohmann Bau';
$pageDescription = 'Unsere Bauleistungen: Hochbau, Tiefbau, Sanierungen, Umbauten und mehr. Professionelle Bauausführung seit über 25 Jahren.';

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen pt-16">
    <!-- Header Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 id="servicesTitle" class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Unsere Leistungen</h1>
                <p id="servicesSubtitle" class="text-xl text-gray-600 max-w-3xl mx-auto">Umfassende Baulösungen aus einer Hand</p>
                <p id="servicesDescription" class="text-lg text-gray-500 mt-4 max-w-4xl mx-auto">Von der ersten Idee bis zur schlüsselfertigen Übergabe begleiten wir Sie durch Ihr gesamtes Bauvorhaben. Unsere erfahrenen Fachkräfte garantieren höchste Qualität und termingerechte Ausführung.</p>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="servicesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Default Services -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl">🏗️</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Hochbau</h3>
                        <p class="text-gray-600 mb-4">Neubau von Wohn- und Geschäftsgebäuden, Einfamilienhäuser bis hin zu komplexen Gewerbeobjekten.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Planung und Ausführung</li>
                            <li>• Rohbau und Ausbau</li>
                            <li>• Schlüsselfertige Übergabe</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl">🚧</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Tiefbau</h3>
                        <p class="text-gray-600 mb-4">Fundamente, Keller, Erschließung und alle Arbeiten unter der Erdoberfläche.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Erdarbeiten und Aushub</li>
                            <li>• Fundamente und Keller</li>
                            <li>• Ver- und Entsorgung</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl">🔨</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Sanierung</h3>
                        <p class="text-gray-600 mb-4">Modernisierung und Instandsetzung bestehender Gebäude nach neuesten Standards.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Energetische Sanierung</li>
                            <li>• Dach- und Fassadensanierung</li>
                            <li>• Badsanierung</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl">🏠</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">An- und Umbau</h3>
                        <p class="text-gray-600 mb-4">Erweiterung und Umgestaltung bestehender Gebäude nach Ihren Wünschen.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Anbauten und Aufstockungen</li>
                            <li>• Grundrissänderungen</li>
                            <li>• Dachausbau</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl">🏢</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Gewerbebau</h3>
                        <p class="text-gray-600 mb-4">Bürogebäude, Lagerhallen, Produktionsstätten und andere Gewerbeimmobilien.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Büro- und Verwaltungsgebäude</li>
                            <li>• Produktions- und Lagerhallen</li>
                            <li>• Individuelle Gewerbeobjekte</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <div class="text-4xl">⚡</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Notdienst</h3>
                        <p class="text-gray-600 mb-4">24/7 Notdienst für dringende Reparaturen und Schadensbehebung.</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Wasserschäden</li>
                            <li>• Sturmschäden</li>
                            <li>• Notfallreparaturen</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Unser Bauprozess</h2>
                <p class="text-lg text-gray-600">Von der ersten Idee bis zur Schlüsselübergabe</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">1</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Beratung</h3>
                    <p class="text-gray-600">Kostenlose Erstberatung und Besichtigung vor Ort</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">2</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Planung</h3>
                    <p class="text-gray-600">Detaillierte Planung und transparente Kostenkalkulation</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">3</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Ausführung</h3>
                    <p class="text-gray-600">Professionelle Bauausführung mit regelmäßiger Qualitätskontrolle</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">4</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Übergabe</h3>
                    <p class="text-gray-600">Abnahme, Übergabe und Garantieleistungen</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Bereit für Ihr Bauprojekt?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Lassen Sie sich kostenlos und unverbindlich beraten. Wir freuen uns auf Ihr Projekt!</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= BASE_URL ?>angebot.php" class="bg-white text-green-700 hover:bg-gray-100 px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i data-lucide="calculator" class="w-5 h-5 mr-2"></i>
                    Angebot anfordern
                </a>
                <a href="<?= BASE_URL ?>kontakt.php" class="border-2 border-white text-white hover:bg-white hover:text-green-700 px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i data-lucide="phone" class="w-5 h-5 mr-2"></i>
                    Jetzt anrufen
                </a>
            </div>
        </div>
    </section>
</div>

<script>
// Load services content
fetch('<?= BASE_URL ?>api/index.php/content/services')
    .then(response => response.json())
    .then(data => {
        if (data && data.content) {
            document.getElementById('servicesTitle').textContent = data.content.title || 'Unsere Leistungen';
            document.getElementById('servicesSubtitle').textContent = data.content.subtitle || 'Umfassende Baulösungen aus einer Hand';
            document.getElementById('servicesDescription').textContent = data.content.description || 'Von der ersten Idee bis zur schlüsselfertigen Übergabe begleiten wir Sie durch Ihr gesamtes Bauvorhaben.';
        }
    })
    .catch(error => console.log('Using default services content'));

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>