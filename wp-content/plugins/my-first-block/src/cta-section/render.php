<section class="not-prose section-padding w-full static-background">
	<div class="relative max-w-container mx-auto bg-brand-dark-blue text-white py-16 px-10 rounded-xl w-full">
		<div class="flex flex-col gap-4 w-full md:max-w-[70%] lg:max-w-[60%]">
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
			<div class="absolute right-1/4 md:right-10 w-full flex items-center gap-5 justify-end max-w-[60%] md:max-w-[40%] bottom-[300px] md:bottom-14">
				<div class="relative">
					<div class="flex justify-center items-center relative">
						<?php if ($image1_url): ?>
							<div class="w-full aspect-[3/4] max-w-[260px] md:max-w-[340px] rounded-lg shadow-lg rotate-[-8deg] bg-white p-1">
								<img
									src="<?php echo esc_url($image1_url); ?>"
									alt="<?php echo esc_attr($image1_alt); ?>"
									class="w-full h-full aspect-[3/4] max-w-[260px] md:max-w-[340px] object-cover"
								/>
							</div>
						<?php endif; ?>

						<?php if ($image2_url): ?>
							<div class="w-full aspect-[3/4] max-w-[260px] md:max-w-[340px] rounded-lg shadow-lg -ml-24 sm:-ml-32 lg:-ml-[160px] z-10 bg-white p-1 rotate-[2deg]">
								<img
									src="<?php echo esc_url($image2_url); ?>"
									alt="<?php echo esc_attr($image2_alt); ?>"
									class="w-full h-full aspect-[3/4] max-w-[260px] md:max-w-[340px] object-cover"
								/>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
