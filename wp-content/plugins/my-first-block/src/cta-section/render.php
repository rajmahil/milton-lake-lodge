<?php
/**
 * CTA Section Block - Render Template
 */

$top_heading = $attributes['topHeading'] ?? 'Top Heading';
$heading = $attributes['heading'] ?? 'Main Heading';
$button_text = $attributes['buttonText'] ?? 'Learn More';
$button_url = $attributes['buttonUrl'] ?? '#';
$image1 = $attributes['image'] ?? null;
$image2 = $attributes['image2'] ?? null;

// Image 1
$image1_url = '';
$image1_alt = '';
$image1_id = null;
if ($image1) {
	if (is_array($image1)) {
		$image1_url = $image1['url'] ?? ($image1['sizes']['large']['url'] ?? '');
		$image1_alt = $image1['alt'] ?? '';
		$image1_id = $image1['id'] ?? null;
	} elseif (is_numeric($image1)) {
		$image1_id = $image1;
		$image1_url = wp_get_attachment_image_url($image1_id, 'large');
		$image1_alt = get_post_meta($image1_id, '_wp_attachment_image_alt', true);
	} elseif (is_string($image1)) {
		$image1_url = $image1;
	}
}

// Image 2
$image2_url = '';
$image2_alt = '';
$image2_id = null;
if ($image2) {
	if (is_array($image2)) {
		$image2_url = $image2['url'] ?? ($image2['sizes']['large']['url'] ?? '');
		$image2_alt = $image2['alt'] ?? '';
		$image2_id = $image2['id'] ?? null;
	} elseif (is_numeric($image2)) {
		$image2_id = $image2;
		$image2_url = wp_get_attachment_image_url($image2_id, 'large');
		$image2_alt = get_post_meta($image2_id, '_wp_attachment_image_alt', true);
	} elseif (is_string($image2)) {
		$image2_url = $image2;
	}
}
?>

<section class="not-prose section-padding w-full static-background">
	<div class="relative max-w-container mx-auto flex items-center justify-between gap-5 bg-brand-dark-blue text-white py-16 px-10 rounded-xl w-full">
		<div class="flex flex-col gap-4 w-full max-w-[60%]">
			<div class="flex flex-col gap-3 w-full">
				<p class="decorative-text !text-brand-yellow-dark text-3xl lg:!text-4xl !my-0 text-center sm:text-left">
					<?php echo esc_html($top_heading); ?>
				</p>
				<h2 class="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600] max-w-none md:max-w-[60%] text-center sm:text-left">
					<?php echo esc_html($heading); ?>
				</h2>
			</div>
			<a href="<?php echo esc_url($button_url); ?>" class="w-fit">
				<button class="btn btn-outline btn-xl">
					<?php echo esc_html($button_text); ?>
				</button>
			</a>
		</div>

		<?php if ($image1_url || $image2_url): ?>
		<div class="absolute right-10 w-full flex items-center gap-5 justify-end max-w-[40%] bottom-14">
			<div class="relative flex justify-center items-center">
				<?php if ($image1_url): ?>
				<div class="w-full aspect-[3/4] max-w-[200px] sm:max-w-[260px] md:max-w-[340px] rounded-lg shadow-lg rotate-[-8deg] bg-white p-1">
					<img
						src="<?php echo esc_url($image1_url); ?>"
						alt="<?php echo esc_attr($image1_alt); ?>"
						class="w-full h-full aspect-[3/4] object-cover"
					/>
				</div>
				<?php endif; ?>

				<?php if ($image2_url): ?>
				<div class="w-full aspect-[3/4] max-w-[200px] sm:max-w-[260px] md:max-w-[340px] rounded-lg shadow-lg -ml-24 sm:-ml-32 lg:-ml-[160px] z-10 bg-white p-1 rotate-[2deg]">
					<img
						src="<?php echo esc_url($image2_url); ?>"
						alt="<?php echo esc_attr($image2_alt); ?>"
						class="w-full h-full aspect-[3/4] object-cover"
					/>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>
