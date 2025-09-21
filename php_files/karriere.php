<?php
require_once __DIR__ . '/config/config.php';

$currentPage = 'career';
$pageTitle = 'Karriere - Hohmann Bau';
$pageDescription = 'Karriere bei Hohmann Bau. Aktuelle Stellenausschreibungen und Möglichkeiten für qualifizierte Fachkräfte im Baugewerbe.';

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen pt-16">
    <!-- Header Section -->
    <section class="py-20 bg-gradient-to-r from-green-700 to-green-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Karriere bei Hohmann Bau</h1>
                <p class="text-xl max-w-3xl mx-auto">Werden Sie Teil eines erfahrenen Teams und gestalten Sie die Zukunft des Bauens mit</p>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Warum bei uns arbeiten?</h2>
                <p class="text-lg text-gray-600">Das erwartet Sie bei Hohmann Bau</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="trending-up" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Weiterbildung</h3>
                    <p class="text-gray-600">Regelmäßige Schulungen und Weiterbildungsmöglichkeiten für Ihre berufliche Entwicklung.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="heart" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Arbeitsklima</h3>
                    <p class="text-gray-600">Ein familiäres Umfeld mit flachen Hierarchien und offener Kommunikation.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shield-check" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Sicherheit</h3>
                    <p class="text-gray-600">Sichere Arbeitsplätze in einem etablierten Unternehmen mit 25+ Jahren Erfahrung.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="coins" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Faire Vergütung</h3>
                    <p class="text-gray-600">Attraktive Bezahlung nach Tarif plus leistungsabhängige Prämien.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="clock" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Work-Life-Balance</h3>
                    <p class="text-gray-600">Flexible Arbeitszeiten und Unterstützung bei der Vereinbarkeit von Familie und Beruf.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="wrench" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Moderne Technik</h3>
                    <p class="text-gray-600">Arbeiten Sie mit modernsten Geräten und Technologien in der Baubranche.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Listings -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Aktuelle Stellenausschreibungen</h2>
                <p class="text-lg text-gray-600">Finden Sie den passenden Job für Ihre Qualifikationen</p>
            </div>
            
            <div id="jobsGrid" class="space-y-6">
                <!-- Default Job Listings -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Bauleiter (m/w/d)</h3>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    Musterstadt
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="briefcase" class="w-4 h-4 mr-1"></i>
                                    Vollzeit
                                </span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Hochbau</span>
                            </div>
                            <p class="text-gray-600 mb-4">Leitung von Hochbauprojekten von der Planung bis zur Fertigstellung. Koordination aller Gewerke und Qualitätskontrolle.</p>
                            <div class="text-sm text-gray-500">
                                <strong>Anforderungen:</strong> Bauingenieur/Architekt, mehrjährige Berufserfahrung, Führungsqualitäten
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 md:ml-6">
                            <button onclick="openJobModal('bauleiter')" class="btn-primary">
                                Details anzeigen
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Maurer (m/w/d)</h3>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    Musterstadt
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="briefcase" class="w-4 h-4 mr-1"></i>
                                    Vollzeit
                                </span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Handwerk</span>
                            </div>
                            <p class="text-gray-600 mb-4">Maurerarbeiten im Hoch- und Tiefbau. Errichtung von Wänden, Fundamenten und anderen Bauteilen.</p>
                            <div class="text-sm text-gray-500">
                                <strong>Anforderungen:</strong> Abgeschlossene Maurerlehre, Berufserfahrung, körperliche Belastbarkeit
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 md:ml-6">
                            <button onclick="openJobModal('maurer')" class="btn-primary">
                                Details anzeigen
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Auszubildende/r Beton- und Stahlbetonbauer (m/w/d)</h3>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    Musterstadt
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="briefcase" class="w-4 h-4 mr-1"></i>
                                    Ausbildung
                                </span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Ausbildung</span>
                            </div>
                            <p class="text-gray-600 mb-4">3-jährige Ausbildung zum Beton- und Stahlbetonbauer. Lernen Sie alle Aspekte des modernen Betonbaus.</p>
                            <div class="text-sm text-gray-500">
                                <strong>Anforderungen:</strong> Hauptschulabschluss, technisches Verständnis, Teamfähigkeit
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 md:ml-6">
                            <button onclick="openJobModal('ausbildung')" class="btn-primary">
                                Details anzeigen
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Jobs Message (initially hidden) -->
            <div id="noJobsMessage" class="hidden text-center py-12">
                <i data-lucide="briefcase" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Derzeit keine offenen Stellen</h3>
                <p class="text-gray-500 mb-6">Schauen Sie gerne regelmäßig vorbei oder senden Sie uns eine Initiativbewerbung.</p>
                <button onclick="openApplicationModal()" class="btn-primary">
                    Initiativbewerbung senden
                </button>
            </div>
        </div>
    </section>

    <!-- Application CTA -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Nichts Passendes gefunden?</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">Senden Sie uns gerne eine Initiativbewerbung. Wir sind immer auf der Suche nach qualifizierten Fachkräften.</p>
            <button onclick="openApplicationModal()" class="btn-primary text-lg">
                <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                Initiativbewerbung senden
            </button>
        </div>
    </section>
