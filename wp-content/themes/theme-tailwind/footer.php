<footer>
  <?php
  $footer_bg_color = get_theme_mod('boilerplate_footer_bg_color', '#ffffff');
  $footer_bg_image_id = get_theme_mod('boilerplate_footer_bg_image', 0);
  $footer_bg_image_url = $footer_bg_image_id ? wp_get_attachment_image_url($footer_bg_image_id, 'full') : '';
  
  $upload_dir = wp_upload_dir();
  $upload_url = $upload_dir['baseurl'];
  ?>

  <div
    x-data="{ rotation: 0 }"
    x-init="window.addEventListener('scroll', () => {
        rotation = window.scrollY * 0.2; // adjust speed here
    });"
    class="static-background w-full bg-brand-light-grey flex items-center justify-center"
  >
    <div class="w-40 h-40 bg-brand-green-dark rounded-full relative translate-y-[60%] p-4">
      <img
        src="<?php echo esc_url($upload_url . '/effects/compass-footer.png'); ?>"
        alt="Milton Lake Lodge | Fishing Saskatchewan"
        width="160"
        height="160"
        class="select-none pointer-events-none transition-transform duration-500 ease-in-out will-change-transform"
        :style="'transform: rotate(' + rotation + 'deg)'"
      >
    </div>
  </div>


  <div class="section-padding bg-brand-green-dark flex flex-col gap-20 pb-8  overflow-hidden">
    <div class="max-w-container w-full mx-auto  grid grid-cols-3 lg:grid-cols-4 gap-2  xl:gap-10 space-y-20">
      <div
        class="flex flex-col gap-8 items-center md:items-start lg:col-span-1 col-span-3  max-w-[450px] mr-auto ml-auto md:ml-0"
      >

        <div class="flex flex-col gap-2 items-center md:items-start">
          <?php
    $footer_logo_id = get_theme_mod('boilerplate_footer_logo');
    

    if ($footer_logo_id) :
         $footer_logo_alt = get_post_meta($footer_logo_id, '_wp_attachment_image_alt', true) ?: 'Footer Logo';
    
    // Get multiple image sizes for responsive images
    $footer_logo_full = wp_get_attachment_image_url($footer_logo_id, 'full');
    $footer_logo_large = wp_get_attachment_image_url($footer_logo_id, 'large');
    $footer_logo_medium = wp_get_attachment_image_url($footer_logo_id, 'medium');
    
    // Get image metadata for dimensions
    $image_meta = wp_get_attachment_metadata($footer_logo_id);
    $width = isset($image_meta['width']) ? $image_meta['width'] : '';
    $height = isset($image_meta['height']) ? $image_meta['height'] : '';
    ?>
          <img
            src="<?php echo esc_url($footer_logo_medium ?: $footer_logo_full); ?>"
            srcset="<?php echo esc_url($footer_logo_medium); ?> 300w, 
                <?php echo esc_url($footer_logo_large); ?> 1024w, 
                <?php echo esc_url($footer_logo_full); ?> <?php echo $width; ?>w"
            sizes="(max-width: 768px) 100px, (max-width: 1024px) 150px, 200px"
            alt="<?php echo esc_attr($footer_logo_alt); ?>"
            class="h-28 w-auto object-contain object-center"
            loading="lazy"
            decoding="async"
            <?php if ($width && $height) : ?>
            width="<?php echo esc_attr($width); ?>"
            height="<?php echo esc_attr($height); ?>"
            <?php endif; ?>
          >
          <?php endif; ?>

          <?php
    $footer_description = get_theme_mod('boilerplate_footer_description');
    if ( $footer_description ) : ?>
          <div class="footer-description text-white leading-relaxed font-normal md:text-left text-center ">
            <?php echo wp_kses_post($footer_description); ?>
          </div>
          <?php endif; ?>
        </div>

        <?php
        $facebook = get_theme_mod('boilerplate_facebook_url');
        $twitter = get_theme_mod('boilerplate_twitter_url');
        $instagram = get_theme_mod('boilerplate_instagram_url');
        $youtube = get_theme_mod('boilerplate_youtube_url');
        $tripadvisor = get_theme_mod('boilerplate_tripadvisor_url');
        $linkedin = get_theme_mod('boilerplate_linkedin_url');
        $terms_conditions = get_theme_mod('boilerplate_terms_conditions_url');
        $privacy_policy = get_theme_mod('boilerplate_privacy_policy_url');
        ?>

        <div class="flex space-x-2">
          <?php if ( $facebook ) : ?>
          <a
            href="<?php echo esc_url($facebook); ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="hover:bg-brand-yellow transition-all duration-300 ease-in-out h-10 w-10 bg-white flex items-center justify-center rounded-full p-1.5 text-brand-green-dark"
          >
            <span class="sr-only">Facebook</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              width="120"
              height="120"
              fill="rgba(17,42,55,1)"
            >
              <path
                d="M14 13.5H16.5L17.5 9.5H14V7.5C14 6.47062 14 5.5 16 5.5H17.5V2.1401C17.1743 2.09685 15.943 2 14.6429 2C11.9284 2 10 3.65686 10 6.69971V9.5H7V13.5H10V22H14V13.5Z"
              ></path>
            </svg>
          </a>
          <?php endif; ?>

          <?php if ( $twitter ) : ?>
          <a
            href="<?php echo esc_url($twitter); ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="hover:bg-brand-yellow transition-all duration-300 ease-in-out h-10 w-10 bg-white flex items-center justify-center rounded-full p-2 text-brand-green-dark"
          >
            <span class="sr-only">Twitter</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              width="120"
              height="120"
              fill="rgba(17,42,55,1)"
            >
              <path
                d="M17.6874 3.0625L12.6907 8.77425L8.37045 3.0625H2.11328L9.58961 12.8387L2.50378 20.9375H5.53795L11.0068 14.6886L15.7863 20.9375H21.8885L14.095 10.6342L20.7198 3.0625H17.6874ZM16.6232 19.1225L5.65436 4.78217H7.45745L18.3034 19.1225H16.6232Z"
              ></path>
            </svg>
          </a>
          <?php endif; ?>

          <?php if ( $linkedin ) : ?>
          <a
            href="<?php echo esc_url($linkedin); ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="hover:bg-brand-yellow transition-all duration-300 ease-in-out h-10 w-10 bg-white flex items-center justify-center rounded-full p-2 text-brand-green-dark"
          >
            <span class="sr-only">Linkedin</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              width="120"
              height="120"
              fill="rgba(17,42,55,1)"
            >
              <path
                d="M6.94048 4.99993C6.94011 5.81424 6.44608 6.54702 5.69134 6.85273C4.9366 7.15845 4.07187 6.97605 3.5049 6.39155C2.93793 5.80704 2.78195 4.93715 3.1105 4.19207C3.43906 3.44699 4.18654 2.9755 5.00048 2.99993C6.08155 3.03238 6.94097 3.91837 6.94048 4.99993ZM7.00048 8.47993H3.00048V20.9999H7.00048V8.47993ZM13.3205 8.47993H9.34048V20.9999H13.2805V14.4299C13.2805 10.7699 18.0505 10.4299 18.0505 14.4299V20.9999H22.0005V13.0699C22.0005 6.89993 14.9405 7.12993 13.2805 10.1599L13.3205 8.47993Z"
              ></path>
            </svg>
          </a>
          <?php endif; ?>

          <?php if ( $instagram ) : ?>
          <a
            href="<?php echo esc_url($instagram); ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="hover:bg-brand-yellow transition-all duration-300 ease-in-out h-10 w-10 bg-white flex items-center justify-center rounded-full p-2 text-brand-green-dark"
          >
            <span class="sr-only">Instagram</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              width="120"
              height="120"
              fill="rgba(17,42,55,1)"
            >
              <path
                d="M13.0281 2.00073C14.1535 2.00259 14.7238 2.00855 15.2166 2.02322L15.4107 2.02956C15.6349 2.03753 15.8561 2.04753 16.1228 2.06003C17.1869 2.1092 17.9128 2.27753 18.5503 2.52503C19.2094 2.7792 19.7661 3.12253 20.3219 3.67837C20.8769 4.2342 21.2203 4.79253 21.4753 5.45003C21.7219 6.0867 21.8903 6.81337 21.9403 7.87753C21.9522 8.1442 21.9618 8.3654 21.9697 8.58964L21.976 8.78373C21.9906 9.27647 21.9973 9.84686 21.9994 10.9723L22.0002 11.7179C22.0003 11.809 22.0003 11.903 22.0003 12L22.0002 12.2821L21.9996 13.0278C21.9977 14.1532 21.9918 14.7236 21.9771 15.2163L21.9707 15.4104C21.9628 15.6347 21.9528 15.8559 21.9403 16.1225C21.8911 17.1867 21.7219 17.9125 21.4753 18.55C21.2211 19.2092 20.8769 19.7659 20.3219 20.3217C19.7661 20.8767 19.2069 21.22 18.5503 21.475C17.9128 21.7217 17.1869 21.89 16.1228 21.94C15.8561 21.9519 15.6349 21.9616 15.4107 21.9694L15.2166 21.9757C14.7238 21.9904 14.1535 21.997 13.0281 21.9992L12.2824 22C12.1913 22 12.0973 22 12.0003 22L11.7182 22L10.9725 21.9993C9.8471 21.9975 9.27672 21.9915 8.78397 21.9768L8.58989 21.9705C8.36564 21.9625 8.14444 21.9525 7.87778 21.94C6.81361 21.8909 6.08861 21.7217 5.45028 21.475C4.79194 21.2209 4.23444 20.8767 3.67861 20.3217C3.12278 19.7659 2.78028 19.2067 2.52528 18.55C2.27778 17.9125 2.11028 17.1867 2.06028 16.1225C2.0484 15.8559 2.03871 15.6347 2.03086 15.4104L2.02457 15.2163C2.00994 14.7236 2.00327 14.1532 2.00111 13.0278L2.00098 10.9723C2.00284 9.84686 2.00879 9.27647 2.02346 8.78373L2.02981 8.58964C2.03778 8.3654 2.04778 8.1442 2.06028 7.87753C2.10944 6.81253 2.27778 6.08753 2.52528 5.45003C2.77944 4.7917 3.12278 4.2342 3.67861 3.67837C4.23444 3.12253 4.79278 2.78003 5.45028 2.52503C6.08778 2.27753 6.81278 2.11003 7.87778 2.06003C8.14444 2.04816 8.36564 2.03847 8.58989 2.03062L8.78397 2.02433C9.27672 2.00969 9.8471 2.00302 10.9725 2.00086L13.0281 2.00073ZM12.0003 7.00003C9.23738 7.00003 7.00028 9.23956 7.00028 12C7.00028 14.7629 9.23981 17 12.0003 17C14.7632 17 17.0003 14.7605 17.0003 12C17.0003 9.23713 14.7607 7.00003 12.0003 7.00003ZM12.0003 9.00003C13.6572 9.00003 15.0003 10.3427 15.0003 12C15.0003 13.6569 13.6576 15 12.0003 15C10.3434 15 9.00028 13.6574 9.00028 12C9.00028 10.3431 10.3429 9.00003 12.0003 9.00003ZM17.2503 5.50003C16.561 5.50003 16.0003 6.05994 16.0003 6.74918C16.0003 7.43843 16.5602 7.9992 17.2503 7.9992C17.9395 7.9992 18.5003 7.4393 18.5003 6.74918C18.5003 6.05994 17.9386 5.49917 17.2503 5.50003Z"
              ></path>
            </svg>
          </a>
          <?php endif; ?>


          <?php if ( $youtube ) : ?>
          <a
            href="<?php echo esc_url($youtube); ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="hover:bg-brand-yellow transition-all duration-300 ease-in-out h-10 w-10 bg-white flex items-center justify-center rounded-full p-2 text-brand-green-dark"
          >
            <span class="sr-only">Youtube</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              width="120"
              height="120"
              fill="rgba(17,42,55,1)"
            >
              <path
                d="M12.2439 4C12.778 4.00294 14.1143 4.01586 15.5341 4.07273L16.0375 4.09468C17.467 4.16236 18.8953 4.27798 19.6037 4.4755C20.5486 4.74095 21.2913 5.5155 21.5423 6.49732C21.942 8.05641 21.992 11.0994 21.9982 11.8358L21.9991 11.9884L21.9991 11.9991C21.9991 11.9991 21.9991 12.0028 21.9991 12.0099L21.9982 12.1625C21.992 12.8989 21.942 15.9419 21.5423 17.501C21.2878 18.4864 20.5451 19.261 19.6037 19.5228C18.8953 19.7203 17.467 19.8359 16.0375 19.9036L15.5341 19.9255C14.1143 19.9824 12.778 19.9953 12.2439 19.9983L12.0095 19.9991L11.9991 19.9991C11.9991 19.9991 11.9956 19.9991 11.9887 19.9991L11.7545 19.9983C10.6241 19.9921 5.89772 19.941 4.39451 19.5228C3.4496 19.2573 2.70692 18.4828 2.45587 17.501C2.0562 15.9419 2.00624 12.8989 2 12.1625V11.8358C2.00624 11.0994 2.0562 8.05641 2.45587 6.49732C2.7104 5.51186 3.45308 4.73732 4.39451 4.4755C5.89772 4.05723 10.6241 4.00622 11.7545 4H12.2439ZM9.99911 8.49914V15.4991L15.9991 11.9991L9.99911 8.49914Z"
              ></path>
            </svg>
          </a>
          <?php endif; ?>


          <?php if ( $tripadvisor ) : ?>
          <a
            href="<?php echo esc_url($tripadvisor); ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="hover:bg-brand-yellow transition-all duration-300 ease-in-out h-10 w-10 bg-white flex items-center justify-center rounded-full p-1.5 text-brand-green-dark"
          >
            <span class="sr-only">Trip Advisor</span>
            <svg
              fill="#000000"
              width="100px"
              height="100px"
              viewBox="0 0 32 32"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g
                id="SVGRepo_bgCarrier"
                stroke-width="0"
              ></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M30.683 12.708c0.375-1.609 1.568-3.219 1.568-3.219h-5.349c-3.005-1.943-6.647-2.968-10.688-2.968-4.187 0-7.968 1.041-10.953 3h-5.009c0 0 1.176 1.583 1.556 3.181-0.973 1.344-1.556 2.964-1.556 4.745 0 4.416 3.599 8.011 8.015 8.011 2.527 0 4.765-1.183 6.245-3.005l1.697 2.552 1.724-2.584c0.761 0.985 1.761 1.781 2.937 2.324 1.943 0.88 4.125 0.979 6.125 0.239 4.141-1.536 6.26-6.161 4.74-10.301-0.276-0.74-0.641-1.401-1.079-1.98zM26.453 23.473c-1.599 0.595-3.339 0.527-4.891-0.192-1.099-0.511-2.005-1.308-2.651-2.303-0.272-0.4-0.5-0.833-0.672-1.296-0.199-0.527-0.292-1.068-0.344-1.62-0.099-1.109 0.057-2.229 0.536-3.271 0.719-1.552 2-2.735 3.604-3.328 3.319-1.219 7 0.484 8.219 3.791 1.224 3.308-0.479 6.991-3.781 8.215h-0.020zM13.563 21.027c-1.151 1.692-3.093 2.817-5.297 2.817-3.525 0-6.401-2.875-6.401-6.396s2.876-6.401 6.401-6.401c3.527 0 6.396 2.88 6.396 6.401 0 0.219-0.036 0.416-0.063 0.64-0.109 1.079-0.453 2.1-1.036 2.959zM4.197 17.364c0 2.188 1.781 3.959 3.964 3.959s3.959-1.771 3.959-3.959c0-2.181-1.776-3.952-3.959-3.952-2.177 0-3.959 1.771-3.959 3.952zM20.265 17.364c0 2.188 1.771 3.959 3.953 3.959s3.959-1.771 3.959-3.959c0-2.181-1.776-3.952-3.959-3.952-2.177 0-3.959 1.771-3.959 3.952zM5.568 17.364c0-1.427 1.161-2.593 2.583-2.593 1.417 0 2.599 1.167 2.599 2.593 0 1.433-1.161 2.6-2.599 2.6-1.443 0-2.604-1.167-2.604-2.6zM21.615 17.364c0-1.427 1.156-2.593 2.593-2.593 1.423 0 2.584 1.167 2.584 2.593 0 1.433-1.156 2.6-2.599 2.6-1.444 0-2.6-1.167-2.6-2.6zM16.208 7.921c2.88 0 5.48 0.516 7.761 1.548-0.86 0.025-1.699 0.176-2.543 0.479-2.015 0.74-3.62 2.224-4.5 4.167-0.416 0.88-0.635 1.812-0.719 2.755-0.301-4.104-3.681-7.353-7.844-7.437 2.281-0.979 4.928-1.511 7.787-1.511z"
                ></path>
              </g>
            </svg>
          </a>
          <?php endif; ?>
        </div>

      </div>



      <?php
  $footer = wp_get_nav_menu_object('Footer Menu');
  $footer_items = [];

  if ( $footer ) {
      $footer_items = wp_get_nav_menu_items( $footer->term_id );
      error_log( print_r( $footer_items, true ) );
  }

  if ( ! empty( $footer_items ) ) :
      // Organize items by hierarchy
      $menu_tree = [];
      $parent_items = [];
      $child_items = [];
      
      // Separate parent and child items
      foreach ( $footer_items as $item ) {
          if ( $item->menu_item_parent == 0 ) {
              $parent_items[$item->ID] = $item;
              $menu_tree[$item->ID] = [
                  'item' => $item,
                  'children' => []
              ];
          } else {
              $child_items[] = $item;
          }
      }
      
      // Assign children to their parents
      foreach ( $child_items as $child ) {
          if ( isset( $menu_tree[$child->menu_item_parent] ) ) {
              $menu_tree[$child->menu_item_parent]['children'][] = $child;
          }
      }
  ?>


      <?php foreach ( $menu_tree as $parent_id => $menu_group ) : ?>
      <div
        class="flex flex-col gap-4 items-center md:items-start w-full md:col-span-1 col-span-3 max-w-[200px] mx-auto">
        <!-- Parent Item as Header -->
        <h4 class="font-medium text-white text-center md:text-left text-2xl">
          <?php if ( !empty($menu_group['item']->url) && $menu_group['item']->url !== '#' ) : ?>
          <?php echo esc_html($menu_group['item']->title); ?>
          <?php else : ?>
          <?php echo esc_html($menu_group['item']->title); ?>
          <?php endif; ?>
        </h4>

        <!-- Child Items -->
        <?php if ( !empty($menu_group['children']) ) : ?>
        <ul class="flex flex-col items-center md:items-start space-y-2">
          <?php foreach ( $menu_group['children'] as $child ) : ?>
          <li class="footer-menu-item">
            <a
              href="<?php echo esc_url($child->url); ?>"
              class="text-gray-300 hover:text-white transition-colors duration-200 text-left"
            >
              <?php echo esc_html($child->title); ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>

      <div class="flex flex-col gap-4 w-full order-last  md:col-span-1 col-span-3 max-w-[450px] mx-auto">
        <h4 class="font-medium text-white text-center text-2xl">Subscribe to Newsletter</h4>
        <form
          class="flex
          flex-col
          items-center
          gap-2"
          action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
        >
          <input
            type="hidden"
            name="action"
            value="my_custom_form_submit"
          >
          <input
            class="form-input   "
            placeholder="youremail@gmail.com"
            type="email"
            name="email"
            id="footer-newsletter-email"
            required
            aria-label="Email for Newsletter Subscription"
            aria-describedby="footer-newsletter-email-description"
            aria-required="true"
            aria-invalid="false"
            aria-describedby="footer-newsletter-email-description"
          />
          <button class="btn btn-primary btn-lg w-full">Subscribe

            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              fill="#000000"
              viewBox="0 0 256 256"
              class="ml-1"
            >
              <path
                d="M227.32,28.68a16,16,0,0,0-15.66-4.08l-.15,0L19.57,82.84a16,16,0,0,0-2.49,29.8L102,154l41.3,84.87A15.86,15.86,0,0,0,157.74,248q.69,0,1.38-.06a15.88,15.88,0,0,0,14-11.51l58.2-191.94c0-.05,0-.1,0-.15A16,16,0,0,0,227.32,28.68ZM157.83,231.85l-.05.14,0-.07-40.06-82.3,48-48a8,8,0,0,0-11.31-11.31l-48,48L24.08,98.25l-.07,0,.14,0L216,40Z"
              ></path>
            </svg>
          </button>
        </form>
      </div>
    </div>

    <div
      class="text-white max-w-container w-full mx-auto flex flex-col md:flex-row items-center justify-center md:justify-between gap-4"
    >
      <p>© <?php echo date('Y'); ?> All Rights Reserved. Built By <a
          class="border-b border-white pb-0.6 cursor-pointer hover:border-brand-yellow hover:text-brand-yellow transition-all duration-300 ease-in-out"
          target="_blank"
          href="https://306technologies.com"
          rel="sponsored"
        >306
          Technologies</a>
      </p>
      <div class="flex flex-row items-center gap-5">

        <?php if ( $terms_conditions ) : ?>
        <a
          target="_blank"
          rel="sponsored"
          class="border-b border-white pb-0.6 cursor-pointer hover:border-brand-yellow hover:text-brand-yellow transition-all duration-300 ease-in-out"
          href="<?php echo esc_url($terms_conditions); ?>"
        >
          Terms & Conditions
        </a>
        <?php endif; ?>

        <?php if ( $privacy_policy ) : ?>
        <a
          target="_blank"
          rel="sponsored"
          class="border-b border-white pb-0.6 cursor-pointer hover:border-brand-yellow hover:text-brand-yellow transition-all duration-300 ease-in-out"
          href="<?php echo esc_url($privacy_policy); ?>"
        >
          Privacy Policy
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>


  <?php
  $footer_bg_image_id = get_theme_mod('boilerplate_footer_bg_image', 0);
  $footer_bg_image_url = $footer_bg_image_id ? wp_get_attachment_image_url($footer_bg_image_id, 'full') : '';
  ?>
  <div
    class="hidden -mt-8 h-[300px] sm:h-[400px] md:h-[550px] sticky bottom-0 z-[-1] bg-right w-full bg-cover <?php echo $footer_bg_image_url ? '' : 'bg-[<?php echo esc_attr($footer_bg_color); ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>'; ?>]'; ?>"
    style="<?php if ($footer_bg_image_url) : ?>background-image: url('<?php echo esc_url($footer_bg_image_url); ?>');<?php endif; ?>"
  >
  </div>
</footer>

<?php wp_footer(); ?>
