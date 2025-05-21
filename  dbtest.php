<?php
echo '<pre>';
echo "Host: " . getenv('WORDPRESS_DB_HOST') . "\n";
echo "User: " . getenv('WORDPRESS_DB_USER') . "\n";
echo "Pass: " . getenv('WORDPRESS_DB_PASSWORD') . "\n";
echo "DB: "   . getenv('WORDPRESS_DB_NAME') . "\n";
?>