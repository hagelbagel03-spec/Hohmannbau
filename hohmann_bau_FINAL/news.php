<?php
$title = 'Aktuelles - Hohmann Bau';
$description = 'Neuigkeiten und Informationen rund um Garten und Landschaftsbau von Hohmann Bau.';
require_once 'config/database.php';

try {
    $db = getDB();
    if (isset($_GET['id'])) {
        $article = $db->prepare("SELECT * FROM news WHERE id = ? AND published = 1");
        $article->execute([$_GET['id']]);
        $article = $article->fetch();
    } else {
        $articles = $db->query("SELECT * FROM news WHERE published = 1 ORDER BY created_at DESC")->fetchAll();
    }
} catch (Exception $e) {
    $articles = [
        ['id' => 'news-1', 'title' => 'Neue Gartensaison 2024', 'excerpt' => 'Frühjahrsrabatt auf alle Planungsleistungen', 'content' => 'Die neue Gartensaison beginnt!', 'created_at' => '2024-03-15'],
        ['id' => 'news-2', 'title' => 'Erweiterte Öffnungszeiten', 'excerpt' => 'Mehr Service für unsere Kunden', 'content' => 'Neue Öffnungszeiten ab sofort.', 'created_at' => '2024-02-20'],
        ['id' => 'news-3', 'title' => 'Auszeichnung erhalten', 'excerpt' => 'Qualität wird belohnt', 'content' => 'Wir freuen uns über die Auszeichnung.', 'created_at' => '2024-01-10']
    ];
}

include 'includes/header.php';
?>

<!-- Navigation -->
<nav class="navbar fixed top-0 left-0 right-0 z-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Hohmann Bau</h1>
                    <p class="text-xs text-gray-600">Garten & Landschaftsbau</p>
                </div>
            </div>
            <div class="hidden lg:flex items-center space-x-2">
                <a href="index.php" class="nav-link">Home</a>
                <a href="about.php" class="nav-link">Über uns</a>
                <a href="services.php" class="nav-link">Leistungen</a>
                <a href="team.php" class="nav-link">Team</a>
                <a href="careers.php" class="nav-link">Karriere</a>
                <a href="news.php" class="nav-link active">Aktuelles</a>
                <a href="contact.php" class="nav-link">Kontakt</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="contact.php" class="btn-primary-pro">
                    <i class="fas fa-envelope"></i>
                    <span>Kontakt</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<?php if (isset($article) && $article): ?>
<!-- Single Article -->
<section class="hero-gradient section-professional pt-32 pb-20 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="news.php" class="inline-flex items-center text-white mb-6 hover:text-gray-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Zurück zu allen Meldungen
        </a>
        <h1 class="heading-1 text-white mb-6"><?php echo htmlspecialchars($article['title']); ?></h1>
        <div class="flex items-center space-x-4 text-gray-200">
            <time><?php echo date('d.m.Y', strtotime($article['created_at'])); ?></time>
            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                <?php echo htmlspecialchars($article['priority'] ?? 'Aktuell'); ?>
            </span>
        </div>
    </div>
</section>

<section class="section-professional bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">
            <p class="text-body text-gray-600 leading-relaxed">
                <?php echo nl2br(htmlspecialchars($article['content'])); ?>
            </p>
        </div>
    </div>
</section>

<?php else: ?>
<!-- News Overview -->
<section class="hero-gradient section-professional pt-32 pb-20 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="heading-1 text-white mb-6">Aktuelles</h1>
        <p class="text-large text-gray-100 max-w-3xl mx-auto">
            Neuigkeiten und Informationen rund um Garten und Landschaftsbau
        </p>
    </div>
</section>

<section class="section-professional bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($articles as $article): ?>
                <div class="card-professional overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-4xl"></i>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-primary-100 text-primary-600 text-xs font-semibold px-2.5 py-0.5 rounded">
                                Wichtig
                            </span>
                            <time class="text-sm text-gray-500"><?php echo date('d.m.Y', strtotime($article['created_at'])); ?></time>
                        </div>
                        <h3 class="heading-3 text-gray-900 mb-3">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </h3>
                        <p class="text-body text-gray-600 mb-4">
                            <?php echo htmlspecialchars($article['excerpt']); ?>
                        </p>
                        <a href="news.php?id=<?php echo $article['id']; ?>" class="text-primary-600 font-semibold hover:text-primary-700 transition-colors inline-flex items-center">
                            Weiterlesen
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>