<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1"
  >
  <?php wp_head(); ?>
</head>

<body
  <?php body_class(); ?>
  <?php
  // Get menu items from the menu named "Main Menu"
  $menu = wp_get_nav_menu_object('Main Menu');
  $menu_items = [];
  
  if ($menu) {
      $menu_items = wp_get_nav_menu_items($menu->term_id);
  }
  
  $cta_text = get_theme_mod('boilerplate_cta_text', 'Get Started');
  $cta_url = get_theme_mod('boilerplate_cta_url', '#');
  $cta_phone = get_theme_mod('boilerplate_cta_phone', '#');
  
  ?>
  x-data="{
      scrollY: 0,
      lastScrollY: 0,
      showNavbar: true,
      isScrolled: false,
      bgFill: false,
      init() {
          this.scrollY = window.scrollY;
          this.lastScrollY = window.scrollY;
  
          window.addEventListener('scroll', () => {
              this.scrollY = window.scrollY;
              this.isScrolled = this.scrollY > 50;
  
              // Show navbar when scrolling up, hide when scrolling down
              if (this.scrollY > this.lastScrollY && this.scrollY > 100) {
                  // Scrolling down & past 100px
                  this.showNavbar = false;
              } else if (this.scrollY < this.lastScrollY) {
                  // Scrolling up
                  this.showNavbar = true;
              }
  
              // Always show navbar when at top
              if (this.scrollY <= 50) {
                  this.showNavbar = true;
              }
  
              this.lastScrollY = this.scrollY;
          });
      }
  }"
