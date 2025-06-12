<?php
/*
  Plugin Name: Brad’s Boilerplate Block Plugin
  Version: 1.0
  Author: Brad
  Author URI: https://github.com/LearnWebCode
*/

if (!defined('ABSPATH')) exit();

// Register all blocks
function register_custom_blocks() {
    $blocks = [
        'hero-section', 'showcase-section', 'features-section', 'accordion-section', 'reviews-section',
        'scroll-image-section', 'cta-section', 'two-col-section', 'pricing-table', 'form-block',
        'page-header-section', 'gallery-section', 'carousel-section', 'calendar-section'
    ];

    foreach ($blocks as $block) {
        register_block_type(__DIR__ . "/build/$block");
    }
}
add_action('init', 'register_custom_blocks');

// Pass season_calendar posts to the frontend
add_action('wp_enqueue_scripts', function () {
    $posts = get_posts([
        'post_type' => 'season_calendar',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    $formatted = array_map(function ($post) {
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

    wp_enqueue_script(
        'my-calendar-frontend',
        plugins_url('src/calendar-section/frontend.js', __FILE__),
        [],
        null,
        true
    );

    wp_localize_script('my-calendar-frontend', 'myCalendarData', ['posts' => $formatted]);
});

// Handle custom form email
add_action('admin_post_nopriv_my_custom_form_submit', 'handle_custom_form_email');
add_action('admin_post_my_custom_form_submit', 'handle_custom_form_email');

function handle_custom_form_email() {
    error_log('Form handler triggered');

    $skip = ['action', '_wpnonce', '_wp_http_referer'];
    $data = [];

    foreach ($_POST as $key => $value) {
        if (in_array($key, $skip)) continue;

        if (is_array($value)) {
            $data[$key] = array_map('sanitize_text_field', $value);
        } elseif (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $data[$key] = sanitize_email($value);
        } elseif (preg_match('/message|comments?|notes?/i', $key)) {
            $data[$key] = sanitize_textarea_field($value);
        } else {
            $data[$key] = sanitize_text_field($value);
        }
    }

    $rows = '';
    foreach ($data as $key => $value) {
        $label = ucwords(str_replace('_', ' ', $key));
        $val = is_array($value) ? implode(', ', $value) : nl2br(esc_html($value));

        $rows .= "<div style=\"display: flex; margin-bottom: 8px; column-gap: 12px;\">
        <div style=\"font-weight: bold; color: #374151; white-space: nowrap;\">$label:</div>
        <div style=\"color: #111827;\">$val</div>
    </div>";
    }
  

    $template_path = plugin_dir_path(__FILE__) . 'components/email-templates/email-template.php';
    $template_html = file_get_contents($template_path);
    $template_html = str_replace('{{dynamic_rows}}', $rows, $template_html);

    

    
    wp_mail(
      'ayush@306technologies.com',
      'New Form Submission',
      $template_html,
      ['Content-Type: text/html; charset=UTF-8']
    );
    

    error_log('Form submitted: ' . print_r($data, true));
    wp_redirect(home_url('/thank-you'));
    exit();
}
