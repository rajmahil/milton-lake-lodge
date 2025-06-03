const Features = ( { heading, features = [] } ) => {
	return (
		<section className="not-prose section-padding w-full bg-brand-grey ">
			<div className=" max-w-container mx-auto flex flex-col gap-16">
				{ heading && (
					<h2 className="!my-0  !text-3xl md:!text-4xl lg:!text-5xl !font-[600] !text-brand-dark-blue text-left lg:max-w-[60%]">
						{ heading }
					</h2>
				) }

				<div className="flex flex-col w-full gap-16 lg:gap-24">
					{ features.map( ( feature, idx ) => {
						const isEven = idx % 2 === 0;
						return (
							<div
								key={ `feature-${ idx }` }
								className={ `
          flex flex-col lg:flex-row items-start lg:items-center gap-8 md:gap-16 
          ${ isEven ? 'lg:flex-row' : 'lg:flex-row-reverse' }
        ` }
							>
								{ /* Image */ }
								<div className="relative w-full ml-2 max-w-[90%] md:max-w-[70%] lg:max-w-[45%] -rotate-2 border-2 border-black rounded-xl ">
									<img
										src={
											feature.image?.sizes?.large?.url ||
											feature.image?.url
										}
										alt={ feature.image?.alt || '' }
										className="w-full rounded-lg h-auto object-cover aspect-[1.8/1] relative z-[2] "
									/>
									<div className="rounded-xl w-full h-full absolute right-2 top-2 bg-black z-[1]"></div>
								</div>

								{ /* Text Area */ }
								<div className="w-full lg:w-1/2 text-brand-dark-blue">
									<h3 className="text-2xl font-semibold my-4">
										{ feature.heading }
									</h3>
									<p className="text-base leading-relaxed">
										{ feature.text }
									</p>
								</div>
							</div>
						);
					} ) }
				</div>
			</div>
		</section>
	);
};

export default Features;
