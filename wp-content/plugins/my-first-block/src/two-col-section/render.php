<?php
/**
 * Two Col Section Block - Render Template
 */

$heading = $attributes['heading'] ?? 'Main Heading';
$text = $attributes['text'] ?? 'Text goes here.';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$image1 = $attributes['image'] ?? null;
$image2 = $attributes['image2'] ?? null;
$inverted = $attributes['inverted'] ?? false;
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';

// Image 1
$image1_id = null;
$image1_alt = '';

if ($image1) {
    if (is_array($image1)) {
        $image1_id = $image1['id'] ?? ($image1['ID'] ?? null);
        $image1_alt = $image1['alt'] ?? '';
    } elseif (is_numeric($image1)) {
        $image1_id = $image1;
        $image1_alt = get_post_meta($image1, '_wp_attachment_image_alt', true);
    }
}

// Image 2
$image2_id = null;
$image2_alt = '';

if ($image2) {
    if (is_array($image2)) {
        $image2_id = $image2['id'] ?? ($image2['ID'] ?? null);
        $image2_alt = $image2['alt'] ?? '';
    } elseif (is_numeric($image2)) {
        $image2_id = $image2;
        $image2_alt = get_post_meta($image2, '_wp_attachment_image_alt', true);
    }
}

$content_order_class = $inverted ? 'order-first' : 'order-last';
?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block not-prose section-padding w-full"
>
  <div
    class="relative max-w-container mx-auto w-full flex flex-col gap-14 sm:gap-20 900:!grid 900:!grid-cols-5 900:!gap-8 itemts-start 900:items-center"
  >
    <div class="<?php echo esc_attr($content_order_class); ?> flex flex-col gap-4 w-full 900:col-span-2 900:max-w-[800px] 900:mx-auto">
      <div class='flex flex-col gap-2 w-full'>
        <h2 class="heading-two text-left">
          <?php echo esc_html($heading); ?>
        </h2>
        <p class="!my-0 text-left text-lg"><?php echo esc_html($text); ?></p>
      </div>

      <?php if ($button_text && $button_url): ?>
      <a href="<?php echo esc_url($button_url); ?>">
        <button class="flex flex-row items-center w-fit gap-1 cursor-pointer text-lg group relative pb-0.5">
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

    <div class="flex justify-center items-center relative col-span-3">
      <?php if ($image1_id): ?>
      <div class="shadow-lg rounded-lg overflow-hidden w-full max-w-[800px] <?php echo $inverted ? 'ml-auto' : 'mr-auto'; ?>">
        <?php
        echo wp_get_attachment_image($image1_id, 'medium', false, [
            'class' => 'aspect-[3/2] w-full object-cover',
            'loading' => 'lazy',
            'decoding' => 'async',
            'fetchpriority' => 'high',
            'alt' => $image1_alt ?? '',
        ]);
        ?>
      </div>
      <?php endif; ?>
    </div>


  </div>
</section>
