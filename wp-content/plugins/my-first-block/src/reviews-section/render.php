<?php
/**
 * Reviews Section Block - Drag on text slider + image animations + animated arrow
 */

$topHeading = $attributes['topHeading'] ?? '';
$reviews = $attributes['reviews'] ?? [];
$totalSlides = count($reviews);

?>

<section
  class="plugin-custom-block not-prose section-padding w-full"
  x-data="{
      currentIndex: 0,
      totalSlides: <?php echo $totalSlides; ?>,
      startX: 0,
      dragging: false,
  
      goToSlide(index) {
          if (index < 0) index = 0;
          if (index >= this.totalSlides) index = this.totalSlides - 1;
          this.currentIndex = index;
          this.$nextTick(() => this.triggerAnimations());
      },
  
      triggerAnimations() {
          const leftImage = this.$refs[`left-${this.currentIndex}`];
          const rightImage = this.$refs[`right-${this.currentIndex}`];
          if (leftImage) {
              leftImage.classList.remove('translate-x-0', 'translate-y-2', 'scale-90', 'rotate-0');
              leftImage.classList.add('animate-slide-center-left');
          }
          if (rightImage) {
              rightImage.classList.remove('translate-x-0', 'translate-y-2', 'scale-90', 'rotate-0');
              rightImage.classList.add('animate-slide-center-right');
          }
      },
  
      onDragStart(event) {
          this.dragging = true;
          this.startX = event.type.includes('touch') ? event.touches[0].clientX : event.clientX;
      },
  
      onDragMove(event) {
          if (!this.dragging) return;
          // Optional: you can implement dragging feedback here if needed
      },
  
      onDragEnd(event) {
          if (!this.dragging) return;
          this.dragging = false;
  
          let endX = event.type.includes('touch') ? (event.changedTouches[0]?.clientX ?? this.startX) : event.clientX;
          let deltaX = endX - this.startX;
          let threshold = 50; // px threshold to change slide
  
          if (deltaX < -threshold && this.currentIndex < this.totalSlides - 1) {
              this.goToSlide(this.currentIndex + 1);
          } else if (deltaX > threshold && this.currentIndex > 0) {
              this.goToSlide(this.currentIndex - 1);
          } else {
              this.goToSlide(this.currentIndex); // snap back if no slide change
          }
      },
  
      init() {
          this.$nextTick(() => this.triggerAnimations());
      }
  }"
