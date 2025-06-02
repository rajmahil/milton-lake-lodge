<?php

function boilerplate_load_assets()
{
    wp_enqueue_script('ourmainjs', get_theme_file_uri('/build/index.js'), ['wp-element', 'react-jsx-runtime'], '1.0', true);
    wp_enqueue_style('ourmaincss', get_theme_file_uri('/build/index.css'));
    wp_enqueue_script(
        'alpinejs',
        'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
        [], // dependencies (none)
        null, // version (or use '3.x.x' if you want)
        true, // load in footer (true) or head (false)
    );
}

add_action('wp_enqueue_scripts', 'boilerplate_load_assets');

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
        'title' => __('Main Button', 'boilerplate'),
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