</div>

<!-- Job Detail Modal -->
<div id="jobModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 id="modalJobTitle" class="text-2xl font-bold text-gray-900"></h3>
                <button onclick="closeJobModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
        <div class="p-6">
            <div id="modalJobContent" class="space-y-4">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="mt-8 flex gap-4">
                <button onclick="openApplicationModal()" class="btn-primary">
                    <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                    Jetzt bewerben
                </button>
                <button onclick="closeJobModal()" class="border-2 border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-2 rounded-lg font-medium transition-colors">
                    Schließen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Application Modal -->
<div id="applicationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-900">Bewerbung senden</h3>
                <button onclick="closeApplicationModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
        <div class="p-6">
            <form id="applicationForm" class="space-y-6" enctype="multipart/form-data">
                <input type="hidden" id="job_id" name="job_id" value="">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" id="app_name" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="app_email" class="block text-sm font-medium text-gray-700 mb-2">E-Mail *</label>
                        <input type="email" id="app_email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="app_phone" class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                    <input type="tel" id="app_phone" name="phone"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="app_cover_letter" class="block text-sm font-medium text-gray-700 mb-2">Anschreiben *</label>
                    <textarea id="app_cover_letter" name="cover_letter" rows="6" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Erzählen Sie uns, warum Sie sich für diese Position interessieren..."></textarea>
                </div>

                <div>
                    <label for="cv_file" class="block text-sm font-medium text-gray-700 mb-2">Lebenslauf hochladen</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i data-lucide="upload" class="mx-auto h-12 w-12 text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="cv_file" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                    <span>Datei auswählen</span>
                                    <input id="cv_file" name="cv_file" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                                </label>
                                <p class="pl-1">oder hierher ziehen</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX bis zu 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">
                        <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                        Bewerbung senden
                    </button>
                    <button type="button" onclick="closeApplicationModal()" class="border-2 border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-2 rounded-lg font-medium transition-colors">
                        Abbrechen
                    </button>
                </div>
                
                <div id="applicationMessage" class="hidden p-4 rounded-lg"></div>
            </form>
        </div>
    </div>
</div>

<script>
// Job data
const jobDetails = {
    'bauleiter': {
        title: 'Bauleiter (m/w/d)',
        description: 'Wir suchen einen erfahrenen Bauleiter für die Leitung unserer Hochbauprojekte.',
        requirements: [
            'Abgeschlossenes Studium Bauingenieurwesen oder Architektur',
            'Mindestens 3 Jahre Berufserfahrung als Bauleiter',
            'Führungsqualitäten und Teamfähigkeit',
            'Kenntnisse in AVA-Software (z.B. GAEB)',
            'Führerschein Klasse B'
        ],
        tasks: [
            'Leitung und Koordination von Bauprojekten',
            'Überwachung der Bauausführung und Qualitätskontrolle',
            'Terminplanung und -überwachung',
            'Kommunikation mit Bauherren und Behörden',
            'Führung des Baustellenpersonals'
        ],
        benefits: [
            'Attraktive Vergütung nach Qualifikation',
            'Firmenwagen auch zur privaten Nutzung',
            'Weiterbildungsmöglichkeiten',
            'Betriebliche Altersvorsorge',
            '30 Tage Urlaub'
        ]
    },
    'maurer': {
        title: 'Maurer (m/w/d)',
        description: 'Verstärken Sie unser Team als erfahrener Maurer im Hoch- und Tiefbau.',
        requirements: [
            'Abgeschlossene Maurerlehre',
            'Mehrjährige Berufserfahrung',
            'Körperliche Belastbarkeit',
            'Teamfähigkeit und Zuverlässigkeit',
            'Führerschein Klasse B erwünscht'
        ],
        tasks: [
            'Maurerarbeiten im Hoch- und Tiefbau',
            'Errichten von Wänden und Fundamenten',
            'Verarbeitung verschiedener Baustoffe',
            'Qualitätskontrolle der eigenen Arbeit',
            'Zusammenarbeit mit anderen Gewerken'
        ],
        benefits: [
            'Tarifliche Bezahlung plus Zulagen',
            'Moderne Arbeitskleidung und Werkzeuge',
            'Gesundheitsförderung',
            'Weihnachtsgeld und Urlaubsgeld',
            'Sichere Arbeitsplätze'
        ]
    },
    'ausbildung': {
        title: 'Auszubildende/r Beton- und Stahlbetonbauer (m/w/d)',
        description: 'Starten Sie Ihre Karriere mit einer fundierten Ausbildung in unserem Unternehmen.',
        requirements: [
            'Hauptschulabschluss oder höher',
            'Interesse an handwerklicher Arbeit',
            'Technisches Verständnis',
            'Teamfähigkeit und Lernbereitschaft',
            'Körperliche Belastbarkeit'
        ],
        tasks: [
            'Erlernen der Betonherstellung und -verarbeitung',
            'Schalungsarbeiten',
            'Bewehrungsarbeiten',
            'Qualitätsprüfung',
            'Theoretische Ausbildung in der Berufsschule'
        ],
        benefits: [
            'Attraktive Ausbildungsvergütung',
            'Übernahmegarantie bei guten Leistungen',
            'Moderne Ausbildungsausstattung',
            'Erfahrene Ausbilder',
            'Zusatzqualifikationen möglich'
        ]
    }
};

