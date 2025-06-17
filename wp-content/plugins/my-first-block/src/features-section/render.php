<?php
/**
 * Features Section Block - Server-side render
 */

$heading = $attributes['heading'] ?? '';
$features = $attributes['features'] ?? [];
?>

<section class="plugin-custom-block not-prose section-padding w-full">
  <div class="max-w-container mx-auto flex flex-col gap-16">

    <?php if ($heading): ?>
    <h2 class="heading-two text-left lg:max-w-[60%]">
      <?php echo esc_html($heading); ?>
    </h2>
    <?php endif; ?>

    <div class="flex flex-col w-full gap-16 lg:gap-24">
      <?php foreach ($features as $idx => $feature):
        $image = $feature['image'] ?? [];
        $is_even = $idx % 2 === 0;
        $image_url = $image['sizes']['large']['url'] ?? ($image['url'] ?? '');
        $image_alt = $image['alt'] ?? '';
        $image_id = $image['id'] ?? 0;

      ?>
      <div class="flex flex-col lg:flex-row items-start lg:items-center gap-8 md:gap-16 <?php echo $is_even ? 'lg:flex-row' : 'lg:flex-row-reverse'; ?>">
      
        <div class="relative w-full ml-2 max-w-[90%] md:max-w-[70%] lg:max-w-[45%]">
          <?php 
            if ($image_id) {
              echo wp_get_attachment_image($image_id, 'large', false, [
                'class' => 'w-full rounded-lg h-auto object-cover aspect-[4/3] relative z-[2]',
                'alt' => esc_attr($image_alt),
              ]);
            } else {
              $image_url = $image['sizes']['large']['url'] ?? ($image['url'] ?? '');
              if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="w-full rounded-lg h-auto object-cover aspect-[4/3] relative z-[2]" />';
              }
            }
          ?>
      </div>

        <div class="w-full lg:w-1/2">
          <h3 class="text-2xl font-semibold my-4">
            <?php echo esc_html($feature['heading'] ?? ''); ?>
          </h3>
          <p class="text-base leading-relaxed">
            <?php echo esc_html($feature['text'] ?? ''); ?>
          </p>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