>
  <div
    class="max-w-container mx-auto flex flex-col lg:grid grid-cols-2 xl:grid-cols-5 items-center justify-center gap-20 lg:gap-10"
  >
    <div class="relative  w-full flex items-center justify-center col-span-1 xl:col-span-3">
      <?php foreach ($reviews as $i => $review): ?>
      <?php
      $image1 = $review['image1'] ?? null;
      $image2 = $review['image2'] ?? null;
      
      $image1_url = $image1['url'] ?? '';
      $image1_alt = $image1['alt'] ?? '';
      $image2_url = $image2['url'] ?? '';
      $image2_alt = $image2['alt'] ?? '';
      
      // Image 1
      $image1_id = null;
      $image1_alt = '';
      
      if ($image1) {
          if (is_array($image1)) {
              $image1_id = $image1['id'] ?? ($image1['ID'] ?? null);
              $image1_alt = $image1['alt'] ?? '';
          } elseif (is_numeric($image1)) {
              $image1_id = $image1;
              $image1_alt = get_post_meta($image1, '_wp_attachment_image_alt', true);
          }
      }
      
      // Image 2
      $image2_id = null;
      $image2_alt = '';
      
      if ($image2) {
          if (is_array($image2)) {
              $image2_id = $image2['id'] ?? ($image2['ID'] ?? null);
              $image2_alt = $image2['alt'] ?? '';
          } elseif (is_numeric($image2)) {
              $image2_id = $image2;
              $image2_alt = get_post_meta($image2, '_wp_attachment_image_alt', true);
          }
      }
      
      ?>
      <?php if (!empty($review['image1']['url'])): ?>
      <div
        x-ref="left-<?php echo $i; ?>"
        x-show="currentIndex === <?php echo $i; ?>"
        class="max-w-[400px] w-full rotate-5 relative left-10 shadow-lg"
      >

        <?php
        echo wp_get_attachment_image($image1_id, 'large', false, [
            'class' => 'aspect-[3/4] w-full h-full object-cover',
            'loading' => 'lazy',
            'decoding' => 'async',
            'fetchpriority' => 'high',
            'alt' => $image1_alt ?? 'Review image 1',
        ]);
        ?>
      </div>
      <?php endif; ?>

      <?php if (!empty($review['image2']['url'])): ?>
      

      <div
        x-ref="right-<?php echo $i; ?>"
        x-show="currentIndex === <?php echo $i; ?>"
        class="max-w-[400px] w-full rotate-[-10deg] shadow-lg relative right-10"
      >
        <?php
        echo wp_get_attachment_image($image2_id, 'large', false, [
            'class' => 'aspect-[3/4]  w-full h-full object-cover',
            'loading' => 'lazy',
            'decoding' => 'async',
            'fetchpriority' => 'auto',
            'alt' => $image2_alt ?? 'Review image 2',
        ]);
        ?>
      </div>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="flex flex-col gap-2 col-span-1 xl:col-span-2 ">
      <?php if ($topHeading): ?>
      <h2 class="heading-two text-center">
        <?php echo esc_html($topHeading); ?>
      </h2>
      <?php endif; ?>
      <div
        class="w-full overflow-hidden !cursor-grab relative"
        @mousedown="onDragStart"
        @mouseup="onDragEnd"
        @touchstart="onDragStart"
        @touchend="onDragEnd"
        @mouseleave="dragging && onDragEnd($event)"
        style="touch-action: pan-y;"
      >
        <div
          class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full flex flex-row items-center justify-between z-[100]"
        >
          <div
            class="p-2 rounded-full bg-white hover:bg-brand-green hover:text-white duration-300 transition-all ease-in-out shadow-lg cursor-pointer"
            @click="goToSlide(currentIndex - 1)"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              fill="currentColor"
              viewBox="0 0 256 256"
            >
              <path
                d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"
              ></path>
            </svg>
          </div>
          <div
            class="p-2 rounded-full bg-white hover:bg-brand-green hover:text-white duration-300 transition-all ease-in-out shadow-lg cursor-pointer"
            @click="goToSlide(currentIndex + 1)"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              fill="currentColor"
              viewBox="0 0 256 256"
            >
              <path
                d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"
              ></path>
            </svg>
          </div>
        </div>
        <div
          class="flex transition-transform duration-500 ease-in-out select-none  items-center "
          :style="'transform: translateX(-' + (currentIndex * 100) + '%)'"
          x-ref="slider"
        >

          <?php foreach ($reviews as $i => $review): ?>
          <div class="w-full flex-shrink-0 flex flex-col  text-center gap-6 ">
            <p class="text-xl lg:text-2xl leading-relaxed max-w-[80%] mx-auto">
              <?php echo esc_html($review['text'] ?? ''); ?>
            </p>

            <div class="flex flex-col items-center gap-2">
              <div class="flex justify-center gap-0">
                <?php
                  $stars = intval($review['stars'] ?? 0);
                  for ($s = 1; $s <= 5; $s++): ?>
                <svg
                  class=" <?php echo $s <= $stars ? 'text-[#EDBB4F]' : 'text-gray-300'; ?>"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  width="22"
                  height="22"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.067 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.955z"
                  />
                </svg>
                <?php endfor; ?>
              </div>
              <p class="text-neutral-600 capitalize">
                <?php echo esc_html($review['name'] ?? ''); ?>
              </p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="flex flex-row items-center justify-center gap-2">
        <div class="flex justify-center gap-1">
          <?php foreach ($reviews as $i => $_): ?>
          <button
            class="h-3 w-3 rounded-full transition-colors !cursor-pointer"
            :class="currentIndex === <?php echo $i; ?> ? 'bg-brand-green-dark' : 'border border-brand-green-dark'"
            @click="goToSlide(<?php echo $i; ?>)"
            aria-label="Go to review <?php echo $i + 1; ?>"
          ></button>
          <?php endforeach; ?>
        </div>
        <div class="text-sm font-medium">
          <span x-text="currentIndex + 1"></span> / <span x-text="totalSlides"></span>
        </div>
      </div>
    </div>


  </div>
</section>
