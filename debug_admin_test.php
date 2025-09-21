<?php
// Debug script to test admin functionality
session_start();

// Set admin session
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'admin';

// Simulate POST data
$_POST = [
    'action' => 'save_service',
    'service_id' => 'test_service_debug',
    'title' => 'Debug Test Service',
    'description' => 'This is a debug test service',
    'features' => '["Debug Feature 1", "Debug Feature 2"]',
    'icon' => '🔧',
    'image' => 'debug.jpg',
    'is_active' => 1
];

echo "POST data:\n";
print_r($_POST);
echo "\n\n";

// Include the admin file
ob_start();
include '/app/Hohmannbau/admin/universal_admin.php';
$output = ob_get_clean();

echo "Admin response:\n";
echo $output;
?>