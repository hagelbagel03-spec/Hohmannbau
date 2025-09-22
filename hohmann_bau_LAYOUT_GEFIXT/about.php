<?php
/**
 * About Page
 * Information about Stadtwache
 */

require_once 'config/database.php';

$pageTitle = 'Über uns - Hohmann Bau';
$pageDescription = 'Erfahren Sie mehr über Hohmann Bau und unsere Expertise im Garten- und Landschaftsbau';

$db = getDB();

// Get about page content
$about = $db->query("SELECT * FROM about LIMIT 1")->fetch();
if (!$about) {
    $about = [
        'title' => 'Über Hohmann Bau',
        'subtitle' => 'Ihr vertrauensvoller Partner für Garten- und Landschaftsbau',
        'content' => 'Hohmann Bau ist ein traditionsreiches Familienunternehmen, das sich seit über 25 Jahren der professionellen Gartengestaltung und dem Landschaftsbau widmet.',
        'mission' => 'Unsere Mission ist es, grüne Träume zu verwirklichen und Lebensräume zu schaffen, die Menschen begeistern.',
        'vision' => 'Nachhaltige Gartenparadiese, die Mensch und Natur in Einklang bringen.',
        'values' => 'Qualität, Nachhaltigkeit, Kreativität und Kundenzufriedenheit stehen im Mittelpunkt unserer Arbeit.',
        'history' => 'Seit der Gründung 1998 hat sich Hohmann Bau zu einem der führenden Gartenbauunternehmen der Region entwickelt.'
    ];
}

// Get team members
$team = $db->query("SELECT * FROM team WHERE active = 1 ORDER BY `order`, name")->fetchAll();

// Get services
$services = $db->query("SELECT * FROM services WHERE active = 1 ORDER BY `order`, title")->fetchAll();

include 'includes/header.php';
?>

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($about['title']); ?></h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                <?php echo htmlspecialchars($about['subtitle']); ?>
            </p>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <div>
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Unsere Geschichte</h2>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        <?php echo nl2br(htmlspecialchars($about['content'])); ?>
                    </p>
                    
                    <?php if ($about['history']): ?>
                    <p class="text-gray-700 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($about['history'])); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="space-y-6">
                <?php if ($about['mission']): ?>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-bullseye text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Unsere Mission</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($about['mission'])); ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <?php if ($about['vision']): ?>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-eye text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Unsere Vision</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($about['vision'])); ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <?php if ($about['values']): ?>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 rounded-full p-3 mr-4">
                            <i class="fas fa-heart text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Unsere Werte</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($about['values'])); ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Services Section -->
        <?php if (!empty($services)): ?>
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Unsere Dienste</h2>
                <p class="text-xl text-gray-600">Umfassende Sicherheitsdienste für unsere Gemeinschaft</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($services as $service): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition duration-300">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-<?php 
                            echo $service['icon'] === 'Shield' ? 'shield-alt' : 
                                 ($service['icon'] === 'Search' ? 'search' : 
                                 ($service['icon'] === 'Users' ? 'users' : 
                                 ($service['icon'] === 'Car' ? 'car' : 'shield-alt'))); 
                        ?> text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($service['description']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Team Section -->
        <?php if (!empty($team)): ?>
        <div>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Unser Team</h2>
                <p class="text-xl text-gray-600">Erfahrene Fachkräfte im Dienste der Bürger</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($team as $member): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition duration-300">
                    <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($member['name']); ?></h3>
                    <p class="text-blue-600 font-semibold mb-3"><?php echo htmlspecialchars($member['position']); ?></p>
                    
                    <?php if ($member['description']): ?>
                    <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($member['description']); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($member['email'] || $member['phone']): ?>
                    <div class="flex justify-center space-x-3">
                        <?php if ($member['email']): ?>
                        <a href="mailto:<?php echo $member['email']; ?>" 
                           class="bg-blue-100 p-2 rounded-full text-blue-600 hover:bg-blue-200 transition duration-300">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($member['phone']): ?>
                        <a href="tel:<?php echo $member['phone']; ?>" 
                           class="bg-green-100 p-2 rounded-full text-green-600 hover:bg-green-200 transition duration-300">
                            <i class="fas fa-phone"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-blue-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Haben Sie Fragen oder Anliegen?</h2>
        <p class="text-xl text-blue-100 mb-8">
            Zögern Sie nicht, uns zu kontaktieren. Wir sind für Sie da.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact.php" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                <i class="fas fa-envelope mr-2"></i>
                Kontakt aufnehmen
            </a>
            <a href="/report.php" class="bg-red-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition duration-300">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Vorfall melden
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>