<?php
/**
 * Pricing Table Block - Server-side render using Alpine.js for tabs
 * Fixed version with USD as default currency and proper CAD conversion
 */

$heading = $attributes['heading'] ?? 'Milton Lake Lodge Mini-Lodge & Outpost Combo Trips';
$tabs = $attributes['tabs'] ?? [];
$rates = get_exchange_rates('USD');
$section_id = !empty($attributes['sectionId']) ? esc_attr($attributes['sectionId']) : '';
$whats_included = $attributes['whatsIncluded'] ?? '';

error_log('Exchange rates: ' . print_r($rates, true));

$exchange_rate = isset($rates['CAD']) ? $rates['CAD'] : 1.3;
?>

<section
  id="<?php echo $section_id; ?>"
  class="plugin-custom-block not-prose section-padding w-full"
  <?php echo get_block_wrapper_attributes(); ?>
>
  <div class="max-w-container mx-auto flex flex-col gap-8">

    <h2 class="text-center heading-two">
      <?php echo esc_html($heading); ?>
    </h2>

    <?php if (!empty($tabs)): ?>
    <div
      x-data="{
          activeTab: 1,
          currency: 'USD',
          exchangeRate: <?php echo $exchange_rate; ?>,
          setTab(tabIndex) {
              this.activeTab = tabIndex;
          },
          convertToCAD(price) {
              if (!price || isNaN(parseFloat(price))) return price;
              return parseFloat(price) * this.exchangeRate;
          },
          formatPrice(price, priceType) {
              if (priceType !== 'currency') return price;
              if (!price || isNaN(parseFloat(price))) return 'N/A';

              const amount = this.currency === 'CAD' ?
                  this.convertToCAD(price) :
                  parseFloat(price);

              return new Intl.NumberFormat('en-US', {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 2
              }).format(amount);
          }
      }"
      class="flex flex-col gap-8"
    >

      <div class="bg-white rounded-lg sm:rounded-full w-fit mx-auto p-1">
        <div class="flex flex-col sm:flex-row justify-center">
          <?php foreach ($tabs as $index => $tab): ?>
          <button
            @click="setTab(<?php echo $index + 1; ?>)"
            :class="{
                'bg-brand-green text-white ': activeTab === <?php echo $index + 1; ?>,
                'text-gray-500 hover:text-gray-800': activeTab !== <?php echo $index + 1; ?>
            }"
            class="px-4 py-2  rounded-full transition-all duration-300 cursor-pointer whitespace-nowrap"
          >
            <?php echo esc_html($tab['title']); ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="relative overflow-hidden h-auto  w-full max-w-4xl mx-auto">
        <?php foreach ($tabs as $index => $tab): ?>
        <div
          x-show="activeTab === <?php echo $index + 1; ?>"
          class="bg-white rounded-2xl overflow-hidden"
          x-cloak
        >
          <div class="grid grid-cols-3 md:grid-cols-2 !py-6 !px-4 sm:!px-8 !text-base !text-gray-800 gap-3 lg:gap-10">
            <p class='col-span-2 md:col-span-1'>Package Type</p>
            <div class="flex  justify-end md:justify-start items-center gap-4 flex-wrap">
              <p class="!mb-0">Per Person</p>
              <div class="bg-neutral-100 rounded-full w-fit p-1">
                <div class="flex  justify-center text-sm">
                  <template
                    x-for="curr in ['USD', 'CAD']"
                    :key="curr"
                  >
                    <button
                      @click="currency = curr"
                      :class="currency === curr ? 'bg-brand-green text-white' : 'text-black'"
                      class="px-4 py-1 text-sm rounded-full transition-all duration-300 cursor-pointer whitespace-nowrap"
                      x-text="curr"
                    ></button>
                  </template>
                </div>
              </div>
            </div>
          </div>

          <?php if (!empty($tab['features'])): ?>
          <?php foreach ($tab['features'] as $feature): ?>
          <div class="grid grid-cols-3 md:grid-cols-2 pb-6 gap-3 lg:gap-10 !pt-0 !px-4 sm:!px-8  ">
            <div class="mb-2 md:mb-0 col-span-2 md:col-span-1">
              <h3 class="!text-lg !font-medium !font-sans !capitalize ">
                <?php echo esc_html($feature['title']); ?>
              </h3>
              <?php if (!empty($feature['description'])): ?>
              <p class="!text-sm !my-0 sm:!max-w-[70%] text-neutral-500">
                <?php echo esc_html($feature['description']); ?>
              </p>
              <?php endif; ?>
            </div>
            <div class="flex justify-end md:justify-start items-start">
              <p class='!text-right'>
                <?php if (($feature['priceType'] ?? 'currency') === 'currency'): ?>
                <?php
                $price = $feature['price'] ?? '';
                $is_numeric = is_numeric($price);
                ?>
                <?php if ($is_numeric): ?>
                <span class="text-lg sm:!text-xl !font-medium ">
                  $<span x-text="formatPrice('<?php echo esc_js($price); ?>', 'currency')"></span>
                </span>
                <span
                  class="!text-sm"
                  x-text="currency"
                ></span>
                <?php else: ?>
                <span class="!text-xl !font-medium text-red-500">
                  Invalid Price
                </span>
                <span class="!text-xs text-red-500 block">
                  (Currency prices must be numeric)
                </span>
                <?php endif; ?>
                <?php else: ?>
                <span class="text-base sm:!text-lg md:!text-xl break-words whitespace-normal block max-w-full ">
                  <?php echo esc_html($feature['price']); ?>
                </span>
                <?php endif; ?>
              </p>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!empty($tab['whatsIncluded'])): ?>
          <div class="border-t border-brand-grey !p-4 sm:!p-8 text-gray-800">
            <h3 class="!text-lg !font-medium !font-sans !capitalize mb-2">
              What's Included
            </h3>
            <ul class="list-disc pl-5">
              <?php foreach ($tab['whatsIncluded'] as $item): ?>
              <li class="w-full">
                <?php echo esc_html($item); ?>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <?php if (!empty($tab['note'])): ?>
          <div class="border-t border-brand-grey !p-4 sm:!p-8 text-gray-800">
            <p class="!text-base">
              <?php echo esc_html($tab['note']); ?>
            </p>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
