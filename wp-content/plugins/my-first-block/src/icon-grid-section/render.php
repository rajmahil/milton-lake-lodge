<?php
/**
 * Accordion Block - Alpine.js Version with Smooth Animations
 */
$heading = $attributes['heading'] ?? '';
$button_text = $attributes['buttonText'] ?? '';
$button_url = $attributes['buttonUrl'] ?? '';
$items = $attributes['items'] ?? [];
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';
?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block not-prose section-padding w-full "
>
  <div class="max-w-container mx-auto flex flex-col gap-16">

    <div>
      <?php if ( $heading ): ?>
      <h2 class="heading-two text-center ">
        <?php echo esc_html($heading); ?>
      </h2>
      <?php endif; ?>
    </div>
    <div class="relative w-full grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4  xl:grid-cols-5 gap-6">
      <?php foreach ( $items as $index => $item ): ?>
      <div
        class=" col-span-1 "
        key="<?php echo esc_attr($index); ?>"
      >

        <div class="flex flex-col items-center justify-center gap-2">
          <?php if ( !empty( $item['image'] ) ): ?>
          <?php echo wp_get_attachment_image($item['image']['id'], 'medium', false, [
              'alt' => esc_attr($item['image']['alt'] ?? ''),
              'class' => 'w-40 h-40 object-contain object-center',
          ]); ?>
          <?php endif; ?>
          <?php if ( !empty( $item['title'] ) ): ?>
          <p class="text-center text-lg md:text-xl"><?php echo esc_html($item['title']); ?></span>
            <?php endif; ?>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($button_url && $button_text): ?>

    <a href="<?php echo esc_url($button_url); ?>">
      <button class="flex flex-row items-center w-fit gap-1 cursor-pointer text-lg group relative pb-0.5 mx-auto">
        <div class="flex items-center border-b border-black gap-1 pb-0.5">
          <span><?php echo esc_html($button_text); ?></span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="18"
            height="18"
            fill="#000000"
            viewBox="0 0 256 256"
            class="group-hover:translate-x-1 transition-transform duration-300 ease-in-out"
          >
            <path
              d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"
            ></path>
          </svg>
        </div>
      </button>
    </a>
    <?php endif; ?>

  </div>
</section>
