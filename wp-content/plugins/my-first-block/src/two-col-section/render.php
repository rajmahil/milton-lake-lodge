<?php
/**
 * Two Col Section Block - Render Template
 */

$top_heading = $attributes['topHeading'] ?? 'Top Heading';
$heading = $attributes['heading'] ?? 'Main Heading';
$text = $attributes['text'] ?? 'Text goes here.';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$image1 = $attributes['image'] ?? null;
$image2 = $attributes['image2'] ?? null;
$inverted = $attributes['inverted'] ?? false;

// Image 1
$image1_url = '';
$image1_alt = '';
if ($image1) {
	if (is_array($image1)) {
		$image1_url = $image1['url'] ?? ($image1['sizes']['large']['url'] ?? '');
		$image1_alt = $image1['alt'] ?? '';
	} elseif (is_numeric($image1)) {
		$image1_url = wp_get_attachment_image_url($image1, 'large');
		$image1_alt = get_post_meta($image1, '_wp_attachment_image_alt', true);
	} elseif (is_string($image1)) {
		$image1_url = $image1;
	}
}

// Image 2
$image2_url = '';
$image2_alt = '';
if ($image2) {
	if (is_array($image2)) {
		$image2_url = $image2['url'] ?? ($image2['sizes']['large']['url'] ?? '');
		$image2_alt = $image2['alt'] ?? '';
	} elseif (is_numeric($image2)) {
		$image2_url = wp_get_attachment_image_url($image2, 'large');
		$image2_alt = get_post_meta($image2, '_wp_attachment_image_alt', true);
	} elseif (is_string($image2)) {
		$image2_url = $image2;
	}
}

$content_order_class = $inverted ? 'order-first' : 'order-last';
?>

<section class="not-prose section-padding w-full static-background">
	<div class="relative max-w-container mx-auto w-full flex flex-col md:flex-row items-center gap-10 justify-between">

		<div class="<?php echo esc_attr($content_order_class); ?> flex flex-col gap-4 w-full">
			<div class='flex flex-col gap-2 w-full'>
			<div class="flex flex-col gap-1 w-full">
				<p class="decorative-text !text-brand-yellow-dark text-3xl lg:!text-4xl !my-0 text-center md:text-left">
					<?php echo esc_html($top_heading); ?>
				</p>
				<h2 class="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600] text-center md:text-left">
					<?php echo esc_html($heading); ?>
				</h2>
			</div>
			<p class="!my-0 text-base leading-relaxed  text-center md:text-left"><?php echo esc_html($text); ?></p></div>
			<a href="<?php echo esc_url($button_url); ?>" class="w-fit mx-auto md:mx-0 group">
				<span class="inline-flex items-center gap-1 border-b border-black pb-[2px]">
					<span class="text-black text-base">
						<?php echo esc_html($button_text); ?>
					</span>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						width="16"
						height="16"
						viewBox="0 0 24 24"
						fill="none"
						stroke="currentColor"
						stroke-width="1.5"
						stroke-linecap="round"
						stroke-linejoin="round"
						class="lucide lucide-arrow-right transition-transform duration-300 ease-in-out group-hover:translate-x-[3px]"
					>
						<path d="M5 12h14" />
						<path d="m12 5 7 7-7 7" />
					</svg>
				</span>
			</a>
		</div>
		<div class="w-full flex items-center gap-5 justify-end md:max-w-[40%] p-4">
			<div class="relative mx-auto">
				<div class="flex justify-center items-center relative">
					<?php if ($image1_url): ?>
						<div class="w-full aspect-[3/4] max-w-[260px] md:max-w-[340px] rounded-lg shadow-lg rotate-[-8deg] bg-white p-1">
							<img src="<?php echo esc_url($image1_url); ?>" alt="<?php echo esc_attr($image1_alt); ?>" class="w-full h-full aspect-[3/4] max-w-[260px] md:max-w-[340px] object-cover" />
						</div>
					<?php endif; ?>

					<?php if ($image2_url): ?>
						<div class="w-full aspect-[3/4] max-w-[260px] md:max-w-[340px] rounded-lg shadow-lg -ml-24 sm:-ml-32 lg:-ml-[160px] z-10 bg-white p-1 rotate-[2deg]">
							<img src="<?php echo esc_url($image2_url); ?>" alt="<?php echo esc_attr($image2_alt); ?>" class="w-full h-full aspect-[3/4] max-w-[260px] md:max-w-[340px] object-cover" />
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

	</div>
</section>
