    <!-- Professional Admin Sidebar -->
    <div class="admin-sidebar w-64 flex flex-col">
        <!-- Logo Section -->
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">Hohmann Bau</h1>
                    <p class="text-gray-400 text-sm">Admin-Panel</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1">
            <!-- Dashboard -->
            <a href="index.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <!-- Content Management -->
            <div class="mt-6 mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-4">Content</p>
            </div>
            
            <a href="homepage.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'homepage.php' ? 'active' : ''; ?>">
                <i class="fas fa-home w-5"></i>
                <span>Homepage</span>
            </a>
            
            <a href="services.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                <i class="fas fa-tools w-5"></i>
                <span>Leistungen</span>
            </a>
            
            <a href="team.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'team.php' ? 'active' : ''; ?>">
                <i class="fas fa-users w-5"></i>
                <span>Team</span>
            </a>
            
            <a href="news.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'news.php' ? 'active' : ''; ?>">
                <i class="fas fa-newspaper w-5"></i>
                <span>News</span>
            </a>
            
            <a href="jobs.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'jobs.php' ? 'active' : ''; ?>">
                <i class="fas fa-briefcase w-5"></i>
                <span>Stellenanzeigen</span>
            </a>
            
            <!-- Communication -->
            <div class="mt-6 mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-4">Kommunikation</p>
            </div>
            
            <a href="applications.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'applications.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt w-5"></i>
                <span>Bewerbungen</span>
                <?php
                // Badge für neue Bewerbungen (optional)
                try {
                    if (isset($db)) {
                        $newApps = $db->query("SELECT COUNT(*) as count FROM applications WHERE status = 'new'")->fetch();
                        if ($newApps['count'] > 0) {
                            echo '<span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-auto">' . $newApps['count'] . '</span>';
                        }
                    }
                } catch (Exception $e) {
                    // Ignoriere Fehler
                }
                ?>
            </a>
            
            <a href="feedback.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'feedback.php' ? 'active' : ''; ?>">
                <i class="fas fa-comments w-5"></i>
                <span>Feedback</span>
            </a>
            
            <a href="reports.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                <i class="fas fa-exclamation-triangle w-5"></i>
                <span>Berichte</span>
            </a>
            
            <a href="chat.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'chat.php' ? 'active' : ''; ?>">
                <i class="fas fa-comments-dollar w-5"></i>
                <span>Chat</span>
            </a>
            
            <!-- Design & Settings -->
            <div class="mt-6 mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-4">Design & Einstellungen</p>
            </div>
            
            <a href="colors.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'colors.php' ? 'active' : ''; ?>">
                <i class="fas fa-palette w-5"></i>
                <span>Design (Einfach)</span>
            </a>
            
            <a href="colors_advanced.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'colors_advanced.php' ? 'active' : ''; ?>">
                <i class="fas fa-sliders-h w-5"></i>
                <span>Erweiterte Farben</span>
            </a>
        </nav>
        
        <!-- Footer Section -->
        <div class="p-4 border-t border-gray-700">
            <!-- Website Link -->
            <a href="../" target="_blank" class="nav-item mb-2">
                <i class="fas fa-external-link-alt w-5"></i>
                <span>Website anzeigen</span>
            </a>
            
            <!-- Admin Info -->
            <div class="flex items-center space-x-3 p-3 bg-gray-800 rounded-lg">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="text-sm">
                    <p class="text-white font-medium">
                        <?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?>
                    </p>
                    <p class="text-gray-400">Administrator</p>
                </div>
            </div>
            
            <!-- Logout -->
            <a href="logout.php" class="nav-item mt-2 text-red-300 hover:text-red-100 hover:bg-red-600">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span>Abmelden</span>
            </a>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
        <!-- Top Navigation Bar -->
        <div class="admin-topbar px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Page Info -->
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin-Panel'; ?>
                    </h2>
                    <?php if (isset($pageSubtitle)): ?>
                        <span class="text-gray-500">•</span>
                        <p class="text-gray-600"><?php echo htmlspecialchars($pageSubtitle); ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Top Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Notifications -->
                    <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-bell"></i>
                    </button>
                    
                    <!-- Settings -->
                    <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-cog"></i>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-2 bg-gray-100 rounded-lg px-3 py-2">
                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-xs"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">
                            <?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="flex-1 p-6 overflow-auto">