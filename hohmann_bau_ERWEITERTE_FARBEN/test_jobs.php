<?php
// Simple test for jobs functionality
require_once 'config/database.php';

echo "<h1>Jobs Test</h1>";

try {
    $db = getDB();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test jobs table
    $jobs = $db->query("SELECT * FROM jobs")->fetchAll();
    echo "<p style='color: green;'>✅ Jobs table accessible! Found " . count($jobs) . " jobs.</p>";
    
    echo "<h3>Jobs:</h3>";
    foreach ($jobs as $job) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo "<strong>" . htmlspecialchars($job['title']) . "</strong><br>";
        echo "Department: " . htmlspecialchars($job['department']) . "<br>";
        echo "Type: " . htmlspecialchars($job['type']) . "<br>";
        echo "Description: " . htmlspecialchars($job['description']) . "<br>";
        echo "</div>";
    }
    
    echo "<br><a href='admin/jobs.php'>Go to Admin Jobs Page</a>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>