<?php

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
$reduced_duration = max($base_duration - 15, 5);
$animation_class = 'showcase-animate-' . uniqid();
?>

<section
  class="plugin-custom-block flex flex-col gap-24 overflow-hidden relative not-prose section-padding w-full bg-brand-green bg-repeat  bg-size-[450px] bg-blend-hard-light"
  style="background-image: url('<?php echo esc_url(wp_get_upload_dir()['baseurl'] . '/effects/green-topo.png'); ?>');"
>

  <div class="relative z-[2] max-w-container flex flex-row flex-wrap gap-5 items-end justify-between">
    <div class="flex flex-col gap-2 lg:max-w-2xl w-full">
      <?php if ($heading): ?>
      <h2 class="heading-two text-left text-white">
        <?php echo esc_html($heading); ?>
      </h2>
      <?php endif; ?>
    </div>
    <div>
      <a href="<?php echo esc_url($button_url); ?>">
        <button
          class="btn btn-outline btn-xl"
          type="button"
        >
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
      <div
        class="<?php echo $rotation_class; ?>
      !w-[calc(100vw-40px)] sm:!w-[calc(50vw-40px)] md:!w-[calc(33.33vw-40px)] lg:!w-[calc(25vw-40px)]
    "
      >
        <img
          src="<?php echo esc_url($image_url); ?>"
          srcset="<?php echo esc_attr(sprintf('%s 150w, %s 300w, %s 1024w, %s %sw', $image['sizes']['thumbnail']['url'] ?? '', $image['sizes']['medium']['url'] ?? '', $image['sizes']['large']['url'] ?? '', $image['sizes']['full']['url'] ?? ($image['url'] ?? ''), $image_width)); ?>"
          sizes="(max-width: 639px) 100vw, (max-width: 767px) 50vw, (max-width: 1023px) 33vw, 25vw"
          alt="<?php echo esc_attr($image_alt); ?>"
          class="flex-shrink-0 h-full aspect-[3/4] w-full object-cover"
          loading="lazy"
          decoding="async"
        />
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>


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
