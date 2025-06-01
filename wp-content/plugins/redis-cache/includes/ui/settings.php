<?php
/**
 * Admin settings page template
 *
 * @package Rhubarb\RedisCache
 */

namespace Rhubarb\RedisCache\UI;

use Rhubarb\RedisCache\UI;
use Rhubarb\RedisCache\Plugin;

defined('\\ABSPATH') || exit();

?>
<div
  id="rediscache"
  class="wrap"
>

  <h1>
    <?php esc_html_e('Redis Object Cache', 'redis-cache'); ?>
  </h1>

  <?php is_network_admin() && settings_errors(); ?>

  <div class="columns">

    <div class="content-column">

      <h2 class="nav-tab-wrapper">
        <?php foreach ( UI::get_tabs() as $ui_tab ) : ?>
        <?php if ( $ui_tab->is_disabled() ) : ?>

        <span
          class="<?php echo esc_attr($ui_tab->nav_classes()); ?>"
          title="<?php echo esc_attr($ui_tab->disabled_notice()); ?>"
        >
          <?php echo esc_html($ui_tab->label()); ?>
        </span>

        <?php else : ?>

        <a
          id="<?php echo esc_attr($ui_tab->nav_id()); ?>"
          class="<?php echo esc_attr($ui_tab->nav_classes()); ?>"
          data-toggle="<?php echo esc_attr($ui_tab->slug()); ?>"
          href="#<?php echo esc_attr($ui_tab->slug()); ?>"
        >
          <?php echo esc_html($ui_tab->label()); ?>
        </a>

        <?php endif; ?>
        <?php endforeach; ?>
      </h2>

      <div class="tab-content">
        <?php foreach ( UI::get_tabs() as $ui_tab ) : ?>
        <?php if ( ! $ui_tab->is_disabled() ) : ?>
        <div
          id="<?php echo esc_attr($ui_tab->id()); ?>"
          class="<?php echo esc_attr($ui_tab->classes()); ?>"
        >
          <?php $ui_tab->display(); ?>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>

    </div>
  </div>

</div>
