<?php
$menu_items = get_query_var('menu_items');
$cta_text = get_theme_mod('boilerplate_cta_text', 'Get Started');
$cta_url = get_theme_mod('boilerplate_cta_url', '#');
$cta_phone = get_theme_mod('boilerplate_cta_phone', '#');

$menu_by_id = [];
foreach ($menu_items as $item) {
    $item->children = []; 
    $menu_by_id[$item->ID] = $item;
}

foreach ($menu_items as $item) {
    $parent_id = (int)$item->menu_item_parent;
    if ($parent_id && isset($menu_by_id[$parent_id])) {
        $menu_by_id[$parent_id]->children[] = $item;
    }
}

$tree = array_filter($menu_items, function($item) {
    return $item->menu_item_parent == 0;
});

function render_menu_node($node, $depth = 0) {
    $has_children = !empty($node->children);
    
    $text_sizes = ['text-lg', 'text-base', 'text-sm', 'text-xs'];
    $text_size = $text_sizes[min($depth, count($text_sizes) - 1)];
    
    $font_weights = ['font-medium', 'font-normal', 'font-light', 'font-extralight'];
    $font_weight = $font_weights[min($depth, count($font_weights) - 1)];
    ?>
<li class="w-full">
  <?php if ($has_children) : ?>
  <div
    x-data="{ expanded: false }"
    class="w-full"
  >
    <button
      @click="expanded = !expanded"
      class="flex flex-row gap-0.5 items-center justify-between w-full text-left py-2 transition-all ease-in-out duration-300 hover:text-brand-green-dark"
    >
      <span class="<?php echo $text_size; ?> <?php echo $font_weight; ?>"><?php echo esc_html($node->title); ?></span>
      <svg
        :class="{ 'rotate-180': expanded }"
        class="h-4 w-4 ease-in-out duration-300"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <polyline points="6 9 12 15 18 9"></polyline>
      </svg>
    </button>

    <div
      x-show="expanded"
      x-collapse
      class="ml-<?php echo min($depth + 2, 6); ?> mt-2 space-y-2"
    >
      <ul class="space-y-2 list-none">
        <?php foreach ($node->children as $child) : ?>
        <?php render_menu_node($child, $depth + 1); ?>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php else : ?>
  <a
    href="<?php echo esc_url($node->url); ?>"
    @click="slideOverOpen = false"
    class="block py-2 <?php echo $text_size; ?> <?php echo $font_weight; ?> hover:text-brand-green-dark transition-colors duration-200"
  >
    <?php echo esc_html($node->title); ?>
  </a>
  <?php endif; ?>
</li>
<?php
}
?>
<div
  x-data="{
      slideOverOpen: false
  }"
  class="relative z-50 w-auto h-auto "
>
  <button
    @click="slideOverOpen=true"
    class="btn btn-outline btn-lg lg:hidden flex"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="24"
      height="24"
      fill="currentColor"
      viewBox="0 0 256 256"
      class="mr-1"
    >
      <path
        d="M224,128a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128ZM40,72H216a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16ZM216,184H40a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16Z"
      ></path>
    </svg>
    Menu</button>
  <template x-teleport="body">
    <div
      x-show="slideOverOpen"
      @keydown.window.escape="slideOverOpen=false"
      class="relative z-[101]"
    >
      <div
        x-show="slideOverOpen"
        x-transition.opacity.duration.600ms
        @click="slideOverOpen = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-md bg-opacity-10"
      ></div>
      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
            <div
              x-show="slideOverOpen"
              @click.away="slideOverOpen = false"
              x-transition:enter="transform transition ease-in-out duration-300"
              x-transition:enter-start="translate-x-full"
              x-transition:enter-end="translate-x-0"
              x-transition:leave="transform transition ease-in-out duration-300"
              x-transition:leave-start="translate-x-0"
              x-transition:leave-end="translate-x-full"
              class="w-screen max-w-md"
            >
              <div
                class="flex flex-col h-full py-5 overflow-y-scroll bg-white border-l shadow-lg border-neutral-100/70">
                <div class="px-4 sm:px-5 flex flex-col gap-4 h-full">
                  <div class="flex items-start justify-between pb-1">
                    <h2 class="text-2xl">Menu</h2>
                    <div class="flex items-center h-auto ml-3">
                      <button
                        @click="slideOverOpen=false"
                        class="flex flex-row items-center gap-1 cursor-pointer bg-neutral-100 hover:bg-neutral-200 duration-300 ease-in-out py-1 px-3 rounded-md"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-4 h-4"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                          ></path>
                        </svg>
                        <span>Close</span>
                      </button>
                    </div>
                  </div>

                  <!-- Menu Items Section -->
                  <div class="relative flex-1">
                    <ul class="flex flex-col items-start gap-4 list-none">
                      <?php foreach ($tree as $node) : ?>
                      <?php render_menu_node($node); ?>
                      <?php endforeach; ?>
                    </ul>
                  </div>

                  <!-- CTA Buttons Section -->
                  <div class=" mt-auto">
                    <div class="flex flex-col gap-3">
                      <?php if ($cta_phone) : ?>
                      <a
                        href="tel:<?php echo esc_attr($cta_phone); ?>"
                        @click="slideOverOpen = false"
                        class="w-full"
                      >
                        <button class="btn btn-secondary btn-lg w-full">
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            fill="currentColor"
                            viewBox="0 0 256 256"
                            class="mr-2"
                          >
                            <path
                              d="M144.27,45.93a8,8,0,0,1,9.8-5.66,86.22,86.22,0,0,1,61.66,61.66,8,8,0,0,1-5.66,9.8A8.23,8.23,0,0,1,208,112a8,8,0,0,1-7.73-5.94,70.35,70.35,0,0,0-50.33-50.33A8,8,0,0,1,144.27,45.93Zm-2.33,41.8c13.79,3.68,22.65,12.54,26.33,26.33A8,8,0,0,0,176,120a8.23,8.23,0,0,0,2.07-.27,8,8,0,0,0,5.66-9.8c-5.12-19.16-18.5-32.54-37.66-37.66a8,8,0,1,0-4.13,15.46Zm81.94,95.35A56.26,56.26,0,0,1,168,232C88.6,232,24,167.4,24,88A56.26,56.26,0,0,1,72.92,32.12a16,16,0,0,1,16.62,9.52l21.12,47.15,0,.12A16,16,0,0,1,109.39,104c-.18.27-.37.52-.57.77L88,129.45c7.49,15.22,23.41,31,38.83,38.51l24.34-20.71a8.12,8.12,0,0,1,.75-.56,16,16,0,0,1,15.17-1.4l.13.06,47.11,21.11A16,16,0,0,1,223.88,183.08Zm-15.88-2s-.07,0-.11,0h0l-47-21.05-24.35,20.71a8.44,8.44,0,0,1-.74.56,16,16,0,0,1-15.75,1.14c-18.73-9.05-37.4-27.58-46.46-46.11a16,16,0,0,1,1-15.7,6.13,6.13,0,0,1,.57-.77L96,95.15l-21-47a.61.61,0,0,1,0-.12A40.2,40.2,0,0,0,40,88A128.14,128.14,0,0,0,168,216A40.21,40.21,0,0,0,208,181.07Z"
                            ></path>
                          </svg>
                          <?php echo esc_html($cta_phone); ?>
                        </button>
                      </a>
                      <?php endif; ?>
                      <a
                        href="<?php echo esc_url($cta_url); ?>"
                        @click="slideOverOpen = false"
                        class="w-full"
                      >
                        <button class="btn btn-primary btn-lg w-full">
                          <?php echo esc_html($cta_text); ?>
                        </button>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>
