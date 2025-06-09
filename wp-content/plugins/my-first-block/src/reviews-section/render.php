<?php
/**
 * Reviews Section Block - Render Template with Carousel and Animations
 */

$topHeading = $attributes['topHeading'] ?? '';
$reviews = $attributes['reviews'] ?? [];
?>

<section class="plugin-custom-block not-prose section-padding w-full ">
  <div class="max-w-container mx-auto grid lg:grid-cols-2  !gap-10 items-center justify-center ">
    <div
      class="relative"
      id="review-images-container"
    >
      <?php foreach ($reviews as $i => $review): ?>
      <div
        class="review-images <?php echo $i === 0 ? 'active' : ''; ?>"
        data-index="<?php echo $i; ?>"
      >
        <div class="flex justify-center items-center relative h-full">
          <?php if (!empty($review['image1']['url'])): ?>
          <div
            class="image-left w-full aspect-[3/4] max-w-[170px] sm:max-w-[200px] md:max-w-[350px] rounded-lg shadow-lg bg-white p-1 absolute"
          >
            <img
              src="<?php echo esc_url($review['image1']['url']); ?>"
              alt="Review image 1"
              class="w-full h-full object-cover"
            />
          </div>
          <?php endif; ?>

          <?php if (!empty($review['image2']['url'])): ?>
          <div
            class="image-right w-full aspect-[3/4] max-w-[170px] sm:max-w-[200px] md:max-w-[350px] rounded-lg shadow-lg bg-white p-1 absolute z-10"
          >
            <img
              src="<?php echo esc_url($review['image2']['url']); ?>"
              alt="Review image 2"
              class="w-full h-full object-cover"
            />
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Review text content - slides -->
    <div class="w-full flex flex-col items-center">
      <?php if ($topHeading): ?>
      <p class="decorative-text !text-brand-yellow-dark text-center text-3xl lg:!text-4xl mb-4">
        <?php echo esc_html($topHeading); ?>
      </p>
      <?php endif; ?>

      <div class="review-carousel-container relative w-full">
        <div
          class="review-slides-wrapper"
          id="review-slides-wrapper"
        >
          <?php foreach ($reviews as $i => $review): ?>
          <div
            class="review-slide"
            data-index="<?php echo $i; ?>"
          >
            <div class="text-center px-4">
              <p class="text-2xl leading-relaxed text-brand-green-dark mb-4">
                <?php echo esc_html($review['text'] ?? ''); ?>
              </p>
              <div class="flex justify-center gap-1 mb-2">
                <?php
                  $stars = intval($review['stars'] ?? 0);
                  for ($s = 1; $s <= 5; $s++): ?>
                <svg
                  class="w-6 h-6 <?php echo $s <= $stars ? 'text-[#EDBB4F]' : 'text-gray-300'; ?>"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.067 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.955z"
                  />
                </svg>
                <?php endfor; ?>
              </div>
              <p class="text-sm text-gray-600 font-medium capitalize">
                <?php echo esc_html($review['name'] ?? ''); ?>
              </p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Pagination Dots -->
      <div class="flex justify-center gap-2 mt-6">
        <?php foreach ($reviews as $i => $_): ?>
        <button
          class="h-2.5 w-2.5 rounded-full transition-colors dot <?php echo $i === 0 ? 'bg-black' : 'bg-white border border-black'; ?>"
          data-dot="<?php echo $i; ?>"
          aria-label="Go to review <?php echo $i + 1; ?>"
        ></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const reviewSlidesWrapper = document.getElementById('review-slides-wrapper');
      const images = document.querySelectorAll('.review-images');
      const dots = document.querySelectorAll('.dot');
      const totalSlides = <?php echo count($reviews); ?>;
      let currentIndex = 0;

      function triggerImageAnimations(imageContainer) {
        // Reset all animations first - both images return to center
        const leftImage = imageContainer.querySelector('.image-left');
        const rightImage = imageContainer.querySelector('.image-right');

        if (leftImage) {
          leftImage.classList.remove('animate-slide-center-left', 'final-position');
          leftImage.classList.add('animate-reset');
        }
        if (rightImage) {
          rightImage.classList.remove('animate-slide-center-right', 'final-position');
          rightImage.classList.add('animate-reset');
        }

        setTimeout(() => {
          if (leftImage) {
            leftImage.classList.remove('animate-reset');
            leftImage.classList.add('animate-slide-center-left');
          }
          if (rightImage) {
            rightImage.classList.remove('animate-reset');
            rightImage.classList.add('animate-slide-center-right');
          }
        }, 100);

        setTimeout(() => {
          if (leftImage) {
            leftImage.classList.add('final-position');
          }
          if (rightImage) {
            rightImage.classList.add('final-position');
          }
        }, 900);
      }

      function showSlide(index) {
        currentIndex = index;

        // Slide the text content
        const translateX = -(index * (100 / totalSlides));
        reviewSlidesWrapper.style.transform = `translateX(${translateX}%)`;

        // Show/hide images and trigger animations
        images.forEach((img, i) => {
          if (i === index) {
            img.classList.add('active');
          } else {
            img.classList.remove('active');
          }
        });

        // Trigger image animations for the active image
        const activeImage = images[index];
        if (activeImage) {
          triggerImageAnimations(activeImage);
        }

        // Update dots
        dots.forEach((dot, i) => {
          if (i === index) {
            dot.classList.remove('bg-white', 'border', 'border-black');
            dot.classList.add('bg-black');
          } else {
            dot.classList.remove('bg-black');
            dot.classList.add('bg-white', 'border', 'border-black');
          }
        });
      }

      // Initialize first slide animations
      const firstActiveImage = images[0];
      if (firstActiveImage) {
        setTimeout(() => {
          triggerImageAnimations(firstActiveImage);
        }, 300);
      }

      dots.forEach(dot => {
        dot.addEventListener('click', () => {
          const index = parseInt(dot.getAttribute('data-dot'));

          if (index !== currentIndex) {
            showSlide(index);
          }
        });
      });
    });
  </script>
</section>
