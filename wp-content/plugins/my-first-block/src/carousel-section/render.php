<?php
/**
 * Carousel Section Block - Render Template (Alpine.js Version)
 * Responsive: 4 slides on lg+, 3 on md+, 2 on sm+, 1 on sm-
 */

$heading = $attributes['heading'] ?? '';
$subheading = $attributes['subheading'] ?? '';
$items = $attributes['items'] ?? [];
$total_items = count($items);
?>

<section class="plugin-custom-block not-prose w-full">
  <div class="text-center flex flex-col gap-16">
    <?php if (!empty($items)) : ?>
    <div
      x-data="{
          currentIndex: 0,
          totalSlides: <?php echo $total_items; ?>,
          slidesPerView: 1,
          slideWidthPercentage: 100,
          gapPercentage: 1.5, // 1.5% of container width for gap
      
          init() {
              this.updateSlidesPerView();
              window.addEventListener('resize', () => this.updateSlidesPerView());
          },
      
          updateSlidesPerView() {
              if (window.innerWidth >= 1024) { // lg+
                  this.slidesPerView = 3;
              } else if (window.innerWidth >= 768) { // md+
                  this.slidesPerView = 2;
              } else if (window.innerWidth >= 640) { // sm+
                  this.slidesPerView = 2;
              } else { // sm-
                  this.slidesPerView = 1;
              }
      
              // Calculate slide width as percentage
              this.slideWidthPercentage = (100 - (this.slidesPerView - 1) * this.gapPercentage) / this.slidesPerView;
      
              // Adjust current index to prevent overscrolling
              const maxIndex = Math.max(0, this.totalSlides - this.slidesPerView);
              if (this.currentIndex > maxIndex) {
                  this.currentIndex = maxIndex;
              }
          },
      
          goToSlide(index) {
              const maxIndex = Math.max(0, this.totalSlides - this.slidesPerView);
              this.currentIndex = Math.min(index, maxIndex);
          },
      
          next() {
              const maxIndex = Math.max(0, this.totalSlides - this.slidesPerView);
              if (this.currentIndex < maxIndex) {
                  this.currentIndex++;
              }
          },
      
          prev() {
              if (this.currentIndex > 0) {
                  this.currentIndex--;
              }
          },
      
          get canGoNext() {
              return this.currentIndex < Math.max(0, this.totalSlides - this.slidesPerView);
          },
      
          get canGoPrev() {
              return this.currentIndex > 0;
          },
      
          get maxDots() {
              return Math.max(1, this.totalSlides - this.slidesPerView + 1);
          }
      }"
      class="relative w-full overflow-hidden"
    >
      <div class='section-padding pb-0'>
        <div class='flex items-end justify-between flex-wrap gap-5 mb-10 sm:mb-16 max-w-container'>
          <div class='flex flex-col gap-2 items-start'>
            <?php if ($heading) : ?>
            <h2 class="heading-two font-bold !text-left"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>

            <?php if ($subheading) : ?>
            <p class="text-xl font-medium !text-left"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
          </div>
          <div class='flex items-center gap-2'>
            <button
              @click="prev()"
              :disabled="!canGoPrev"
              :class="canGoPrev ? 'bg-white hover:bg-gray-100' : 'bg-gray-200 cursor-not-allowed'"
              class="rounded-full p-3 transition "
              aria-label="Previous slide"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                :class="canGoPrev ? 'text-black' : 'text-gray-400'"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 19l-7-7 7-7"
                />
              </svg>
            </button>

            <button
              @click="next()"
              :disabled="!canGoNext"
              :class="canGoNext ? 'bg-white hover:bg-gray-100' : 'bg-gray-200 cursor-not-allowed'"
              class="rounded-full p-3 transition "
              aria-label="Next slide"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                :class="canGoNext ? 'text-black' : 'text-gray-400'"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div class="relative w-full overflow-hidden section-padding !pt-0 ">
        <div
          class="carousel-track flex transition-transform duration-500 ease-in-out max-w-container mx-auto"
          :style="'transform: translateX(-' + (currentIndex * (slideWidthPercentage + gapPercentage)) + '%)'"
        >
          <?php foreach ($items as $item) : ?>
          <div
            class="carousel-slide flex-shrink-0"
            :style="{ 'width': slideWidthPercentage + '%', 'margin-right': gapPercentage + '%' }"
          >
            <div class="relative rounded-2xl overflow-hidden aspect-[10/11]">
              <?php if (!empty($item['image']['url'])) : ?>
              <div
                class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('<?php echo esc_url($item['image']['url']); ?>');"
              ></div>

              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

              <?php endif; ?>

              <div class="absolute bottom-0 left-0 right-0 p-4 lg:p-6 text-white">
                <div class='flex flex-col items-start'>
                  <?php if (!empty($item['title'])) : ?>
                  <h3 class="text-3xl font-bold uppercase tracking-wide">
                    <?php echo esc_html($item['title']); ?>
                  </h3>
                  <?php endif; ?>

                  <?php if (!empty($item['text'])) : ?>
                  <p class="text-lg leading-relaxed">
                    <?php echo esc_html($item['text']); ?>
                  </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
</section>
