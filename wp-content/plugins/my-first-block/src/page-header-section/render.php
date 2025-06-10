<?php
/**
 * Page Header Block - Render Template (Fixed Height, Full-Width Background)
 */

$heading = $attributes['heading'] ?? '';
$breadcrumbs = $attributes['breadcrumbs'] ?? [];
$image = $attributes['image'] ?? null;
?>

<section class='plugin-custom-block'>
  <section
    class="relative  h-[350px] md:h-[500px] lg:h-[600px] flex items-center not-prose justify-center bg-cover bg-center"
    style="<?php echo !empty($image['url']) ? 'background-image: url(' . esc_url($image['url']) . ');' : ''; ?>"
  >

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 text-center px-4 flex items-center justify-center w-full">
      <div class="flex flex-col items-center gap-2 max-w-screen-xl mx-auto">
        <?php if (!empty($heading)): ?>
        <h1 class="!my-0 !text-5xl md:!text-6xl lg:!text-7xl !font-[600] text-center !text-white !uppercase">
          <?php echo esc_html($heading); ?>
        </h1>
        <?php endif; ?>

        <?php if (!empty($breadcrumbs)): ?>
        <nav aria-label="Breadcrumb">
          <ol class="!p-0 !my-0 !flex justify-center flex-wrap items-center text-sm text-white gap-2">
            <?php foreach ($breadcrumbs as $i => $crumb): ?>
            <li class="flex items-center gap-1">
              <?php if (!empty($crumb['link'])): ?>
              <a
                href="<?php echo esc_url($crumb['link']); ?>"
                class="!text-white !text-base"
              >
                <?php echo esc_html($crumb['text']); ?>
              </a>
              <?php else: ?>
              <span class="!text-white !text-base"><?php echo esc_html($crumb['text']); ?></span>
              <?php endif; ?>
              <?php if ($i < count($breadcrumbs) - 1): ?>
              <span class="!text-white !text-base">/</span>
              <?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ol>
        </nav>
        <?php endif; ?>
      </div>
    </div>
  </section>
</section>
