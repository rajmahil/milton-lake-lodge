<?php
/**
 * Plugin Name: Redis Configurator
 * Description: Provides an admin interface to configure Redis settings, writes them to a drop-in config file, and generates a proper object-cache.php stub.
 * Version: 1.1
 * Author: ChatGPT
 */

// Bootstrap: ensure we know WP_DIRs
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}
if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
    define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
}

// Paths for our config and drop-in
define( 'REDIS_CONFIG_DIR', WP_CONTENT_DIR . '/redis-cache-config' );
define( 'REDIS_CONFIG_FILE', REDIS_CONFIG_DIR . '/redis-config.php' );
define( 'REDIS_DROPIN_FILE', WP_CONTENT_DIR . '/object-cache.php' );

// Activation: ensure config dir and write both files
register_activation_hook( __FILE__, function() {
    if ( ! file_exists( REDIS_CONFIG_DIR ) ) {
        wp_mkdir_p( REDIS_CONFIG_DIR );
    }
    Redis_Configurator::write_config();
});

// Init plugin
add_action( 'plugins_loaded', [ 'Redis_Configurator', 'init' ] );

class Redis_Configurator {
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_settings_page' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
        add_action( 'update_option_redis_settings', [ __CLASS__, 'write_config' ], 10, 2 );
        // Also write config on every load if missing
        if ( ! file_exists( REDIS_CONFIG_FILE ) ) {
            self::write_config();
        }
    }

    public static function add_settings_page() {
        add_options_page(
            'Redis Settings',
            'Redis Settings',
            'manage_options',
            'redis-configurator',
            [ __CLASS__, 'render_settings_page' ]
        );
    }

    public static function register_settings() {
        register_setting( 'redis_settings_group', 'redis_settings' );
        add_settings_section( 'redis_main', 'Connection Settings', '__return_false', 'redis-configurator' );
        $fields = [
            'scheme'   => 'tcp',
            'host'     => '127.0.0.1',
            'port'     => '6379',
            'username' => '',
            'password' => '',
            'prefix'   => 'site_',
            'database' => '0',
        ];
        foreach ( $fields as $key => $default ) {
            add_settings_field(
                "redis_{$key}",
                ucfirst( $key ),
                [ __CLASS__, 'field_callback' ],
                'redis-configurator',
                'redis_main',
                [ 'id' => $key, 'default' => $default ]
            );
        }
    }

    public static function field_callback( $args ) {
        $options = get_option( 'redis_settings', [] );
        $value   = isset( $options[ $args['id'] ] ) ? esc_attr( $options[ $args['id'] ] ) : $args['default'];
        printf(
            '<input type="text" name="redis_settings[%1$s]" value="%2$s" class="regular-text" />',
            esc_attr( $args['id'] ),
            $value
        );
    }

    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Redis Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'redis_settings_group' );
                do_settings_sections( 'redis-configurator' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function write_config() {
        // Ensure config directory
        if ( ! file_exists( REDIS_CONFIG_DIR ) ) {
            wp_mkdir_p( REDIS_CONFIG_DIR );
        }

        $opts = get_option( 'redis_settings', [] );
        // Build define lines
        $defines = [];
        foreach ( $opts as $key => $val ) {
            $constant = 'WP_REDIS_' . strtoupper( $key );
            $value    = var_export( $val, true );
            $defines[] = "define('{$constant}', {$value});";
        }
        // Always enable cache
        $defines[] = "define('WP_CACHE', true);";

        // Write the config file
        $content  = "<?php\n// Auto-generated Redis config drop-in\n" . implode( "\n", $defines ) . "\n";
        file_put_contents( REDIS_CONFIG_FILE, $content );

        // Generate object-cache.php stub
        $dropin = "<?php\n";
        $dropin .= "// Auto-generated stub to include Redis config and Redis Object Cache drop-in\n";
        $dropin .= "if ( file_exists( __DIR__ . '/redis-cache-config/redis-config.php' ) ) {\n";
        $dropin .= "    require_once __DIR__ . '/redis-cache-config/redis-config.php';\n";
        $dropin .= "}\n";
        // include real object-cache.php from the Redis plugin
        $dropin .= "if ( file_exists( WP_PLUGIN_DIR . '/redis-cache/includes/object-cache.php' ) ) {\n";
        $dropin .= "    require_once WP_PLUGIN_DIR . '/redis-cache/includes/object-cache.php';\n";
        $dropin .= "}\n";
        file_put_contents( REDIS_DROPIN_FILE, $dropin );
    }
}

// Initialize
Redis_Configurator::init();
