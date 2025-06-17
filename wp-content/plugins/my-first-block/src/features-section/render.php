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
        $is_even = $idx % 2 === 0;
        $image = $feature['image'] ?? [];
        $image_url = $image['sizes']['large']['url'] ?? ($image['url'] ?? '');
        $image_alt = $image['alt'] ?? '';
      ?>
      <div class="flex flex-col lg:flex-row items-start lg:items-center gap-8 md:gap-16 <?php echo $is_even ? 'lg:flex-row' : 'lg:flex-row-reverse'; ?>">

        <!-- Image -->
        <div class="relative w-full ml-2 max-w-[90%] md:max-w-[70%] lg:max-w-[45%]">
          <img
            src="<?php echo esc_url($image_url); ?>"
            alt="<?php echo esc_attr($image_alt); ?>"
            class="w-full rounded-lg h-auto object-cover aspect-[4/3] relative z-[2]"
          />
        </div>

        <!-- Text Area -->
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
