const TwoCol = ( {
	topHeading,
	heading,
	buttonText,
	buttonUrl,
	image,
	image2,
	text,
	inverted,
} ) => {
	return (
		<section className="not-prose section-padding w-full static-background">
			<div className="relative max-w-container mx-auto w-full flex flex-col md:flex-row items-center gap-10 justify-between">
				<div
					className={ `${
						inverted ? 'order-first' : 'order-last'
					} flex flex-col gap-4 w-full` }
				>
					<div className="w-full flex flex-col gap-2">
						<div className="flex flex-col gap-1 w-full">
							<p className="decorative-text !text-brand-yellow-dark text-3xl lg:!text-4xl !my-0 text-center md:text-left">
								{ topHeading }
							</p>
							<h2 className="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600] text-center md:text-left">
								{ heading }
							</h2>
						</div>
						<p className="!my-0 text-base leading-relaxed text-center md:text-left">
							{ text }
						</p>
					</div>
					<a
						href={ buttonUrl || '#' }
						className="w-fit mx-auto md:mx-0 group"
					>
						<span className="inline-flex !text-black items-center gap-1 border-b border-black pb-[2px]">
							<span className="text-black text-base">
								{ buttonText || 'Learn More' }
							</span>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="16"
								height="16"
								viewBox="0 0 24 24"
								fill="none"
								stroke="currentColor"
								strokeWidth="1.5"
								strokeLinecap="round"
								strokeLinejoin="round"
								className="lucide lucide-arrow-right transition-transform duration-300 ease-in-out group-hover:translate-x-[3px]"
							>
								<path d="M5 12h14" />
								<path d="m12 5 7 7-7 7" />
							</svg>
						</span>
					</a>
				</div>
				<div className=" w-full flex items-center gap-5 justify-end  md:max-w-[40%] p-4 sm:p-0">
					<div className="relative mx-auto">
						<div className="flex justify-center items-center relative ">
							{ image?.url && (
								<div className="w-full aspect-[3/4] max-w-[260px] md:max-w-[340px] rounded-lg shadow-lg rotate-[-8deg]  bg-white p-1">
									<img
										src={ image.url }
										className="w-full h-full aspect-[3/4] max-w-[260px] md:max-w-[340px] object-cover "
									/>
								</div>
							) }

							{ image2?.url && (
								<div className="w-full  aspect-[3/4] max-w-[260px] md:max-w-[340px]  rounded-lg shadow-lg  -ml-24 sm:-ml-32 lg:-ml-[160px] z-10 bg-white p-1 rotate-[2deg]">
									<img
										src={ image2.url }
										className="w-full h-full aspect-[3/4] max-w-[260px] md:max-w-[340px] object-cover "
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

export default TwoCol;
