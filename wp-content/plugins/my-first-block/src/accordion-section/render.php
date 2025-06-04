<?php
/**
 * Accordion Block - Alpine.js Version with Rotating + to X Icon
 */

$heading = $attributes['heading'] ?? '';
$subheading = $attributes['subheading'] ?? '';
$items = $attributes['items'] ?? [];
?>

<section class="not-prose section-padding w-full static-background">
  <div
    class="max-w-container mx-auto grid lg:grid-cols-2 gap-10 items-start"
    x-data="{ activeAccordion: null }"
  >

    <div class="flex flex-col !gap-5">
      <?php if ( $heading ): ?>
      <h2 class="text-3xl md:text-4xl lg:text-5xl font-semibold text-left">
        <?php echo esc_html($heading); ?>
      </h2>
      <?php endif; ?>

      <?php if ( $subheading ): ?>
      <p class="text-lg text-left max-w-2xl">
        <?php echo esc_html($subheading); ?>
      </p>
      <?php endif; ?>
    </div>

    <div class="relative w-full overflow-hidden flex flex-col gap-5">
      <?php foreach ( $items as $index => $item ): ?>
      <?php $id = 'accordion-item-' . $index; ?>
      <div
        class="!cursor-pointer group !bg-white rounded-xl p-4"
        @click="activeAccordion === '<?php echo $id; ?>' ? activeAccordion = null : activeAccordion = '<?php echo $id; ?>'"
      >
        <button
          type="button"
          class="w-full text-left flex items-center justify-between text-xl font-medium select-none group-hover:cursor-pointer"
        >
          <p class="!text-lg font-normal"><?php echo esc_html($item['title'] ?? ''); ?></p>
          <svg
            class="w-5 h-5 transition-transform duration-300"
            :class="{ 'rotate-45': activeAccordion === '<?php echo $id; ?>' }"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <line
              x1="12"
              y1="5"
              x2="12"
              y2="19"
            />
            <line
              x1="5"
              y1="12"
              x2="19"
              y2="12"
            />
          </svg>
        </button>

        <p
          class="text-base text-muted-foreground !pt-4"
          x-show="activeAccordion === '<?php echo $id; ?>'"
          x-transition
        >
          <?php echo esc_html($item['text'] ?? ''); ?>
        </p>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
