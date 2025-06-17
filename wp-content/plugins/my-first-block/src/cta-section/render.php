<?php
/**
 * CTA Section Block - Render Template
 */

$top_heading = $attributes['topHeading'] ?? 'Top Heading';
$heading = $attributes['heading'] ?? 'Main Heading';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$image1 = $attributes['image'] ?? null;
$image2 = $attributes['image2'] ?? null;
$background_image = $attributes['backgroundImage'] ?? [];

// Background image handling
$bg_style = "background-image: url('" . esc_url(wp_get_upload_dir()['baseurl'] . '/effects/green-topo.png') . "');";
if (!empty($background_image['url'])) {
    $bg_style = "background-image: url('" . esc_url($background_image['url']) . "');";
}

// Image processing helper function
function process_image($img) {
    if (!$img) return null;
    
    if (is_array($img)) {
        return [
            'url' => $img['url'] ?? ($img['sizes']['large']['url'] ?? ''),
            'alt' => $img['alt'] ?? '',
            'id' => $img['id'] ?? null
        ];
    }
    elseif (is_numeric($img)) {
        return [
            'url' => wp_get_attachment_image_url($img, 'large'),
            'alt' => get_post_meta($img, '_wp_attachment_image_alt', true),
            'id' => $img
        ];
    }
    return ['url' => $img, 'alt' => '', 'id' => null];
}

$image1_data = process_image($image1);
$image2_data = process_image($image2);
?>

<section class="plugin-custom-block section-padding w-full">
  <div 
    class="relative max-w-container bg-brand-green bg-blend-hard-light grid grid-cols-5 !items-center text-white w-full rounded-2xl z-[0] bg-repeat bg-size-[450px]"
    style="<?php echo $bg_style; ?>"
  >
    <div class="flex flex-col gap-4 w-full relative z-[1] items-start justify-center section-padding py-16 col-span-5 900:col-span-2">
      <h2 class="heading-two text-center 900:text-left text-white">
        <?php echo esc_html($heading); ?>
      </h2>
      <a href="<?php echo esc_url($button_url); ?>" class="mx-auto 900:mx-0">
        <button class="btn btn-outline btn-xl">
          <?php echo esc_html($button_text); ?>
        </button>
      </a>
    </div>

    <?php if (($image1_data && $image1_data['url']) || ($image2_data && $image2_data['url'])) : ?>
      <div class="flex justify-center items-center relative col-span-5 900:col-span-3 pb-20 900:pb-0">
        <?php if ($image1_data && $image1_data['url']) : ?>
          <div class="max-w-[400px] w-full rotate-5 relative left-10">
            <img
              src="<?php echo esc_url($image1_data['url']); ?>"
              alt="<?php echo esc_attr($image1_data['alt']); ?>"
              class="aspect-[3/4] w-full object-cover"
              loading="lazy"
              decoding="async"
            />
          </div>
        <?php endif; ?>
        
        <?php if ($image2_data && $image2_data['url']) : ?>
          <div class="max-w-[400px] w-full rotate-[-10deg] relative right-10">
            <img
              src="<?php echo esc_url($image2_data['url']); ?>"
              alt="<?php echo esc_attr($image2_data['alt']); ?>"
              class="aspect-[3/4] w-full object-cover"
              loading="lazy"
              decoding="async"
            />
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>