import { useEffect, useState } from 'react';

const Showcase = ( {
	topHeading,
	heading,
	buttonText,
	buttonUrl,
	images = [],
	imagesSpeed,
} ) => {
	const [ duration, setDuration ] = useState( '30s' );

	useEffect( () => {
		const updateSpeed = () => {
			const isMobile = window.innerWidth < 768;
			let baseDuration =
				imagesSpeed === 'slow' ? 60 : imagesSpeed === 'fast' ? 15 : 30;

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
		<section className="flex flex-col gap-24 overflow-hidden relative not-prose section-padding w-full  static-background ">
			<div className="relative z-[2] max-w-container flex flex-row flex-wrap gap-5 items-end justify-between">
				<div className="flex flex-col gap-4 lg:max-w-[60%] w-full">
					{ topHeading && (
						<p className="decorative-text text-brand-yellow !text-2xl">
							{ topHeading }
						</p>
					) }
					{ heading && (
						<h2 className="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600]  text-left ">
							{ heading }
						</h2>
					) }
				</div>

				<div>
					<a href={ buttonUrl || '#' }>
						<button className="btn btn-outline btn-xl">
							{ buttonText || 'Learn More' }
						</button>
					</a>
				</div>
			</div>
			<div className="group relative w-full h-full select-none ">
				<div
					className="flex w-max animate-slide gap-10 whitespace-nowrap"
					style={ { animationDuration: duration } }
				>
					{ images.concat( images ).map( ( image, idx ) => (
						<div
							key={ `showcase-${ image.id || idx }-${ Math.floor(
								idx / images.length
							) }` }
							className={ `px-1 py-1.5 bg-white rounded-lg overflow-hidden 
								${
									idx % 3 === 0
										? 'rotate-[-3deg]'
										: idx % 3 === 1
										? 'rotate-[2deg]'
										: 'rotate-[-1deg]'
								}
							` }
						>
							<img
								src={ image?.sizes?.large?.url || image?.url }
								srcSet={ ` 
									${ image?.sizes?.thumbnail?.url || '' } 150w, 
									${ image?.sizes?.medium?.url || '' } 300w, 
									${ image?.sizes?.large?.url || '' } 1024w, 
									${ image?.sizes?.full?.url || image?.url || '' } ${ image?.width || '' }w 
								` }
								sizes="(max-width: 768px) 100vw, 1024px"
								alt={ image?.alt || '' }
								width={ image?.width }
								height={ image?.height }
								className="flex-shrink-0 h-full aspect-[3/4] w-full max-w-[200px] md:max-w-[300px] object-cover"
								loading="eager"
							/>
						</div>
					) ) }
				</div>
			</div>
		</section>
	);
};

export default Showcase;
