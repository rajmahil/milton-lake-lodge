<?php
/**
 * Features Section Block - Server-side render
 */

$heading = $attributes['heading'] ?? '';
$features = $attributes['features'] ?? [];
?>

<section class="not-prose section-padding w-full bg-brand-grey relative z-[2]">
  <div class="max-w-container mx-auto flex flex-col gap-16">

    <?php if ($heading): ?>
      <h2 class="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600] !text-brand-dark-blue text-left lg:max-w-[60%]">
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
          <div class="relative w-full ml-2 max-w-[90%] md:max-w-[70%] lg:max-w-[45%] -rotate-2 border-2 border-black rounded-xl">
            <img
              src="<?php echo esc_url($image_url); ?>"
              alt="<?php echo esc_attr($image_alt); ?>"
              class="w-full rounded-lg h-auto object-cover aspect-[1.8/1] relative z-[2]"
            />
            <div class="rounded-xl w-full h-full absolute right-2 top-2 bg-black z-[1]"></div>
          </div>

          <!-- Text Area -->
          <div class="w-full lg:w-1/2 text-brand-dark-blue">
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
