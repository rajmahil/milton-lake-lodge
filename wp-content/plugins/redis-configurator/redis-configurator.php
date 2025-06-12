<?php
/**
 * Plugin Name: Redis Configurator
 * Description: Provides an admin interface to configure Redis settings, writes them to a drop-in config file, and generates a proper object-cache.php stub.
 * Version: 1.2
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
egister_activation_hook( __FILE__, function() {
    if ( ! file_exists( REDIS_CONFIG_DIR ) ) {
        wp_mkdir_p( REDIS_CONFIG_DIR );
    }
    Redis_Configurator::write_config();
});

// Init plugin\add_action( 'plugins_loaded', [ 'Redis_Configurator', 'init' ] );

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
            'host'     => '',
            'port'     => '',
            'username' => '',
            'password' => '',
            'prefix'   => '',
            'database' => '',
        ];
        foreach ( $fields as $key => $default ) {
            add_settings_field(
                "redis_{$key}",
                ucfirst( $key ),
                [ __CLASS__, 'field_callback' ],
                'redis-configurator',
                'redis_main',
                [ 'id' => $key ]
            );
        }
    }

    public static function field_callback( $args ) {
        $options = get_option( 'redis_settings', [] );
        $value   = isset( $options[ $args['id'] ] ) ? esc_attr( $options[ $args['id'] ] ) : '';
        printf(
            '<input type="text" name="redis_settings[%1$s]" value="%2$s" class="regular-text" placeholder="%3$s" />',
            esc_attr( $args['id'] ),
            $value,
            esc_attr( strtoupper( $args['id'] ) )
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

    // Fetch either user-saved options or environment vars
    protected static function get_config_value( $key, $default = '' ) {
        $opts = get_option( 'redis_settings', [] );
        if ( ! empty( $opts[ $key ] ) ) {
            return $opts[ $key ];
        }
        // Fallback to environment var
        $env_key = 'WP_REDIS_' . strtoupper( $key );
        $env_val = getenv( $env_key ) ?: getenv( 'REDIS_' . strtoupper( $key ) );
        return $env_val !== false ? $env_val : $default;
    }

    public static function write_config() {
        // Ensure config directory
        if ( ! file_exists( REDIS_CONFIG_DIR ) ) {
            wp_mkdir_p( REDIS_CONFIG_DIR );
        }

        // Keys to define
        $keys = [ 'scheme' => 'tcp', 'host' => '', 'port' => '', 'username' => '', 'password' => '', 'prefix' => '', 'database' => '' ];
        $defines = [];
        foreach ( $keys as $key => $default ) {
            $val = self::get_config_value( $key, $default );
            $const = 'WP_REDIS_' . strtoupper( $key );
            $defines[] = sprintf("define('%s', %s);", $const, var_export( $val, true ) );
        }
        // Additional constants
        $defines[] = "define('WP_CACHE', true);";
        $defines[] = "define('WP_REDIS_TIMEOUT', 1);";
        $defines[] = "define('WP_REDIS_READ_TIMEOUT', 1);";
        $defines[] = "define('WP_REDIS_SSL_CONTEXT', ['verify_peer'=>false,'verify_peer_name'=>false]);";

        // Write config file
        $content  = "<?php\n// Auto-generated Redis config drop-in\n" . implode( "\n", $defines ) . "\n";
        file_put_contents( REDIS_CONFIG_FILE, $content );

        // Generate object-cache.php stub
        $dropin = "<?php\n";
        $dropin .= "// Auto-generated stub to include Redis config and real drop-in\n";
        $dropin .= "if ( file_exists( __DIR__ . '/redis-cache-config/redis-config.php' ) ) {\n";
        $dropin .= "  require_once __DIR__ . '/redis-cache-config/redis-config.php';\n";
        $dropin .= "}\n";
        $dropin .= "// Load Redis Object Cache drop-in from plugin\n";
        $dropin .= "if ( file_exists( WP_PLUGIN_DIR . '/redis-cache/includes/object-cache.php' ) ) {\n";
        $dropin .= "  require_once WP_PLUGIN_DIR . '/redis-cache/includes/object-cache.php';\n";
        $dropin .= "}\n";
        file_put_contents( REDIS_DROPIN_FILE, $dropin );
    }
}

// Initialize
Redis_Configurator::init();
