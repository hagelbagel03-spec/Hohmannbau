<?php
require_once __DIR__ . '/config/config.php';

$currentPage = 'quote';
$pageTitle = 'Angebot anfordern - Hohmann Bau';
$pageDescription = 'Fordern Sie ein kostenloses und unverbindliches Angebot für Ihr Bauvorhaben an. Schnelle Bearbeitung garantiert.';

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen pt-16">
    <!-- Header Section -->
    <section class="py-20 bg-gradient-to-r from-green-700 to-green-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Angebot anfordern</h1>
                <p class="text-xl max-w-3xl mx-auto">Kostenlos & unverbindlich - Wir erstellen Ihnen ein maßgeschneidertes Angebot für Ihr Bauvorhaben</p>
            </div>
        </div>
    </section>

    <!-- Quote Form Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-xl p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Ihr Bauprojekt</h2>
                    <p class="text-gray-600">Teilen Sie uns die Details zu Ihrem Vorhaben mit</p>
                </div>

                <form id="quoteForm" class="space-y-6" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                        <input type="tel" id="phone" name="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="project_type" class="block text-sm font-medium text-gray-700 mb-2">Projekttyp *</label>
                        <select id="project_type" name="project_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Bitte wählen...</option>
                            <option value="neubau">Neubau</option>
                            <option value="sanierung">Sanierung</option>
                            <option value="anbau">Anbau</option>
                            <option value="umbau">Umbau</option>
                            <option value="tiefbau">Tiefbau</option>
                            <option value="sonstiges">Sonstiges</option>
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Projektbeschreibung *</label>
                        <textarea id="description" name="description" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Beschreiben Sie Ihr Bauvorhaben so detailliert wie möglich..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="budget_range" class="block text-sm font-medium text-gray-700 mb-2">Budget (ungefähr)</label>
                            <select id="budget_range" name="budget_range"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Keine Angabe</option>
                                <option value="bis-50k">Bis 50.000 €</option>
                                <option value="50k-100k">50.000 € - 100.000 €</option>
                                <option value="100k-250k">100.000 € - 250.000 €</option>
                                <option value="250k-500k">250.000 € - 500.000 €</option>
                                <option value="ueber-500k">Über 500.000 €</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="timeline" class="block text-sm font-medium text-gray-700 mb-2">Gewünschter Zeitrahmen</label>
                            <select id="timeline" name="timeline"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Keine Angabe</option>
                                <option value="sofort">So schnell wie möglich</option>
                                <option value="3-monate">In den nächsten 3 Monaten</option>
                                <option value="6-monate">In den nächsten 6 Monaten</option>
                                <option value="12-monate">In den nächsten 12 Monaten</option>
                                <option value="flexibel">Zeitlich flexibel</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="blueprint_file" class="block text-sm font-medium text-gray-700 mb-2">Pläne/Dokumente hochladen</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i data-lucide="upload" class="mx-auto h-12 w-12 text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="blueprint_file" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                        <span>Dateien auswählen</span>
                                        <input id="blueprint_file" name="blueprint_file" type="file" class="sr-only" 
                                               accept=".pdf,.jpg,.jpeg,.png,.dwg,.doc,.docx">
                                    </label>
                                    <p class="pl-1">oder hierher ziehen</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, JPG, PNG, DWG, DOC bis zu 10MB</p>
                            </div>
                        </div>
                        <div id="fileInfo" class="hidden mt-2 p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i data-lucide="file" class="w-5 h-5 text-green-600 mr-2"></i>
                                <span id="fileName" class="text-sm text-green-800"></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-start">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-gray-600">
                                <strong>Was passiert als nächstes?</strong>
                                <ul class="mt-2 space-y-1 list-disc list-inside">
                                    <li>Wir prüfen Ihre Anfrage binnen 24 Stunden</li>
                                    <li>Bei Bedarf kontaktieren wir Sie für Rückfragen</li>
                                    <li>Sie erhalten ein detailliertes, kostenloses Angebot</li>
                                    <li>Optional: Persönlicher Beratungstermin vor Ort</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-primary justify-center py-4 text-lg">
                        <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                        Angebot anfordern
                    </button>
                    
                    <div id="formMessage" class="hidden p-4 rounded-lg"></div>
                </form>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Warum bei uns anfragen?</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="clock" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Schnelle Bearbeitung</h3>
                    <p class="text-gray-600">Antwort binnen 24 Stunden garantiert</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="calculator" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Detaillierte Kalkulation</h3>
                    <p class="text-gray-600">Transparente Preise ohne versteckte Kosten</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shield-check" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Unverbindlich</h3>
                    <p class="text-gray-600">Kostenlos und ohne Verpflichtungen</p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Handle file upload display
document.getElementById('blueprint_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    
    if (file) {
        fileName.textContent = file.name;
        fileInfo.classList.remove('hidden');
    } else {
        fileInfo.classList.add('hidden');
    }
});

// Handle quote form submission
document.getElementById('quoteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('formMessage');
    
    fetch('<?= BASE_URL ?>/api/index.php/quote-request', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.className = 'p-4 rounded-lg bg-green-50 border border-green-200 text-green-800';
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                <strong>Vielen Dank für Ihre Anfrage!</strong>
            </div>
            <p class="mt-1">Wir haben Ihre Angebots-Anfrage erhalten und melden uns binnen 24 Stunden bei Ihnen.</p>
        `;
        messageDiv.classList.remove('hidden');
        
        // Reset form
        document.getElementById('quoteForm').reset();
        document.getElementById('fileInfo').classList.add('hidden');
        
        // Reinitialize icons
        lucide.createIcons();
        
        // Scroll to message
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>