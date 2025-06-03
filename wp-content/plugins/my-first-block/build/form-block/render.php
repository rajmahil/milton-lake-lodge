<?php
/**
 * Form section - Server-side render
 * Optimized for performance, SEO, and accessibility
 */

$topHeading = $attributes['topHeading'] ?? 'Your Adventure Awaits';
$heading = $attributes['heading'] ?? 'Your Adventure Awaits';
$fields = $attributes['fields'] ?? [];
?>

<section class="my-unique-plugin-wrapper-class">
  <div class="section-padding static-background flex flex-col gap-12">
    <div class="max-w-3xl w-full mx-auto">
      <p class="decorative-text text-brand-yellow-dark !text-4xl !my-0 text-center">
        <?php echo esc_html($topHeading); ?>
      </p>
      <h2 class="!text-4xl font-bold mb-4 text-center !my-0 text-brand-dark-blue">
        <?php echo esc_html($heading); ?>
      </h2>
    </div>

    <div class="max-w-2xl w-full mx-auto">
      <form class="grid grid-cols-2 gap-4">
        <?php foreach ( $fields as $index => $field ) : ?>
        <?php
        switch ( $field['type'] ) {
            case 'text':
            case 'email':
            case 'tel':
                ?>
        <input
          type="<?php echo esc_attr($type ?? 'text'); ?>"
          name="<?php echo esc_attr($name ?? ''); ?>"
          value="<?php echo esc_attr($value ?? ''); ?>"
          placeholder="<?php echo esc_attr($placeholder ?? ''); ?>"
          class="form-input"
          <?php if (!empty($required)) {
              echo 'required';
          } ?>
          <?php if (!empty($disabled)) {
              echo 'disabled';
          } ?>
        />

        <?php
          break;

            case 'textarea':
                ?>
        <textarea
          class="border p-2 w-full"
          placeholder="<?php echo esc_attr($field['placeholder'] ?? ''); ?>"
        ></textarea>
        <?php
                break;

            case 'select':
                ?>
        <select class="border p-2 w-full">
          <?php if ( ! empty( $field['options'] ) ) : ?>
          <?php foreach ( $field['options'] as $option ) : ?>
          <option value="<?php echo esc_attr($option['value']); ?>">
            <?php echo esc_html($option['label']); ?>
          </option>
          <?php endforeach; ?>
          <?php endif; ?>
        </select>
        <?php
                break;

            case 'checkbox':
                ?>
        <label class="flex items-center space-x-2">
          <input
            type="checkbox"
            class="form-checkbox"
          />
          <span><?php echo esc_html($field['label'] ?? ''); ?></span>
        </label>
        <?php
                break;

            case 'radio':
                ?>
        <label class="flex items-center space-x-2">
          <input
            type="radio"
            name="<?php echo esc_attr($field['name'] ?? 'radio-group'); ?>"
            class="form-radio"
          />
          <span><?php echo esc_html($field['label'] ?? ''); ?></span>
        </label>
        <?php
                break;

            default:
                error_log( 'Unknown field type: ' . $field['type'] );
                break;
        }
        ?>
        <?php endforeach; ?>
      </form>
    </div>
  </div>
</section>
