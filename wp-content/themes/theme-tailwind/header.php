<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1"
  >
  <?php wp_head(); ?>
</head>

<body
  <?php body_class(); ?>
  <?php
  // Get menu items from the menu named "Main Menu"
  $menu = wp_get_nav_menu_object('Main Menu');
  $menu_items = [];
  
  if ($menu) {
      $menu_items = wp_get_nav_menu_items($menu->term_id);
  }
  
  $cta_text = get_theme_mod('boilerplate_cta_text', 'Get Started');
  $cta_url = get_theme_mod('boilerplate_cta_url', '#');
  
  ?>
>
  <div class="fixed z-[100] w-full section-padding !py-1 bg-black/20 backdrop-blur-md">
    <!-- Header Container -->
    <div class="max-w-container mx-auto flex items-center justify-between">

      <!-- Site Logo -->
      <div class="site-logo">
        <?php if ( has_custom_logo() ) : ?>
        <?php
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
        ?>
        <a href="<?php echo esc_url(home_url('/')); ?>">
          <img
            src="<?php echo esc_url($logo[0]); ?>"
            alt="<?php bloginfo('name'); ?> Logo"
            class="h-16 w-auto"
          >
        </a>
        <?php else : ?>
        <a
          href="<?php echo esc_url(home_url('/')); ?>"
          class="text-white text-xl font-bold"
        >
          <?php bloginfo('name'); ?>
        </a>
        <?php endif; ?>
      </div>

      <!-- Menu Items Display -->
      <?php if (!empty($menu_items)) : ?>
      <ul class="list-none flex flex-row gap-4">
        <?php foreach ($menu_items as $item) : ?>
        <li>
          <a
            href="<?php echo esc_url($item->url); ?>"
            class="text-white text-lg"
          >
            <?php echo esc_html($item->title); ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else : ?>
      <p class="text-white">No menu items found.</p>
      <?php endif; ?>


      <div>
        <a
          href="<?php echo esc_url($cta_url); ?>"
          class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded"
        >
          <?php echo esc_html($cta_text); ?>
        </a>
      </div>

    </div>
  </div>

  <?php wp_footer(); ?>
</body>

</html>
