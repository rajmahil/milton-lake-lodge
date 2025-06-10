<?php
/**
 * Pricing Table Block - Server-side render using Alpine.js for tabs
 * Fixed version with proper currency validation and conversion
 */

$heading = $attributes['heading'] ?? 'Milton Lake Lodge Mini-Lodge & Outpost Combo Trips';
$tabs = $attributes['tabs'] ?? [];
$exchange_rate = $attributes['exchange_rate'] ?? 1.25; // CAD to USD rate
?>

<section
  class="plugin-custom-block not-prose section-padding w-full"
  <?php echo get_block_wrapper_attributes(); ?>
>
  <div class="max-w-container mx-auto flex flex-col gap-8">

    <h2 class="text-center heading-two font-extrabold uppercase text-brand-green">
      <?php echo esc_html($heading); ?>
    </h2>

    <?php if (!empty($tabs)): ?>
    <div
      x-data="{
          activeTab: 1,
          currency: 'CAD',
          exchangeRate: <?php echo $exchange_rate; ?>,
          setTab(tabIndex) {
              this.activeTab = tabIndex;
          },
          convertPrice(price, fromCAD = true) {
              if (!price || isNaN(parseFloat(price))) return price;
              const numPrice = parseFloat(price);
              if (this.currency === 'CAD') {
                  return fromCAD ? numPrice : (numPrice * this.exchangeRate);
              } else {
                  return fromCAD ? (numPrice / this.exchangeRate) : numPrice;
              }
          },
          formatPrice(price, priceType) {
              if (priceType !== 'currency') return price;
              if (!price || isNaN(parseFloat(price))) return 'N/A';
              const converted = this.convertPrice(price, true);
              return new Intl.NumberFormat('en-US', {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 0
              }).format(Math.round(converted));
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
            class="px-6 py-2 text-lg rounded-full transition-all duration-300 cursor-pointer whitespace-nowrap"
          >
            <?php echo esc_html($tab['title']); ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="relative overflow-hidden h-auto max-w-5xl w-full mx-auto">
        <?php foreach ($tabs as $index => $tab): ?>
        <div
          x-show="activeTab === <?php echo $index + 1; ?>"
          class="bg-white rounded-2xl overflow-hidden"
          x-cloak
        >
          <div class="grid grid-cols-3 md:grid-cols-2 py-6 px-4 sm:px-8 !text-base !text-gray-800 gap-3 lg:gap-10">
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
          <div class="grid grid-cols-3 md:grid-cols-2 pb-6 gap-3 lg:gap-10 !pt-0 px-4 sm:px-8 ">
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
              <p>
                <?php if (($feature['priceType'] ?? 'currency') === 'currency'): ?>
                <?php
                $price = $feature['price'] ?? '';
                $is_numeric = is_numeric($price);
                ?>
                <?php if ($is_numeric): ?>
                <span class="text-lg sm:!text-xl !font-medium">
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
                <span
                  class="text-base sm:!text-lg md:!text-xl break-words whitespace-normal block max-w-full text-right"
                >
                  <?php echo esc_html($feature['price']); ?>
                </span>
                <?php endif; ?>
              </p>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
          <?php if (!empty($tab['note'])): ?>
          <div class="border-t border-brand-grey p-4 sm:p-8 text-gray-800">
            <p class="!text-sm">
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
