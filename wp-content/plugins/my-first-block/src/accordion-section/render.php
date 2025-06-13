<?php
/**
 * Accordion Block - Alpine.js Version with Smooth Animations
 */
$heading = $attributes['heading'] ?? '';
$subheading = $attributes['subheading'] ?? '';
$items = $attributes['items'] ?? [];
?>

<section class="plugin-custom-block not-prose section-padding w-full ">
  <div
    class="max-w-container mx-auto grid lg:grid-cols-2 gap-8 items-start"
    x-data="{ activeAccordion: null }"
  >

    <div class="flex flex-col gap-2">
      <?php if ( $heading ): ?>
      <h2 class="heading-two text-left">
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
        class="accordion-item !cursor-pointer group bg-white rounded-md border border-brand-grey p-6 transition-all duration-200 ease-in-out "
        @click="activeAccordion === '<?php echo $id; ?>' ? activeAccordion = null : activeAccordion = '<?php echo $id; ?>'"
      >
        <button
          type="button"
          class="w-full text-left flex items-center justify-between text-xl font-medium select-none "
        >
          <h3 class="text-lg !cursor-pointer font-normal !normal-case flex flex-row w-full items-center justify-between">
            <?php echo esc_html($item['title'] ?? ''); ?></p>
            <svg
              class="accordion-icon w-5 h-5 transition-transform duration-300 ease-out flex-shrink-0"
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

        <div
          class="accordion-content text-base text-neutral-500 !cursor-default"
          x-show="activeAccordion === '<?php echo $id; ?>'"
          x-collapse
          x-cloak
        >
          <div class="pt-4">
            <?php echo esc_html($item['text'] ?? ''); ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
