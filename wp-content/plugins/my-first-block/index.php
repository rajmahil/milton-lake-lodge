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
    register_block_type(__DIR__ . '/build/showcase-section');
    register_block_type(__DIR__ . '/build/features-section');
    register_block_type(__DIR__ . '/build/accordion-section');
    register_block_type(__DIR__ . '/build/reviews-section');
    register_block_type(__DIR__ . '/build/scroll-image-section');
    register_block_type(__DIR__ . '/build/cta-section');
    register_block_type(__DIR__ . '/build/two-col-section');
    register_block_type(__DIR__ . '/build/pricing-table');
    register_block_type(__DIR__ . '/build/form-block');
    register_block_type(__DIR__ . '/build/page-header-section');
    register_block_type(__DIR__ . '/build/gallery-section');
    register_block_type(__DIR__ . '/build/calendar-section');
}
add_action('init', 'registerBlocks');

add_action('wp_enqueue_scripts', function () {
    $posts = get_posts([
        'post_type' => 'season_calendar',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    $formatted_posts = array_map(function ($post) {
        return [
            'id' => $post->ID,
            'title' => get_the_title($post),
            'default_month_view' => get_field('default_month_view', $post->ID),
            'additional_notes' => get_field('additional_notes', $post->ID),
            'calendar_legend' => get_field('calendar_legend', $post->ID),
            'slot_types' => get_field('slot_types', $post->ID),
            'trips' => get_field('trips', $post->ID),
        ];
    }, $posts);

    wp_enqueue_script('my-calendar-frontend', plugins_url('src/calendar-section/frontend.js', __FILE__), [], null, true);

    wp_localize_script('my-calendar-frontend', 'myCalendarData', [
        'posts' => $formatted_posts,
    ]);
});
