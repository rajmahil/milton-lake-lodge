<?php
/**
 * Docker-specific wp-config.php
 * Reads settings from environment for production deployments.
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 */

// Helper to load from ENV or *_FILE
if (!function_exists('getenv_docker')) {
    function getenv_docker($env, $default = '')
    {
        if ($file = getenv($env . '_FILE')) {
            return rtrim(file_get_contents($file), "\r\n");
        } elseif (($val = getenv($env)) !== false) {
            return $val;
        }
        return $default;
    }
}

// ------------------------
// Redis Object Cache
// ------------------------
define('WP_REDIS_SCHEME', getenv_docker('WP_REDIS_SCHEME', 'tcp'));
define('WP_REDIS_HOST', getenv_docker('WP_REDIS_HOST', 'crossover.proxy.rlwy.net'));
define('WP_REDIS_PORT', getenv_docker('WP_REDIS_PORT', 40516));
define('WP_REDIS_CLIENT', getenv_docker('WP_REDIS_CLIENT', 'predis'));
define('WP_REDIS_USERNAME', getenv_docker('WP_REDIS_USERNAME', 'default'));
define('WP_REDIS_PASSWORD', getenv_docker('WP_REDIS_PASSWORD', ''));
define('WP_REDIS_PREFIX', getenv_docker('WP_REDIS_PREFIX', 'miltonlake_'));
define('WP_REDIS_DATABASE', getenv_docker('WP_REDIS_DATABASE', 0));
define('WP_REDIS_TIMEOUT', getenv_docker('WP_REDIS_TIMEOUT', 1));
define('WP_REDIS_READ_TIMEOUT', getenv_docker('WP_REDIS_READ_TIMEOUT', 1));
define('WP_REDIS_SSL_CONTEXT', [
    'verify_peer' => false,
    'verify_peer_name' => false,
]);

// ------------------------
// Exchange Rate API Key
// ------------------------
define('EXCHANGE_RATE_API_KEY', getenv_docker('EXCHANGE_RATE_API_KEY', ''));

// ------------------------
// WP Super Cache plugin path
// ------------------------
if (!defined('WPCACHEHOME')) {
    define('WPCACHEHOME', WP_CONTENT_DIR . '/plugins/wp-super-cache/');
}

// ------------------------
// Database settings
// ------------------------
define('DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'local'));
define('DB_USER', getenv_docker('WORDPRESS_DB_USER', 'root'));
define('DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'root'));
define('DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'localhost'));
define('DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8mb4'));
define('DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', ''));

// ------------------------
// Authentication Unique Keys and Salts
// Change at any time to invalidate all sessions
// ------------------------
define('AUTH_KEY', getenv_docker('WORDPRESS_AUTH_KEY', ''));
define('SECURE_AUTH_KEY', getenv_docker('WORDPRESS_SECURE_AUTH_KEY', ''));
define('LOGGED_IN_KEY', getenv_docker('WORDPRESS_LOGGED_IN_KEY', ''));
define('NONCE_KEY', getenv_docker('WORDPRESS_NONCE_KEY', ''));
define('AUTH_SALT', getenv_docker('WORDPRESS_AUTH_SALT', ''));
define('SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', ''));
define('LOGGED_IN_SALT', getenv_docker('WORDPRESS_LOGGED_IN_SALT', ''));
define('NONCE_SALT', getenv_docker('WORDPRESS_NONCE_SALT', ''));
define('WP_CACHE_KEY_SALT', getenv_docker('WORDPRESS_CACHE_KEY_SALT', ''));

// ------------------------
// MinIO (S3-compatible) Storage
// ------------------------
define('MINIO_ENDPOINT', getenv_docker('MINIO_ENDPOINT', ''));
define('MINIO_ACCESS_KEY', getenv_docker('MINIO_ACCESS_KEY', ''));
define('MINIO_SECRET_KEY', getenv_docker('MINIO_SECRET_KEY', ''));
define('MINIO_BUCKET', getenv_docker('MINIO_BUCKET', ''));
define('MINIO_PUBLIC_URL', getenv_docker('MINIO_PUBLIC_URL', ''));

// ------------------------
// Table prefix
// ------------------------
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

// ------------------------
// Debugging and environment
// ------------------------
define('WP_DEBUG', filter_var(getenv_docker('WORDPRESS_DEBUG', false), FILTER_VALIDATE_BOOLEAN));
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
define('WP_ENVIRONMENT_TYPE', getenv_docker('WORDPRESS_ENVIRONMENT_TYPE', 'production'));

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
