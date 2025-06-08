<?php
/**
 * Social Icon Component
 *
 * @param array $args {
 *     @type string $icon    Icon name (facebook, twitter, etc.)
 *     @type string $url     Link URL
 *     @type string $size    Icon size (small, medium, large)
 *     @type string $class   Additional CSS classes
 * }
 */

$defaults = [
    'icon' => 'facebook',
    'url' => '#',
    'size' => 'medium',
    'class' => '',
];

$args = wp_parse_args($args, $defaults);

$size_classes = [
    'small' => 'w-4 h-4',
    'medium' => 'w-6 h-6',
    'large' => 'w-8 h-8',
];

$icon_class = $size_classes[$args['size']] ?? $size_classes['medium'];
?>

<a
  href="<?php echo esc_url($args['url']); ?>"
  class="social-icon social-icon--<?php echo esc_attr($args['icon']); ?> <?php echo esc_attr($args['class']); ?>"
  target="_blank"
  rel="noopener noreferrer"
>
  <svg
    class="<?php echo esc_attr($icon_class); ?>"
    aria-hidden="true"
  >
    <?php if ($args['icon'] === 'facebook'): ?>
    <path
      d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
    />
    <?php elseif ($args['icon'] === 'twitter'): ?>
    <path
      d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
    />
    <?php endif; ?>
  </svg>
  <span class="sr-only"><?php echo esc_html(ucfirst($args['icon'])); ?></span>
</a>
