<?php
/**
 * Scroll Image Section - Server-side render
 */

$topHeading = $attributes['topHeading'] ?? '';
$heading = $attributes['heading'] ?? '';
$buttonText = $attributes['buttonText'] ?? '';
$buttonUrl = $attributes['buttonUrl'] ?? '#';
$image = $attributes['image'] ?? [];

$imageUrl = $image['sizes']['large']['url'] ?? $image['url'] ?? '/placeholder.svg';
$thumbnail = $image['sizes']['thumbnail']['url'] ?? '';
$medium = $image['sizes']['medium']['url'] ?? '';
$large = $image['sizes']['large']['url'] ?? '';
$full = $image['sizes']['full']['url'] ?? '';
$imageAlt = $image['alt'] ?? '';
$imageWidth = $image['width'] ?? '';
$imageHeight = $image['height'] ?? '';
?>

<section class="section-padding static-background">
  <div class="relative w-full overflow-hidden rounded-lg max-w-container mx-auto h-screen flex items-start justify-start not-prose p-10">

    <!-- Background Image -->
    <div class="absolute inset-0 z-0 transform origin-center transition-transform duration-300 ease-out" style="will-change: transform;">
      <img
        src="<?php echo esc_url($imageUrl); ?>"
        srcset="<?php echo esc_attr("{$thumbnail} 150w, {$medium} 300w, {$large} 1024w, {$full} {$imageWidth}w"); ?>"
        sizes="(max-width: 768px) 100vw, 1024px"
        alt="<?php echo esc_attr($imageAlt); ?>"
        width="<?php echo esc_attr($imageWidth); ?>"
        height="<?php echo esc_attr($imageHeight); ?>"
        class="object-cover w-full h-full"
        loading="eager"
      />
      <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <!-- Text Content -->
    <div class="relative z-10 text-white flex flex-col gap-4">
      <div class="flex flex-col items-center sm:items-start gap-3">
        <?php if ($topHeading): ?>
          <p class="decorative-text !text-brand-yellow-dark text-3xl lg:!text-4xl !my-0 text-center sm:text-left">
            <?php echo esc_html($topHeading); ?>
          </p>
        <?php endif; ?>

        <?php if ($heading): ?>
          <h2 class="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600] max-w-none md:max-w-[60%] text-center sm:text-left">
            <?php echo esc_html($heading); ?>
          </h2>
        <?php endif; ?>
      </div>

      <?php if ($buttonText): ?>
        <a href="<?php echo esc_url($buttonUrl); ?>" class="inline-block mt-4 mx-auto sm:mx-0">
          <button class="btn btn-xl btn-outline ">
            <?php echo esc_html($buttonText); ?>
          </button>
        </a>
      <?php endif; ?>
    </div>

  </div>
</section>
