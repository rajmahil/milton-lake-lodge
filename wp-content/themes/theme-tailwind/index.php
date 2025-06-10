<?php get_header(); ?>

<?php
// Get menu items from the menu named "Main Menu"
// This part is kept as it was, assuming it's needed for this template.
$menu = wp_get_nav_menu_object('Main Menu');
$menu_items = []; // Initialize to prevent errors if $menu is false

if ($menu) {
    $menu_items = wp_get_nav_menu_items($menu->term_id);
}
?>

<div class="p-0 static-background bg-brand-light-grey">
  <!-- Content Area -->
  <div class="max-w-full">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php
    // Get the post content
    $content = get_the_content();
    
    // Parse blocks from content
    $blocks = parse_blocks($content);
    
    if (!empty($blocks)) {
        foreach ($blocks as $block) {
            // Skip truly empty blocks or blocks that might cause issues if innerHTML is not present.
            if (empty($block['blockName']) && empty($block['innerHTML'])) {
                continue;
            }
    
            // Render the block to check its HTML content
            $rendered_block = render_block($block);
    
            // Check if the rendered HTML contains the custom wrapper class
            if (strpos($rendered_block, 'plugin-custom-block') !== false) {
                // Custom block: render directly without standard wrappers
                echo $rendered_block;
            } else {
                // Standard Gutenberg block: wrap with standard containers
                // Only render if the block actually produced some output
                if (!empty(trim($rendered_block))) {
                    // Using the wrapper from your single.php example
                    echo '<div class="section-padding !py-0">';
                    echo '  <div class="max-w-container w-full mx-auto">';
                    echo $rendered_block;
                    echo '  </div>';
                    echo '</div>';
                }
            }
        }
    } else {
        // Fallback for content that might not be block-based or if parse_blocks returns empty
        // This ensures that classic editor content or content without blocks still renders.
        // You might want to wrap this in the standard Gutenberg block wrappers too,
        // or handle it differently based on your needs.
        if (!empty(trim($content))) {
            echo '<div class="section-padding !py-0">';
            echo '  <div class="max-w-container w-full mx-auto">';
            echo apply_filters('the_content', $content); // Apply 'the_content' filters for consistency
            echo '  </div>';
            echo '</div>';
        }
    }
    ?>
    <?php endwhile; else : ?>
    <div class="section-padding !py-0">
      <div class="max-w-container w-full mx-auto">
        <p><?php esc_html_e('Sorry, no posts matched your criteria.'); ?></p>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>