>
  <div
    class="fixed z-[100] w-full section-padding !py-2 transition-all duration-300 ease-in-out "
    :class="{
        'transform -translate-y-full':
            !showNavbar,
        'transform translate-y-0': showNavbar,
        'bg-brand-dark-blue/50 backdrop-blur-md shadow-lg': isScrolled,
        'bg-transparent':
            !isScrolled,
    }"
  >
    <!-- Header Container -->
    <div class="max-w-container mx-auto flex items-center justify-between">
      <div class="flex flex-row items-center gap-8">
        <!-- Site Logo -->
        <div class="site-logo">
          <?php if ( has_custom_logo() ) : ?>
          <?php
          $custom_logo_id = get_theme_mod('custom_logo');
          $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
          ?>
          <a href="<?php echo esc_url(home_url('/')); ?>">
            <img
              src="<?php echo esc_url($logo[0]); ?>"
              alt="<?php bloginfo('name'); ?> Logo"
              class=" w-auto transition-all duration-300"
              :class="{
                  'h-14': isScrolled,
                  'h-16': !isScrolled
              }"
            >
          </a>
          <?php else : ?>
          <a
            href="<?php echo esc_url(home_url('/')); ?>"
            class="text-white text-xl font-bold"
          >
            <?php bloginfo('name'); ?>
          </a>
          <?php endif; ?>
        </div>

        <!-- Menu Items Display -->
        <?php if (!empty($menu_items)) : ?>
        <nav
          x-data="{ openDropdown: null }"
          class="relative"
        >
          <ul class="list-none hidden lg:flex flex-row ">
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
            <?php $has_children = !empty($menu_tree[$parent_item->ID]['children']); ?>
            <li
              class="relative"
              <?php if ($has_children) : ?>
              x-data="{ open: false }"
              @mouseenter="open = true; bgFill = true; openDropdown = <?php echo $index; ?>"
              @mouseleave="open = false; bgFill = false; openDropdown = null"
              @click.away="open = false; openDropdown = null"
              <?php endif; ?>
            >
              <a
                href="<?php echo esc_url($parent_item->url); ?>"
                class="text-white text-base flex items-center gap-1 hover:text-gray-200 transition-all duration-300 py-2 px-3 rounded-md hover:bg-white/10"
                <?php if ($has_children) : ?>
                @click.prevent="open = !open; openDropdown = open ? <?php echo $index; ?> : null"
                :class="{ 'bg-white/10': open }"
                <?php endif; ?>
              >
                <?php echo esc_html($parent_item->title); ?>
                <?php if ($has_children) : ?>
                <svg
                  class="w-4 h-4 transition-transform duration-300"
                  :class="{ 'rotate-180': open }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  ></path>
                </svg>
                <?php endif; ?>
              </a>

              <?php if ($has_children) : ?>
              <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                class="absolute top-full left-0 mt-1 bg-white shadow-xl rounded-lg py-2 min-w-48 z-50 border border-gray-100"
                style="display: none;"
              >
                <ul class="space-y-1">
                  <?php foreach ($menu_tree[$parent_item->ID]['children'] as $child_item) : ?>
                  <li>
                    <a
                      href="<?php echo esc_url($child_item->url); ?>"
                      class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-150 text-sm font-medium"
                    >
                      <?php echo esc_html($child_item->title); ?>
                    </a>
                  </li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </nav>
        <?php else : ?>
        <p class="text-white">No menu items found.</p>
        <?php endif; ?>
      </div>

      <!-- Main CTA -->
      <div class="flex flex-row items-center gap-2">
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
                        class="flex flex-col h-full py-5 overflow-y-scroll bg-white border-l shadow-lg border-neutral-100/70"
                      >
                        <div class="px-4 sm:px-5">
                          <div class="flex items-start justify-between pb-1">
                            <h2
                              class="text-base font-semibold leading-6 text-gray-900"
                              id="slide-over-title"
                            >Menu</h2>
                            <div class="flex items-center h-auto ml-3">
                              <button
                                @click="slideOverOpen=false"
                                class="absolute top-0 right-0 z-30 flex items-center justify-center px-3 py-2 mt-4 mr-5 space-x-1 text-xs font-medium uppercase border rounded-md border-neutral-200 text-neutral-600 hover:bg-neutral-100"
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
                        </div>
                        <div class="relative flex-1 px-4 mt-5 sm:px-5">
                          <div class="absolute inset-0 px-4 sm:px-5">
                            <div
                              class="relative h-full overflow-hidden border border-dashed rounded-md border-neutral-300"
                            >
                            <nav class="w-full h-full overflow-y-auto">
                                <ul class="space-y-1">
                                    <?php 
                                    $render_menu_items = function($items, $parent_id = 0, $level = 0) use (&$render_menu_items) {
                                        $output = '';
                                        foreach ($items as $item) {
                                            if ($item->menu_item_parent == $parent_id) {
                                                $has_children = false;
                                                $children = [];
                                                foreach ($items as $child) {
                                                    if ($child->menu_item_parent == $item->ID) {
                                                        $has_children = true;
                                                        $children[] = $child;
                                                    }
                                                }
                                                
                                                $output .= '<li class="w-full ml-2 !mr-2">';
                                                
                                                if ($has_children) {
                                                  
                                                    $output .= '<div x-data="{ open: false }" class="w-full">';
                                                    $output .= '<div class="flex items-stretch w-full rounded-md hover:bg-gray-50 group">';
                                                    
                                                    $text_size = $level === 0 ? 'text-base' : ($level === 1 ? 'text-sm' : 'text-xs');
                                                    $font_weight = $level === 0 ? 'font-medium' : ($level === 1 ? 'font-normal' : 'font-light');
                                                    $padding_left = $level === 0 ? 'pl-4' : ($level === 1 ? 'pl-8' : ($level === 2 ? 'pl-12' : 'pl-16'));
                                                    
                                                    $output .= '<a href="' . esc_url($item->url) . '" class="flex-1 py-3 pr-4 ' . $padding_left . ' ' . $text_size . ' ' . $font_weight . ' text-gray-900 group-hover:bg-gray-50 rounded-md">';
                                                    $output .= esc_html($item->title);
                                                    $output .= '</a>';
                                                    
                                                    $output .= '<button @click="open = !open" class="flex items-center justify-center px-3 py-3 text-gray-900 hover:bg-gray-100 rounded-md ml-2" aria-label="Toggle submenu for ' . esc_attr($item->title) . '">';
                                                    $output .= '<svg class="w-4 h-4 transition-transform duration-300" :class="{ \'rotate-180\': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                                                    $output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
                                                    $output .= '</svg>';
                                                    $output .= '</button>';
                                                    
                                                    $output .= '</div>';
                                                    
                                                    $output .= '<div x-show="open" x-collapse class="mt-1">';
                                                    $output .= '<ul class="space-y-1">';
                                                    $output .= $render_menu_items($items, $item->ID, $level + 1);
                                                    $output .= '</ul>';
                                                    $output .= '</div>';
                                                    
                                                    $output .= '</div>';
                                                } else {
                                                    $text_size = $level === 0 ? 'text-base' : ($level === 1 ? 'text-sm' : 'text-xs');
                                                    $font_weight = $level === 0 ? 'font-medium' : ($level === 1 ? 'font-normal' : 'font-light');
                                                    $padding_left = $level === 0 ? 'pl-4' : ($level === 1 ? 'pl-8' : ($level === 2 ? 'pl-12' : 'pl-16'));
                                                    
                                                    $output .= '<a href="' . esc_url($item->url) . '" class="block py-3 pr-4 ' . $padding_left . ' ' . $text_size . ' ' . $font_weight . ' text-gray-900 hover:bg-gray-50 rounded-md transition-colors duration-200">';
                                                    $output .= esc_html($item->title);
                                                    $output .= '</a>';
                                                }
                                                
                                                $output .= '</li>';
                                            }
                                        }
                                        return $output;
                                    };
                                    
                                    echo $render_menu_items($menu_items);
                                    ?>
                                </ul>
                                
                                <div class="mt-8 space-y-3">
                                    <?php if ($cta_phone) : ?>
                                        <a 
                                            href="tel:<?php echo esc_attr($cta_phone); ?>" 
                                            class="btn btn-outline btn-lg w-full flex items-center justify-center transition-colors duration-200"
                                        >
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                width="20" 
                                                height="20" 
                                                fill="currentColor" 
                                                viewBox="0 0 256 256"
                                                class="mr-2 flex-shrink-0"
                                                aria-hidden="true"
                                            >
                                                <path d="M144.27,45.93a8,8,0,0,1,9.8-5.66,86.22,86.22,0,0,1,61.66,61.66,8,8,0,0,1-5.66,9.8A8.23,8.23,0,0,1,208,112a8,8,0,0,1-7.73-5.94,70.35,70.35,0,0,0-50.33-50.33A8,8,0,0,1,144.27,45.93Zm-2.33,41.8c13.79,3.68,22.65,12.54,26.33,26.33A8,8,0,0,0,176,120a8.23,8.23,0,0,0,2.07-.27,8,8,0,0,0,5.66-9.8c-5.12-19.16-18.5-32.54-37.66-37.66a8,8,0,1,0-4.13,15.46Zm81.94,95.35A56.26,56.26,0,0,1,168,232C88.6,232,24,167.4,24,88A56.26,56.26,0,0,1,72.92,32.12a16,16,0,0,1,16.62,9.52l21.12,47.15,0,.12A16,16,0,0,1,109.39,104c-.18.27-.37.52-.57.77L88,129.45c7.49,15.22,23.41,31,38.83,38.51l24.34-20.71a8.12,8.12,0,0,1,.75-.56,16,16,0,0,1,15.17-1.4l.13.06,47.11,21.11A16,16,0,0,1,223.88,183.08Zm-15.88-2s-.07,0-.11,0h0l-47-21.05-24.35,20.71a8.44,8.44,0,0,1-.74.56,16,16,0,0,1-15.75,1.14c-18.73-9.05-37.4-27.58-46.46-46.11a16,16,0,0,1,1-15.7,6.13,6.13,0,0,1,.57-.77L96,95.15l-21-47a.61.61,0,0,1,0-.12A40.2,40.2,0,0,0,40,88A128.14,128.14,0,0,0,168,216A40.21,40.21,0,0,0,208,181.07Z"></path>
                                            </svg>
                                            <span><?php echo esc_html($cta_phone); ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </nav>
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


        <?php if ($cta_phone) : ?>
        <a
          href="tel:<?php echo esc_attr($cta_phone); ?>"
          class="text-white text-sm  hover:text-gray-200 transition-all duration-300  xl:flex hidden"
        >
          <button class="btn btn-outline btn-lg">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              fill="currentColor"
              viewBox="0 0 256 256"
              class="mr-1"
            >
              <path
                d="M144.27,45.93a8,8,0,0,1,9.8-5.66,86.22,86.22,0,0,1,61.66,61.66,8,8,0,0,1-5.66,9.8A8.23,8.23,0,0,1,208,112a8,8,0,0,1-7.73-5.94,70.35,70.35,0,0,0-50.33-50.33A8,8,0,0,1,144.27,45.93Zm-2.33,41.8c13.79,3.68,22.65,12.54,26.33,26.33A8,8,0,0,0,176,120a8.23,8.23,0,0,0,2.07-.27,8,8,0,0,0,5.66-9.8c-5.12-19.16-18.5-32.54-37.66-37.66a8,8,0,1,0-4.13,15.46Zm81.94,95.35A56.26,56.26,0,0,1,168,232C88.6,232,24,167.4,24,88A56.26,56.26,0,0,1,72.92,32.12a16,16,0,0,1,16.62,9.52l21.12,47.15,0,.12A16,16,0,0,1,109.39,104c-.18.27-.37.52-.57.77L88,129.45c7.49,15.22,23.41,31,38.83,38.51l24.34-20.71a8.12,8.12,0,0,1,.75-.56,16,16,0,0,1,15.17-1.4l.13.06,47.11,21.11A16,16,0,0,1,223.88,183.08Zm-15.88-2s-.07,0-.11,0h0l-47-21.05-24.35,20.71a8.44,8.44,0,0,1-.74.56,16,16,0,0,1-15.75,1.14c-18.73-9.05-37.4-27.58-46.46-46.11a16,16,0,0,1,1-15.7,6.13,6.13,0,0,1,.57-.77L96,95.15l-21-47a.61.61,0,0,1,0-.12A40.2,40.2,0,0,0,40,88,128.14,128.14,0,0,0,168,216,40.21,40.21,0,0,0,208,181.07Z"
              ></path>
            </svg>
            <?php echo esc_html($cta_phone); ?>
          </button>
        </a>
        <?php endif; ?>
        <a
          href="<?php echo esc_url($cta_url); ?>"
          class="md:block hidden"
        >
          <button class="btn btn-primary btn-lg">
            <?php echo esc_html($cta_text); ?>
          </button>
        </a>
      </div>
    </div>
  </div>


  <?php wp_footer(); ?>
</body>

</html>
