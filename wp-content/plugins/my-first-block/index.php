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
    register_block_type(__DIR__ . '/build/carousel-section');
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

add_action('admin_post_nopriv_my_custom_form_submit', 'handle_custom_form_email');
add_action('admin_post_my_custom_form_submit', 'handle_custom_form_email');

function handle_custom_form_email()
{
    error_log('Handler fired');

    // Skip internal keys like action, _wpnonce, etc.
    $skip_keys = ['action', '_wpnonce', '_wp_http_referer'];

    $sanitized_data = [];

    foreach ($_POST as $key => $value) {
        if (in_array($key, $skip_keys)) {
            continue;
        }

        // Basic sanitization
        if (is_array($value)) {
            $sanitized_value = array_map('sanitize_text_field', $value);
        } elseif (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $sanitized_value = sanitize_email($value);
        } elseif (preg_match('/message|comments?|notes?/i', $key)) {
            $sanitized_value = sanitize_textarea_field($value);
        } else {
            $sanitized_value = sanitize_text_field($value);
        }

        $sanitized_data[$key] = $sanitized_value;
    }

    // Build HTML body
    $body = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>';
    $body .= '<h2 style="color:#1f2937;">New Form Submission</h2>';
    $body .= '<table cellspacing="0" cellpadding="8" style="border-collapse:collapse;width:100%;max-width:600px;font-family:sans-serif;">';

    foreach ($sanitized_data as $key => $value) {
        $display_key = ucwords(str_replace('_', ' ', $key));
        $display_value = is_array($value) ? implode(', ', $value) : nl2br(esc_html($value));

        $body .= "<tr>
                    <td style='background:#f9f9f9;border:1px solid #e0e0e0;font-weight:bold;width:35%;'>$display_key</td>
                    <td style='border:1px solid #e0e0e0;'>$display_value</td>
                  </tr>";
    }

    $body .= '</table>';
    $body .= '</body></html>';

    // Email config
    $to = 'raj@306technologies.com';
    $subject = 'New Form Submission';
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    error_log('Sanitized submission: ' . print_r($sanitized_data, true));

    wp_mail($to, $subject, $body, $headers);

    wp_redirect(home_url('/thank-you'));
    exit();
}
