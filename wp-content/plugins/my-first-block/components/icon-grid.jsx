const IconGrid = ( props ) => {
	const {
		heading,
		buttonText,
		buttonUrl,
		items = [],
		sectionId = '',
	} = props;

	return (
		<section
			id={ sectionId }
			className="plugin-custom-block not-prose section-padding w-full"
		>
			<div className="max-w-container mx-auto flex flex-col gap-16">
				{ heading && (
					<div>
						<h2 className="heading-two text-center">{ heading }</h2>
					</div>
				) }

				<div className="relative w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 sm:gap-6">
					{ items.map( ( item, index ) => (
						<div className="col-span-1" key={ index }>
							<div className="flex flex-row items-center justify-start gap-1 sm:gap-2">
								{ item?.image?.url && (
									<img
										src={ item.image.url }
										alt={ item.image.alt || '' }
										className="w-16 h-16 object-contain object-center"
									/>
								) }
								{ item?.title && (
									<p className="text-left text-lg md:text-xl">
										{ item.title }
									</p>
								) }
							</div>
						</div>
					) ) }
				</div>

				{ buttonUrl && buttonText && (
					<a href={ buttonUrl }>
						<button className="flex flex-row items-center w-fit gap-1 cursor-pointer text-lg group relative pb-0.5 mx-auto">
							<div className="flex items-center border-b border-black gap-1 pb-0.5">
								<span>{ buttonText }</span>
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
		</section>
	);
};

export default IconGrid;
