<!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">Hohmann Bau</h3>
                    <p class="text-gray-400 mb-4">Ihr zuverlässiger Partner für alle Bauvorhaben</p>
                    <p class="text-sm text-gray-500">
                        © 2024 Hohmann Bau GmbH. Alle Rechte vorbehalten.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Navigation</h4>
                    <div class="space-y-2 text-gray-400">
                        <a href="<?= BASE_URL ?>/" class="block hover:text-white transition-colors">Home</a>
                        <a href="<?= BASE_URL ?>/leistungen.php" class="block hover:text-white transition-colors">Leistungen</a>
                        <a href="<?= BASE_URL ?>/projekte.php" class="block hover:text-white transition-colors">Projekte</a>
                        <a href="<?= BASE_URL ?>/team.php" class="block hover:text-white transition-colors">Team</a>
                        <a href="<?= BASE_URL ?>/kontakt.php" class="block hover:text-white transition-colors">Kontakt</a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Services</h4>
                    <div class="space-y-2 text-gray-400">
                        <a href="<?= BASE_URL ?>/karriere.php" class="block hover:text-white transition-colors">Karriere</a>
                        <a href="<?= BASE_URL ?>/angebot.php" class="block hover:text-white transition-colors">Angebot anfordern</a>
                        <a href="<?= BASE_URL ?>/admin/" class="block hover:text-white transition-colors">Admin-Panel</a>
                        <p>Notdienst 24/7</p>
                        <p>Kostenlose Beratung</p>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontakt</h4>
                    <div class="space-y-2 text-gray-400" id="footerContact">
                        <p>Bahnhofstraße 123</p>
                        <p>12345 Musterstadt</p>
                        <p>+49 123 456 789</p>
                        <p>info@hohmann-bau.de</p>
                    </div>
                    <div class="flex space-x-4 mt-4">
                        <i data-lucide="facebook" class="w-6 h-6 text-gray-400 hover:text-white cursor-pointer transition-colors"></i>
                        <i data-lucide="instagram" class="w-6 h-6 text-gray-400 hover:text-white cursor-pointer transition-colors"></i>
                        <i data-lucide="linkedin" class="w-6 h-6 text-gray-400 hover:text-white cursor-pointer transition-colors"></i>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8">
                <div class="text-center text-gray-400 text-sm">
                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-8">
                        <span>Impressum</span>
                        <span>Datenschutz</span>
                        <span>AGB</span>
                        <span>Sitemap</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Help & Support Widget -->
    <div class="fixed bottom-4 right-4 z-40">
        <button onclick="toggleHelpWidget()" class="bg-green-700 hover:bg-green-800 text-white p-3 rounded-full shadow-lg transition-all duration-300">
            <i data-lucide="help-circle" class="w-6 h-6"></i>
        </button>
        
        <div id="helpWidget" class="hidden absolute bottom-16 right-0 bg-white rounded-lg shadow-xl p-6 w-80 border">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Hilfe & Support</h3>
                <button onclick="toggleHelpWidget()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="space-y-3">
                <a href="<?= BASE_URL ?>/kontakt.php" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center">
                        <i data-lucide="mail" class="w-5 h-5 mr-3 text-green-700"></i>
                        <div>
                            <div class="font-medium">Kontakt</div>
                            <div class="text-sm text-gray-600">Nachricht senden</div>
                        </div>
                    </div>
                </a>
                <a href="tel:+4912345678" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center">
                        <i data-lucide="phone" class="w-5 h-5 mr-3 text-green-700"></i>
                        <div>
                            <div class="font-medium">Anrufen</div>
                            <div class="text-sm text-gray-600">+49 123 456 789</div>
                        </div>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>/angebot.php" class="block p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex items-center">
                        <i data-lucide="calculator" class="w-5 h-5 mr-3 text-green-700"></i>
                        <div>
                            <div class="font-medium text-green-700">Angebot anfordern</div>
                            <div class="text-sm text-green-600">Kostenlos & unverbindlich</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleHelpWidget() {
            const widget = document.getElementById('helpWidget');
            widget.classList.toggle('hidden');
        }
        
        // Load contact info for footer
        fetch('<?= BASE_URL ?>/api/index.php/contact-info')
            .then(response => response.json())
            .then(data => {
                if (data && data.address) {
                    const footerContact = document.getElementById('footerContact');
                    footerContact.innerHTML = `
                        <p>${data.address}</p>
                        <p>${data.phone}</p>
                        <p>${data.email}</p>
                        <p>${data.opening_hours}</p>
                    `;
                }
            })
            .catch(error => console.log('Using default contact info'));
        
        // Initialize Lucide icons after content load
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>