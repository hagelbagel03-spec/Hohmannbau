<?php
require_once __DIR__ . '/config/config.php';

$currentPage = 'projects';
$pageTitle = 'Projekte - Hohmann Bau';
$pageDescription = 'Entdecken Sie unsere erfolgreich abgeschlossenen Bauprojekte. Referenzen aus verschiedenen Bereichen des Hoch- und Tiefbaus.';

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen pt-16">
    <!-- Header Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 id="projectsTitle" class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Unsere Projekte</h1>
                <p id="projectsSubtitle" class="text-xl text-gray-600 max-w-3xl mx-auto">Referenzen aus verschiedenen Bereichen</p>
                <p id="projectsDescription" class="text-lg text-gray-500 mt-4 max-w-4xl mx-auto">Entdecken Sie unsere erfolgreich abgeschlossenen Bauprojekte und lassen Sie sich von der Vielfalt und Qualität unserer Arbeit überzeugen.</p>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-2">
                <button onclick="filterProjects('all')" class="filter-btn active px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Alle Projekte
                </button>
                <button onclick="filterProjects('hochbau')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Hochbau
                </button>
                <button onclick="filterProjects('tiefbau')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Tiefbau
                </button>
                <button onclick="filterProjects('sanierung')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Sanierung
                </button>
                <button onclick="filterProjects('gewerbebau')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Gewerbebau
                </button>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="projectsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Default Projects -->
                <div class="project-card bg-white rounded-lg shadow-lg overflow-hidden card-hover" data-category="hochbau">
                    <div class="h-64 bg-gray-200 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">Hochbau</span>
                            <span class="text-sm text-gray-500">2023</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Einfamilienhaus Musterstadt</h3>
                        <p class="text-gray-600">Modernes Einfamilienhaus mit 150m² Wohnfläche, Keller und Garage. Energieeffizienzklasse A+.</p>
                    </div>
                </div>

                <div class="project-card bg-white rounded-lg shadow-lg overflow-hidden card-hover" data-category="gewerbebau">
                    <div class="h-64 bg-gray-200 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-medium">Gewerbebau</span>
                            <span class="text-sm text-gray-500">2023</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Bürogebäude TechPark</h3>
                        <p class="text-gray-600">Fünfstöckiges Bürogebäude mit 2.500m² Bürofläche und moderner Gebäudetechnik.</p>
                    </div>
                </div>

                <div class="project-card bg-white rounded-lg shadow-lg overflow-hidden card-hover" data-category="sanierung">
                    <div class="h-64 bg-gray-200 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">Sanierung</span>
                            <span class="text-sm text-gray-500">2022</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Altbausanierung Stadtmitte</h3>
                        <p class="text-gray-600">Vollständige energetische Sanierung eines denkmalgeschützten Gebäudes aus dem 19. Jahrhundert.</p>
                    </div>
                </div>

                <div class="project-card bg-white rounded-lg shadow-lg overflow-hidden card-hover" data-category="tiefbau">
                    <div class="h-64 bg-gray-200 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full font-medium">Tiefbau</span>
                            <span class="text-sm text-gray-500">2022</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tiefgarage Wohnanlage</h3>
                        <p class="text-gray-600">Zweistöckige Tiefgarage mit 80 Stellplätzen und moderner Belüftungsanlage.</p>
                    </div>
                </div>

                <div class="project-card bg-white rounded-lg shadow-lg overflow-hidden card-hover" data-category="hochbau">
                    <div class="h-64 bg-gray-200 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">Hochbau</span>
                            <span class="text-sm text-gray-500">2021</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Mehrfamilienhaus am Park</h3>
                        <p class="text-gray-600">Dreistöckiges Mehrfamilienhaus mit 12 Wohneinheiten und Balkonzugang.</p>
                    </div>
                </div>

                <div class="project-card bg-white rounded-lg shadow-lg overflow-hidden card-hover" data-category="gewerbebau">
                    <div class="h-64 bg-gray-200 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1590725175023-d2b489a2a4ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-medium">Gewerbebau</span>
                            <span class="text-sm text-gray-500">2021</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Produktionshalle LogiCorp</h3>
                        <p class="text-gray-600">Moderne Produktions- und Lagerhalle mit 5.000m² Fläche und Krananlage.</p>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button id="loadMoreBtn" class="btn-primary" onclick="loadMoreProjects()">
                    <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                    Weitere Projekte laden
                </button>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Unsere Zahlen sprechen für sich</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">150+</div>
                    <div class="text-green-200">Abgeschlossene Projekte</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">25+</div>
                    <div class="text-green-200">Jahre Erfahrung</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <div class="text-green-200">Kundenzufriedenheit</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">30+</div>
                    <div class="text-green-200">Mitarbeiter</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Ihr Projekt könnte das nächste sein</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">Lassen Sie uns gemeinsam Ihr Bauvorhaben realisieren. Von der Planung bis zur Fertigstellung.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= BASE_URL ?>angebot.php" class="btn-primary">
                    <i data-lucide="calculator" class="w-5 h-5 mr-2"></i>
                    Angebot anfordern
                </a>
                <a href="<?= BASE_URL ?>kontakt.php" class="border-2 border-green-700 text-green-700 hover:bg-green-700 hover:text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i data-lucide="phone" class="w-5 h-5 mr-2"></i>
                    Beratung vereinbaren
                </a>
            </div>
        </div>
    </section>
</div>

<style>
.filter-btn {
    background-color: #f3f4f6;
    color: #6b7280;
}

.filter-btn.active {
    background-color: #15803d;
    color: white;
}

.filter-btn:hover {
    background-color: #e5e7eb;
}

.filter-btn.active:hover {
    background-color: #166534;
}

.project-card.hidden {
    display: none;
}
</style>

<script>
let currentFilter = 'all';

function filterProjects(category) {
    const buttons = document.querySelectorAll('.filter-btn');
    const projects = document.querySelectorAll('.project-card');
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter projects
    projects.forEach(project => {
        if (category === 'all' || project.dataset.category === category) {
            project.classList.remove('hidden');
            project.style.display = 'block';
        } else {
            project.classList.add('hidden');
            project.style.display = 'none';
        }
    });
    
    currentFilter = category;
}

function loadMoreProjects() {
    // This would typically load more projects from the API
    // For now, we'll just show a message
    const btn = document.getElementById('loadMoreBtn');
    btn.innerHTML = '<i data-lucide="check" class="w-5 h-5 mr-2"></i>Alle Projekte geladen';
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
    lucide.createIcons();
}

// Load projects content
fetch('<?= BASE_URL ?>api/index.php/content/projects')
    .then(response => response.json())
    .then(data => {
        if (data && data.content) {
            document.getElementById('projectsTitle').textContent = data.content.title || 'Unsere Projekte';
            document.getElementById('projectsSubtitle').textContent = data.content.subtitle || 'Referenzen aus verschiedenen Bereichen';
            document.getElementById('projectsDescription').textContent = data.content.description || 'Entdecken Sie unsere erfolgreich abgeschlossenen Bauprojekte.';
        }
    })
    .catch(error => console.log('Using default projects content'));

// Load actual projects from API
fetch('<?= BASE_URL ?>api/index.php/projects')
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            // Here you would replace the default projects with real data
            console.log('Projects loaded:', data.length);
        }
    })
    .catch(error => console.log('Using default projects'));

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>