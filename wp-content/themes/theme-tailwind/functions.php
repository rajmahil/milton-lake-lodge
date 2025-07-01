<?php

function boilerplate_load_assets()
{
    wp_enqueue_script('ourmainjs', get_theme_file_uri('/build/index.js'), ['wp-element', 'react-jsx-runtime'], '1.0', true);
    wp_enqueue_style('ourmaincss', get_theme_file_uri('/build/index.css'));
    wp_enqueue_script(
        'alpinejs-plugin-collapse',
        'https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js',
        [], // no dependencies, loads first
        null,
        true, // load in footer
    );

    // Enqueue Alpine core and make it dependent on the collapse plugin
    wp_enqueue_script(
        'alpinejs',
        'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
        ['alpinejs-plugin-collapse'], // depends on collapse plugin
        null,
        true,
    );

    add_filter(
        'script_loader_tag',
        function ($tag, $handle, $src) {
            if ('alpinejs' === $handle || 'alpinejs-plugin-collapse' === $handle) {
                return '<script
                  src="' .
                                    esc_url($src) .
                                    '"
                  defer
                ></script>';
            }
            return $tag;
        },
        10,
        3,
    );
}

add_action('wp_enqueue_scripts', 'boilerplate_load_assets');

function boilerplate_theme_setup()
{
    add_theme_support('menus');

    register_nav_menus([
        'main_menu' => __('Main Menu', 'boilerplate_theme'),
    ]);
}
add_action('after_setup_theme', 'boilerplate_theme_setup');

function boilerplate_add_support()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height' => 100, // optional - you can change these
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
    ]);
}

add_action('after_setup_theme', 'boilerplate_add_support');

