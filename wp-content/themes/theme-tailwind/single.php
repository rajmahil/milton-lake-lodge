<?php
get_header(); ?>

<div class="prose w-full !max-w-none page-header pb-0 h-full">
  <?php if (have_posts()) {
    while(have_posts()) {
      the_post(); 

      $published_date = get_the_date('F j, Y'); // e.g., "January 15, 2024"
      $published_time = get_the_time('g:i A'); // e.g., "2:30 PM"
      $iso_date = get_the_date('c'); // ISO 8601 format for schema markup
      
      // Get the post content
      $content = get_the_content();
      
      // Parse blocks from content
      $blocks = parse_blocks($content);
      
      // Separate blocks by checking rendered HTML for the custom class
      $gutenberg_blocks = [];
      $custom_blocks = [];
      
      foreach ($blocks as $block) {
        if (empty($block['blockName'])) {
          // Handle classic content or empty blocks
          continue;
        }
        
        // Render the block to check its HTML content
        $rendered_block = render_block($block);
        
        // Check if the rendered HTML contains the custom wrapper class
        if (strpos($rendered_block, 'plugin-custom-block') !== false) {
          $custom_blocks[] = [
            'block' => $block,
            'rendered' => $rendered_block
          ];
        } else {
          // Standard Gutenberg blocks
          $gutenberg_blocks[] = [
            'block' => $block,
            'rendered' => $rendered_block
          ];
        }
      }
      ?>


  <div
    class="w-full section-padding h-[450px]  flex items-center justify-center bg-gradient-to-br from-brand-green-dark to-brand-green"
  >
    <div class="max-w-container flex flex-col gap-2 items-center justify-center mt-4">
      <h1 class="text-6xl text-center text-white !mb-0"><?php the_title(); ?></h1>
      <p class="!my-0 text-lg text-white">Published on <?php echo esc_html($published_date); ?></p>
    </div>
  </div>

  <div class="w-full min-h-screen content-wrapper">
    <!-- Render Standard Gutenberg Blocks -->
    <?php if (!empty($gutenberg_blocks)) : ?>
    <div class="section-padding">
      <div class="max-w-container w-full mx-auto">
        <?php
        foreach ($gutenberg_blocks as $block_data) {
            echo $block_data['rendered'];
        }
        ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Render Custom Blocks (with my-unique-plugin-wrapper-class) -->
    <?php if (!empty($custom_blocks)) : ?>

    <?php
    foreach ($custom_blocks as $block_data) {
        echo $block_data['rendered'];
        echo '</div>';
    }
    ?>

    <?php endif; ?>

  </div>
  <?php }
  } ?>
</div>

<?php get_footer(); ?>
