</main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-shield-alt text-2xl text-blue-400"></i>
                        <span class="font-bold text-xl">Hohmann Bau</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Sicherheit und Schutz für unsere Gemeinschaft. Moderne Polizeiarbeit im Dienste der Bürger.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-blue-400 transition duration-300">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-blue-400 transition duration-300">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-blue-400 transition duration-300">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Schnellzugriff</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-300 hover:text-blue-400 transition duration-300">Startseite</a></li>
                        <li><a href="about.php" class="text-gray-300 hover:text-blue-400 transition duration-300">Über uns</a></li>
                        <li><a href="services.php" class="text-gray-300 hover:text-blue-400 transition duration-300">Dienste</a></li>
                        <li><a href="team.php" class="text-gray-300 hover:text-blue-400 transition duration-300">Team</a></li>
                        <li><a href="news.php" class="text-gray-300 hover:text-blue-400 transition duration-300">Aktuelles</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontakt</h3>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-phone text-blue-400"></i>
                            <span class="text-gray-300">+49 123 456-789</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-blue-400"></i>
                            <span class="text-gray-300">info@hohmann-bau.de</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-map-marker-alt text-blue-400 mt-1"></i>
                            <span class="text-gray-300">
                                Hohmann Garten & Landschaftsbau<br>
                                Meisterstraße 45<br>
                                12345 Gartenstadt
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-8 border-gray-700">
            
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-300 text-sm">
                    © <?php echo date('Y'); ?> Hohmann Bau. Alle Rechte vorbehalten.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="about.php" class="text-gray-300 hover:text-blue-400 text-sm transition duration-300">Über uns</a>
                    <a href="admin/login.php" class="text-gray-300 hover:text-blue-400 text-sm transition duration-300">Admin</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Chat Widget -->
    <div id="chatWidget" class="fixed bottom-4 right-4 z-50">
        <div id="chatButton" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 cursor-pointer shadow-lg transition duration-300" onclick="toggleChat()">
            <i class="fas fa-comments text-xl"></i>
        </div>
        
        <div id="chatWindow" class="hidden absolute bottom-16 right-0 w-80 bg-white rounded-lg shadow-xl border">
            <div class="bg-blue-600 text-white p-4 rounded-t-lg">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Hilfe & Support</h3>
                    <button onclick="toggleChat()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-4">
                <p class="text-gray-600 mb-4">Hallo! Wie können wir Ihnen helfen?</p>
                
                <div class="space-y-2">
                    <button onclick="openEmail()" class="w-full text-left p-2 hover:bg-gray-100 rounded transition duration-300">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                        E-Mail senden
                    </button>
                    <button onclick="callPhone()" class="w-full text-left p-2 hover:bg-gray-100 rounded transition duration-300">
                        <i class="fas fa-phone mr-2 text-green-600"></i>
                        Anrufen
                    </button>
                    <button onclick="reportIncident()" class="w-full text-left p-2 hover:bg-gray-100 rounded transition duration-300">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                        Vorfall melden
                    </button>
                </div>
                
                <div class="mt-4 p-3 bg-gray-50 rounded text-sm text-gray-600">
                    <strong>Öffnungszeiten:</strong><br>
                    Mo-Fr: 8:00-18:00<br>
                    <strong>Notfall:</strong> 110
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Chat Widget Functions
        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            chatWindow.classList.toggle('hidden');
        }
        
        function openEmail() {
            window.location.href = 'mailto:support@hohmann-bau.de';
        }
        
        function callPhone() {
            window.location.href = 'tel:+491234567890';
        }
        
        function reportIncident() {
            window.location.href = 'report.php';
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Form validation helper
        function validateForm(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    isValid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
                
                if (input.type === 'email' && input.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(input.value)) {
                        input.classList.add('border-red-500');
                        isValid = false;
                    }
                }
            });
            
            return isValid;
        }
        
        // Success/Error message display
        function showMessage(message, type = 'success') {
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            messageDiv.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                if (messageDiv.parentElement) {
                    messageDiv.remove();
                }
            }, 5000);
        }
        
        // Loading state helper
        function setLoading(button, isLoading) {
            if (isLoading) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Wird verarbeitet...';
            } else {
                button.disabled = false;
                button.innerHTML = button.getAttribute('data-original-text') || 'Senden';
            }
        }
    </script>
    
    <!-- Additional JavaScript if specified -->
    <?php if (isset($additionalJS)): ?>
        <?php echo $additionalJS; ?>
    <?php endif; ?>
    
</body>
</html>