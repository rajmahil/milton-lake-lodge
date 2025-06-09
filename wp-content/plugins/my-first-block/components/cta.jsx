const Cta = ( {
	topHeading,
	heading,
	buttonText,
	buttonUrl,
	image,
	image2,
} ) => {
	return (
		<section className="plugin-custom-block not-prose section-padding w-full static-background">
			<div className="relative max-w-container  grid 1050:grid-cols-2 items-center  gap-20 1050:gap-10 bg-brand-green-dark text-white py-16 px-10 rounded-xl w-full">
				<div className="flex flex-col gap-4 w-full">
					<div className="flex flex-col gap-3 w-full items-center 1050:items-start">
						{ topHeading && (
							<p className="decorative-text !text-brand-yellow-dark text-3xl lg:!text-4xl !my-0">
								{ topHeading }
							</p>
						) }
						{ heading && (
							<h2 className="!text-3xl md:!text-4xl lg:!text-5xl !font-[600] text-center 1050:text-left  !my-0">
								{ heading }
							</h2>
						) }
					</div>
					{ buttonText && (
						<a
							href={ buttonUrl || '#' }
							className="w-fit mx-auto 1050:mx-0 "
						>
							<button className="btn btn-outline btn-xl">
								{ buttonText }
							</button>
						</a>
					) }
				</div>
				<div class="1050:absolute w-full flex !items-center gap-5 !justify-end !bottom-10 !right-0">
					<div class="relative mx-auto 1050:mx-0">
						<div class="flex !justify-center !items-center relative">
							{ image?.url && (
								<div className="w-full aspect-[3/4] !max-w-[260px] 700:!max-w-[250px] 1050:!max-w-[300px] rotate-[-8deg] rounded-lg shadow-lg bg-white p-1">
									<img
										src={ image.url }
										alt=""
										class="w-full h-full !aspect-[3/4] !max-w-[260px] 700:!max-w-[250px] 1050:!max-w-[300px] object-cover"
									/>
								</div>
							) }
							{ image2?.url && (
								<div class="w-full aspect-[3/4] !max-w-[260px] 700:!max-w-[250px] 1050:!max-w-[300px]  rounded-lg shadow-lg -ml-[20%] z-10 bg-white p-1 rotate-[2deg]">
									<img
										src={ image2.url }
										alt=""
										class="w-full h-full !aspect-[3/4] !max-w-[260px] 700:!max-w-[250px] 1050:!max-w-[300px] object-cover"
									/>
								</div>
							) }
						</div>
					</div>
				</div>
			</div>
		</section>
	);
};

export default Cta;
