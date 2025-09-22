<?php
/**
 * Careers Page
 * Job application form
 */

require_once 'config/database.php';

$pageTitle = 'Karriere - Hohmann Bau';
$pageDescription = 'Werden Sie Teil unseres Gartenbau-Teams. Gestalten Sie mit uns grüne Träume.';

// Standardwerte falls Datenbank nicht verfügbar
$jobs = [
    ['title' => 'Gärtner/in (m/w/d)', 'department' => 'Gartengestaltung', 'type' => 'full-time', 'description' => 'Wir suchen motivierte Gärtner für die Umsetzung kreativer Gartenprojekte.', 'requirements' => 'Abgeschlossene Gärtnerausbildung und Leidenschaft für Pflanzen erwünscht.'],
    ['title' => 'Landschaftsgärtner/in (m/w/d)', 'department' => 'Landschaftsbau', 'type' => 'full-time', 'description' => 'Für komplexe Landschaftsbauprojekte suchen wir erfahrene Fachkräfte.', 'requirements' => 'Mehrjährige Erfahrung im Landschaftsbau und Maschinenführerschein.'],
    ['title' => 'Gartendesigner/in (m/w/d)', 'department' => 'Planung', 'type' => 'part-time', 'description' => 'Unterstützung bei der Gartenplanung und Kundenberatung.', 'requirements' => 'Studium Landschaftsarchitektur oder vergleichbare Qualifikation erwünscht.']
];

// Versuche Datenbank zu laden, falls verfügbar
if (!isset($db_error)) {
    try {
        $db = getDB();
        $jobs_db = $db->query("SELECT * FROM jobs WHERE active = 1 ORDER BY created_at DESC")->fetchAll();
        if (!empty($jobs_db)) {
            $jobs = $jobs_db;
        }
    } catch (Exception $e) {
        $db_error = true;
    }
}

include 'includes/header.php';
?>

<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Karriere bei Hohmann Bau</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Werden Sie Teil unseres grünen Teams und gestalten Sie mit uns die schönsten Gärten der Region.
            </p>
        </div>

        <!-- Job Opportunities -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Aktuelle Stellenausschreibungen</h2>
            
            <div class="space-y-6">
                <?php foreach ($jobs as $job): ?>
                <div class="border-l-4 border-blue-500 pl-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($job['title']); ?></h3>
                        <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                            <?php 
                            $type_labels = [
                                'full-time' => 'Vollzeit',
                                'part-time' => 'Teilzeit', 
                                'contract' => 'Befristet',
                                'intern' => 'Praktikum'
                            ];
                            echo $type_labels[$job['type']] ?? 'Vollzeit';
                            ?>
                        </span>
                    </div>
                    <p class="text-gray-600 mb-3"><?php echo htmlspecialchars($job['department'] ?? ''); ?> • <?php echo isset($job['location']) ? htmlspecialchars($job['location']) : 'Musterstadt'; ?> • <?php echo isset($job['created_at']) ? 'Ab sofort' : 'Sofort'; ?></p>
                    <p class="text-gray-700 mb-3">
                        <?php echo htmlspecialchars($job['description']); ?>
                    </p>
                    <?php if (isset($job['requirements']) && $job['requirements']): ?>
                    <p class="text-gray-600 text-sm">
                        <strong>Anforderungen:</strong> <?php echo htmlspecialchars($job['requirements']); ?>
                    </p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Application Form -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Bewerbung einreichen</h2>
            
            <form id="applicationForm" enctype="multipart/form-data" class="space-y-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Persönliche Daten</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Vollständiger Name *</label>
                            <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Vor- und Nachname">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-Mail-Adresse *</label>
                            <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ihre.email@beispiel.de">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefonnummer *</label>
                        <input type="tel" id="phone" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="+49 123 456789">
                    </div>
                </div>

                <!-- Position -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bewerbung</h3>
                    
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Gewünschte Position *</label>
                        <select id="position" name="position" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Bitte wählen...</option>
                            <option value="Gärtner/in">Gärtner/in (m/w/d)</option>
                            <option value="Landschaftsgärtner/in">Landschaftsgärtner/in (m/w/d)</option>
                            <option value="Gartendesigner/in">Gartendesigner/in (m/w/d)</option>
                            <option value="Bauleiter/in">Bauleiter/in Landschaftsbau (m/w/d)</option>
                            <option value="Auszubildende/r">Auszubildende/r Gärtner/in</option>
                            <option value="Andere">Andere Position</option>
                        </select>
                    </div>
                    
                    <div class="mt-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Anschreiben/Motivation *</label>
                        <textarea id="message" name="message" rows="6" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Beschreiben Sie Ihre Motivation und Qualifikationen..."></textarea>
                    </div>
                </div>

                <!-- CV Upload -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bewerbungsunterlagen</h3>
                    
                    <div>
                        <label for="cv_file" class="block text-sm font-medium text-gray-700 mb-2">Lebenslauf (PDF, DOC, DOCX) *</label>
                        <input type="file" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Maximale Dateigröße: 5MB</p>
                    </div>
                    
                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Zusätzliche Unterlagen:</strong> Weitere Dokumente wie Zeugnisse oder Zertifikate 
                                    können Sie gerne in einer zusammengefassten PDF-Datei hochladen oder per E-Mail nachreichen.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Notice -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <label class="flex items-start">
                        <input type="checkbox" required class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">
                            Ich stimme der Verarbeitung meiner persönlichen Daten für Zwecke des Bewerbungsverfahrens zu. 
                            Meine Daten werden vertraulich behandelt und nicht an Dritte weitergegeben. *
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" id="submitBtn" class="btn-primary px-8 py-3 text-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Bewerbung senden
                    </button>
                </div>
            </form>
        </div>

        <!-- Benefits Section -->
        <div class="mt-12 bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Warum bei uns arbeiten?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Teamarbeit</h3>
                    <p class="text-gray-600">Arbeiten Sie in einem engagierten und professionellen Team</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Weiterbildung</h3>
                    <p class="text-gray-600">Regelmäßige Schulungen und Fortbildungsmöglichkeiten</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sinnvolle Arbeit</h3>
                    <p class="text-gray-600">Tragen Sie zur Sicherheit und zum Wohlbefinden der Gemeinschaft bei</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('applicationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Validate form
    if (!validateForm('applicationForm')) {
        showMessage('Bitte füllen Sie alle Pflichtfelder aus.', 'error');
        return;
    }
    
    // Check file upload
    const fileInput = document.getElementById('cv_file');
    if (!fileInput.files[0]) {
        showMessage('Bitte laden Sie Ihren Lebenslauf hoch.', 'error');
        return;
    }
    
    // Check file size (5MB)
    if (fileInput.files[0].size > 5 * 1024 * 1024) {
        showMessage('Die Datei ist zu groß. Maximale Größe: 5MB.', 'error');
        return;
    }
    
    setLoading(submitBtn, true);
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('api/applications.php', {
            method: 'POST',
            body: formData
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const textResponse = await response.text();
            console.error('Non-JSON response:', textResponse);
            throw new Error('Server returned invalid response format');
        }
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Ihre Bewerbung wurde erfolgreich übermittelt. Wir werden uns zeitnah bei Ihnen melden.', 'success');
            this.reset();
        } else {
            throw new Error(result.error || 'Ein Fehler ist aufgetreten');
        }
    } catch (error) {
        console.error('Application submission error:', error);
        showMessage('Fehler beim Senden der Bewerbung: ' + error.message, 'error');
    } finally {
        setLoading(submitBtn, false);
        submitBtn.innerHTML = originalText;
    }
});
</script>

<?php include 'includes/footer.php'; ?>