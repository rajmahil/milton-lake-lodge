import React, { useState, useEffect, useCallback } from 'react';

const CarouselBlock = ( { heading, subheading, items } ) => {
	const totalItems = items.length;
	const [ currentIndex, setCurrentIndex ] = useState( 0 );
	const [ slidesPerView, setSlidesPerView ] = useState( 1 );
	const [ isMounted, setIsMounted ] = useState( false );

	const updateSlidesPerView = useCallback( () => {
		if ( window.innerWidth >= 1024 ) {
			setSlidesPerView( 3 );
		} else if ( window.innerWidth >= 640 ) {
			setSlidesPerView( 2 );
		} else {
			setSlidesPerView( 1 );
		}
	}, [] );

	useEffect( () => {
		setIsMounted( true );
		updateSlidesPerView();

		window.addEventListener( 'resize', updateSlidesPerView );
		return () =>
			window.removeEventListener( 'resize', updateSlidesPerView );
	}, [ updateSlidesPerView ] );

	useEffect( () => {
		if ( ! isMounted ) return;
		const maxIndex = Math.max( 0, totalItems - slidesPerView );
		if ( currentIndex > maxIndex ) {
			setCurrentIndex( maxIndex );
		}
	}, [ slidesPerView, currentIndex, totalItems, isMounted ] );

	const nextSlide = () => {
		const maxIndex = Math.max( 0, totalItems - slidesPerView );
		if ( currentIndex < maxIndex ) {
			setCurrentIndex( currentIndex + 1 );
		}
	};

	const prevSlide = () => {
		if ( currentIndex > 0 ) {
			setCurrentIndex( currentIndex - 1 );
		}
	};

	const canGoNext = currentIndex < Math.max( 0, totalItems - slidesPerView );
	const canGoPrev = currentIndex > 0;

	const getSlideWidthClass = () => {
		switch ( slidesPerView ) {
			case 1:
				return 'w-full';
			case 2:
				return 'w-1/2';
			case 3:
				return 'w-1/3';

			default:
				return 'w-full';
		}
	};

	if ( totalItems === 0 ) return null;

	return (
		<section className="plugin-custom-block not-prose w-full static-background">
			<div className="text-center flex flex-col gap-16">
				<div className="relative w-full overflow-hidden">
					<div className="section-padding pb-0">
						<div className="flex items-end justify-between flex-wrap gap-5 mb-10 sm:mb-16 max-w-container mx-auto">
							<div className="flex flex-col gap-2 items-start">
								{ heading && (
									<h2 className="heading-two font-bold !text-left !my-0">
										{ heading }
									</h2>
								) }
								{ subheading && (
									<p className="text-xl font-medium !text-left !my-0">
										{ subheading }
									</p>
								) }
							</div>

							<div className="flex items-center gap-2">
								<button
									onClick={ prevSlide }
									disabled={ ! canGoPrev }
									className={ `rounded-full p-3 transition  ${
										canGoPrev
											? 'bg-white hover:bg-gray-100'
											: 'bg-gray-200 cursor-not-allowed'
									}` }
									aria-label="Previous slide"
								>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										className={ `h-5 w-5 ${
											canGoPrev
												? 'text-black'
												: 'text-gray-400'
										}` }
										fill="none"
										viewBox="0 0 24 24"
										stroke="currentColor"
									>
										<path
											strokeLinecap="round"
											strokeLinejoin="round"
											strokeWidth={ 2 }
											d="M15 19l-7-7 7-7"
										/>
									</svg>
								</button>

								<button
									onClick={ nextSlide }
									disabled={ ! canGoNext }
									className={ `rounded-full p-3 transition ${
										canGoNext
											? 'bg-white hover:bg-gray-100'
											: 'bg-gray-200 cursor-not-allowed'
									}` }
									aria-label="Next slide"
								>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										className={ `h-5 w-5 ${
											canGoNext
												? 'text-black'
												: 'text-gray-400'
										}` }
										fill="none"
										viewBox="0 0 24 24"
										stroke="currentColor"
									>
										<path
											strokeLinecap="round"
											strokeLinejoin="round"
											strokeWidth={ 2 }
											d="M9 5l7 7-7 7"
										/>
									</svg>
								</button>
							</div>
						</div>
					</div>

					<div className="relative w-full overflow-hidden section-padding  !pt-0">
						<div
							className="carousel-track flex transition-transform duration-500 ease-in-out max-w-container gap-5"
							style={ {
								transform: `translateX(-${
									currentIndex * ( 100 / slidesPerView )
								}%)`,
							} }
						>
							{ items.map( ( item, index ) => (
								<div
									key={ index }
									className={ `carousel-slide flex-shrink-0 ${ getSlideWidthClass() }` }
								>
									<div className="relative rounded-2xl overflow-hidden aspect-[10/11]">
										{ item.image?.url && (
											<>
												<div
													className="absolute inset-0 bg-cover bg-center bg-no-repeat"
													style={ {
														backgroundImage: `url('${ item.image.url }')`,
													} }
												/>
												<div className="absolute inset-0 bg-black/20" />
											</>
										) }

										<div className="absolute bottom-0 left-0 right-0 p-4 lg:p-6 text-white">
											<div className="flex flex-col items-start">
												{ item.title && (
													<h3 className="!my-0 !text-3xl font-bold uppercase tracking-wide">
														{ item.title }
													</h3>
												) }
												{ item.text && (
													<p className="!my-0 !text-base sm:!text-lg sm:!leading-relaxed !text-left">
														{ item.text }
													</p>
												) }
											</div>
										</div>
									</div>
								</div>
							) ) }
						</div>
					</div>
				</div>
			</div>
		</section>
	);
};

export default CarouselBlock;
