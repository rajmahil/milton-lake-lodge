import { useState, useCallback, useEffect, useRef } from 'react';

const GallerySection = ( { heading, images = [] } ) => {
	const [ isOpen, setIsOpen ] = useState( false );
	const [ isClosing, setIsClosing ] = useState( false );
	const [ activeIndex, setActiveIndex ] = useState( 0 );
	const modalRef = useRef( null );
	const overlayRef = useRef( null );

	const handleOpen = useCallback( ( index ) => {
		setActiveIndex( index );
		setIsOpen( true );
	}, [] );

	const handleClose = useCallback( () => {
		setIsClosing( true );

		setTimeout( () => {
			setIsOpen( false );
			setIsClosing( false );
		}, 200 );
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
			const originalStyle = window.getComputedStyle(
				document.body
			).overflow;
			const originalDocStyle = window.getComputedStyle(
				document.documentElement
			).overflow;

			document.body.style.overflow = 'hidden';
			document.documentElement.style.overflow = 'hidden';
			document.body.style.position = 'fixed';
			document.body.style.width = '100%';
			document.body.style.height = '100%';

			window.addEventListener( 'keydown', handleKeyDown );
			document.addEventListener( 'mousedown', handleOutsideClick );

			const preventScroll = ( e ) => {
				e.preventDefault();
				return false;
			};

			window.addEventListener( 'scroll', preventScroll, {
				passive: false,
			} );
			window.addEventListener( 'touchmove', preventScroll, {
				passive: false,
			} );
			window.addEventListener( 'wheel', preventScroll, {
				passive: false,
			} );

			return () => {
				document.body.style.overflow = originalStyle;
				document.documentElement.style.overflow = originalDocStyle;
				document.body.style.position = '';
				document.body.style.width = '';
				document.body.style.height = '';
				window.removeEventListener( 'scroll', preventScroll );
				window.removeEventListener( 'touchmove', preventScroll );
				window.removeEventListener( 'wheel', preventScroll );
			};
		} else {
			document.body.style.overflow = '';
			document.documentElement.style.overflow = '';
			document.body.style.position = '';
			document.body.style.width = '';
			document.body.style.height = '';
			window.removeEventListener( 'keydown', handleKeyDown );
			document.removeEventListener( 'mousedown', handleOutsideClick );
		}

		return () => {
			window.removeEventListener( 'keydown', handleKeyDown );
			document.removeEventListener( 'mousedown', handleOutsideClick );
			document.body.style.overflow = '';
			document.documentElement.style.overflow = '';
			document.body.style.position = '';
			document.body.style.width = '';
			document.body.style.height = '';
		};
	}, [ isOpen, handleKeyDown, handleOutsideClick ] );

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

				<div className="flex flex-col !gap-2 sm:!gap-4">
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
											className="w-full h-full object-cover transition-transform duration-500 hover:scale-110 select-none"
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
												className="w-full h-full object-cover transition-transform duration-500 hover:scale-110 select-none"
											/>
										</div>
									) ) }
								</div>
							</div>

							{ group.length > 5 && (
								<div className="flex flex-col md:flex-row gap-2 sm:gap-4 ">
									<div className="flex-1 grid grid-cols-2 gap-2 sm:gap-4">
										{ group
											.slice( 5, 9 )
											.map( ( img, idx ) => (
												<div
													key={ idx }
													className="aspect-[3/2] relative overflow-hidden rounded-xl cursor-zoom-in"
													onClick={ () =>
														handleOpen(
															groupIndex * 10 +
																idx +
																5
														)
													}
												>
													<img
														src={ img.url }
														alt={ img.alt || '' }
														className="w-full h-full object-cover transition-transform duration-500 hover:scale-110 select-none"
													/>
												</div>
											) ) }
									</div>

									{ group[ 9 ] && (
										<div
											className="flex-1 aspect-[3/2] relative overflow-hidden rounded-xl cursor-zoom-in"
											onClick={ () =>
												handleOpen(
													groupIndex * 10 + 9
												)
											}
										>
											<img
												src={ group[ 9 ].url }
												alt={ group[ 9 ].alt || '' }
												className="w-full h-full object-cover transition-transform duration-500 hover:scale-110 select-none"
											/>
										</div>
									) }
								</div>
							) }
						</div>
					) ) }
				</div>

				{ ( isOpen || isClosing ) && (
					<div
						ref={ overlayRef }
						className="fixed inset-0 !z-[1000] w-screen h-screen bg-black/90 select-none cursor-zoom-out overflow-hidden touch-none"
						style={ { overscrollBehavior: 'none' } }
						onTouchMove={ ( e ) => e.preventDefault() }
						onWheel={ ( e ) => e.preventDefault() }
						onScroll={ ( e ) => e.preventDefault() }
					>
						<div className="absolute inset-0 flex items-center justify-center pointer-events-none">
							<div
								ref={ modalRef }
								className="relative w-full h-full max-w-[90vw] max-h-[90vh] pointer-events-auto"
							>
								<button
									onClick={ ( e ) => {
										e.stopPropagation();
										handlePrev();
									} }
									className="absolute !z-[1001] left-4 top-1/2 -translate-y-1/2 hidden xl:flex items-center justify-center text-white rounded-full cursor-pointer bg-white/10 w-14 h-14 hover:bg-white/20 transition-colors duration-200"
									aria-label="Previous image"
								>
									<svg
										className="w-6 h-6"
										xmlns="http://www.w3.org/2000/svg"
										fill="none"
										viewBox="0 0 24 24"
										strokeWidth="1.5"
										stroke="currentColor"
									>
										<path
											strokeLinecap="round"
											strokeLinejoin="round"
											d="M15.75 19.5L8.25 12l7.5-7.5"
										/>
									</svg>
								</button>

								<div className="relative flex items-center justify-center w-full h-full">
									<img
										src={ images[ activeIndex ]?.url }
										alt={ images[ activeIndex ]?.alt || '' }
										className="object-contain object-center w-full h-full max-w-[90vw] max-h-[85vh] select-none cursor-zoom-out rounded-lg shadow-xl"
									/>

									<div className="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-black/90 text-white py-1 px-4 rounded-full text-sm font-medium lg:block !hidden">
										<span>{ activeIndex + 1 }</span>
										<span>/</span>
										<span>{ images.length }</span>
									</div>

									<div className="absolute bottom-14 left-1/2 transform -translate-x-1/2 flex items-center gap-4 lg:!hidden">
										<button
											onClick={ ( e ) => {
												e.stopPropagation();
												handlePrev();
											} }
											className="flex items-center justify-center text-white rounded-full cursor-pointer bg-white/10 w-10 sm:w-14 h-10 sm:h-14 hover:bg-white/20 transition-colors duration-200"
											aria-label="Previous image"
										>
											<svg
												className="w-5 sm:w-6 h-5 sm:h-6"
												xmlns="http://www.w3.org/2000/svg"
												fill="none"
												viewBox="0 0 24 24"
												strokeWidth="1.5"
												stroke="currentColor"
											>
												<path
													strokeLinecap="round"
													strokeLinejoin="round"
													d="M15.75 19.5L8.25 12l7.5-7.5"
												/>
											</svg>
										</button>

										<div className="bg-black/90 text-white flex items-center py-2 px-4 rounded-full text-sm font-medium">
											<span>{ activeIndex + 1 }</span>
											<span>/</span>
											<span>{ images.length }</span>
										</div>

										<button
											onClick={ ( e ) => {
												e.stopPropagation();
												handleNext();
											} }
											className="flex items-center justify-center text-white rounded-full cursor-pointer bg-white/10 w-10 sm:w-14 h-10 sm:h-14 hover:bg-white/20 transition-colors duration-200"
											aria-label="Next image"
										>
											<svg
												className="w-5 sm:w-6 h-5 sm:h-6"
												xmlns="http://www.w3.org/2000/svg"
												fill="none"
												viewBox="0 0 24 24"
												strokeWidth="1.5"
												stroke="currentColor"
											>
												<path
													strokeLinecap="round"
													strokeLinejoin="round"
													d="M8.25 4.5l7.5 7.5-7.5 7.5"
												/>
											</svg>
										</button>
									</div>
								</div>

								<button
									onClick={ ( e ) => {
										e.stopPropagation();
										handleNext();
									} }
									className="absolute !z-[1001] right-4 top-1/2 -translate-y-1/2 hidden xl:flex items-center justify-center text-white rounded-full cursor-pointer bg-white/10 w-14 h-14 hover:bg-white/20 transition-colors duration-200"
									aria-label="Next image"
								>
									<svg
										className="w-6 h-6"
										xmlns="http://www.w3.org/2000/svg"
										fill="none"
										viewBox="0 0 24 24"
										strokeWidth="1.5"
										stroke="currentColor"
									>
										<path
											strokeLinecap="round"
											strokeLinejoin="round"
											d="M8.25 4.5l7.5 7.5-7.5 7.5"
										/>
									</svg>
								</button>

								<button
									onClick={ handleClose }
									className="fixed top-4 right-4 flex items-center justify-center text-white bg-white/10 w-12 h-12 rounded-full cursor-pointer hover:bg-white/20 transition-colors duration-200"
									aria-label="Close gallery"
								>
									<svg
										className="w-6 h-6"
										xmlns="http://www.w3.org/2000/svg"
										fill="none"
										viewBox="0 0 24 24"
										strokeWidth="1.5"
										stroke="currentColor"
									>
										<path
											strokeLinecap="round"
											strokeLinejoin="round"
											d="M6 18L18 6M6 6l12 12"
										/>
									</svg>
								</button>
							</div>
						</div>
					</div>
				) }
			</div>
		</section>
	);
};

export default GallerySection;
