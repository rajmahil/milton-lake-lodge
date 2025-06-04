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
         error_log( 'Current field: ' . print_r( $field, true ) );
        switch ( $field['type'] ) {
          case 'text':
          case 'email':
          case 'tel':
      ?>
        <input
          type="<?php echo esc_attr($field['type'] ?? 'text'); ?>"
          name="<?php echo esc_attr($field['name'] ?? ''); ?>"
          value="<?php echo esc_attr($field['value'] ?? ''); ?>"
          placeholder="<?php echo esc_attr(($field['placeholder'] ?? '') . ($field['required'] === true ? ' *' : '')); ?>"
          class="form-input	<?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>"
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
          class="form-input min-h-24 pt-4 <?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>"
          name="<?php echo esc_attr($field['name'] ?? ''); ?>"
          placeholder="<?php echo esc_attr(($field['placeholder'] ?? '') . ($field['required'] === true ? ' *' : '')); ?>"
        ><?php echo esc_html($field['value'] ?? ''); ?></textarea>
        <?php
          break;

          case 'select':
     		 ?>
        <select
          name="<?php echo esc_attr($field['name'] ?? ''); ?>"
          class="border p-2 w-full <?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>"
        >
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
        <label class="flex items-center space-x-2 <?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>">
          <input
            type="checkbox"
            name="<?php echo esc_attr($field['name'] ?? ''); ?>"
            class="form-checkbox"
            <?php if (!empty($field['checked'])) {
                echo 'checked';
            } ?>
          />
          <span><?php echo esc_html($field['label'] ?? ''); ?></span>
        </label>
        <?php
          break;

          case 'radio':
      ?>
        <div
          x-data="{
              radioGroupSelectedValue: null,
              radioGroupOptions: JSON.parse($el.dataset.options)
          }"
          data-options='<?php echo esc_attr(
              json_encode(
                  array_map(function ($option) {
                      return [
                          'title' => $option,
                          'value' => $option,
                      ];
                  }, $field['options'] ?? []),
              ),
          ); ?>'
          class="space-y-3"
        >
          <?php if ( ! empty( $field['label'] ) ) : ?>
          <label class="block mb-2 text-medium text-left">
            <?php echo esc_html($field['label']); ?>
            <?php if ( ! empty( $field['required'] ) ) : ?>
            <span class="text-red-500">*</span>
            <?php endif; ?>
          </label>
          <?php endif; ?>

          <template
            x-for="(option, index) in radioGroupOptions"
            :key="index"
          >
            <label
              @click="radioGroupSelectedValue = option.value"
              class="flex items-start p-5 space-x-3 bg-white  rounded-md  hover:bg-gray-50  cursor-pointer"
            >
              <input
                type="radio"
                name="<?php echo esc_attr($field['name'] ?? 'radio-group'); ?>"
                :value="option.value"
                x-model="radioGroupSelectedValue"
                class="text-gray-900 translate-y-px focus:ring-gray-700"
              />
              <span class="relative flex flex-col text-left space-y-1.5 leading-none">
                <span
                  x-text="option.title"
                  class="font-normal"
                ></span>
              </span>
            </label>
          </template>
        </div>

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
