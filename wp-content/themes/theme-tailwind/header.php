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
  ?>
>
  <div class="bg-blue-400">

    <!-- Menu Items Display -->
    <div
      class="prose "
      data-aos="fade-up"
      data-aos-delay="200"
    >
      <?php if (!empty($menu_items)) : ?>
      <ul class="list-none flex flex-row gap-4">
        <?php foreach ($menu_items as $item) : ?>

        <li>
          <a
            href="<?php echo esc_url($item->url); ?>"
            class="text-blue-800 font-semibold"
          >
            <?php echo esc_html($item->title); ?>
          </a>



        </li>

        <?php endforeach; ?>

      </ul>
      <?php else : ?>
      <p>No menu items found. </p>
      <?php endif; ?>
    </div>

  </div>
