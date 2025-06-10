import { useState, useCallback, useEffect, useRef } from 'react';

const GallerySection = ( { heading, images = [] } ) => {
	const [ isOpen, setIsOpen ] = useState( false );
	const [ activeIndex, setActiveIndex ] = useState( 0 );
	const modalRef = useRef( null );
	const overlayRef = useRef( null );

	const handleOpen = useCallback( ( index ) => {
		setActiveIndex( index );
		setIsOpen( true );
	}, [] );

	const handleClose = useCallback( () => {
		setIsOpen( false );
	}, [] );

	const handleNext = useCallback( () => {
		setActiveIndex( ( prev ) => ( prev + 1 ) % images.length );
	}, [ images.length ] );

	const handlePrev = useCallback( () => {
		setActiveIndex(
			( prev ) => ( prev - 1 + images.length ) % images.length
		);
	}, [ images.length ] );

	const handleKeyDown = useCallback(
		( e ) => {
			if ( e.key === 'Escape' ) handleClose();
			if ( e.key === 'ArrowRight' ) handleNext();
			if ( e.key === 'ArrowLeft' ) handlePrev();
		},
		[ handleClose, handleNext, handlePrev ]
	);

	// Handle outside click
	const handleOutsideClick = useCallback(
		( e ) => {
			if ( overlayRef.current && overlayRef.current === e.target ) {
				handleClose();
			}
		},
		[ handleClose ]
	);

	useEffect( () => {
		if ( isOpen ) {
			document.body.style.overflow = 'hidden';
			window.addEventListener( 'keydown', handleKeyDown );
			document.addEventListener( 'mousedown', handleOutsideClick );
		} else {
			document.body.style.overflow = '';
			window.removeEventListener( 'keydown', handleKeyDown );
			document.removeEventListener( 'mousedown', handleOutsideClick );
		}

		return () => {
			window.removeEventListener( 'keydown', handleKeyDown );
			document.removeEventListener( 'mousedown', handleOutsideClick );
			document.body.style.overflow = '';
		};
	}, [ isOpen, handleKeyDown, handleOutsideClick ] );

	// Group images into sets of 10
	const groupedImages = [];
	for ( let i = 0; i < images.length; i += 10 ) {
		groupedImages.push( images.slice( i, i + 10 ) );
	}

	return (
		<section className="plugin-custom-block not-prose section-padding w-full static-background">
			<div className="max-w-container mx-auto flex flex-col gap-16">
				{ heading && (
					<h2 className="!my-0 heading-two font-semibold text-center">
						{ heading }
					</h2>
				) }

				{ groupedImages.map( ( group, groupIndex ) => (
					<div
						key={ groupIndex }
						className="flex flex-col gap-2 sm:gap-4"
					>
						<div className="flex flex-col md:flex-row gap-2 sm:gap-4">
							{ group[ 0 ] && (
								<div
									className="flex-1 aspect-[3/2] relative overflow-hidden rounded-xl cursor-zoom-in"
									onClick={ () =>
										handleOpen( groupIndex * 10 )
									}
								>
									<img
										src={ group[ 0 ].url }
										alt={ group[ 0 ].alt || '' }
										className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
									/>
								</div>
							) }

							<div className="flex-1 grid grid-cols-2 gap-2 sm:gap-4">
								{ group.slice( 1, 5 ).map( ( img, idx ) => (
									<div
										key={ idx }
										className="aspect-[3/2] relative overflow-hidden rounded-xl cursor-zoom-in"
										onClick={ () =>
											handleOpen(
												groupIndex * 10 + idx + 1
											)
										}
									>
										<img
											src={ img.url }
											alt={ img.alt || '' }
											className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
										/>
									</div>
								) ) }
							</div>
						</div>

						{ group.length > 5 && (
							<div className="flex flex-col md:flex-row gap-2 sm:gap-4">
								<div className="flex-1 grid grid-cols-2 gap-2 sm:gap-4">
									{ group.slice( 5, 9 ).map( ( img, idx ) => (
										<div
											key={ idx }
											className="aspect-[3/2] relative overflow-hidden rounded-xl cursor-zoom-in"
											onClick={ () =>
												handleOpen(
													groupIndex * 10 + idx + 5
												)
											}
										>
											<img
												src={ img.url }
												alt={ img.alt || '' }
												className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
											/>
										</div>
									) ) }
								</div>

								{ group[ 9 ] && (
									<div
										className="flex-1 aspect-[3/2] relative overflow-hidden rounded-xl cursor-zoom-in"
										onClick={ () =>
											handleOpen( groupIndex * 10 + 9 )
										}
									>
										<img
											src={ group[ 9 ].url }
											alt={ group[ 9 ].alt || '' }
											className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
										/>
									</div>
								) }
							</div>
						) }
					</div>
				) ) }

				{ isOpen && (
					<div
						ref={ overlayRef }
						className="fixed inset-0 !z-[1000] bg-black/90 flex items-center justify-center"
					>
						<div
							ref={ modalRef }
							className="relative w-full h-full max-w-[90%] max-h-[90vh] flex items-center justify-center p-4"
						>
							<button
								onClick={ handlePrev }
								className="absolute left-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors duration-200 z-10"
								aria-label="Previous image"
							>
								<svg
									className="w-6 h-6"
									viewBox="0 0 24 24"
									stroke="currentColor"
									fill="none"
								>
									<path
										d="M15 6l-6 6 6 6"
										strokeWidth="1.5"
										strokeLinecap="round"
										strokeLinejoin="round"
									/>
								</svg>
							</button>

							<div className="w-full h-full flex items-center justify-center">
								<img
									src={ images[ activeIndex ]?.url }
									alt={ images[ activeIndex ]?.alt || '' }
									className="max-w-full max-h-full object-contain rounded-lg shadow-xl"
								/>
							</div>

							<button
								onClick={ handleNext }
								className="absolute right-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors duration-200 z-10"
								aria-label="Next image"
							>
								<svg
									className="w-6 h-6"
									viewBox="0 0 24 24"
									stroke="currentColor"
									fill="none"
								>
									<path
										d="M9 6l6 6-6 6"
										strokeWidth="1.5"
										strokeLinecap="round"
										strokeLinejoin="round"
									/>
								</svg>
							</button>

							<button
								onClick={ handleClose }
								className="absolute top-4 right-4 bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors duration-200 z-10"
								aria-label="Close gallery"
							>
								<svg
									className="w-6 h-6"
									viewBox="0 0 24 24"
									stroke="currentColor"
									fill="none"
								>
									<path
										d="M6 6l12 12M6 18L18 6"
										strokeWidth="1.5"
										strokeLinecap="round"
										strokeLinejoin="round"
									/>
								</svg>
							</button>

							<div className="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-black/50 text-white py-1 px-3 rounded-full text-sm">
								{ activeIndex + 1 } / { images.length }
							</div>
						</div>
					</div>
				) }
			</div>
		</section>
	);
};

export default GallerySection;
