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
  class="static-background bg-brand-light-grey"
  <?php body_class(); ?>
  <?php
  // Get menu items from the menu named "Main Menu"
  $menu = wp_get_nav_menu_object('Main Menu');
  $menu_items = [];
  $enable_dark_mode = false;

  
  if ($menu) {
    $menu_items = wp_get_nav_menu_items($menu->term_id);
    
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    error_log('This is the Current Path: ' . $current_path);
    
    $dark_paths = get_field('dark_paths', 'nav_menu_' . $menu->term_id);
    
    if ($dark_paths) {
        foreach ($dark_paths as $dark_path) {
            $path = $dark_path['add_paths']; 
            if ($path && $current_path === $path) {
                $enable_dark_mode = true;
                break;
            }
        }
    }
    
    error_log('Dark paths: ' . var_export($dark_paths, true));
    error_log('Enable dark mode is: ' . var_export($enable_dark_mode, true));
}

  $cta_text = get_theme_mod('boilerplate_cta_text', 'Get Started');
  $cta_url = get_theme_mod('boilerplate_cta_url', '#');
  $cta_phone = get_theme_mod('boilerplate_cta_phone', '#');
  
  //change the navbar theme based off this
  $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  error_log('This is the Current Path: ' . $current_path);
  
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
    class="fixed z-[100] w-full section-padding !py-2 transition-all duration-300 ease-in-out isolate"
    :class="{
        'transform -translate-y-full': !showNavbar,
        'transform translate-y-0': showNavbar,
        <?php if ($enable_dark_mode): ?>
        'bg-brand-green-dark shadow-lg ring-1 ring-black/5': true
        <?php else: ?>
        'bg-brand-green-dark/50 backdrop-blur-xl shadow-lg ring-1 ring-black/5': isScrolled,
        'bg-transparent': !isScrolled,
        <?php endif; ?>
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
          x-data="{
              navigationMenuOpen: false,
              navigationMenu: '',
              navigationMenuCloseDelay: 200,
              navigationMenuCloseTimeout: null,
              navigationMenuLeave() {
                  let that = this;
                  this.navigationMenuCloseTimeout = setTimeout(() => {
                      that.navigationMenuClose();
                  }, this.navigationMenuCloseDelay);
              },
              navigationMenuReposition(navElement) {
                  this.navigationMenuClearCloseTimeout();
                  this.$refs.navigationDropdown.style.left = navElement.offsetLeft + 'px';
                  this.$refs.navigationDropdown.style.marginLeft = (navElement.offsetWidth / 2) + 'px';
              },
              navigationMenuClearCloseTimeout() {
                  clearTimeout(this.navigationMenuCloseTimeout);
              },
              navigationMenuClose() {
                  this.navigationMenuOpen = false;
                  this.navigationMenu = '';
              }
          }"
          class="relative z-10 w-auto lg:block hidden"
        >
          <div class="relative">
            <ul class="flex items-center gap-0 list-none  rounded-md  group">
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
                  class="flex flex-row gap-0.5 items-center px-2.5 py-1 text-white transition-all ease-in-out duration-300"
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
          <div
            x-ref="navigationDropdown"
            x-show="navigationMenuOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            @mouseover="navigationMenuClearCloseTimeout()"
            @mouseleave="navigationMenuLeave()"
            class="absolute top-0 pt-5 duration-300 ease-in-out -translate-x-1/2 translate-y-11"
            x-cloak
          >
            <?php foreach ($parent_items as $index => $parent_item) : ?>
            <?php
            $has_children = !empty($menu_tree[$parent_item->ID]['children']);
            $menu_key = $parent_item->post_name; // or any dynamic value you want
            ?>
            <?php if ($has_children) : ?>
            <div
              x-show="navigationMenu == '<?php echo esc_js($menu_key); ?>'"
              class="relative flex items-stretch justify-center w-full max-w-2xl p-3 gap-x-3 bg-white  rounded-md shadow-2xl"
            >
              <div class="absolute h-4.5 w-4.5 bg-white -top-1.5 left-1/2 -translate-x-1/2 rotate-45 rounded-xs"></div>

              <ul class="w-50">
                <?php foreach ($menu_tree[$parent_item->ID]['children'] as $child_item) : ?>
                <li class="py-1 px-2 group opacity-50 hover:opacity-100 transition-opacity duration-300">
                  <a
                    @click="navigationMenuClose()"
                    href="<?php echo esc_url($child_item->url); ?>"
                    class="text-base flex flex-row items-center justify-between"
                  >
                    <?php echo esc_html($child_item->title); ?>
                    <?php
                    get_template_part('template-parts/icons/arrow-right', null, [
                        'size' => 14,
                        'color' => 'currentColor',
                        'class' => 'ml-1 scale-0 group-hover:scale-100 transition-transform duration-300 ease-in-out',
                    ]);
                    ?>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>


            </div>
            <?php endif ?>
            <?php endforeach ?>
        </nav>
        <?php else : ?>
        <p class="text-white">No menu items found.</p>
        <?php endif; ?>
      </div>

      <!-- Main CTA -->
      <div class="flex flex-row items-center gap-2">
        <?php
        set_query_var('menu_items', $menu_items);
        set_query_var('menu_tree', $menu_tree);
        set_query_var('parent_items', $parent_items);
        
        get_template_part('template-parts/components/menu-drawer');
        ?>
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