function boilerplate_customize_register($wp_customize)
{
    // Section
    $wp_customize->add_section('boilerplate_cta_section', [
        'title' => __('Main Call To Action', 'boilerplate'),
        'priority' => 30,
    ]);

    // CTA Text
    $wp_customize->add_setting('boilerplate_cta_text', [
        'default' => 'Get Started',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_cta_text_control', [
        'label' => __('CTA Button Text', 'boilerplate'),
        'section' => 'boilerplate_cta_section',
        'settings' => 'boilerplate_cta_text',
        'type' => 'text',
    ]);

    // CTA URL
    $wp_customize->add_setting('boilerplate_cta_url', [
        'default' => '#',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_cta_url_control', [
        'label' => __('CTA Button URL', 'boilerplate'),
        'section' => 'boilerplate_cta_section',
        'settings' => 'boilerplate_cta_url',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('boilerplate_cta_phone', [
        'default' => '',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_cta_phone_control', [
        'label' => __('CTA Phone Number', 'boilerplate'),
        'section' => 'boilerplate_cta_section',
        'settings' => 'boilerplate_cta_phone',
        'type' => 'text', // You can also use 'tel', but Customizer doesn't validate 'tel'
    ]);

    // Section for Footer
    $wp_customize->add_section('boilerplate_footer_section', [
        'title' => __('Footer Settings', 'boilerplate'),
        'priority' => 40,
    ]);

    // Footer Logo setting - FIXED
    $wp_customize->add_setting('boilerplate_footer_logo', [
        'sanitize_callback' => 'absint', // Changed from 'esc_url_raw' to 'absint'
        'capability' => 'edit_theme_options',
        'default' => 0, // Changed from '' to 0
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Cropped_Image_Control($wp_customize, 'boilerplate_footer_logo_control', [
            'label' => __('Footer Logo', 'boilerplate'),
            'section' => 'boilerplate_footer_section',
            'settings' => 'boilerplate_footer_logo',
            'flex_width' => true,
            'flex_height' => true,
            'width' => 400,
            'height' => 150,
        ]),
    );

    $wp_customize->add_setting('boilerplate_compass_image', [
        'sanitize_callback' => 'absint',
        'capability' => 'edit_theme_options',
        'default' => 0,
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Cropped_Image_Control($wp_customize, 'boilerplate_compass_image_control', [
            'label' => __('Compass Image', 'boilerplate'),
            'description' => __('Upload image for compass icon', 'boilerplate'),
            'section' => 'boilerplate_footer_section',
            'settings' => 'boilerplate_compass_image',
            'flex_width' => true,
            'flex_height' => true,
            'width' => 200,
            'height' => 200,
        ]),
    );

    //Footer description
    $wp_customize->add_setting('boilerplate_footer_description', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post', // allows basic HTML if needed
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_footer_description_control', [
        'label' => __('Footer Description', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_footer_description',
        'type' => 'textarea',
    ]);

    //Footer social media links
    // Facebook
    $wp_customize->add_setting('boilerplate_facebook_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_facebook_url_control', [
        'label' => __('Facebook URL', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_facebook_url',
        'type' => 'url',
    ]);

    // Twitter
    $wp_customize->add_setting('boilerplate_twitter_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_twitter_url_control', [
        'label' => __('Twitter URL', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_twitter_url',
        'type' => 'url',
    ]);

    // Instagram
    $wp_customize->add_setting('boilerplate_instagram_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_instagram_url_control', [
        'label' => __('Instagram URL', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_instagram_url',
        'type' => 'url',
    ]);

    // Youtube
    $wp_customize->add_setting('boilerplate_youtube_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_youtube_url_control', [
        'label' => __('Youtube URL', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_youtube_url',
        'type' => 'url',
    ]);

    // Linkedin
    $wp_customize->add_setting('boilerplate_linkedin_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_linkedin_url_control', [
        'label' => __('Linkedin URL', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_linkedin_url',
        'type' => 'url',
    ]);

    // Trip advisor
    $wp_customize->add_setting('boilerplate_tripadvisor_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_tripadvisor_url_control', [
        'label' => __('Trip Advisor URL', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_tripadvisor_url',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('boilerplate_terms_conditions_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_terms_conditions_url', [
        'label' => __('Terms & Conditions URL', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_terms_conditions_url',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('boilerplate_privacy_policy_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('boilerplate_privacy_policy_url', [
        'label' => __('Privacy Policy URL', 'boilerplate'),
        'section' => 'boilerplate_footer_section',
        'settings' => 'boilerplate_privacy_policy_url',
        'type' => 'url',
    ]);

    // $wp_customize->add_setting('boilerplate_footer_bg_color', [
    //     'default' => '#ffffff',
    //     'sanitize_callback' => 'sanitize_hex_color',
    //     'capability' => 'edit_theme_options',
    //     'transport' => 'refresh',
    // ]);

    // $wp_customize->add_control(
    //     new WP_Customize_Color_Control($wp_customize, 'boilerplate_footer_bg_color_control', [
    //         'label' => __('Footer Background Color', 'boilerplate'),
    //         'section' => 'boilerplate_footer_section',
    //         'settings' => 'boilerplate_footer_bg_color',
    //     ]),
    // );

    // $wp_customize->add_setting('boilerplate_footer_bg_image', [
    //     'sanitize_callback' => 'absint', // store the image ID (like your logo)
    //     'capability' => 'edit_theme_options',
    //     'default' => 0,
    //     'transport' => 'refresh',
    // ]);

    // $wp_customize->add_control(
    //     new WP_Customize_Media_Control($wp_customize, 'boilerplate_footer_bg_image_control', [
    //         'label' => __('Footer Background Image', 'boilerplate'),
    //         'section' => 'boilerplate_footer_section',
    //         'settings' => 'boilerplate_footer_bg_image',
    //         'mime_type' => 'image', // only allow images
    //     ]),
    // );
}

add_action('customize_register', 'boilerplate_customize_register');

// Helper function to get footer logo URL
function boilerplate_get_footer_logo_url()
{
    $logo_id = get_theme_mod('boilerplate_footer_logo', 0);
    if ($logo_id) {
        return wp_get_attachment_image_url($logo_id, 'full');
    }
    return false;
}

// Helper function to display footer logo
function boilerplate_display_footer_logo()
{
    $logo_id = get_theme_mod('boilerplate_footer_logo', 0);
    if ($logo_id) {
        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        $logo_alt = get_post_meta($logo_id, '_wp_attachment_image_alt', true);
        echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr($logo_alt) . '" class="footer-logo">';
    }
}

function boilerplate_get_compass_image_url()
{
    $image_id = get_theme_mod('boilerplate_compass_image', 0);
    return $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
}

function boilerplate_display_compass_image($class = 'compass-icon')
{
    if ($url = boilerplate_get_compass_image_url()) {
        $alt = get_post_meta(get_theme_mod('boilerplate_compass_image'), '_wp_attachment_image_alt', true) ?: 'Compass Icon';
        printf('<img src="%s" alt="%s" class="%s">', esc_url($url), esc_attr($alt), esc_attr($class));
    }
}

function boilerplate_preload_fonts()
{
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri()) . '/assets/fonts/Bebas-Bold.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri()) . '/assets/fonts/OverusedGrotesk-Medium.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri()) . '/assets/fonts/OverusedGrotesk-Roman.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri()) . '/assets/fonts/OverusedGrotesk-SemiBold.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
}
add_action('wp_head', 'boilerplate_preload_fonts', 5);

add_filter('acf/load_field/name=trip_type', function ($field) {
    global $post;

    if (!$post) {
        return $field; // fallback for safety
    }

    // Get the slot_types repeater from this post:
    $slot_types = get_field('slot_types', $post->ID);

    $field['choices'] = [];

    if (!empty($slot_types)) {
        foreach ($slot_types as $item) {
            $label = $item['label_'] ?? '';

            if ($label !== '') {
                // Use label as both value and label
                $field['choices'][$label] = $label;
            }
        }
    }
    return $field;
});

function add_custom_admin_box_to_submissions()
{
    add_meta_box(
        'submission_admin_box', // Unique ID
        'Submission Overview', // Box title
        'render_submission_admin_box', // Content callback
        'submissions', // Your CPT slug
        'normal', // Context (normal, side, advanced)
        'default', // Priority
    );
}
add_action('add_meta_boxes', 'add_custom_admin_box_to_submissions');

function render_submission_admin_box($post)
{
    echo '<div style="padding: 10px;">';

    $form_fields = get_field('form_fields', $post->ID);

    if (!empty($form_fields)) {
        echo '<ul style="margin-left: 0px;">';
        foreach ($form_fields as $field) {
            $label = isset($field['label']) ? esc_html(str_replace('-', ' ', $field['label'])) : 'Unnamed Field';
            $value = isset($field['value']) ? (is_array($field['value']) ? implode(', ', array_map('esc_html', $field['value'])) : esc_html($field['value'])) : '';

            echo '<li style="border-bottom: 1px solid #dbdbdb; padding: 8px 0;"><strong>' . $label . ':</strong> ' . $value . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p><em>No form fields submitted.</em></p>';
    }

    echo '</div>';
}

add_theme_support('responsive-embeds');

function add_theme_color_meta_tag()
{
    echo '<meta name="theme-color" content="#00251e">' . "\n";
}
add_action('wp_head', 'add_theme_color_meta_tag');

function add_friendlycaptcha_script()
{
    echo '<script
      src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.1.23/site.min.js"
      async
      defer
    ></script>';
}
add_action('wp_head', 'add_friendlycaptcha_script');
