import React, { useState, useEffect, useCallback, useRef } from 'react';

const CarouselBlock = ( { heading, subheading, items, sectionId } ) => {
	const totalItems = items.length;
	const containerRef = useRef();

	const [ currentIndex, setCurrentIndex ] = useState( 0 );
	const [ slidesPerView, setSlidesPerView ] = useState( 1 );
	const [ startX, setStartX ] = useState( 0 );
	const [ currentX, setCurrentX ] = useState( 0 );
	const [ dragOffset, setDragOffset ] = useState( 0 );
	const [ isDragging, setIsDragging ] = useState( false );
	const [ containerWidth, setContainerWidth ] = useState( 0 );

	const updateSlidesPerView = useCallback( () => {
		if ( window.innerWidth >= 1200 ) {
			setSlidesPerView( 3 );
		} else if ( window.innerWidth >= 640 ) {
			setSlidesPerView( 2 );
		} else {
			setSlidesPerView( 1 );
		}
	}, [] );

	useEffect( () => {
		updateSlidesPerView();
		const updateWidth = () => {
			setContainerWidth( containerRef.current?.clientWidth || 1 );
		};

		updateWidth();
		window.addEventListener( 'resize', () => {
			updateSlidesPerView();
			updateWidth();
		} );
		return () => {
			window.removeEventListener( 'resize', updateSlidesPerView );
			window.removeEventListener( 'resize', updateWidth );
		};
	}, [ updateSlidesPerView ] );

	useEffect( () => {
		const maxIndex = Math.max( 0, totalItems - slidesPerView );
		if ( currentIndex > maxIndex ) {
			setCurrentIndex( maxIndex );
		}
	}, [ slidesPerView, currentIndex, totalItems ] );

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

	const slideWidthPercentage =
		( 100 - ( slidesPerView - 1 ) * 1.5 ) / slidesPerView;
	const gapPercentage = 1.5;

	const handleDragStart = ( e ) => {
		setIsDragging( true );
		const clientX = e.type.includes( 'touch' )
			? e.touches[ 0 ].clientX
			: e.clientX;
		setStartX( clientX );
		setCurrentX( clientX );
		setDragOffset( 0 );

		window.addEventListener( 'mousemove', handleDragMove, {
			passive: false,
		} );
		window.addEventListener( 'touchmove', handleDragMove, {
			passive: false,
		} );
		window.addEventListener( 'mouseup', handleDragEnd );
		window.addEventListener( 'touchend', handleDragEnd );
	};

	const handleDragMove = ( e ) => {
		if ( ! isDragging ) return;
		e.preventDefault();
		const clientX = e.type.includes( 'touch' )
			? e.touches[ 0 ].clientX
			: e.clientX;
		setCurrentX( clientX );
		setDragOffset( clientX - startX );
	};

	const handleDragEnd = () => {
		if ( ! isDragging ) return;
		const threshold = containerWidth * 0.15;
		const distance = Math.abs( dragOffset );

		if ( distance > threshold ) {
			if ( dragOffset > 0 && canGoPrev ) {
				prevSlide();
			} else if ( dragOffset < 0 && canGoNext ) {
				nextSlide();
			}
		}

		setIsDragging( false );
		setDragOffset( 0 );

		window.removeEventListener( 'mousemove', handleDragMove );
		window.removeEventListener( 'touchmove', handleDragMove );
		window.removeEventListener( 'mouseup', handleDragEnd );
		window.removeEventListener( 'touchend', handleDragEnd );
	};

	const getTransform = () => {
		const baseTransform =
			currentIndex * ( slideWidthPercentage + gapPercentage );
		if ( isDragging && containerWidth ) {
			let dragPercent = ( dragOffset / containerWidth ) * 100;

			if ( currentIndex === 0 && dragOffset > 0 ) {
				dragPercent *= 0.3;
			}
			const maxIndex = Math.max( 0, totalItems - slidesPerView );
			if ( currentIndex === maxIndex && dragOffset < 0 ) {
				dragPercent *= 0.3;
			}
			return `translateX(-${ baseTransform - dragPercent }%)`;
		}
		return `translateX(-${ baseTransform }%)`;
	};

	if ( ! items || items.length === 0 ) return null;

	return (
		<section
			id={ sectionId || undefined }
			className="plugin-custom-block not-prose w-full static-background"
		>
			<div className="text-center flex flex-col gap-16">
				<div
					class="relative w-full overflow-hidden flex flex-col gap-10 lg:gap-16"
					ref={ containerRef }
				>
					<div className="section-padding pb-0">
						<div className="flex items-end justify-between flex-wrap gap-5 max-w-container mx-auto">
							<div className="flex flex-col gap-2 items-start max-w-4xl mr-auto">
								{ heading && (
									<h2 className="heading-two font-bold !text-left !my-0">
										{ heading }
									</h2>
								) }
								{ subheading && (
									<p className="text-xl text-neutral-500 !text-left !my-0">
										{ subheading }
									</p>
								) }
							</div>

							<div className="flex items-center gap-2">
								<button
									onClick={ prevSlide }
									disabled={ ! canGoPrev }
									className={ `rounded-full p-3 transition ${
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

					<div className="relative w-full overflow-hidden section-padding !pt-0">
						<div
							className={ `carousel-track flex max-w-container mx-auto select-none ${
								isDragging
									? 'transition-none cursor-grabbing'
									: 'transition-transform duration-500 cursor-grab'
							}` }
							style={ {
								transform: getTransform(),
								gap: `${ gapPercentage }%`,
							} }
							onMouseDown={ handleDragStart }
							onTouchStart={ handleDragStart }
						>
							{ items.map( ( item, index ) => (
								<div
									key={ index }
									className="carousel-slide flex-shrink-0"
									style={ {
										width: `${ slideWidthPercentage }%`,
									} }
								>
									<div className="relative rounded-2xl overflow-hidden aspect-[5/7] group cursor-pointer">
										{ item.image?.url && (
											<>
												<div
													className="absolute inset-0 bg-cover bg-center bg-no-repeat"
													style={ {
														backgroundImage: `url('${ item.image.url }')`,
													} }
												/>
												<div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
											</>
										) }
										<div class="absolute bottom-0 left-0 right-0  px-5 py-8  text-white ">
											<div className="flex flex-col items-start">
												{ item.title && (
													<h3 className="!my-0 !text-3xl md:!text-4xl font-bold uppercase tracking-wide !text-left">
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
