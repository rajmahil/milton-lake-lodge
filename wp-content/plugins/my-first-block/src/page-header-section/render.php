<?php
/**
 * Page Header Block - Render Template (Fixed Height, Full-Width Background)
 */

$heading = $attributes['heading'] ?? '';
$navigation = $attributes['navigation'] ?? [];
$breadcrumbs = $attributes['breadcrumbs'] ?? [];
$image = $attributes['image'] ?? null;

$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';

?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block"
>
  <section
    class="relative  h-[350px] md:h-[500px] lg:h-[600px] flex items-center not-prose justify-center bg-cover bg-center"
    style="<?php echo !empty($image['url']) ? 'background-image: url(' . esc_url($image['url']) . ');' : ''; ?>"
  >

    <div class="absolute inset-0 bg-brand-green-dark/60 backdrop-blur-[2px]"></div>

    <div class="relative z-10 text-center px-4 flex items-center justify-center w-full">
      <div class="flex flex-col items-center gap-4 max-w-screen-xl mx-auto !pt-14 lg:!pt-5">
        <?php if (!empty($heading)): ?>
        <h1 class="!text-5xl sm:!text-6xl md:!text-7xl lg:!text-8xl !font-[600] text-center !text-white !uppercase">
          <?php echo esc_html($heading); ?>
        </h1>
        <?php endif; ?>

        <?php if (!empty($navigation)): ?>
        <nav
          aria-label="page-navigation"
          class="400:block hidden"
        >
          <ol
            class="flex flex-row items-center justify-center w-fit bg-brand-light-grey/60 backdrop-blur-md p-1 rounded-full"
          >
            <?php foreach ($navigation as $i => $item): ?>
            <li class="flex items-center gap-1">
              <?php if (!empty($item['link'])): ?>
              <a
                href="<?php echo esc_url($item['link']); ?>"
                class=" !text-base btn btn-light btn-sm hover:!bg-brand-green-dark hover:text-white transition-all duration-200 ease-in-out"
              >
                <?php echo esc_html($item['text']); ?>
              </a>
              <?php else: ?>
              <span class="!text-base"><?php echo esc_html($item['text']); ?></span>
              <?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ol>
        </nav>
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
