<?php

function boilerplate_load_assets()
{
    wp_enqueue_script('ourmainjs', get_theme_file_uri('/build/index.js'), ['wp-element', 'react-jsx-runtime'], '1.0', true);
    wp_enqueue_style('ourmaincss', get_theme_file_uri('/build/index.css'));
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
}
add_action('customize_register', 'boilerplate_customize_register');
