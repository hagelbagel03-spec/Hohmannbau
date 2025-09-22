#!/bin/bash
# Fix all admin files to include functions.php

cd /app/admin

# List of files to fix
files=("applications.php" "chat.php" "feedback.php" "homepage.php" "jobs.php" "news.php" "reports.php" "services.php" "team.php")

for file in "${files[@]}"; do
    echo "Fixing $file..."
    
    # Add functions.php include after auth.php
    sed -i '/require_once.*auth\.php/a require_once '\''includes/functions.php'\'';' "$file"
    
    echo "Fixed $file"
done

echo "All admin files fixed!"