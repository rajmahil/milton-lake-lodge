<?php
/**
 * Carousel Section Block - Render Template (Alpine.js Version)
 * Responsive: 3 slides on md+, 2 on sm+, 1 on sm-
 * Fixed smooth dragging implementation with click prevention
 */

$heading = $attributes['heading'] ?? '';
$subheading = $attributes['subheading'] ?? '';
$items = $attributes['items'] ?? [];
$total_items = count($items);
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';
?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block not-prose w-full"
>
  <div class="text-center flex flex-col gap-16">
    <?php if (!empty($items)) : ?>
    <div
      x-data="{
          currentIndex: 0,
          totalSlides: <?php echo $total_items; ?>,
          slidesPerView: 1,
          slideWidthPercentage: 100,
          gapPercentage: 1.5,
          startX: 0,
          currentX: 0,
          dragOffset: 0,
          isDragging: false,
          hasDragged: false,
          containerWidth: 0,
          boundHandleDragMove: null,
          boundHandleDragEnd: null,
          initialTransform: 0,
      
          init() {
              this.updateSlidesPerView();
              window.addEventListener('resize', () => {
                  this.updateSlidesPerView();
                  this.updateContainerWidth();
              });
      
              this.boundHandleDragMove = this.handleDragMove.bind(this);
              this.boundHandleDragEnd = this.handleDragEnd.bind(this);
              this.updateContainerWidth();
          },
      
          updateContainerWidth() {
              this.containerWidth = this.$refs.container.clientWidth;
          },
      
          updateSlidesPerView() {
              if (window.innerWidth >= 1200) {
                  this.slidesPerView = 3;
              } else if (window.innerWidth >= 640) {
                  this.slidesPerView = 2;
              } else {
                  this.slidesPerView = 1;
              }
      
              this.slideWidthPercentage = (100 - (this.slidesPerView - 1) * this.gapPercentage) / this.slidesPerView;
      
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
          },
      
          handleDragStart(event) {
              const target = event.target;
              if (target.closest('a')) {
                  return;
              }
      
              this.isDragging = true;
              this.hasDragged = false;
              this.startX = event.type.includes('touch') ? event.touches[0].clientX : event.clientX;
              this.currentX = this.startX;
              this.dragOffset = 0;
              this.updateContainerWidth();
              this.initialTransform = this.currentIndex * (this.slideWidthPercentage + this.gapPercentage);
      
              window.addEventListener('mousemove', this.boundHandleDragMove, { passive: false });
              window.addEventListener('touchmove', this.boundHandleDragMove, { passive: false });
              window.addEventListener('mouseup', this.boundHandleDragEnd);
              window.addEventListener('touchend', this.boundHandleDragEnd);
      
              event.preventDefault();
          },
      
          handleDragMove(event) {
              if (!this.isDragging) return;
      
              event.preventDefault();
              this.currentX = event.type.includes('touch') ? event.touches[0].clientX : event.clientX;
              this.dragOffset = this.currentX - this.startX;
      
              if (Math.abs(this.dragOffset) > 5) {
                  this.hasDragged = true;
              }
          },
      
          handleDragEnd() {
              if (!this.isDragging) return;
      
              window.removeEventListener('mousemove', this.boundHandleDragMove);
              window.removeEventListener('touchmove', this.boundHandleDragMove);
              window.removeEventListener('mouseup', this.boundHandleDragEnd);
              window.removeEventListener('touchend', this.boundHandleDragEnd);
      
              this.isDragging = false;
      
              const threshold = this.containerWidth * 0.15;
              const dragDistance = Math.abs(this.dragOffset);
      
              if (dragDistance > threshold) {
                  if (this.dragOffset > 0 && this.canGoPrev) {
                      this.prev();
                  } else if (this.dragOffset < 0 && this.canGoNext) {
                      this.next();
                  }
              }
      
              this.dragOffset = 0;
              setTimeout(() => {
                  this.hasDragged = false;
              }, 10);
          },
      
          handleLinkClick(event, link) {
              if (this.hasDragged) {
                  event.preventDefault();
                  return false;
              }
      
              if (link && link !== '#') {
                  window.location.href = link;
              }
          },
      
          get trackTransform() {
              if (this.isDragging && this.containerWidth > 0) {
                  const dragPercentage = (this.dragOffset / this.containerWidth) * 100;
                  const baseTransform = this.initialTransform;
                  let finalDragPercentage = dragPercentage;
                  const maxIndex = Math.max(0, this.totalSlides - this.slidesPerView);
      
                  if (this.currentIndex === 0 && dragPercentage > 0) {
                      finalDragPercentage = dragPercentage * 0.3;
                  } else if (this.currentIndex === maxIndex && dragPercentage < 0) {
                      finalDragPercentage = dragPercentage * 0.3;
                  }
      
                  return `translateX(${-baseTransform + finalDragPercentage}%)`;
              } else {
                  const baseTransform = this.currentIndex * (this.slideWidthPercentage + this.gapPercentage);
                  return `translateX(-${baseTransform}%)`;
              }
          }
      }"
      class="relative w-full overflow-hidden flex flex-col gap-10 lg:gap-16"
      x-ref="container"
    >
      <div class='section-padding pb-0'>
        <div class='flex items-end justify-between flex-wrap gap-5 max-w-container'>
          <div class='flex flex-col gap-2 items-start lg:max-w-4xl'>
            <?php if ($heading) : ?>
            <h2 class="heading-two !text-left"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
            <p class="text-xl !text-left text-neutral-600"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
          </div>
          <div class='flex items-center gap-2'>
            <button
              @click="prev()"
              :disabled="!canGoPrev"
              :class="canGoPrev ? 'opacity-100 cursor-pointer hover:bg-white duration-300 transition-all ease-in-out' :
                  'opacity-50 cursor-not-allowed'"
              class="rounded-full p-3 border border-dashed"
              aria-label="Previous slide"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
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
              :class="canGoNext ? 'opacity-100 cursor-pointer hover:bg-white duration-300 transition-all ease-in-out' :
                  'opacity-50 cursor-not-allowed'"
              class="rounded-full p-3 border border-dashed"
              aria-label="Next slide"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
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

      <div class="relative w-full overflow-hidden section-padding !pt-0">
        <div
          class="carousel-track flex ease-in-out max-w-container mx-auto cursor-grab select-none"
          :class="{
              'transition-none cursor-grabbing': isDragging,
              'cursor-grab transition-transform duration-500': !isDragging
          }"
          :style="'transform: ' + trackTransform"
          @mousedown="handleDragStart"
          @touchstart="handleDragStart"
        >
          <?php foreach ($items as $item) : ?>
          <?php $item_link = !empty($item['link']) ? $item['link'] : '#'; ?>
          <div
            class="carousel-slide flex-shrink-0"
            :style="{ 'width': slideWidthPercentage + '%', 'margin-right': gapPercentage + '%' }"
          >
            <div class="relative rounded-2xl overflow-hidden aspect-[5/7] ">
              <div
                class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-500 "
                style="background-image: url('<?php echo esc_url($item['image']['url']); ?>');"
              ></div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              <div
                class="absolute bottom-0 left-0 right-0 px-5 py-8 text-white h-auto flex flex-col items-start justify-start gap-4"
              >
                <div class='flex flex-col items-start'>
                  <?php if (!empty($item['title'])) : ?>
                  <h3 class="!text-4xl font-bold uppercase tracking-wide !text-left">
                    <?php echo esc_html($item['title']); ?>
                  </h3>
                  <?php endif; ?>
                  <?php if (!empty($item['text'])) : ?>
                  <p class="text-lg sm:text-xl !text-left leading-tight">
                    <?php echo esc_html($item['text']); ?>
                  </p>
                  <?php endif; ?>
                </div>
                <?php if (!empty($item_link) && $item_link !== '#') : ?>
                <a
                  href="<?php echo esc_url($item_link); ?>"
                  class="flex flex-row items-center gap-2 text-white group"
                  @click="handleLinkClick($event, '<?php echo esc_url($item_link); ?>')"
                >
                  <span>Learn More</span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    fill="currentColor"
                    viewBox="0 0 256 256"
                    class="group-hover:ml-2 transition-all duration-300 ease-in-out"
                  >
                    <path
                      d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"
                    ></path>
                  </svg>
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
