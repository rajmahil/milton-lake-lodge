const Cta = ( {
	topHeading,
	heading,
	buttonText,
	buttonUrl,
	image,
	image2,
} ) => {
	return (
		<section className="not-prose section-padding w-full static-background">
			<div className="relative max-w-container mx-auto flex items-center justify-between gap-5 bg-brand-dark-blue text-white py-16 px-10 rounded-xl w-full">
				<div className="flex flex-col gap-4 w-full max-w-[60%]">
					<div className="flex flex-col gap-3 w-full">
						<p className="decorative-text !text-brand-yellow-dark text-3xl lg:!text-4xl !my-0 text-center sm:text-left">
							{ topHeading }
						</p>
						<h2 className="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600] max-w-none md:max-w-[60%] text-center sm:text-left">
							{ heading }
						</h2>
					</div>
					<a href={ buttonUrl || '#' } className="w-fit">
						<button className="btn btn-outline btn-xl">
							{ buttonText || 'Learn More' }
						</button>
					</a>
				</div>
				<div className="absolute right-10 w-full flex items-center gap-5 justify-end max-w-[40%] bottom-14">
					<div className="relative">
						<div className="flex justify-center items-center relative ">
							{ image?.url && (
								<div className="w-full aspect-[3/4] max-w-[200px] sm:max-w-[260px] md:max-w-[340px] rounded-lg shadow-lg rotate-[-8deg]  bg-white p-1">
									<img
										src={ image.url }
										className="w-full h-full aspect-[3/4] max-w-[200px] sm:max-w-[260px] md:max-w-[340px] object-cover "
									/>
								</div>
							) }

							{ image2?.url && (
								<div className="w-full  aspect-[3/4] max-w-[200px] sm:max-w-[260px] md:max-w-[340px]  rounded-lg shadow-lg  -ml-24 sm:-ml-32 lg:-ml-[160px] z-10 bg-white p-1 rotate-[2deg]">
									<img
										src={ image2.url }
										className="w-full h-full aspect-[3/4] max-w-[200px] sm:max-w-[260px] md:max-w-[340px] object-cover "
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
