const TwoCol = ( {
	heading,
	text,
	buttonText,
	buttonUrl,
	image,
	image2,
	inverted,
	sectionId,
} ) => {
	const contentOrderClass = inverted ? 'order-first' : 'order-last';

	return (
		<section
			id={ sectionId || undefined }
			className="plugin-custom-block not-prose section-padding w-full static-background"
		>
			<div className="relative max-w-container mx-auto w-full flex flex-col gap-14 sm:gap-20 900:!grid 900:!grid-cols-5 900:!gap-8 itemts-start 900:items-center">
				<div
					className={ `${ contentOrderClass } flex flex-col gap-4 w-full 900:col-span-2 900:max-w-[600px] 900:mx-auto` }
				>
					<div className="flex flex-col gap-2 w-full">
						<h2 className="heading-two text-left">{ heading }</h2>
						<p className="!my-0 text-left !text-base lg:!text-lg">
							{ text }
						</p>
					</div>
					{ buttonUrl && buttonText && (
						<a href={ buttonUrl }>
							<button className="flex flex-row items-center w-fit gap-1 cursor-pointer !text-lg group relative pb-0.5">
								<div className="flex items-center border-b border-black gap-1 pb-0.5">
									<span className="!text-black !text-base sm:!text-lg">
										{ buttonText }
									</span>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										width="18"
										height="18"
										fill="#000000"
										viewBox="0 0 256 256"
										className="group-hover:translate-x-1 transition-transform duration-300 ease-in-out"
									>
										<path d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z" />
									</svg>
								</div>
							</button>
						</a>
					) }
				</div>

				<div class="flex justify-center items-center relative col-span-3">
					<div
						className={ `shadow-lg rounded-lg overflow-hidden w-full max-w-[800px] ` }
					>
						{ image && image.id && (
							<img
								src={ image.url }
								alt={ image.alt || '' }
								className="aspect-[3/2] w-full object-cover"
								loading="lazy"
								decoding="async"
								fetchPriority="high"
							/>
						) }
					</div>
				</div>
			</div>
		</section>
	);
};

export default TwoCol;
