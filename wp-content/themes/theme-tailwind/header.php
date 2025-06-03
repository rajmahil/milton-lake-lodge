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
    class="fixed z-[100] w-full section-padding !py-1.5 transition-all duration-300 ease-in-out"
    :class="{
        'transform -translate-y-full': !showNavbar,
        'transform translate-y-0': showNavbar,
        'bg-brand-dark-blue backdrop-blur-md shadow-lg': isScrolled,
        'bg-transparent': !isScrolled,
    }"
  >
    <!-- Header Container -->
    <div class="max-w-container mx-auto flex items-center justify-between ">
      <div class="flex flex-row items-center gap-12">
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
                  'h-16': isScrolled,
                  'h-20': !isScrolled
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
          <ul class="list-none flex flex-row gap-2">
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
                class="text-white text-base flex items-center gap-1 hover:text-gray-200 transition-all duration-200 py-2 px-3 rounded-md hover:bg-white/10"
                <?php if ($has_children) : ?>
                @click.prevent="open = !open; openDropdown = open ? <?php echo $index; ?> : null"
                :class="{ 'bg-white/10': open }"
                <?php endif; ?>
              >
                <?php echo esc_html($parent_item->title); ?>
                <?php if ($has_children) : ?>
                <svg
                  class="w-4 h-4 transition-transform duration-200"
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
                x-transition:enter="transition ease-out duration-200"
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
        <?php if ($cta_phone) : ?>
        <a
          href="tel:<?php echo esc_attr($cta_phone); ?>"
          class="text-white text-sm  hover:text-gray-200 transition-all duration-200"
        >
          <button
            class="btn btn-outline"
            :class="{ 'btn-md': isScrolled, 'btn-lg': !isScrolled }"
          >
            <?php echo esc_html($cta_phone); ?>
          </button>
        </a>
        <?php endif; ?>
        <a href="<?php echo esc_url($cta_url); ?>">
          <button
            class="btn btn-primary"
            :class="{ 'btn-md': isScrolled, 'btn-lg': !isScrolled }"
          >
            <?php echo esc_html($cta_text); ?>
          </button>
        </a>
      </div>
    </div>
  </div>


  <?php wp_footer(); ?>
</body>

</html>
