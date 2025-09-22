<?php
/**
 * Report Incident Page
 * Form for citizens to report incidents
 */

require_once 'config/database.php';

$pageTitle = 'Schadensmeldung - Hohmann Bau';
$pageDescription = 'Melden Sie Garten- oder Sturmschäden für schnelle Hilfe durch Hohmann Bau';

include 'includes/header.php';
?>

<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Schadensmeldung</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Melden Sie uns Sturm-, Hagel- oder andere Gartenschäden. Unser Notdienst-Team ist schnell vor Ort.
            </p>
        </div>

        <!-- Emergency Notice -->
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-leaf text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <strong>Bei Garten-Notfällen (Sturm, Wasserschäden, etc.) erreichen Sie unseren Notdienst:</strong>
                        <a href="tel:110" class="font-bold underline ml-2">110</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form id="reportForm" class="space-y-6">
                <!-- Incident Details -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Schadens-Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="incident_type" class="block text-sm font-medium text-gray-700 mb-2">Art des Schadens *</label>
                            <select id="incident_type" name="incident_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Bitte wählen...</option>
                                <option value="Sturmschaden">Sturmschaden</option>
                                <option value="Hagelschaden">Hagelschaden</option>
                                <option value="Wasserschaden">Wasserschaden</option>
                                <option value="Frostschaden">Frostschaden</option>
                                <option value="Umgestürzter Baum">Umgestürzter Baum</option>
                                <option value="Schädlingsbefall">Schädlingsbefall</option>
                                <option value="Andere">Andere Gartenschäden</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Ort des Schadens *</label>
                            <input type="text" id="location" name="location" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Straße, Hausnummer, Ort">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="incident_date" class="block text-sm font-medium text-gray-700 mb-2">Datum des Schadens *</label>
                            <input type="date" id="incident_date" name="incident_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung des Schadens *</label>
                        <textarea id="description" name="description" rows="5" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Beschreiben Sie den Gartenschaden so detailliert wie möglich..."></textarea>
                    </div>
                </div>

                <!-- Reporter Information -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Ihre Kontaktdaten</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="reporter_name" class="block text-sm font-medium text-gray-700 mb-2">Vollständiger Name *</label>
                            <input type="text" id="reporter_name" name="reporter_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Vor- und Nachname">
                        </div>
                        
                        <div>
                            <label for="reporter_email" class="block text-sm font-medium text-gray-700 mb-2">E-Mail-Adresse *</label>
                            <input type="email" id="reporter_email" name="reporter_email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="ihre.email@beispiel.de">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="reporter_phone" class="block text-sm font-medium text-gray-700 mb-2">Telefonnummer *</label>
                        <input type="tel" id="reporter_phone" name="reporter_phone" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="+49 123 456789">
                    </div>
                    
                    <div class="mt-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_witness" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-700">Ich war vor Ort als der Schaden entstanden ist</span>
                        </label>
                    </div>
                </div>

                <!-- Additional Details -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Zusätzliche Informationen</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="witnesses_present" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Es waren weitere Personen anwesend (Nachbarn, etc.)</span>
                            </label>
                        </div>
                        
                        <div>
                            <label for="witness_details" class="block text-sm font-medium text-gray-700 mb-2">Details zu weiteren Personen (falls vorhanden)</label>
                            <textarea id="witness_details" name="witness_details" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Namen, Kontaktdaten oder weitere Informationen..."></textarea>
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="evidence_available" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Fotos des Schadens sind verfügbar</span>
                            </label>
                        </div>
                        
                        <div>
                            <label for="evidence_description" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung der Fotos/Dokumentation</label>
                            <textarea id="evidence_description" name="evidence_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Beschreiben Sie verfügbare Fotos oder andere Dokumentation des Schadens..."></textarea>
                        </div>
                        
                        <div>
                            <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">Zusätzliche Informationen</label>
                            <textarea id="additional_info" name="additional_info" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Weitere wichtige Informationen zum Gartenschaden..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Privacy Notice -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Ihre Daten werden vertraulich behandelt und nur für die Bearbeitung Ihrer Meldung verwendet. 
                                Bei Rückfragen kontaktieren wir Sie über die angegebenen Kontaktdaten.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" id="submitBtn" class="btn-primary px-8 py-3 text-lg">
                        <i class="fas fa-leaf mr-2"></i>
                        Schadensmeldung senden
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.getElementById('reportForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Validate form
    if (!validateForm('reportForm')) {
        showMessage('Bitte füllen Sie alle Pflichtfelder aus.', 'error');
        return;
    }
    
    setLoading(submitBtn, true);
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('/api/reports.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Ihre Schadensmeldung wurde erfolgreich übermittelt. Unser Notdienst-Team wird sich schnellstmöglich bei Ihnen melden.', 'success');
            this.reset();
        } else {
            throw new Error(result.error || 'Ein Fehler ist aufgetreten');
        }
    } catch (error) {
        showMessage(error.message, 'error');
    } finally {
        setLoading(submitBtn, false);
        submitBtn.innerHTML = originalText;
    }
});

// Set today as max date
document.getElementById('incident_date').max = new Date().toISOString().split('T')[0];
</script>

<?php include 'includes/footer.php'; ?>