<?php
// Load WordPress core
require_once __DIR__ . '/wp-load.php';

header('Content-Type: text/plain');

echo "REDIS DEBUG INFO\n";
echo "================\n";

echo 'WP_REDIS_SCHEME: ' . (defined('WP_REDIS_SCHEME') ? WP_REDIS_SCHEME : 'NOT SET') . "\n";
echo 'WP_REDIS_HOST: ' . (defined('WP_REDIS_HOST') ? WP_REDIS_HOST : 'NOT SET') . "\n";
echo 'WP_REDIS_PORT: ' . (defined('WP_REDIS_PORT') ? WP_REDIS_PORT : 'NOT SET') . "\n";
echo 'WP_REDIS_CLIENT: ' . (defined('WP_REDIS_CLIENT') ? WP_REDIS_CLIENT : 'NOT SET') . "\n";
echo 'WP_REDIS_USERNAME: ' . (defined('WP_REDIS_USERNAME') ? WP_REDIS_USERNAME : 'NOT SET') . "\n";
echo 'WP_REDIS_PASSWORD: ' . (defined('WP_REDIS_PASSWORD') ? '********' : 'NOT SET') . "\n"; // Hide actual password
echo 'WP_REDIS_PREFIX: ' . (defined('WP_REDIS_PREFIX') ? WP_REDIS_PREFIX : 'NOT SET') . "\n";
echo 'WP_REDIS_DATABASE: ' . (defined('WP_REDIS_DATABASE') ? WP_REDIS_DATABASE : 'NOT SET') . "\n";
echo 'WP_REDIS_TIMEOUT: ' . (defined('WP_REDIS_TIMEOUT') ? WP_REDIS_TIMEOUT : 'NOT SET') . "\n";
echo 'WP_REDIS_READ_TIMEOUT: ' . (defined('WP_REDIS_READ_TIMEOUT') ? WP_REDIS_READ_TIMEOUT : 'NOT SET') . "\n";

echo "\nEnvironment Variables:\n";
echo "----------------------\n";
echo 'REDIS_HOST: ' . getenv('WP_REDIS_HOST') . "\n";
echo 'REDIS_PORT: ' . getenv('WP_REDIS_PORT') . "\n";
echo 'REDIS_USERNAME: ' . getenv('WP_REDIS_USERNAME') . "\n";
echo 'REDIS_PASSWORD: ' . (getenv('WP_REDIS_PASSWORD') ? '********' : 'NOT SET') . "\n";

echo "\nWP_CACHE: " . (defined('WP_CACHE') && WP_CACHE ? 'ENABLED' : 'DISABLED') . "\n";
?>