function openJobModal(jobKey) {
    const job = jobDetails[jobKey];
    if (!job) return;
    
    document.getElementById('modalJobTitle').textContent = job.title;
    
    const content = document.getElementById('modalJobContent');
    content.innerHTML = `
        <div>
            <h4 class="font-semibold text-gray-900 mb-2">Stellenbeschreibung</h4>
            <p class="text-gray-600 mb-4">${job.description}</p>
        </div>
        
        <div>
            <h4 class="font-semibold text-gray-900 mb-2">Ihre Aufgaben</h4>
            <ul class="list-disc list-inside text-gray-600 space-y-1">
                ${job.tasks.map(task => `<li>${task}</li>`).join('')}
            </ul>
        </div>
        
        <div>
            <h4 class="font-semibold text-gray-900 mb-2">Ihr Profil</h4>
            <ul class="list-disc list-inside text-gray-600 space-y-1">
                ${job.requirements.map(req => `<li>${req}</li>`).join('')}
            </ul>
        </div>
        
        <div>
            <h4 class="font-semibold text-gray-900 mb-2">Was wir bieten</h4>
            <ul class="list-disc list-inside text-gray-600 space-y-1">
                ${job.benefits.map(benefit => `<li>${benefit}</li>`).join('')}
            </ul>
        </div>
    `;
    
    document.getElementById('job_id').value = jobKey;
    document.getElementById('jobModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeJobModal() {
    document.getElementById('jobModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openApplicationModal() {
    closeJobModal();
    document.getElementById('applicationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeApplicationModal() {
    document.getElementById('applicationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('applicationForm').reset();
}

// Handle application form submission
document.getElementById('applicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('applicationMessage');
    
    fetch('<?= BASE_URL ?>/api/index.php/applications', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.className = 'p-4 rounded-lg bg-green-50 border border-green-200 text-green-800';
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                <strong>Bewerbung erfolgreich gesendet!</strong>
            </div>
            <p class="mt-1">Vielen Dank für Ihre Bewerbung. Wir melden uns in Kürze bei Ihnen.</p>
        `;
        messageDiv.classList.remove('hidden');
        
        // Reset form
        document.getElementById('applicationForm').reset();
        
        // Reinitialize icons
        lucide.createIcons();
        
        // Close modal after delay
        setTimeout(() => {
            closeApplicationModal();
        }, 3000);
    })
    .catch(error => {
        messageDiv.className = 'p-4 rounded-lg bg-red-50 border border-red-200 text-red-800';
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
                <strong>Fehler beim Senden</strong>
            </div>
            <p class="mt-1">Bitte versuchen Sie es erneut oder kontaktieren Sie uns direkt.</p>
        `;
        messageDiv.classList.remove('hidden');
        lucide.createIcons();
    });
});

// Load jobs from API
fetch('<?= BASE_URL ?>/api/index.php/jobs')
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            // Here you would replace the default jobs with real data
            console.log('Jobs loaded:', data.length);
        }
    })
    .catch(error => console.log('Using default jobs'));

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>