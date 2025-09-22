<!-- Professional Footer -->
<footer class="footer-professional section-professional">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Company Info -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-leaf text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Hohmann Bau</h3>
                        <p class="text-secondary-400 text-sm">Garten & Landschaftsbau</p>
                    </div>
                </div>
                <p class="text-secondary-300 text-sm leading-relaxed">
                    Ihr zuverlässiger Partner für professionelle Gartengestaltung und Landschaftsbau seit über 25 Jahren.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-secondary-700 rounded-lg flex items-center justify-center hover:bg-primary-500 transition-colors">
                        <i class="fab fa-facebook-f text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-secondary-700 rounded-lg flex items-center justify-center hover:bg-primary-500 transition-colors">
                        <i class="fab fa-instagram text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-secondary-700 rounded-lg flex items-center justify-center hover:bg-primary-500 transition-colors">
                        <i class="fab fa-linkedin-in text-white"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="index.php" class="footer-link">Home</a></li>
                    <li><a href="about.php" class="footer-link">Über uns</a></li>
                    <li><a href="services.php" class="footer-link">Leistungen</a></li>
                    <li><a href="team.php" class="footer-link">Team</a></li>
                    <li><a href="careers.php" class="footer-link">Karriere</a></li>
                    <li><a href="news.php" class="footer-link">Aktuelles</a></li>
                </ul>
            </div>
            
            <!-- Services -->
            <div>
                <h4 class="text-white font-semibold mb-4">Leistungen</h4>
                <ul class="space-y-2">
                    <li><a href="services.php" class="footer-link">Gartenplanung</a></li>
                    <li><a href="services.php" class="footer-link">Landschaftsbau</a></li>
                    <li><a href="services.php" class="footer-link">Pflanzarbeiten</a></li>
                    <li><a href="services.php" class="footer-link">Gartenpflege</a></li>
                    <li><a href="services.php" class="footer-link">Beratung</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-white font-semibold mb-4">Kontakt</h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-primary-400"></i>
                        <div class="text-secondary-300 text-sm">
                            <p>Musterstraße 123</p>
                            <p>12345 Musterstadt</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-primary-400"></i>
                        <a href="tel:+49123456789" class="footer-link text-sm">+49 123 456-789</a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-primary-400"></i>
                        <a href="mailto:info@hohmann-bau.de" class="footer-link text-sm">info@hohmann-bau.de</a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock text-primary-400"></i>
                        <div class="text-secondary-300 text-sm">
                            <p>Mo-Fr: 7:00-17:00</p>
                            <p>Sa: 8:00-14:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-secondary-700 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-secondary-400 text-sm">
                    © 2024 Hohmann Bau. Alle Rechte vorbehalten.
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="footer-link text-sm">Impressum</a>
                    <a href="#" class="footer-link text-sm">Datenschutz</a>
                    <a href="#" class="footer-link text-sm">AGB</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Professional JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
    
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    document.querySelectorAll('.card-professional').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Form enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Wird gesendet...';
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.dataset.originalText || 'Senden';
                }, 3000);
            }
        });
    });
    
    // Phone number click tracking
    document.querySelectorAll('a[href^="tel:"]').forEach(link => {
        link.addEventListener('click', function() {
            // You can add analytics tracking here
            console.log('Phone number clicked:', this.href);
        });
    });
    
    // Professional loading states for buttons
    document.querySelectorAll('.btn-primary-pro, .btn-secondary-pro').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.href && !this.href.includes('#')) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }
        });
    });
});
</script>

</body>
</html>