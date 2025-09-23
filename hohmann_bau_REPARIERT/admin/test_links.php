<?php
session_start();
echo "Session Status:\n";
print_r($_SESSION);

echo "\nDateien im admin Verzeichnis:\n";
$files = scandir('.');
foreach($files as $file) {
    if($file != '.' && $file != '..') {
        echo "- $file\n";
    }
}
?>