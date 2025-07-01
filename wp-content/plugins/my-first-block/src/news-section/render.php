<?php
/**
 * Accordion Block - Alpine.js Version with Smooth Animations
 */

$sort_by = $attributes['sortBy'] ?? 'newest';

$order = 'DESC';
$order_by = 'date';

if ($sort_by === 'oldest') {
    $order = 'ASC';
} elseif ($sort_by === 'title') {
    $order_by = 'title';
    $order = 'ASC';
}

$query = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 6,
    'orderby' => $order_by,
    'order' => $order,
]);

?>

<section
  id="<?php echo esc_attr($section_id); ?>"
  class="plugin-custom-block not-prose section-padding w-full"
>
  <div class="max-w-container mx-auto">
    <?php if ($query->have_posts()) : ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php while ($query->have_posts()) : $query->the_post(); ?>
      <article class="bg-white p-4 rounded-lg  hover:shadow-lg transition-shadow duration-300 flex flex-col gap-8">

        <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail('large', [
              'class' => 'w-full aspect-[3/2] object-cover object-center rounded-md overflow-hidden',
          ]); ?>
        </a>

        <?php else: ?>
        <a href="<?php the_permalink(); ?>">
          <div
            class="w-full aspect-[3/2] flex items-center justify-center  rounded-md overflow-hidden bg-brand-light-grey h-full"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="32"
              height="32"
              fill="#000000"
              class="opacity-50"
              viewBox="0 0 256 256"
            >
              <path
                d="M216,40H72A16,16,0,0,0,56,56V72H40A16,16,0,0,0,24,88V200a16,16,0,0,0,16,16H184a16,16,0,0,0,16-16V184h16a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40ZM72,56H216v62.75l-10.07-10.06a16,16,0,0,0-22.63,0l-20,20-44-44a16,16,0,0,0-22.62,0L72,109.37ZM184,200H40V88H56v80a16,16,0,0,0,16,16H184Zm32-32H72V132l36-36,49.66,49.66a8,8,0,0,0,11.31,0L194.63,120,216,141.38V168ZM160,84a12,12,0,1,1,12,12A12,12,0,0,1,160,84Z"
              ></path>
            </svg>
          </div>
        </a>
        <?php endif; ?>

        <div class="flex flex-col items-start justify-between w-full h-full gap-6">
          <div class="w-full flex flex-col gap-1">
            <h2 class="text-2xl sm:text-3xl">
              <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
              </a>
            </h2>
            <p class="text-neutral-600 text-sm">
              <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
            </p>
          </div>

          <div
            class="flex flex-row items-center justify-between gap-1 w-full border-t border-dashed border-neutral-300 pt-4"
          >
            <p class=" text-neutral-600 text-sm">
              Published on <?php echo get_the_date(); ?>
            </p>

            <a href="<?php the_permalink(); ?>">
              <button class="flex flex-row items-center w-fit gap-1 cursor-pointer relative pb-0.5 mx-auto">
                <div class="flex items-center  text-sm text-neutral-600 gap-1 pb-0.5">
                  <span>Read More</span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 256 256"
                    class="group-hover:translate-x-1 transition-transform duration-300 ease-in-out"
                  >
                    <path
                      d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"
                    ></path>
                  </svg>
                </div>
              </button>
            </a>
          </div>
        </div>
      </article>
      <?php endwhile; ?>
    </div>
    <?php wp_reset_postdata(); ?>
    <?php else : ?>
    <p class="text-gray-600">No news found.</p>
    <?php endif; ?>
  </div>
</section>
