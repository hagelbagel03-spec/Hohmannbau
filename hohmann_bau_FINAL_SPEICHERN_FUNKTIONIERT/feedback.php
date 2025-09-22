<?php
/**
 * Feedback Page
 * Citizen feedback and rating form
 */

require_once 'config/database.php';

$pageTitle = 'Bewertung - Hohmann Bau';
$pageDescription = 'Bewerten Sie unsere Gartenbau-Leistungen und teilen Sie Ihre Erfahrungen';

include 'includes/header.php';
?>

<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Bewerten Sie unsere Arbeit</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Ihre Zufriedenheit ist unser Antrieb. Teilen Sie Ihre Erfahrungen mit unserem Gartenbau-Service.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form id="feedbackForm" class="space-y-6">
                <!-- Personal Information -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Kontaktdaten</h2>
                    
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
                </div>

                <!-- Feedback Content -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Ihr Feedback</h2>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Betreff *</label>
                        <input type="text" id="subject" name="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Worum geht es in Ihrem Feedback?">
                    </div>
                    
                    <div class="mt-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Ihre Nachricht *</label>
                        <textarea id="message" name="message" rows="6" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Teilen Sie uns Ihr Feedback mit..."></textarea>
                    </div>
                </div>

                <!-- Rating -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Bewertung</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Wie bewerten Sie unsere Arbeit? *</label>
                        <div class="flex items-center space-x-2">
                            <div id="rating" class="flex space-x-1">
                                <button type="button" class="rating-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition duration-200" data-rating="1">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button type="button" class="rating-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition duration-200" data-rating="2">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button type="button" class="rating-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition duration-200" data-rating="3">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button type="button" class="rating-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition duration-200" data-rating="4">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button type="button" class="rating-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition duration-200" data-rating="5">
                                    <i class="fas fa-star"></i>
                                </button>
                            </div>
                            <span id="ratingText" class="ml-4 text-sm text-gray-600">Bitte bewerten Sie uns</span>
                        </div>
                        <input type="hidden" id="rating_value" name="rating" required>
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-500">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-2 text-center">
                            <span>1 = Sehr schlecht</span>
                            <span>2 = Schlecht</span>
                            <span>3 = Durchschnitt</span>
                            <span>4 = Gut</span>
                            <span>5 = Sehr gut</span>
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
                                Ihr Feedback hilft uns dabei, unseren Service zu verbessern. Ihre Daten werden vertraulich behandelt 
                                und nur für die Bearbeitung Ihres Feedbacks verwendet.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" id="submitBtn" class="btn-primary px-8 py-3 text-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Feedback senden
                    </button>
                </div>
            </form>
        </div>

        <!-- Feedback Statistics -->
        <div class="mt-12 bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Bürgerzufriedenheit</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">4.8</div>
                    <div class="flex justify-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600">Durchschnittliche Bewertung</p>
                </div>
                
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">95%</div>
                    <p class="text-gray-600">Zufriedene Bürger</p>
                </div>
                
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">1,234</div>
                    <p class="text-gray-600">Feedback-Eingänge dieses Jahr</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Rating System
let selectedRating = 0;
const ratingStars = document.querySelectorAll('.rating-star');
const ratingText = document.getElementById('ratingText');
const ratingValue = document.getElementById('rating_value');

const ratingLabels = {
    1: 'Sehr schlecht',
    2: 'Schlecht', 
    3: 'Durchschnitt',
    4: 'Gut',
    5: 'Sehr gut'
};

ratingStars.forEach(star => {
    star.addEventListener('click', function() {
        selectedRating = parseInt(this.dataset.rating);
        ratingValue.value = selectedRating;
        updateRatingDisplay();
        ratingText.textContent = ratingLabels[selectedRating];
    });
    
    star.addEventListener('mouseenter', function() {
        const hoverRating = parseInt(this.dataset.rating);
        highlightStars(hoverRating);
    });
});

document.getElementById('rating').addEventListener('mouseleave', function() {
    updateRatingDisplay();
});

function highlightStars(rating) {
    ratingStars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

function updateRatingDisplay() {
    highlightStars(selectedRating);
}

// Form Submission
document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Validate form
    if (!validateForm('feedbackForm')) {
        showMessage('Bitte füllen Sie alle Pflichtfelder aus.', 'error');
        return;
    }
    
    // Validate rating
    if (selectedRating === 0) {
        showMessage('Bitte geben Sie eine Bewertung ab.', 'error');
        return;
    }
    
    setLoading(submitBtn, true);
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('/api/feedback.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Vielen Dank für Ihr Feedback! Wir schätzen Ihre Meinung sehr.', 'success');
            this.reset();
            selectedRating = 0;
            updateRatingDisplay();
            ratingText.textContent = 'Bitte bewerten Sie uns';
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
</script>

<?php include 'includes/footer.php'; ?>