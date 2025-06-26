<?php
/**
 * Hero Section Block - Server-side render
 * Optimized for performance, SEO, and accessibility
 */

// Extract attributes with safe defaults
$heading = $attributes['heading'] ?? 'Your Adventure Awaits';
$subheading = $attributes['subheading'] ?? 'Discover amazing fishing experiences';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$button2_text = $attributes['button2Text'] ?? 'Learn More';
$button2_url = $attributes['button2Url'] ?? '#';
$image = $attributes['image'] ?? null;
$backgroundColor = $attributes['backgroundColor'] ?? '#00473f';
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';

$tripAdvisorStars = $attributes['tripAdvisorStars'];
$tripAdvisorReviews = $attributes['tripAdvisorReviews'];

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
} else {
    // Fallback image if none provided
    $image_url = get_template_directory_uri() . '/assets/images/default-hero.jpg';
    $image_alt = 'Default Hero Image';
}
?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block static-background"
  style="background: <?php echo esc_attr($backgroundColor ?? ''); ?>"
>
  <section
    class="h-[95vh] min-h-[800px] flex items-end overflow-hidden relative not-prose section-padding pb-6  w-full  rounded-b-4xl overlfow-hidden "
  >
    <!-- Background Image -->
    <div class="absolute top-0 left-0 w-full h-full z-[0] pointer-events-none select-none">
      <?php if ($image_url): ?>
      <?php if ($image_id): ?>
      <!-- Use WordPress function for better performance and responsive images -->
      <?php echo wp_get_attachment_image($image_id, 'full', false, [
          'class' => 'object-cover object-center w-full h-full ',
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


      <div class="w-full h-full bg-gradient-to-br from-brand-green-dark to-brand-green-dark/80"></div>
      <?php endif; ?>
    </div>

    <!-- Gradient Overlay -->
    <div class="absolute bottom-0 left-0 w-full h-full bg-gradient-to-t from-black/70 to-black/0 ">
    </div>

    <!-- Content -->
    <div class="relative z-[2] max-w-container overflow-hidden flex flex-col gap-14">
      <!-- Text Content -->
      <div class=" w-full  flex flex-row gap-5 flex-wrap items-end justify-between ">
        <div class="flex flex-col gap-0 max-w-[650px] ">
          <h1
            class="!my-0 !text-6xl md:!text-7xl lg:!text-8xl !font-[700] text-left text-white font-sans drop-shadow-lg "
          >
            <?php echo esc_html($heading); ?>
          </h1>
          <?php if ($subheading): ?>
          <p class="!my-0 text-xl md:text-2xl  text-left  text-white">
            <?php echo esc_html($subheading); ?>
          </p>
          <?php endif; ?>
        </div>
        <div class="flex flex-row gap-2">
          <?php if ($button_url && $button_text): ?>
          <a
            href="<?php echo esc_url($button_url); ?>"
            class="hero-btn-link"
          >
            <button
              class="btn btn-primary btn-xl"
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
              class="btn btn-outline btn-xl"
              type="button"
            >
              <?php echo esc_html($button2_text); ?>
            </button>
          </a>
          <?php endif; ?>
        </div>
      </div>


      <div
        class="flex flex-col lg:grid md:grid-cols-3 lg:grid-cols-5 gap-0  w-full p-1 lg:p-2 bg-black/50 rounded-md relative overflow-hidden"
      >
        <div
          class="flex flex-row gap-2 lg:gap-3 p-2 text-white  rounded-lg w-full px-2 items-center justify-start min-w-[250px] "
        >
          <div class="bg-[#33e0a1] text-black h-fit p-1 rounded-full">
            <svg
              fill="currentColor"
              width="35px"
              height="35px"
              viewBox="0 0 32 32"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g
                id="SVGRepo_bgCarrier"
                stroke-width="0"
              ></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M30.683 12.708c0.375-1.609 1.568-3.219 1.568-3.219h-5.349c-3.005-1.943-6.647-2.968-10.688-2.968-4.187 0-7.968 1.041-10.953 3h-5.009c0 0 1.176 1.583 1.556 3.181-0.973 1.344-1.556 2.964-1.556 4.745 0 4.416 3.599 8.011 8.015 8.011 2.527 0 4.765-1.183 6.245-3.005l1.697 2.552 1.724-2.584c0.761 0.985 1.761 1.781 2.937 2.324 1.943 0.88 4.125 0.979 6.125 0.239 4.141-1.536 6.26-6.161 4.74-10.301-0.276-0.74-0.641-1.401-1.079-1.98zM26.453 23.473c-1.599 0.595-3.339 0.527-4.891-0.192-1.099-0.511-2.005-1.308-2.651-2.303-0.272-0.4-0.5-0.833-0.672-1.296-0.199-0.527-0.292-1.068-0.344-1.62-0.099-1.109 0.057-2.229 0.536-3.271 0.719-1.552 2-2.735 3.604-3.328 3.319-1.219 7 0.484 8.219 3.791 1.224 3.308-0.479 6.991-3.781 8.215h-0.020zM13.563 21.027c-1.151 1.692-3.093 2.817-5.297 2.817-3.525 0-6.401-2.875-6.401-6.396s2.876-6.401 6.401-6.401c3.527 0 6.396 2.88 6.396 6.401 0 0.219-0.036 0.416-0.063 0.64-0.109 1.079-0.453 2.1-1.036 2.959zM4.197 17.364c0 2.188 1.781 3.959 3.964 3.959s3.959-1.771 3.959-3.959c0-2.181-1.776-3.952-3.959-3.952-2.177 0-3.959 1.771-3.959 3.952zM20.265 17.364c0 2.188 1.771 3.959 3.953 3.959s3.959-1.771 3.959-3.959c0-2.181-1.776-3.952-3.959-3.952-2.177 0-3.959 1.771-3.959 3.952zM5.568 17.364c0-1.427 1.161-2.593 2.583-2.593 1.417 0 2.599 1.167 2.599 2.593 0 1.433-1.161 2.6-2.599 2.6-1.443 0-2.604-1.167-2.604-2.6zM21.615 17.364c0-1.427 1.156-2.593 2.593-2.593 1.423 0 2.584 1.167 2.584 2.593 0 1.433-1.156 2.6-2.599 2.6-1.444 0-2.6-1.167-2.6-2.6zM16.208 7.921c2.88 0 5.48 0.516 7.761 1.548-0.86 0.025-1.699 0.176-2.543 0.479-2.015 0.74-3.62 2.224-4.5 4.167-0.416 0.88-0.635 1.812-0.719 2.755-0.301-4.104-3.681-7.353-7.844-7.437 2.281-0.979 4.928-1.511 7.787-1.511z"
                ></path>
              </g>
            </svg>
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex flex-col gap-0 items-start">
              <div class="flex flex-row">
                <?php for ($i = 0; $i < 4; $i++): ?>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 16 16"
                  fill="currentColor"
                  class="w-6"
                >
                  <path
                    fill-rule="evenodd"
                    d="M8 1.75a.75.75 0 0 1 .692.462l1.41 3.393 3.664.293a.75.75 0 0 1 .428 1.317l-2.791 2.39.853 3.575a.75.75 0 0 1-1.12.814L7.998 12.08l-3.135 1.915a.75.75 0 0 1-1.12-.814l.852-3.574-2.79-2.39a.75.75 0 0 1 .427-1.318l3.663-.293 1.41-3.393A.75.75 0 0 1 8 1.75Z"
                    clip-rule="evenodd"
                  />
                </svg>
                <?php endfor; ?>

                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 16 16"
                  fill="currentColor"
                  class="w-6"
                  style="clip-path: inset(0 30% 0 0);"
                >
                  <path
                    fill-rule="evenodd"
                    d="M8 1.75a.75.75 0 0 1 .692.462l1.41 3.393 3.664.293a.75.75 0 0 1 .428 1.317l-2.791 2.39.853 3.575a.75.75 0 0 1-1.12.814L7.998 12.08l-3.135 1.915a.75.75 0 0 1-1.12-.814l.852-3.574-2.79-2.39a.75.75 0 0 1 .427-1.318l3.663-.293 1.41-3.393A.75.75 0 0 1 8 1.75Z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
              <p class="font-[400] text-sm">Rated <?php echo esc_html($tripAdvisorStars); ?>/5 stars on Trip Advisor</p>
            </div>
          </div>
          <div
            class="absolute top-0 right-0 w-10 lg:w-20 h-full bg-gradient-to-l from-black to-transparent z-[100] lg:block hidden"
          >
          </div>
        </div>
        <div class="col-span-2 lg:col-span-4 overflow-hidden flex flex-row items-center relative rounded-md">
          <div class="absolute top-0 left-0 w-12 lg:w-20 h-full bg-gradient-to-r from-black to transparent z-[100]">
          </div>
          <div
            class="absolute bottom-0 right-0 w-10 lg:w-20 h-full bg-gradient-to-l from-black to-transparent z-[100] lg:hidden block"
          >
          </div>
          <?php for($x =1 ; $x <=2; $x++): ?>
          <div class="flex flex-row flex-nowrap text-white animate-slide">
            <?php foreach ( $tripAdvisorReviews as $tripAdvisorReview ) : ?>
            <div class="py-2 px-4 rounded-lg flex flex-row gap-4 items-center  min-w-[400px]  md:min-w-[350px] ">
              <div class="h-12 w-12 min-h-12 min-w-12 aspect-square rounded-full bg-white">
                <?php if ( ! empty( $tripAdvisorReview['image'] ) ) : ?>
                <img
                  src="<?php echo esc_url($tripAdvisorReview['image']); ?>"
                  alt=""
                  class="object-cover object-center w-full h-full rounded-full"
                  loading="eager"
                  fetchpriority="high"
                  decoding="async"
                />
                <?php endif; ?>
              </div>
              <p class="text-[15px] text-left !leading-tight">
                <?php echo !empty($tripAdvisorReview['text']) ? esc_html($tripAdvisorReview['text']) : ''; ?>
              </p>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endfor; ?>

        </div>
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
