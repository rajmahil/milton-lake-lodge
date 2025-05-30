<?php get_header(); ?>

<?php
// Get menu items from the menu named "Main Menu"
$menu = wp_get_nav_menu_object('Main Menu');
$menu_items = [];

if ($menu) {
    $menu_items = wp_get_nav_menu_items($menu->term_id);
}
?>

<div class="p-0">


  <!-- React component mount point    <div id="render-react-example-here"></div> -->

  <!-- Posts Content -->
  <div class="prose max-w-full">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="">
      <!-- <h3><a href="<?php the_permalink(); ?>" class="text-gray-900 font-semibold"><?php the_title(); ?></a></h3> -->
      <?php the_content(); ?>
    </div>
    <?php endwhile; endif; ?>
  </div>
</div>


<?php get_footer(); ?>
