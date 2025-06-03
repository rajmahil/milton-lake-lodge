<?php

/*
  Plugin Name: Brad&rsquo;s Boilerplate Block Plugin
  Version: 1.0
  Author: Brad
  Author URI: https://github.com/LearnWebCode
*/

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

function registerBlocks()
{
    register_block_type(__DIR__ . '/build/hero-section');
    register_block_type(__DIR__ . '/build/form-block');
}
add_action('init', 'registerBlocks');

function boilerplate_editor_styles()
{
    // Enqueue your main theme Tailwind CSS (or a separate editor-specific CSS if needed)
    wp_enqueue_style('boilerplate-editor-css', get_template_directory_uri() . '/build/style-index.css', [], '1.0');
}
add_action('enqueue_block_editor_assets', 'boilerplate_editor_styles');
