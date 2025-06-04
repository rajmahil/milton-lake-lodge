<?php
/**
 * Showcase Section Block - Server-side render
 */

$top_heading = $attributes['topHeading'] ?? '';
$heading = $attributes['heading'] ?? '';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$images = $attributes['images'] ?? [];
$imagesSpeed = $attributes['imagesSpeed'] ?? 'medium'; 

$speed_map = [
  'slow' => 60,
  'medium' => 30,
  'fast' => 15,
];

$base_duration = $speed_map[$imagesSpeed] ?? 30;
$reduced_duration = max($base_duration - 15, 5); // prevent negative or zero
$animation_class = 'showcase-animate-' . uniqid(); // unique class for inline CSS override
?>

<section class="flex flex-col gap-24 overflow-hidden relative not-prose section-padding w-full static-background">

  <!-- Header Content -->
  <div class="relative z-[2] max-w-container flex flex-row flex-wrap gap-5 items-end justify-between">
    <div class="flex flex-col gap-4 lg:max-w-[60%] w-full">
      <?php if ($top_heading): ?>
        <p class="decorative-text text-brand-yellow !text-2xl">
          <?php echo esc_html($top_heading); ?>
        </p>
      <?php endif; ?>

      <?php if ($heading): ?>
        <h2 class="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600]  text-left">
          <?php echo esc_html($heading); ?>
        </h2>
      <?php endif; ?>
    </div>

    <div>
      <a href="<?php echo esc_url($button_url); ?>">
        <button class="btn btn-outline btn-xl" type="button">
          <?php echo esc_html($button_text); ?>
        </button>
      </a>
    </div>
  </div>

  <!-- Sliding Images Gallery -->
  <div class="group relative w-full h-full select-none">
    <div
      class="flex w-max animate-slide gap-10 whitespace-nowrap <?php echo esc_attr($animation_class); ?>"
      style="animation-duration: <?php echo esc_attr($base_duration); ?>s;"
    >
      <?php foreach (array_merge($images, $images) as $idx => $image): 
        $image_url = $image['sizes']['large']['url'] ?? ($image['url'] ?? '');
        $image_alt = $image['alt'] ?? '';
        $image_width = $image['width'] ?? '';
        $image_height = $image['height'] ?? '';

        $rotation_class = match ($idx % 3) {
          0 => 'rotate-[-3deg]',
          1 => 'rotate-[2deg]',
          default => 'rotate-[-1deg]',
        };
      ?>
        <div class="px-1 py-1.5 bg-white rounded-lg overflow-hidden <?php echo $rotation_class; ?>">
          <img
            src="<?php echo esc_url($image_url); ?>"
            srcset="<?php echo esc_attr(sprintf(
              '%s 150w, %s 300w, %s 1024w, %s %sw',
              $image['sizes']['thumbnail']['url'] ?? '',
              $image['sizes']['medium']['url'] ?? '',
              $image['sizes']['large']['url'] ?? '',
              $image['sizes']['full']['url'] ?? $image['url'] ?? '',
              $image_width
            )); ?>"
            sizes="(max-width: 768px) 100vw, 1024px"
            alt="<?php echo esc_attr($image_alt); ?>"
            width="<?php echo esc_attr($image_width); ?>"
            height="<?php echo esc_attr($image_height); ?>"
            class="flex-shrink-0 h-full aspect-[3/4] w-full max-w-[200px] md:max-w-[300px] object-cover"
            loading="eager"
          />
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Responsive animation speed override -->
<style>
  @media (max-width: 767px) {
    .<?php echo $animation_class; ?> {
      animation-duration: <?php echo esc_attr($reduced_duration); ?>s !important;
    }
  }
</style>

<?php if ($heading): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPageElement",
  "name": "Showcase Section",
  "description": "<?php echo esc_js($heading); ?>",
  "images": "<?php echo esc_js($images[0]['url'] ?? ''); ?>"
}
</script>
<?php endif; ?>
