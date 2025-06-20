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
          gapPercentage: 1.5, // 1.5% of container width for gap
          startX: 0,
          currentX: 0,
          dragOffset: 0,
          isDragging: false,
          hasDragged: false, // Track if user has dragged
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
      
              // Bind drag handlers
              this.boundHandleDragMove = this.handleDragMove.bind(this);
              this.boundHandleDragEnd = this.handleDragEnd.bind(this);
      
              // Get initial container width
              this.updateContainerWidth();
          },
      
          updateContainerWidth() {
              this.containerWidth = this.$refs.container.clientWidth;
          },
      
          updateSlidesPerView() {
              if (window.innerWidth >= 1200) { // lg+ (3 slides)
                  this.slidesPerView = 3;
              } else if (window.innerWidth >= 640) { // sm+ (2 slides)
                  this.slidesPerView = 2;
              } else { // sm- (1 slide)
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
          },
      
          // Improved drag functionality
          handleDragStart(event) {
              this.isDragging = true;
              this.hasDragged = false; // Reset drag flag
              this.startX = event.type.includes('touch') ? event.touches[0].clientX : event.clientX;
              this.currentX = this.startX;
              this.dragOffset = 0;
      
              // Update container width for accurate calculations
              this.updateContainerWidth();
      
              // Store the initial transform position
              this.initialTransform = this.currentIndex * (this.slideWidthPercentage + this.gapPercentage);
      
              // Attach window event listeners
              window.addEventListener('mousemove', this.boundHandleDragMove, { passive: false });
              window.addEventListener('touchmove', this.boundHandleDragMove, { passive: false });
              window.addEventListener('mouseup', this.boundHandleDragEnd);
              window.addEventListener('touchend', this.boundHandleDragEnd);
      
              // Prevent text selection during drag
              event.preventDefault();
          },
      
          handleDragMove(event) {
              if (!this.isDragging) return;
      
              // Prevent scrolling on touch devices
              event.preventDefault();
      
              this.currentX = event.type.includes('touch') ? event.touches[0].clientX : event.clientX;
              this.dragOffset = this.currentX - this.startX;
      
              // Mark as dragged if user has moved more than 5px
              if (Math.abs(this.dragOffset) > 5) {
                  this.hasDragged = true;
              }
          },
      
          handleDragEnd() {
              if (!this.isDragging) return;
      
              // Remove window event listeners
              window.removeEventListener('mousemove', this.boundHandleDragMove);
              window.removeEventListener('touchmove', this.boundHandleDragMove);
              window.removeEventListener('mouseup', this.boundHandleDragEnd);
              window.removeEventListener('touchend', this.boundHandleDragEnd);
      
              this.isDragging = false;
      
              // Determine if we should change slide based on drag distance and velocity
              const threshold = this.containerWidth * 0.15; // 15% threshold
              const dragDistance = Math.abs(this.dragOffset);
      
              if (dragDistance > threshold) {
                  if (this.dragOffset > 0 && this.canGoPrev) {
                      this.prev();
                  } else if (this.dragOffset < 0 && this.canGoNext) {
                      this.next();
                  }
              }
      
              // Reset drag offset
              this.dragOffset = 0;
      
              // Reset hasDragged after a short delay to allow click event to be prevented
              setTimeout(() => {
                  this.hasDragged = false;
              }, 10);
          },
      
          // Handle click events on links
          handleLinkClick(event, link) {
              // Prevent navigation if user has dragged
              if (this.hasDragged) {
                  event.preventDefault();
                  return false;
              }
      
              // Allow normal navigation if no drag occurred
              if (link && link !== '#') {
                  window.location.href = link;
              }
          },
      
          // Calculate track transform with smooth drag offset
          get trackTransform() {
              if (this.isDragging && this.containerWidth > 0) {
                  // Convert drag offset to percentage of container width
                  const dragPercentage = (this.dragOffset / this.containerWidth) * 100;
                  const baseTransform = this.initialTransform;
      
                  // Apply resistance at boundaries
                  let finalDragPercentage = dragPercentage;
                  const maxIndex = Math.max(0, this.totalSlides - this.slidesPerView);
      
                  // Resistance when trying to go before first slide
                  if (this.currentIndex === 0 && dragPercentage > 0) {
                      finalDragPercentage = dragPercentage * 0.3; // 30% resistance
                  }
                  // Resistance when trying to go after last slide
                  else if (this.currentIndex === maxIndex && dragPercentage < 0) {
                      finalDragPercentage = dragPercentage * 0.3; // 30% resistance
                  }
      
                  return `translateX(${-baseTransform + finalDragPercentage}%)`;
              } else {
                  // Normal transform when not dragging
                  const baseTransform = this.currentIndex * (this.slideWidthPercentage + this.gapPercentage);
                  return `translateX(-${baseTransform}%)`;
              }
          }
      }"
      class="relative w-full overflow-hidden flex flex-col gap-10 lg:gap-16"
      x-ref="container"
    >
      <div class='section-padding pb-0'>
        <div class='flex items-end justify-between flex-wrap gap-5  max-w-container'>
          <div class='flex flex-col gap-2 items-start lg:max-w-4xl'>
            <?php if ($heading) : ?>
            <h2 class="heading-two !text-left"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>

            <?php if ($subheading) : ?>
            <p class="text-xl !text-left text-neutral-500"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
          </div>
          <div class='flex items-center gap-2'>
            <button
              @click="prev()"
              :disabled="!canGoPrev"
              :class="canGoPrev ? 'opacity-100 cursor-pointer hover:bg-white duration-300 transition-all ease-in-out' :
                  'opacity-50 cursor-not-allowed'"
              class="rounded-full p-3 border border-dashed "
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
              class="rounded-full p-3 border border-dashed "
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

      <div class="relative w-full overflow-hidden section-padding !pt-0 ">
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
            <div
              class="relative rounded-2xl overflow-hidden aspect-[5/7] group cursor-pointer"
              @click="handleLinkClick($event, '<?php echo esc_js($item_link); ?>')"
            >
              <?php if (!empty($item['image']['url'])) : ?>
              <div
                class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-500 group-hover:scale-105"
                style="background-image: url('<?php echo esc_url($item['image']['url']); ?>');"
              ></div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

              <?php endif; ?>
              <div class="absolute bottom-0 left-0 right-0 p-8  text-white  h-auto">
                <div class='flex flex-col items-start'>
                  <?php if (!empty($item['title'])) : ?>
                  <h3 class="!text-3xl md:!text-4xl font-bold uppercase tracking-wide !text-left">
                    <?php echo esc_html($item['title']); ?>
                  </h3>
                  <?php endif; ?>

                  <?php if (!empty($item['text'])) : ?>
                  <p class="text-lg sm:text-xl  !text-left">
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
