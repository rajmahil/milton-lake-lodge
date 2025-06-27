import { useRef, useState, useEffect } from 'react';
import { LazyLoadImage } from 'react-lazy-load-image-component';

const ScrollImage = ( {
	heading,
	subheading,
	buttonText,
	buttonUrl,
	image,
	sectionId,
} ) => {
	const containerRef = useRef( null );
	const [ scale, setScale ] = useState( 0.9 );
	const tickingRef = useRef( false );

	const updateScale = () => {
		if ( ! containerRef.current ) return;

		const rect = containerRef.current.getBoundingClientRect();
		const windowHeight = window.innerHeight;
		const containerHeight = containerRef.current.offsetHeight;

		const elementTop = rect.top;
		const elementBottom = rect.bottom;

		if ( elementBottom > 0 && elementTop < windowHeight ) {
			const visibleHeight =
				Math.min( windowHeight, elementBottom ) -
				Math.max( 0, elementTop );
			const progress = Math.min(
				1,
				visibleHeight / Math.min( windowHeight, containerHeight )
			);
			setScale( 0.9 + progress * 0.1 );
		} else if ( elementBottom <= 0 ) {
			setScale( 1.0 );
		} else {
			setScale( 0.9 );
		}
	};

	useEffect( () => {
		const handleScroll = () => {
			if ( ! tickingRef.current ) {
				window.requestAnimationFrame( () => {
					updateScale();
					tickingRef.current = false;
				} );
				tickingRef.current = true;
			}
		};

		const handleResize = () => {
			if ( ! tickingRef.current ) {
				window.requestAnimationFrame( () => {
					updateScale();
					tickingRef.current = false;
				} );
				tickingRef.current = true;
			}
		};

		// Initial calculation
		updateScale();

		window.addEventListener( 'scroll', handleScroll, { passive: true } );
		window.addEventListener( 'resize', handleResize, { passive: true } );

		return () => {
			window.removeEventListener( 'scroll', handleScroll );
			window.removeEventListener( 'resize', handleResize );
		};
	}, [] );

	const imageUrl =
		image?.sizes?.large?.url || image?.url || '/placeholder.svg';
	const thumbnail = image?.sizes?.thumbnail?.url || '';
	const medium = image?.sizes?.medium?.url || '';
	const large = image?.sizes?.large?.url || '';
	const full = image?.sizes?.full?.url || '';
	const imageAlt = image?.alt || '';
	const imageWidth = image?.width || '';
	const imageHeight = image?.height || '';

	return (
		<section
			id={ sectionId || undefined }
			className="plugin-custom-block static-background"
		>
			<div
				ref={ containerRef }
				className="relative w-full overflow-hidden rounded-2xl h-[150vh] flex items-start justify-start not-prose"
				style={ {
					transform: `scale(${ scale })`,
					willChange: 'transform',
				} }
			>
				<div
					className="absolute inset-0 z-0 transform origin-center"
					style={ { willChange: 'transform' } }
				>
					<LazyLoadImage
						src={ imageUrl }
						srcSet={ `${ thumbnail } 150w, ${ medium } 300w, ${ large } 1024w, ${ full } ${ imageWidth }w` }
						sizes="(max-width: 768px) 100vw, 1024px"
						alt={ imageAlt }
						width={ imageWidth }
						height={ imageHeight }
						className="object-cover w-full h-full"
						loading="eager"
					/>
					<div className="absolute inset-0 bg-black/30"></div>
				</div>

				<div className="section-padding w-full">
					<div className="relative z-10 text-white flex flex-col items-start justify-start gap-4 !max-w-7xl !w-full mx-auto">
						<div className="flex flex-col w-full items-start gap-0 max-w-[600px] mr-auto">
							{ heading && (
								<h2 className="heading-two max-w-none text-left">
									{ heading }
								</h2>
							) }
							{ subheading && (
								<p className="!text-lg md:t!text-xl text-left !py-0 !my-0">
									{ subheading }
								</p>
							) }
						</div>

						{ buttonText && (
							<a
								href={ buttonUrl || '#' }
								className="inline-block mt-4 w-fit"
							>
								<button className="btn btn-xl btn-outline">
									{ buttonText }
								</button>
							</a>
						) }
					</div>
				</div>
			</div>
		</section>
	);
};

export default ScrollImage;
