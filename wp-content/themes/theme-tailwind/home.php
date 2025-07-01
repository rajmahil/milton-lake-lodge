<?php get_header(); ?>

<div class="max-w-container mx-auto py-12">
  <h1 class="text-3xl font-bold mb-8">Blog</h1>

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <div class="mb-6">
    <h2 class="text-xl font-semibold">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
    <p class="text-gray-600"><?php the_excerpt(); ?></p>
  </div>
  <?php endwhile; else : ?>
  <p>No posts found.</p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
