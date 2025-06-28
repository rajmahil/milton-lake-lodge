<?php
/**
 * CTA Section Block - Render Template
 */

$heading = $attributes['heading'] ?? 'Main Heading';
$subheading = $attributes['subheading'] ?? 'Subheading text goes here.';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';
$background_image = $attributes['backgroundImage'] ?? [];
?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block section-padding w-full"
>
  <div
    class="relative max-w-container  bg-brand-green bg-blend-hard-light  !items-center text-white w-full rounded-2xl z-[0] bg-repeat bg-size-[450px]"
    style="background-image: url('<?php echo !empty($background_image['url']) ? esc_url($background_image['url']) : esc_url(wp_get_upload_dir()['baseurl'] . '/effects/green-topo.png'); ?>');"
  >
    <div class="flex flex-col gap-6 w-full relative z-[1] !items-center !justify-center section-padding ">
      <div>
        <h2 class="heading-two !text-center text-white">
          <?php echo esc_html($heading); ?>
        </h2>
        <p class="text-lg !text-center text-white">
          <?php echo esc_html($subheading); ?>
        </p>
      </div>
      <a href="<?php echo esc_url($button_url); ?>">
        <button class="btn btn-outline btn-xl">
          <?php echo esc_html($button_text); ?>
        </button>
      </a>
    </div>

  </div>
</section>
