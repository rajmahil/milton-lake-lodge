<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

define('WP_REDIS_SCHEME', 'tcp');
define('WP_REDIS_HOST', 'crossover.proxy.rlwy.net');
define('WP_REDIS_PORT', 40516);
define('WP_REDIS_CLIENT', 'predis');
define('WP_REDIS_USERNAME', 'default');
define('WP_REDIS_PASSWORD', 'LctkHNPFReQWgebFsfiuieehYcqZvtZl');
define('WP_REDIS_PREFIX', 'miltonlake_');
define('WP_REDIS_DATABASE', 0);
define('WP_REDIS_TIMEOUT', 1);
define('WP_REDIS_READ_TIMEOUT', 1);

// SSL Context for Redis
define('WP_REDIS_SSL_CONTEXT', [
    'verify_peer' => false,
    'verify_peer_name' => false,
]);

// Exchange Rate API
define('EXCHANGE_RATE_API_KEY', '1ce01530097bff1a9e1bd9d7');

define('WPCACHEHOME', '/Users/rajmahil/Local Sites/wp-test/app/public/wp-content/plugins/wp-super-cache/');
define('DB_NAME', getenv('WORDPRESS_DB_NAME') ?: 'local');
define('DB_USER', getenv('WORDPRESS_DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') ?: 'root');

// The host needs the “host:port” form, e.g. “containers-123.railway.internal:3306”
define('DB_HOST', getenv('WORDPRESS_DB_HOST') ?: 'localhost');

// Leave these as-is
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', getenv('WORDPRESS_AUTH_KEY') ?: 'x}-V,^S6?*[&IOcM(RLUSb3JRyR8HR2Qik>jeas0mCVo&ZR/Mms@$9heF>Z{FIEX');
define('SECURE_AUTH_KEY', getenv('WORDPRESS_SECURE_AUTH_KEY') ?: 'w-#4=SVr!*lOyt_xlsT9)KCFRa+B-,&.Q:(@,:~[x3h?xt7A=ak-|@nQK@:C._%r');
define('LOGGED_IN_KEY', getenv('WORDPRESS_LOGGED_IN_KEY') ?: 'jE?86.zu.b^v(g(eBt}<}tgV{*aiyNP3~*4>OsUVdo78Go=x6f1jny^FJxuzP$c#');
define('NONCE_KEY', getenv('WORDPRESS_NONCE_KEY') ?: 'z7us.]]UggrizMj.O_h4F< %3p7rspX*{gJ1A{GNT=u,vD$*h:]xC$2z?B:VR=mw');
define('AUTH_SALT', getenv('WORDPRESS_AUTH_SALT') ?: 'mE C[>d(NIIK80Qs{dQ{B4n)f1|_9iT^vWY?)~Iz<Y^oyXM7CZ!r.yJs}z6,!Ibj');
define('SECURE_AUTH_SALT', getenv('WORDPRESS_SECURE_AUTH_SALT') ?: 't40tx/#Q!{.~xtvbe=1p^7|k|;uiD%l+zJ?O_]%[~e093#38#]P/oY~hfWxbF-pR');
define('LOGGED_IN_SALT', getenv('WORDPRESS_LOGGED_IN_SALT') ?: 'S;/K@:F_utKa>X0:j<*42.$0#sL-]4iwDv38oghPrBrJ,k=|`A*)Uo6%Dh.-1EET');
define('NONCE_SALT', getenv('WORDPRESS_NONCE_SALT') ?: '412~.I|LITT@l6VXITM:Hshk^I]t6|N{8<FY7w3JnS73`Rap*[W[&YM)DwxR+H?k');
define('WP_CACHE_KEY_SALT', getenv('WORDPRESS_CACHE_KEY_SALT') ?: 'fIs,R_qOo;dp(;B65roya}RUJ@xbiG$:b:oa#hh$j*?EQn`~I~(;=Ka$xsxZ/WYj');

define('MINIO_ENDPOINT', getenv('MINIO_ENDPOINT') ?: 'https://bucket-production-599e.up.railway.app:443');
define('MINIO_ACCESS_KEY', getenv('MINIO_ACCESS_KEY') ?: '9pXsoXgu8tm4hpjKPanh');
define('MINIO_SECRET_KEY', getenv('MINIO_SECRET_KEY') ?: 'dEMI8Vb53H834EZf1oFZkSdoWJBsd239v9CFi8kf');
define('MINIO_BUCKET', getenv('MINIO_BUCKET') ?: 'wpmedia');
define('MINIO_PUBLIC_URL', getenv('MINIO_PUBLIC_URL') ?: 'https://bucket-production-599e.up.railway.app/wpmedia');

// Parse REDIS_URL from Railway into the constants Redis Object Cache actually uses

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/* Add any custom values between this line and the "stop editing" line. */

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// if (!defined('WP_DEBUG')) {
//     define('WP_DEBUG', true);
// }

define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

define('WP_ENVIRONMENT_TYPE', 'local');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
