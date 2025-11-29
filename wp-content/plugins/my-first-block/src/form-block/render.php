<?php
/**
 * Form section - Server-side render
 * Optimized for performance, SEO, and accessibility
 */

$heading = $attributes['heading'] ?? 'Your Adventure Awaits';
$subheading = $attributes['subheading'] ?? 'Join us for an unforgettable experience.';
$fields = $attributes['fields'] ?? [];
$formTitle = $attributes['formTitle'] ?? [];
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';

?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block"
>
  <div class="section-padding static-background flex flex-col gap-12 !bg-brand-light-grey">
    <div class="max-w-3xl w-full mx-auto flex flex-col gap-0">
      <h2 class="heading-two text-center">
        <?php echo esc_html($heading); ?>
      </h2>
      <?php if (!empty($subheading)): ?>
      <p class="text-center text-xl text-neutral-600">
        <?php echo esc_html($subheading); ?>
      </p>
      <?php endif; ?>
    </div>

    <div
      class="max-w-2xl w-full mx-auto"
      x-data="{
          loading: false,
          humanAnswer: ''
      }"
      x-init="loading = false;
      window.addEventListener('pageshow', () => {
          loading = false;
      });"
    >
      <form
        @submit.prevent="loading = true; $el.submit()"
        class="grid grid-cols-2 gap-4"
        action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
        method="POST"
      >
        <input
          type="hidden"
          name="action"
          value="my_custom_form_submit"
        >
        <input
          type="hidden"
          name="form_template"
          value="main_form"
        >
        <input
          type="hidden"
          name="formTitle"
          value="<?php echo esc_attr($formTitle); ?>"
        >

        <?php foreach ( $fields as $index => $field ) : ?>
        <?php
         error_log( 'Current field: ' . print_r( $field, true ) );
        switch ( $field['type'] ) {
          case 'text':
          case 'email':
          case 'tel':
      ?>
        <input
          :disabled="loading"
          :class="loading ? 'opacity-50 cursor-not-allowed' : ' '"
          type="<?php echo esc_attr($field['type'] ?? 'text'); ?>"
          name="<?php echo esc_attr($field['name'] ?? ''); ?>"
          value="<?php echo esc_attr($field['value'] ?? ''); ?>"
          required="<?php echo !empty($field['required']) ? 'required' : ''; ?>"
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
          :disabled="loading"
          :class="loading ? 'opacity-50 cursor-not-allowed' : ' '"
          required="<?php echo !empty($field['required']) ? 'required' : ''; ?>"
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
          <?php if (!empty($field['label'])) : ?>
          <label class="block mb-3 text-medium text-center">
            <?php echo esc_html($field['label']); ?>
            <?php if (!empty($field['required'])) : ?>
            <span>*</span>
            <?php endif; ?>
          </label>
          <?php endif; ?>

          <input
            type="hidden"
            name="<?php echo esc_attr($field['name'] ?? ''); ?>"
            x-model="selected"
            <?php if (!empty($field['required'])) {
                echo 'required';
            } ?>
          >

          <button
            :disabled="loading"
            :class="loading ? 'opacity-50 cursor-not-allowed' : ' '"
            @click="open = !open"
            type="button"
            class="w-full form-input cursor-pointer flex items-center justify-start px-4 relative"
          >
            <p
              class="text-base capitalize"
              :class="!selected ? 'text-neutral-400' : ''"
              x-text="selected ? selected : placeholder"
            ></p>

            <span
              class="flex justify-center items-center pointer-events-none absolute right-0 h-14 px-2 text-neutral-400"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
                width="22"
                height="22"
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
              checkboxSelectedValue: [],
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
                class="flex items-center justify-start p-3  sm:p-5 space-x-3 bg-white border border-brand-grey rounded-md  cursor-pointer <?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>"
              >
                <input
                  :disabled="loading"
                  :class="loading ? 'opacity-50 cursor-not-allowed' : ' '"
                  type="checkbox"
                  name="<?php echo esc_attr($field['name'] ?? 'checkbox-group'); ?>[]"
                  :value="option.value"
                  x-model="checkboxSelectedValue"
                  class="accent-brand-green-dark translate-y-px focus:ring-brand-green-dark !min-h-4.5 !min-w-4.5"
                />
                <span class="relative flex flex-col text-left space-y-1.5">
                  <span
                    x-text="option.title"
                    class="font-normal tex-left capitalize leading-tight  mt-0.5"
                  ></span>
                </span>
              </label>

            </template>
          </div>
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
                class="flex items-start p-3 sm:p-5 space-x-3 bg-white border border-brand-grey rounded-md  cursor-pointer <?php echo $field['fullWidth'] === true ? 'col-span-2' : ''; ?>"
              >
                <input
                  :disabled="loading"
                  :class="loading ? 'opacity-50 cursor-not-allowed' : ' '"
                  type="radio"
                  name="<?php echo esc_attr($field['name'] ?? 'radio-group'); ?>"
                  :value="option.value"
                  x-model="radioGroupSelectedValue"
                  class="accent-brand-green-dark translate-y-px focus:ring-brand-green-dark"
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

        <!-- Simple human check instead of captcha -->
        <div class="col-span-2 mt-4">
          <label class="block mb-2 text-medium">
            What is 6 + 3?
          </label>
          <input
            name="human-check"
            type="number"
            min="0"
            x-model="humanAnswer"
            :disabled="loading"
            :class="loading ? 'opacity-50 cursor-not-allowed' : ' '"
            class="form-input w-full"
            placeholder="Enter your answer"
            required
          />
        </div>

        <input
          type="hidden"
          name="form_time"
          value="<?php echo time(); ?>"
        >

        <input
          type="text"
          name="website"
          id="website"
          style="display:none !important;"
        >

        <button
          id="form-submit-button"
          type="submit"
          :disabled="loading || Number(humanAnswer) !== 9"
          :class="(loading || Number(humanAnswer) !== 9) ? 'opacity-50 cursor-not-allowed' : ' '"
          class="btn btn-dark btn-xl col-span-2 h-14 !mt-4 w-full"
        >
          <?php echo esc_html($attributes['submitButtonText'] ?? 'Submit'); ?>

          <span
            x-show="loading"
            class="animate-spin text-white"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="26"
              height="26"
              fill="currentColor"
              viewBox="0 0 256 256"
              style="animation:spin 1s linear infinite;"
            >
              <path
                d="M136,32V64a8,8,0,0,1-16,0V32a8,8,0,0,1,16,0Zm37.25,58.75a8,8,0,0,0,5.66-2.35l22.63-22.62a8,8,0,0,0-11.32-11.32L167.6,77.09a8,8,0,0,0,5.65,13.66ZM224,120H192a8,8,0,0,0,0,16h32a8,8,0,0,0,0-16Zm-45.09,47.6a8,8,0,0,0-11.31,11.31l22.62,22.63a8,8,0,0,0,11.32-11.32ZM128,184a8,8,0,0,0-8,8v32a8,8,0,0,0,16,0V192A8,8,0,0,0,128,184ZM77.09,167.6,54.46,190.22a8,8,0,0,0,11.32,11.32L88.4,178.91A8,8,0,0,0,77.09,167.6ZM72,128a8,8,0,0,0-8-8H32a8,8,0,0,0,0,16H64A8,8,0,0,0,72,128ZM65.78,54.46A8,8,0,0,0,54.46,65.78L77.09,88.4A8,8,0,0,0,88.4,77.09Z"
              ></path>
            </svg>
          </span>
        </button>

      </form>
    </div>

  </div>
</section>
