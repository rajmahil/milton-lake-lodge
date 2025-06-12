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
?>


<section class="plugin-custom-block section-padding w-full">
  <div class="relative max-w-container  grid grid-cols-5 items-center  gap-5 text-white  w-full">
    <div
      class="absolute bottom-0 left-0 bg-brand-green w-full  h-[80%] rounded-2xl z-[0] bg-repeat  bg-size-[400px] bg-blend-hard-light"
      style="background-image: url('<?php echo esc_url(wp_get_upload_dir()['baseurl'] . '/effects/green-topo.png'); ?>');"
    ></div>
    <div class="flex flex-col gap-4 w-full relative z-[1] items-start section-padding col-span-2  h-[80%] mt-auto">
      <h2 class="!text-3xl md:!text-4xl lg:!text-5xl !font-[600] text-center 1050:text-left">
        <?php echo esc_html($heading); ?>
      </h2>
      <a href="<?php echo esc_url($button_url); ?>">
        <button class="btn btn-outline btn-xl">
          <?php echo esc_html($button_text); ?>
        </button>
      </a>
    </div>

    <?php if ($image1_url || $image2_url): ?>
    <div class="flex justify-center items-center relative col-span-3">
      <?php if ($image1_url): ?>
      <div class="max-w-[400px] w-full rotate-5 relative left-10 shadow-lg">
        <?php
        echo wp_get_attachment_image(
            $image1_id,
            'medium', // size name (you can also use 'medium', 'full', or custom sizes)
            true, // icon (false = no icon fallback)
            [
                'class' => 'aspect-[3/4] w-full object-cover',
                'alt' => $image1_alt,
                'loading' => 'lazy', // lazy load for performance
                'fetchpriority' => 'auto', // auto or 'high' for above-the-fold
                'decoding' => 'async', // async decoding
            ],
        );
        ?>
      </div>
      <?php endif; ?>
      <?php if ($image2_url): ?>
      <div class="max-w-[400px] w-full rotate-[-10deg] shadow-lg relative right-10">
        <?php
        echo wp_get_attachment_image(
            $image2_id,
            'medium', // size name (you can also use 'medium', 'full', or custom sizes)
            true, // icon (false = no icon fallback)
            [
                'class' => 'aspect-[3/4] w-full object-cover',
                'alt' => $image2_alt,
                'loading' => 'lazy', // lazy load for performance
                'fetchpriority' => 'auto', // auto or 'high' for above-the-fold
                'decoding' => 'async', // async decoding
            ],
        );
        ?>
      </div>
      <?php endif; ?>
    </div>

    <?php endif; ?>

  </div>
</section>
