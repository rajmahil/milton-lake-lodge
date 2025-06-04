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

        <div
          x-data="{
              open: false,
              selected: '',
              placeholder: '<?php echo $field['placeholder']; ?>',
              items: <?php echo esc_attr(json_encode($field['options'] ?? [])); ?>
          }"
          class="relative <?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>"
        >
          <?php if ( ! empty( $field['label'] ) ) : ?>
          <label class="block mb-3 text-medium text-center">
            <?php echo esc_html($field['label']); ?>
            <?php if ( ! empty( $field['required'] ) ) : ?>
            <span>*</span>
            <?php endif; ?>
          </label>
          <?php endif; ?>

          <!-- Button -->
          <button
            @click="open = !open"
            type="button"
            class="w-full form-input cursor-pointer flex items-center justify-start px-4 relative"
          >
            <p
              class="text-base capitalize"
              :class="!selected ? 'text-muted-foreground/50' : ''"
              x-text="selected ? selected : placeholder"
            ></p>
            <span
              class="absolute inset-y-0 right-2 top-1/2 -translate-y-1/2 flex items-center pr-2 pointer-events-none">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
                class="w-6 h-6 text-muted-foreground/50"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                  clip-rule="evenodd"
                ></path>
              </svg>
            </span>
          </button>

          <ul
            x-show="open"
            @click.away="open = false"
            class="absolute left-0 w-full mt-1 bg-white border border-brand-grey rounded-md shadow-2xl max-h-50 overflow-auto z-10"
            x-transition
          >
            <template
              x-for="item in items"
              :key="item"
            >
              <li
                @click="selected = item; open = false"
                class="px-4 py-3 hover:bg-brand-light-grey cursor-pointer capitalize"
              >
                <span x-text="item"></span>
              </li>
            </template>
          </ul>

        </div>


        <?php
          break;

          case 'checkbox':
      ?>

        <div
          class="relative col-span-2"
          x-data="{
              checkboxSelectedValue: null,
              checkboxOptions: JSON.parse($el.dataset.options)
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
        >
          <?php if ( ! empty( $field['label'] ) ) : ?>
          <label class="block mb-3 text-medium text-center">
            <?php echo esc_html($field['label']); ?>
            <?php if ( ! empty( $field['required'] ) ) : ?>
            <span>*</span>
            <?php endif; ?>
          </label>
          <?php endif; ?>
          <div class="grid grid-cols-2 gap-2">
            <template
              x-for="(option, index) in checkboxOptions"
              :key="index"
            >
              <label
                @click="checkboxSelectedValue = option.value"
                class="flex items-center justify-center p-5 space-x-3 bg-white border border-brand-grey rounded-md  cursor-pointer <?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>"
              >
                <input
                  type="checkbox"
                  name="<?php echo esc_attr($field['name'] ?? 'checbox-group'); ?>"
                  :value="option.value"
                  x-model="radioGroupSelectedValue"
                  class="accent-brand-dark-blue translate-y-px focus:ring-brand-dark-blue !h-4.5 !w-4.5"
                />
                <span class="relative flex flex-col text-left space-y-1.5 leading-none">
                  <span
                    x-text="option.title"
                    class="font-normal tex-left capitalize"
                  ></span>
                </span>
              </label>

            </template>
          </div>
          </label>
        </div>

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
          class="col-span-2 my-2"
        >
          <?php if ( ! empty( $field['label'] ) ) : ?>
          <label class="block mb-3 text-medium text-center">
            <?php echo esc_html($field['label']); ?>
            <?php if ( ! empty( $field['required'] ) ) : ?>
            <span>*</span>
            <?php endif; ?>
          </label>
          <?php endif; ?>
          <div class="grid grid-cols-2 gap-2">
            <template
              x-for="(option, index) in radioGroupOptions"
              :key="index"
            >
              <label
                @click="radioGroupSelectedValue = option.value"
                class="flex items-start p-5 space-x-3 bg-white border border-brand-grey rounded-md  cursor-pointer <?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>"
              >
                <input
                  type="radio"
                  name="<?php echo esc_attr($field['name'] ?? 'radio-group'); ?>"
                  :value="option.value"
                  x-model="radioGroupSelectedValue"
                  class="accent-brand-dark-blue translate-y-px focus:ring-brand-dark-blue"
                />
                <span class="relative flex flex-col text-left space-y-1.5 leading-none">
                  <span
                    x-text="option.title"
                    class="font-normal tex-left capitalize"
                  ></span>
                </span>
              </label>

            </template>
          </div>
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
