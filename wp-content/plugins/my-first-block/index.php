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
    // register_block_type(__DIR__ . '/build/form-block');
}
add_action('init', 'registerBlocks');
