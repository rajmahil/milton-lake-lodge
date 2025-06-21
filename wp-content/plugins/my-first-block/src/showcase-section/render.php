<?php

$heading = $attributes['heading'] ?? '';
$text = $attributes['text'] ?? '';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$images = $attributes['images'] ?? [];
$background_image = $attributes['backgroundImage'] ?? [];
$imagesSpeed = $attributes['imagesSpeed'] ?? 'medium';

$speed_map = [
    'slow' => 60,
    'medium' => 30,
    'fast' => 15,
];

$base_duration = $speed_map[$imagesSpeed] ?? 30;
$reduced_duration = max($base_duration - 15, 5);
$animation_class = 'showcase-animate-' . uniqid();
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';

?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block flex flex-col gap-24 overflow-hidden relative not-prose section-padding w-full bg-brand-green bg-repeat bg-blend-hard-light bg-size-[450px]"
  style="background-image: url('<?php echo !empty($background_image['url']) ? esc_url($background_image['url']) : esc_url(wp_get_upload_dir()['baseurl'] . '/effects/green-topo.png'); ?>');"
>

  <div class="relative z-[2] max-w-container flex flex-row flex-wrap gap-5 items-end justify-between">
    <div class="flex flex-col gap-2 lg:max-w-4xl w-full">
      <?php if ($heading): ?>
      <h2 class="heading-two text-left text-white">
        <?php echo esc_html($heading); ?>
      </h2>
      <?php endif; ?>
      <?php if ($text): ?>
      <p class="text-xl text-white">
        <?php echo esc_html($text); ?>
      </p>
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
      <?php foreach ($images as $idx => $image):

			$id = $image['id'] ?? '';
    $rotation_class = match ($idx % 3) {
        0 => 'rotate-[-3deg]',
        1 => 'rotate-[2deg]',
        default => 'rotate-[-1deg]',
    };

    error_log($image . ' Image URLS Showcase');
?>
      <div class="<?php echo $rotation_class; ?>
      !w-[calc(90vw-40px)] lg:!w-[calc(50vw-40px)] xl:!w-[calc(33.33vw-40px)] ">
        <div class="aspect-[3/2] overflow-hidden flex items-center justify-center">
          <?php echo wp_get_attachment_image($id, 'large', false, [
              'class' => 'w-full h-auto  shadow-md ',
          ]); ?>
        </div>
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
