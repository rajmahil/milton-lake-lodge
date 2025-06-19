<?php
/**
 * Scroll Image Section - Server-side render with optimized Alpine.js scroll animation
 */

$heading = $attributes['heading'] ?? '';
$buttonText = $attributes['buttonText'] ?? '';
$buttonUrl = $attributes['buttonUrl'] ?? '#';
$image = $attributes['image'] ?? [];

$imageUrl = $image['sizes']['large']['url'] ?? ($image['url'] ?? '/placeholder.svg');
$thumbnail = $image['sizes']['thumbnail']['url'] ?? '';
$medium = $image['sizes']['medium']['url'] ?? '';
$large = $image['sizes']['large']['url'] ?? '';
$full = $image['sizes']['full']['url'] ?? '';
$imageAlt = $image['alt'] ?? '';
$imageWidth = $image['width'] ?? '';
$imageHeight = $image['height'] ?? '';

$section_id = ! empty( $attributes['sectionId'] ) ? esc_attr( $attributes['sectionId'] ) : '';

?>

<section
id="<?php echo $section_id; ?>" 
  x-data="optimizedScrollScale()"
  x-init="init()"
  class="plugin-custom-block"
>
  <div
    class="relative w-full overflow-hidden rounded-2xl h-[150vh] flex items-start justify-start not-prose"
    x-ref="container"
    :style="`transform: scale(${scale}); will-change: transform;`"
  >
    <div
      class="absolute inset-0 z-0 transform origin-center"
      style="will-change: transform;"
    >
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



    <div class="section-padding w-full">
      <div class="relative z-10 text-white flex flex-col items-start justify-start gap-4 !max-w-7xl !w-full mx-auto ">
        <div class="flex flex-col w-full items-start gap-3">
          <?php if ($heading): ?>
          <h2
            class="heading-two max-w-none md:max-w-[60%] text-left"
          >
            <?php echo esc_html($heading); ?>
          </h2>
          <?php endif; ?>
        </div>

        <?php if ($buttonText): ?>
        <a
          href="<?php echo esc_url($buttonUrl); ?>"
          class="inline-block mt-4  w-fit"
        >
          <button class="btn btn-xl btn-outline">
            <?php echo esc_html($buttonText); ?>
          </button>
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<script>
  function optimizedScrollScale() {
    return {
      scale: 0.9,
      ticking: false,

      init() {
        // Initial calculation
        this.updateScale();

        // Throttled scroll listener for performance
        window.addEventListener('scroll', () => {
          if (!this.ticking) {
            requestAnimationFrame(() => {
              this.updateScale();
              this.ticking = false;
            });
            this.ticking = true;
          }
        }, {
          passive: true
        });

        // Handle resize events
        window.addEventListener('resize', () => {
          if (!this.ticking) {
            requestAnimationFrame(() => {
              this.updateScale();
              this.ticking = false;
            });
            this.ticking = true;
          }
        }, {
          passive: true
        });
      },

      updateScale() {
        const container = this.$refs.container;
        if (!container) return;

        const rect = container.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        const containerHeight = container.offsetHeight;

        // Calculate when element is in viewport
        const elementTop = rect.top;
        const elementBottom = rect.bottom;

        // Element is visible when top is above viewport bottom and bottom is below viewport top
        if (elementBottom > 0 && elementTop < windowHeight) {
          // Calculate progress: 0 when element just enters, 1 when fully visible
          const visibleHeight = Math.min(windowHeight, elementBottom) - Math.max(0, elementTop);
          const progress = Math.min(1, visibleHeight / Math.min(windowHeight, containerHeight));

          // Scale from 0.9 to 1.0 based on visibility progress
          this.scale = 0.9 + (progress * 0.1);
        } else if (elementBottom <= 0) {
          // Element has scrolled past - keep at full scale
          this.scale = 1.0;
        } else {
          // Element hasn't entered viewport yet
          this.scale = 0.9;
        }

        // Round to 3 decimal places to avoid unnecessary re-renders
        this.scale = Math.round(this.scale * 1000) / 1000;
      }
    }
  }
</script>
