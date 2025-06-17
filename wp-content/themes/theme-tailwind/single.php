<?php
get_header(); ?>

<div class="prose w-full !max-w-none page-header pb-0 h-full">
  <?php if (have_posts()) {
    while(have_posts()) {
      the_post(); 

      $published_date = get_the_date('F j, Y'); // e.g., "January 15, 2024"
      // $published_time = get_the_time('g:i A'); // e.g., "2:30 PM" - Not used in current output
      // $iso_date = get_the_date('c'); // ISO 8601 format for schema markup - Not used in current output
      
      // Get the post content
      $content = get_the_content();
      
      // Parse blocks from content
      $blocks = parse_blocks($content);
      ?>

  <div
    class="w-full section-padding h-[450px] flex items-center justify-center bg-brand-green bg-repeat  bg-size-[450px] bg-blend-hard-light"
    style="background-image: url('<?php echo esc_url(wp_get_upload_dir()['baseurl'] . '/effects/green-topo.png'); ?>');"
  >
    <div class="max-w-container flex flex-col gap-2 items-center justify-center mt-4">
      <h1 class="text-6xl text-center text-white !mb-0"><?php the_title(); ?></h1>
      <p class="!my-0 text-lg text-white">Published on <?php echo esc_html($published_date); ?></p>
    </div>
  </div>

  <div class="w-full min-h-screen content-wrapper">
    <?php
    if (!empty($blocks)) {
        foreach ($blocks as $block) {
            // It's good practice to check if innerHTML exists, especially for empty or non-rendering blocks.
            // However, render_block should handle most cases.
            if (empty($block['blockName']) && empty($block['innerHTML'])) {
                // Skip truly empty blocks or blocks that might cause issues if innerHTML is not present.
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
                    echo '<div class="section-padding !py-0">';
                    echo '  <div class="max-w-container w-full mx-auto prose">';
                    echo $rendered_block;
                    echo '  </div>';
                    echo '</div>';
                }
            }
        }
    }
    ?>
  </div>
  <?php } // end while
  } // end if ?>
</div>

<?php get_footer(); ?>
