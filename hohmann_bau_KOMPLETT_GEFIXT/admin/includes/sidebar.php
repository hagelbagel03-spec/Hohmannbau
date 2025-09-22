    <!-- Admin Sidebar -->
    <div class="admin-sidebar w-64 shadow-lg">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">Hohmann Bau</h1>
                    <p class="text-gray-300 text-sm">Admin-Panel</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="space-y-2">
                <a href="index.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="homepage.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-home w-5"></i>
                    <span>Homepage</span>
                </a>
                
                <a href="services.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-tools w-5"></i>
                    <span>Leistungen</span>
                </a>
                
                <a href="team.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-users w-5"></i>
                    <span>Team</span>
                </a>
                
                <a href="news.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-newspaper w-5"></i>
                    <span>News</span>
                </a>
                
                <a href="jobs.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-briefcase w-5"></i>
                    <span>Stellenanzeigen</span>
                </a>
                
                <a href="applications.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Bewerbungen</span>
                </a>
                
                <a href="feedback.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-comments w-5"></i>
                    <span>Feedback</span>
                </a>
                
                <a href="reports.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-exclamation-triangle w-5"></i>
                    <span>Berichte</span>
                </a>
                
                <a href="chat.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-comments w-5"></i>
                    <span>Chat</span>
                </a>
                
                <a href="colors.php" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-palette w-5"></i>
                    <span>Design</span>
                </a>
                
                <hr class="my-6 border-gray-600">
                
                <a href="../" target="_blank" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-external-link-alt w-5"></i>
                    <span>Website anzeigen</span>
                </a>
                
                <a href="logout.php" class="flex items-center space-x-3 text-red-300 hover:text-red-100 hover:bg-red-600 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Abmelden</span>
                </a>
            </nav>
        </div>
        
        <!-- Admin Info -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-600">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="text-sm">
                    <p class="text-white font-medium">
                        <?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?>
                    </p>
                    <p class="text-gray-400">Administrator</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 admin-content">
        <div class="p-8">