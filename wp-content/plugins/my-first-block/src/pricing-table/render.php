<?php
/**
 * Pricing Table Block - Server-side render using Alpine.js for tabs
 */

$heading = $attributes['heading'] ?? 'Milton Lake Lodge Mini-Lodge & Outpost Combo Trips';
$tabs = $attributes['tabs'] ?? [];
?>

<section
  class="not-prose section-padding w-full"
  <?php echo get_block_wrapper_attributes(); ?>
>
  <div class="max-w-container mx-auto flex flex-col gap-8">

    <h2 class="!my-0 text-center heading-two uppercase text-brand-dark-green">
      <?php echo esc_html($heading); ?>
    </h2>

    <?php if (!empty($tabs)): ?>
    <div
      x-data="{
          activeTab: 1,
          setTab(tabIndex) {
              this.activeTab = tabIndex;
          }
      }"
      class="flex flex-col gap-8"
    >

      <div class="bg-white rounded-full w-fit mx-auto p-1">
        <div class="flex justify-center">
          <?php foreach ($tabs as $index => $tab): ?>
          <button
            @click="setTab(<?php echo $index + 1; ?>)"
            :class="{
                'bg-[#123C2A] text-white font-bold': activeTab === <?php echo $index + 1; ?>,
                'text-gray-500 hover:text-gray-800': activeTab !== <?php echo $index + 1; ?>
            }"
            class="px-6 py-2 text-lg rounded-full transition-all duration-300 cursor-pointer whitespace-nowrap"
          >
            <?php echo esc_html($tab['title']); ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <div
        class="relative overflow-hidden"
        style="height: auto; min-height: 300px;"
      >
        <?php foreach ($tabs as $index => $tab): ?>
        <div
          x-show="activeTab === <?php echo $index + 1; ?>"
          class="bg-white rounded-2xl overflow-hidden"
          x-cloak
        >
          <div class=" 750:grid 750:grid-cols-2 py-6 px-8 !text-base !text-gray-800 1000:gap-10">
            <p class='!hidden 750:!block'>Package Type</p>
            <div
              class="flex items-center gap-10"
              x-data="{ currency: 'CAD' }"
            >
              <p class="!mb-0 !hidden 750:!block">Per Person</p>
              <div class="bg-stone-200 rounded-full w-fit p-1">
                <div class="flex justify-center text-sm">
                  <template
                    x-for="curr in ['USD', 'CAD']"
                    :key="curr"
                  >
                    <button
                      @click="currency = curr"
                      :class="currency === curr ? 'bg-[#123C2A] text-white' : 'text-black'"
                      class="px-3 py-1 text-sm rounded-full transition-all duration-300 cursor-pointer whitespace-nowrap"
                      x-text="curr"
                    ></button>
                  </template>
                </div>
              </div>
            </div>
          </div>
          <div class='pt-8 750:pt-0'>
            <?php if (!empty($tab['features'])): ?>
            <?php foreach ($tab['features'] as $feature): ?>
            <div class="grid 750:grid-cols-2 pb-6 1000:gap-10 !pt-0 px-8">
              <div class="mb-2 750:mb-0">
                <span class="!text-xl !font-medium !capitalize">
                  <?php echo esc_html($feature['title']); ?>
                </span>
                <?php if (!empty($feature['description'])): ?>
                <p class="!text-sm !my-0 750:!max-w-[70%]">
                  <?php echo esc_html($feature['description']); ?>
                </p>
                <?php endif; ?>
              </div>
              <div class="mt-2 750:mt-0">
                <p>
                  <?php if (($feature['priceType'] ?? 'currency') === 'currency'): ?>
                  <span class="!text-xl !font-medium">
                    $<?php echo esc_html($feature['price']); ?>
                  </span>
                  <span class="!text-sm">CAD</span>
                  <?php else: ?>
                  <?php echo esc_html($feature['price']); ?>
                  <?php endif; ?>
                </p>
              </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p class="text-gray-800 px-8 !py-0 !pb-5 !text-base">
              No features available for this package.
            </p>
            <?php endif; ?>
          </div>

          <?php if (!empty($tab['note'])): ?>
          <div class="border-t border-brand-grey p-8">
            <p class="text-base text-neutral-600">
              <?php echo esc_html($tab['note']); ?>
            </p>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php else: ?>
    <div class="text-center px-8 !py-0 !pb-5 text-gray-500">
      No packages avaialble yet. Please add packages in the editor.
    </div>
    <?php endif; ?>
  </div>
</section>
