<?php
/**
 * Plugin Name: Redis Configurator
 * Description: Provides an admin interface to configure Redis settings and writes them to a drop-in config file.
 * Version: 1.0
 * Author: ChatGPT
 */

// Directory for generated config
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}

define( 'REDIS_CONFIG_DIR', WP_CONTENT_DIR . '/redis-cache-config' );
define( 'REDIS_CONFIG_FILE', REDIS_CONFIG_DIR . '/redis-config.php' );

// Create config directory if not exists
register_activation_hook( __FILE__, function() {
    if ( ! file_exists( REDIS_CONFIG_DIR ) ) {
        wp_mkdir_p( REDIS_CONFIG_DIR );
    }
    // generate initial config
    Redis_Configurator::write_config();
});

// Include drop-in config when plugin loads
add_action( 'plugins_loaded', function() {
    if ( file_exists( REDIS_CONFIG_FILE ) ) {
        include REDIS_CONFIG_FILE;
    }
});

class Redis_Configurator {
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_settings_page' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
        add_action( 'update_option_redis_settings', [ __CLASS__, 'write_config' ], 10, 2 );
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

    // Write the PHP config file based on saved settings
    public static function write_config() {
        if ( ! file_exists( REDIS_CONFIG_DIR ) ) {
            wp_mkdir_p( REDIS_CONFIG_DIR );
        }
        $opts = get_option( 'redis_settings', [] );
        $defines = [];
        foreach ( $opts as $key => $val ) {
            $constant = 'WP_REDIS_' . strtoupper( $key );
            $value    = var_export( $val, true );
            $defines[] = "define('{$constant}', {$value});";
        }
        $content = "<?php\n// Auto-generated Redis config drop-in\n" . implode( "\n", $defines ) . "\n";
        file_put_contents( REDIS_CONFIG_FILE, $content );
    }
}

Redis_Configurator::init();
