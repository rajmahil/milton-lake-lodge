const Showcase = ( {
	heading,
	buttonText,
	buttonUrl,
	images = [],
	imagesSpeed,
	backgroundImage,
	sectionId,
	text,
} ) => {
	const speedMap = {
		slow: 90,
		medium: 60,
		fast: 30,
	};

	const baseDuration = speedMap[ imagesSpeed ] ?? 30;

	console.log( 'images', images );

	return (
		<section
			id={ sectionId || undefined }
			className="plugin-custom-block flex flex-col gap-24 overflow-hidden relative not-prose section-padding w-full bg-brand-green bg-repeat bg-blend-hard-light bg-size-[450px]"
			style={ {
				backgroundImage: backgroundImage?.url
					? `url('${ backgroundImage.url }')`
					: "url('http://milton-lodge.local/wp-content/uploads/effects/green-topo.png')",
			} }
		>
			<div className="relative z-[2] max-w-container flex flex-row flex-wrap gap-5 items-end justify-between">
				<div className="flex flex-col gap-2 lg:max-w-4xl w-full">
					{ heading && (
						<h2 className="heading-two !text-left !text-white">
							{ heading }
						</h2>
					) }
					{ text && <p class="text-xl text-white">{ text }</p> }
				</div>

				<div>
					<a href={ buttonUrl || '#' }>
						<button
							className="btn btn-outline btn-xl"
							type="button"
						>
							{ buttonText || 'Learn More' }
						</button>
					</a>
				</div>
			</div>

			{ /* Sliding Images Gallery */ }
			<div className="group relative w-full h-full select-none">
				<div
					className="flex w-max animate-slide gap-10 whitespace-nowrap"
					style={ { animationDuration: `${ baseDuration }s` } }
				>
					{ [ ...images, ...images ].map( ( image, idx ) => {
						const rotationClass =
							idx % 3 === 0
								? 'rotate-[-3deg]'
								: idx % 3 === 1
								? 'rotate-[2deg]'
								: 'rotate-[-1deg]';

						return (
							<div
								key={ `showcase-${
									image.id || idx
								}-${ Math.floor( idx / images.length ) }` }
								className={ `${ rotationClass } !w-[calc(90vw-40px)] lg:!w-[calc(50vw-40px)] xl:!w-[calc(33.33vw-40px)] px-4` }
							>
								<img
									src={
										image?.sizes?.large?.url ||
										image?.url ||
										'/placeholder.svg'
									}
									sizes="(max-width: 639px) 100vw, (max-width: 767px) 50vw, (max-width: 1023px) 33vw, 25vw"
									alt={ image?.alt || '' }
									className="w-full h-auto "
									loading="lazy"
									decoding="async"
								/>
							</div>
						);
					} ) }
				</div>
			</div>

			{ /* Structured Data for SEO */ }
			{ heading && (
				<script type="application/ld+json">
					{ JSON.stringify( {
						'@context': 'https://schema.org',
						'@type': 'WebPageElement',
						name: 'Showcase Section',
						description: heading,
						images: images[ 0 ]?.url || '',
					} ) }
				</script>
			) }
		</section>
	);
};

export default Showcase;
