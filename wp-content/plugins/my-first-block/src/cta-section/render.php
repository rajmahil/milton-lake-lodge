<?php
/**
 * CTA Section Block - Render Template
 */

$heading = $attributes['heading'] ?? 'Main Heading';
$subheading = $attributes['subheading'] ?? 'Subheading text goes here.';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$image1 = $attributes['image'] ?? null;
$image2 = $attributes['image2'] ?? null;
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';

// Image 1
$image1_url = '';
$image1_alt = '';
$image1_id = null;
if ($image1) {
    if (is_array($image1)) {
        $image1_url = $image1['url'] ?? ($image1['sizes']['large']['url'] ?? '');
        $image1_alt = $image1['alt'] ?? '';
        $image1_id = $image1['id'] ?? null;
    } elseif (is_numeric($image1)) {
        $image1_id = $image1;
        $image1_url = wp_get_attachment_image_url($image1_id, 'large');
        $image1_alt = get_post_meta($image1_id, '_wp_attachment_image_alt', true);
    } elseif (is_string($image1)) {
        $image1_url = $image1;
    }
}

// Image 2
$image2_url = '';
$image2_alt = '';
$image2_id = null;
if ($image2) {
    if (is_array($image2)) {
        $image2_url = $image2['url'] ?? ($image2['sizes']['large']['url'] ?? '');
        $image2_alt = $image2['alt'] ?? '';
        $image2_id = $image2['id'] ?? null;
    } elseif (is_numeric($image2)) {
        $image2_id = $image2;
        $image2_url = wp_get_attachment_image_url($image2_id, 'large');
        $image2_alt = get_post_meta($image2_id, '_wp_attachment_image_alt', true);
    } elseif (is_string($image2)) {
        $image2_url = $image2;
    }
}

$background_image = $attributes['backgroundImage'] ?? [];
?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block section-padding w-full"
>
  <div
    class="relative max-w-container bg-brand-green bg-blend-hard-light grid grid-cols-5 !items-center text-white w-full rounded-2xl z-[0] bg-repeat bg-size-[450px]"
    style="background-image: url('<?php echo !empty($background_image['url']) ? esc_url($background_image['url']) : esc_url(wp_get_upload_dir()['baseurl'] . '/effects/green-topo.png'); ?>');"
  >
    <div
      class="flex flex-col gap-6 w-full relative z-[1] !items-start !justify-center section-padding px-4 py-16 col-span-5 900:col-span-2"
    >

      <div>
        <h2 class="heading-two !text-left text-white">
          <?php echo esc_html($heading); ?>
        </h2>
        <?php if (!empty($subheading)): ?>
        <p class="text-lg !text-left text-white">
          <?php echo esc_html($subheading); ?>
        </p>
      </div>
      <?php endif; ?>
      <a href="<?php echo esc_url($button_url); ?>">
        <button class="btn btn-outline btn-xl">
          <?php echo esc_html($button_text); ?>
        </button>
      </a>
    </div>

    <?php if ($image1_url || $image2_url): ?>
    <div class="flex justify-center items-center relative col-span-5 900:col-span-3 pb-20 900:pb-0">
      <?php if ($image1_url): ?>
      <div class="max-w-[400px] w-full rotate-5 relative left-10">
        <?php
        echo wp_get_attachment_image($image1_id, 'large', false, [
            'class' => 'aspect-[3/4] w-full object-cover',
            'alt' => esc_attr($image1_alt),
            'loading' => 'lazy',
            'decoding' => 'async',
        ]);
        ?>
      </div>
      <?php endif; ?>
      <?php if ($image2_url): ?>
      <div class="max-w-[400px] w-full rotate-[-10deg] relative right-10">
        <?php
        echo wp_get_attachment_image($image2_id, 'large', false, [
            'class' => 'aspect-[3/4] w-full object-cover',
            'alt' => esc_attr($image2_alt),
            'loading' => 'lazy',
            'decoding' => 'async',
        ]);
        ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
