import { useEffect, useState } from 'react';

const Showcase = ( {
	heading,
	buttonText,
	buttonUrl,
	images = [],
	imagesSpeed,
	backgroundImage,
} ) => {
	const [ duration, setDuration ] = useState( '30s' );

	// Map speed options to duration values
	const speedMap = {
		slow: 60,
		medium: 30,
		fast: 15,
	};

	useEffect( () => {
		const updateSpeed = () => {
			const isMobile = window.innerWidth < 768;
			let baseDuration = speedMap[ imagesSpeed ] || 30;

			if ( isMobile ) {
				baseDuration = Math.max( baseDuration - 15, 5 );
			}

			setDuration( `${ baseDuration }s` );
		};

		updateSpeed();
		window.addEventListener( 'resize', updateSpeed );
		return () => window.removeEventListener( 'resize', updateSpeed );
	}, [ imagesSpeed ] );

	return (
		<section
			className="plugin-custom-block flex flex-col gap-24 overflow-hidden relative not-prose section-padding w-full bg-brand-green bg-repeat bg-blend-hard-light bg-size-[450px]"
			style={ {
				backgroundImage: backgroundImage?.url
					? `url('${ backgroundImage.url }')`
					: "url('http://milton-lodge.local/wp-content/uploads/effects/green-topo.png')",
			} }
		>
			<div className="relative z-[2] max-w-container flex flex-row flex-wrap gap-5 items-end justify-between">
				<div className="flex flex-col gap-2 lg:max-w-2xl w-full">
					{ heading && (
						<h2 className="heading-two !text-left !text-white">
							{ heading }
						</h2>
					) }
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
					style={ { animationDuration: duration } }
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
								className={ `${ rotationClass } !w-[calc(100vw-40px)] sm:!w-[calc(50vw-40px)] md:!w-[calc(33.33vw-40px)] lg:!w-[calc(25vw-40px)]` }
							>
								<img
									src={
										image?.sizes?.large?.url ||
										image?.url ||
										'/placeholder.svg'
									}
									srcSet={ `
                    ${ image?.sizes?.thumbnail?.url || '' } 150w,
                    ${ image?.sizes?.medium?.url || '' } 300w,
                    ${ image?.sizes?.large?.url || '' } 1024w,
                    ${ image?.sizes?.full?.url || image?.url || '' } ${
						image?.width || ''
					}w
                  ` }
									sizes="(max-width: 639px) 100vw, (max-width: 767px) 50vw, (max-width: 1023px) 33vw, 25vw"
									alt={ image?.alt || '' }
									className="flex-shrink-0 h-full aspect-[3/4] w-full object-cover"
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
