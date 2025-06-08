<?php
/**
 * Arrow Right Icon Template Part
 *
 * @param array $args {
 *     @type int    $size  Icon size (default: 32)
 *     @type string $color Icon color (default: #000000)
 *     @type string $class CSS classes
 * }
 */

$defaults = [
    'size' => 32,
    'color' => '#000000',
    'class' => '',
];

$args = wp_parse_args($args, $defaults);
?>

<svg
  xmlns="http://www.w3.org/2000/svg"
  width="<?php echo intval($args['size']); ?>"
  height="<?php echo intval($args['size']); ?>"
  fill="<?php echo esc_attr($args['color']); ?>"
  viewBox="0 0 256 256"
  class="<?php echo esc_attr($args['class']); ?>"
>
  <path
    d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"
  ></path>
</svg>
