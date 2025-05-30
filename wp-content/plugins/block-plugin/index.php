<?php
/**
 * Plugin Name:       My First Block
 * Description:       Multi-block plugin with Tailwind CSS v4
 * Version:           0.1.0
 * Text Domain:       my-first-block
 */

if (!defined('ABSPATH')) {
    exit();
}

function my_first_block_init()
{
    // Register the block using the build directory
    register_block_type(__DIR__ . '/build/my-first-block');
}
add_action('init', 'my_first_block_init');
