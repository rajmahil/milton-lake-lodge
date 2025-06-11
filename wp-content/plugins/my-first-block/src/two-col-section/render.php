<?php
/**
 * Two Col Section Block - Render Template
 */

$top_heading = $attributes['topHeading'] ?? 'Top Heading';
$heading = $attributes['heading'] ?? 'Main Heading';
$text = $attributes['text'] ?? 'Text goes here.';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$image1 = $attributes['image'] ?? null;
$image2 = $attributes['image2'] ?? null;
$inverted = $attributes['inverted'] ?? false;

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

<section class="plugin-custom-block not-prose section-padding w-full">
  <div class="relative max-w-container mx-auto w-full grid grid-cols-5 gap-8 items-center">
    <div class="<?php echo esc_attr($content_order_class); ?> flex flex-col gap-4 w-full col-span-2 max-w-[600px] mx-auto">
      <div class='flex flex-col gap-2 w-full'>
        <h2 class="heading-two text-left">
          <?php echo esc_html($heading); ?>
        </h2>
        <p class="!my-0 text-left text-lg"><?php echo esc_html($text); ?></p>
      </div>
      <a href="<?php echo esc_url($button_url); ?>">
        <button class="flex flex-row items-center w-fit gap-1 border-b border-black pb-0.5">
          <p class="text-black text-base">
            <?php echo esc_html($button_text); ?>
          </p>
      </a>
    </div>



    <div class="flex justify-center items-center relative col-span-3">
      <?php if ($image1_id): ?>
      <div class="max-w-[400px] w-full rotate-5 relative left-10 shadow-lg">
        <?php
        echo wp_get_attachment_image($image1_id, 'medium', false, [
            'class' => 'aspect-[3/4] w-full object-cover',
            'loading' => 'lazy',
            'decoding' => 'async',
            'fetchpriority' => 'high',
            'alt' => $image1_alt ?? '',
        ]);
        ?>
      </div>
      <?php endif; ?>
      <?php if ($image2_id): ?>
      <div class="max-w-[400px] w-full rotate-[-10deg] shadow-lg relative right-10">
        <?php
        echo wp_get_attachment_image($image2_id, 'large', false, [
            'class' => 'aspect-[3/4] w-full object-cover',
            'loading' => 'lazy',
            'decoding' => 'async',
            'fetchpriority' => 'auto',
            'alt' => $image2_alt ?? '',
        ]);
        ?>
      </div>
      <?php endif; ?>
    </div>


  </div>
</section>
