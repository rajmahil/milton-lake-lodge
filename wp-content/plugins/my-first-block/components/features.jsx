const Features = ( { heading, features = [] } ) => {
	return (
		<section className="plugin-custom-block not-prose section-padding w-full static-background">
			<div className="max-w-container mx-auto flex flex-col gap-16">
				{ heading && (
					<h2 className="heading-two text-left lg:max-w-[60%]">
						{ heading }
					</h2>
				) }

				<div className="flex flex-col w-full gap-16 lg:gap-24">
					{ features.map( ( feature, idx ) => {
						const isEven = idx % 2 === 0;

						const image = feature.image || {};
						const imageUrl =
							image?.sizes?.large?.url || image?.url || '';
						const imageAlt = image?.alt || '';

						return (
							<div
								key={ `feature-${ idx }` }
								className={ `flex flex-col lg:flex-row !items-start lg:!items-center gap-8 md:gap-16 ${
									isEven
										? 'lg:!flex-row'
										: 'lg:!flex-row-reverse'
								}` }
							>
								<div className="relative w-full lg:!ml-2 max-w-[90%] md:max-w-[70%] lg:max-w-[45%]">
									{ imageUrl ? (
										<img
											src={ imageUrl }
											alt={ imageAlt }
											className="w-full rounded-lg h-auto object-cover aspect-[4/3] relative z-[2]"
										/>
									) : (
										<div className="w-full rounded-lg h-auto aspect-[4/3] bg-gray-200 border-2 border-dashed" />
									) }
								</div>

								<div className="w-full lg:w-1/2">
									<h3 className="!text-2xl sm:!text-3xl md:!text-4xl lg:!text-5xl font-semibold !my-4">
										{ feature.heading }
									</h3>
									<p className="!text-base lg:!text-lg leading-relaxed lg:!max-w-[80%]">
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
