<?php
/*
  Plugin Name: Brad’s Boilerplate Block Plugin
  Version: 1.0
  Author: Brad
  Author URI: https://github.com/LearnWebCode
*/

if (!defined('ABSPATH')) {
    exit();
}

// Register all blocks
function register_custom_blocks()
{
    $blocks = ['news-section', 'icon-grid-section', 'hero-section', 'showcase-section', 'features-section', 'accordion-section', 'reviews-section', 'scroll-image-section', 'cta-section', 'two-col-section', 'pricing-table', 'form-block', 'page-header-section', 'gallery-section', 'carousel-section', 'calendar-section'];

    foreach ($blocks as $block) {
        register_block_type(__DIR__ . "/build/$block");
    }
}
add_action('init', 'register_custom_blocks');

// Pass season_calendar posts to the frontend
function get_my_calendar_data()
{
    $posts = get_posts([
        'post_type' => 'season_calendar',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    return array_map(function ($post) {
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
}

// Enqueue frontend script and localize data
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('my-calendar-frontend', plugins_url('src/calendar-section/frontend.js', __FILE__), [], null, true);

    wp_localize_script('my-calendar-frontend', 'myCalendarData', [
        'posts' => get_my_calendar_data(),
    ]);
});

// Enqueue editor script and localize data
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script('my-calendar-editor', plugins_url('src/calendar-section/edit.js', __FILE__), ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components'], null, true);

    wp_localize_script('my-calendar-editor', 'myCalendarData', [
        'posts' => get_my_calendar_data(),
    ]);
});
// Handle custom form email
add_action('admin_post_nopriv_my_custom_form_submit', 'handle_custom_form_email');
add_action('admin_post_my_custom_form_submit', 'handle_custom_form_email');

function get_exchange_rates($base = 'USD')
{
    $transient_key = 'exchange_rates_' . strtolower($base);
    $cached_rates = get_transient($transient_key);

    if ($cached_rates !== false) {
        return $cached_rates;
    }

    $api_key = defined('EXCHANGE_RATE_API_KEY') ? EXCHANGE_RATE_API_KEY : '';

    if (!$api_key) {
        error_log('ExchangeRate API key is missing.');
        return false;
    }

    $url = "https://v6.exchangerate-api.com/v6/{$api_key}/latest/{$base}";
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        error_log('Exchange Rate API request failed: ' . $response->get_error_message());
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['conversion_rates'])) {
        // Cache the result for 6 hours (21600 seconds)
        set_transient($transient_key, $data['conversion_rates'], 6 * HOUR_IN_SECONDS);
        return $data['conversion_rates'];
    }

    error_log('Invalid exchange rate data: ' . $body);
    return false;
}

function handle_custom_form_email()
{
    error_log('Form handler triggered');

    $skip = ['action', '_wpnonce', '_wp_http_referer', 'form_template', 'formTitle'];
    $data = [];
    $form_template = isset($_POST['form_template']) ? sanitize_text_field($_POST['form_template']) : 'default';
    $form_title = isset($_POST['formTitle']) ? sanitize_text_field($_POST['formTitle']) : 'Form Submission';

    foreach ($_POST as $key => $value) {
        if (in_array($key, $skip)) {
            continue;
        }

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

    // Insert a new Submission post
    $name = $data['your-name'] ?? $data['your-email'];
    $post_title = "{$name} | {$form_title}";
    $post_id = wp_insert_post([
        'post_title' => sanitize_text_field($post_title),
        'post_type' => 'submissions',
        'post_status' => 'private',
    ]);

    $content_blocks = '';

    foreach ($data as $key => $value) {
        $label = ucwords(str_replace('_', ' ', $key));
        $val = is_array($value) ? implode(', ', $value) : esc_html($value);

        $content_blocks .= "<p><strong>{$label}:</strong> {$val}</p>\n";
    }

    $repeater_rows = [];

    foreach ($data as $key => $value) {
        $label = ucwords(str_replace('_', ' ', $key));
        $val = is_array($value) ? implode(', ', $value) : $value;

        $repeater_rows[] = [
            'label' => $label,
            'value' => $val,
        ];
    }

    update_field('form_fields', $repeater_rows, $post_id);

    // Now update post content
    wp_update_post([
        'ID' => $post_id,
        'post_content' => $content_blocks,
    ]);

    // Build HTML rows for email
    $rows = '';
    foreach ($data as $key => $value) {
        $label = ucwords(str_replace('_', ' ', $key));
        $val = is_array($value) ? implode(', ', $value) : nl2br(esc_html($value));

        $rows .= "<div style=\"display: flex; margin-bottom: 8px; column-gap: 12px;\">
            <div style=\"font-weight: bold; color: #374151; white-space: nowrap;\">$label:</div>
            <div style=\"color: #111827;\">$val</div>
        </div>";
    }

    // Determine template file path based on form_template value
    $base_path = plugin_dir_path(__FILE__) . 'components/email-templates/';
    $template_file = $base_path . $form_template . '-template.php';

    if (!file_exists($template_file)) {
        $template_file = $base_path . 'newsletter-template.php';
    }

    // Load template content
    $template_html = file_get_contents($template_file);

    // Replace placeholders
    if ($form_template === 'main_form') {
        $template_html = str_replace('{{ dynamic_rows }}', $rows, $template_html);
    }

    $user_email = isset($data['your-email']) && is_email($data['your-email']) ? $data['your-email'] : null;

    error_log('User email: ' . print_r($user_email, true));

    $recipients = [get_option('admin_email')];
    if ($user_email) {
        $recipients[] = $user_email;
    }

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if ($user_email) {
        $headers[] = 'Reply-To: ' . $user_email;
    }

    if ($form_template === 'main_form') {
        $subject = 'New Form Submission';
    } else {
        $subject = 'New Newsletter Subscription';
    }

    error_log("Form Template: $form_template\nEmail HTML Content:\n" . $template_html);

    wp_mail($recipients, $subject, $template_html, $headers);

    error_log('Form submitted: ' . print_r($data, true));
    wp_redirect(home_url('/thank-you'));
    exit();
}
