<?php
/**
 * News Page
 * Display all published news articles
 */

require_once 'config/database.php';

$pageTitle = 'Aktuelles - Hohmann Bau';
$pageDescription = 'Aktuelle Nachrichten und Tipps rund um Garten- und Landschaftsbau';

$db = getDB();

// Get single article if ID provided
$article = null;
if (isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ? AND published = 1");
    $stmt->execute([htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8')]);
    $article = $stmt->fetch();
}

// Get all published news if no specific article
$articles = [];
if (!$article) {
    $articles = $db->query("SELECT * FROM news WHERE published = 1 ORDER BY date DESC")->fetchAll();
}

include 'includes/header.php';
?>

<?php if ($article): ?>
<!-- Single Article -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="h-64 bg-gray-200 flex items-center justify-center">
                <i class="fas fa-newspaper text-6xl text-gray-400"></i>
            </div>
            
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                        <?php echo $article['priority'] === 'high' ? 'Wichtig' : ($article['priority'] === 'urgent' ? 'Eilmeldung' : 'Aktuell'); ?>
                    </span>
                    <time class="text-gray-500">
                        <?php echo date('d.m.Y H:i', strtotime($article['date'])); ?>
                    </time>
                </div>
                
                <h1 class="text-4xl font-bold text-gray-900 mb-6"><?php echo htmlspecialchars($article['title']); ?></h1>
                
                <?php if ($article['excerpt']): ?>
                <p class="text-xl text-gray-600 mb-8 font-medium">
                    <?php echo htmlspecialchars($article['excerpt']); ?>
                </p>
                <?php endif; ?>
                
                <div class="prose max-w-none">
                    <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                </div>
                
                <div class="mt-8 pt-8 border-t">
                    <a href="news.php" class="text-green-600 hover:text-green-800 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Zurück zu allen Meldungen
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php else: ?>
<!-- All Articles -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Aktuelle Meldungen</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Bleiben Sie über wichtige Ereignisse und Nachrichten aus unserer Gemeinde informiert
            </p>
        </div>

        <?php if (empty($articles)): ?>
        <div class="text-center py-12">
            <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-400 mb-2">Keine Meldungen verfügbar</h2>
            <p class="text-gray-500">Es sind derzeit keine aktuellen Meldungen vorhanden.</p>
        </div>
        <?php else: ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($articles as $news): ?>
            <article class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            <?php echo $news['priority'] === 'high' ? 'Wichtig' : ($news['priority'] === 'urgent' ? 'Eilmeldung' : 'Aktuell'); ?>
                        </span>
                        <time class="text-sm text-gray-500">
                            <?php echo date('d.m.Y', strtotime($news['date'])); ?>
                        </time>
                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                        <?php echo htmlspecialchars($news['title']); ?>
                    </h2>
                    
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        <?php echo htmlspecialchars($news['excerpt'] ?: substr(strip_tags($news['content']), 0, 120) . '...'); ?>
                    </p>
                    
                    <a href="news.php?id=<?php echo $news['id']; ?>" 
                       class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
                        Weiterlesen
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    color: #374151;
    line-height: 1.7;
}

.prose p {
    margin-bottom: 1.25em;
}

.prose h2 {
    font-size: 1.5em;
    font-weight: 600;
    margin-top: 2em;
    margin-bottom: 1em;
}

.prose h3 {
    font-size: 1.25em;
    font-weight: 600;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
}
</style>

<?php include 'includes/footer.php'; ?>