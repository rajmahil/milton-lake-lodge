<?php
/**
 * Hero Section Block - Server-side render
 * Optimized for performance, SEO, and accessibility
 */

// Extract attributes with safe defaults
$top_heading = $attributes['topHeading'] ?? 'Top Fishing Adventures';
$heading = $attributes['heading'] ?? 'Your Adventure Awaits';
$subheading = $attributes['subheading'] ?? 'Discover amazing fishing experiences';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$button2_text = $attributes['button2Text'] ?? 'Learn More';
$button2_url = $attributes['button2Url'] ?? '#';
$image = $attributes['image'] ?? null;

// Simplified image handling - works with most WordPress block formats
$image_url = '';
$image_alt = '';
$image_id = null;

// Handle different image formats from block editor
if ($image) {
    if (is_array($image)) {
        // Image object from media library
        $image_url = $image['url'] ?? ($image['sizes']['large']['url'] ?? '');
        $image_alt = $image['alt'] ?? '';
        $image_id = $image['id'] ?? null;
    } elseif (is_numeric($image)) {
        // Image ID
        $image_id = $image;
        $image_url = wp_get_attachment_image_url($image_id, 'large');
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    } elseif (is_string($image)) {
        // Direct URL
        $image_url = $image;
    }
}
?>

<section class="my-unique-plugin-wrapper-class">
  <section class="h-screen flex items-end overflow-hidden relative not-prose section-padding w-full hero-section static-background">
    <!-- Background Image -->
    <div class="absolute top-0 left-0 w-full h-full z-[0] pointer-events-none select-none">
      <?php if ($image_url): ?>
      <?php if ($image_id): ?>
      <!-- Use WordPress function for better performance and responsive images -->
      <?php echo wp_get_attachment_image($image_id, 'large', false, [
          'class' => 'object-cover object-center w-full h-full',
          'loading' => 'eager',
          'fetchpriority' => 'high',
          'decoding' => 'async',
          'alt' => $image_alt,
      ]); ?>
      <?php else: ?>
      <!-- Fallback for direct URLs -->
      <img
        src="<?php echo esc_url($image_url); ?>"
        alt="<?php echo esc_attr($image_alt); ?>"
        class="object-cover object-center w-full h-full"
        loading="eager"
        fetchpriority="high"
        decoding="async"
      />
      <?php endif; ?>
      <?php else: ?>
      <!-- Placeholder background when no image -->
      <div class="w-full h-full bg-gradient-to-br from-brand-dark-blue to-brand-dark-blue/80"></div>
      <?php endif; ?>
    </div>

    <!-- Gradient Overlay -->
    <div class="absolute bottom-0 left-0 w-full h-full bg-gradient-to-t from-brand-dark-blue to-black/0"></div>

    <!-- Content -->
    <div class="relative z-[2] max-w-container flex flex-row gap-5 flex-wrap items-center justify-between">
      <!-- Text Content -->
      <div class="flex flex-col gap-4 max-w-[650px] w-full">
        <p class="decorative-text text-brand-yellow text-2xl">
        <?php echo esc_html($top_heading); ?>
        </p>

        <h1 class="!my-0 !text-4xl md:!text-5xl lg:!text-6xl !font-[600]  text-left">
          <?php echo esc_html($heading); ?>
        </h1>

        <?php if ($subheading): ?>
        <p class="!my-0 text-xl  text-left !leading-normal">
          <?php echo esc_html($subheading); ?>
        </p>
        <?php endif; ?>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-row gap-2">
        <?php if ($button_url && $button_text): ?>
        <a
          href="<?php echo esc_url($button_url); ?>"
          class="hero-btn-link"
        >
          <button
            class="btn btn-outline btn-xl"
            type="button"
          >
            <?php echo esc_html($button_text); ?>
          </button>
        </a>

        <a
          href="<?php echo esc_url($button2_url); ?>"
          class="hero-btn-link"
        >
          <button
            class="btn btn-primary btn-xl"
            type="button"
          >
            <?php echo esc_html($button2_text); ?>
          </button>
        </a>
        <?php else: ?>
        <!-- Default buttons when no URL is set -->
        <button
          class="btn btn-outline btn-xl"
          type="button"
        >
          Learn More
        </button>

        <button
          class="btn btn-primary btn-xl"
          type="button"
        >
          Get Started
        </button>
        <?php endif; ?>
      </div>
  </section>
</section>

<?php
// Add structured data for SEO
if ($heading || $subheading): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPageElement",
    "name": "Hero Section",
    "description": "<?php echo esc_js($subheading ?: $heading); ?>",
    "image": "<?php echo esc_js($image_url ?: ''); ?>"
}
</script>
<?php endif; ?>
