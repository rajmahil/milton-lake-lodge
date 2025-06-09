<?php
$menu_items = get_query_var('menu_items');
$menu_tree = get_query_var('menu_tree');
$parent_items = get_query_var('parent_items');
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
                <div class="px-4 sm:px-5 flex flex-col gap-4">
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


                  <div class="relative">

                    <ul class="flex flex-col items-start gap-4 list-none  ">
                      <?php
                      $menu_tree = [];
                      $parent_items = [];
                      
                      foreach ($menu_items as $item) {
                          if ($item->menu_item_parent == 0) {
                              $parent_items[] = $item;
                              $menu_tree[$item->ID] = [
                                  'item' => $item,
                                  'children' => [],
                              ];
                          } else {
                              if (isset($menu_tree[$item->menu_item_parent])) {
                                  $menu_tree[$item->menu_item_parent]['children'][] = $item;
                              }
                          }
                      }
                      
                      ?>
                      <?php foreach ($parent_items as $index => $parent_item) : ?>
                      <?php
                      $has_children = !empty($menu_tree[$parent_item->ID]['children']);
                      $menu_key = $parent_item->post_name; // or any dynamic value you want
                      ?>
                      <li>
                        <button
                          :class="{
                              'bg-white/15 rounded-md ': navigationMenu ==
                                  '<?php echo esc_js($menu_key); ?>',
                          }"
                          class="flex flex-row gap-0.5 items-center transition-all ease-in-out duration-300"
                          @mouseover="navigationMenuOpen = true; navigationMenuReposition($el); navigationMenu = '<?php echo esc_js($menu_key); ?>'"
                          @mouseleave="navigationMenuLeave()"
                        >
                          <?php echo esc_html($parent_item->title); ?>
                          <?php if ($has_children) : ?>
                          <svg
                            :class="{ '-rotate-180': navigationMenuOpen == true && navigationMenu == '<?php echo esc_js($menu_key); ?>' }"
                            class="relative top-[1px] ml-1 h-3 w-3 ease-in-out duration-300"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                          >
                            <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                          <?php endif; ?>
                        </button>
                      </li>
                      <?php endforeach; ?>
                    </ul>
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
