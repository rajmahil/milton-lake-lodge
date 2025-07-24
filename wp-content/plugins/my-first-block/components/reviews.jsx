import { useState } from 'react';

export default function ReviewsSection( { heading, reviews = [], sectionId } ) {
	const [ currentIndex, setCurrentIndex ] = useState( 0 );
	const [ dragging, setDragging ] = useState( false );
	const [ startX, setStartX ] = useState( 0 );
	const totalSlides = reviews.length;

	const goToSlide = ( index ) => {
		if ( index < 0 ) index = 0;
		if ( index >= totalSlides ) index = totalSlides - 1;
		setCurrentIndex( index );
	};

	const onDragStart = ( event ) => {
		setDragging( true );
		const clientX = event.type.includes( 'touch' )
			? event.touches[ 0 ].clientX
			: event.clientX;
		setStartX( clientX );
	};

	const onDragEnd = ( event ) => {
		if ( ! dragging ) return;
		setDragging( false );

		const clientX = event.type.includes( 'touch' )
			? event.changedTouches?.[ 0 ]?.clientX ?? startX
			: event.clientX;

		const deltaX = clientX - startX;
		const threshold = 50;

		if ( deltaX < -threshold && currentIndex < totalSlides - 1 ) {
			goToSlide( currentIndex + 1 );
		} else if ( deltaX > threshold && currentIndex > 0 ) {
			goToSlide( currentIndex - 1 );
		}
	};

	return (
		<>
			<style jsx>{ `
				@keyframes slideFromCenterToLeft {
					0% {
						transform: translateX( 0 ) translateY( 8px )
							rotate( 0deg ) scale( 0.9 );
					}
					100% {
						transform: translateX( 40px ) translateY( 0 )
							rotate( 5deg ) scale( 1 );
					}
				}

				@keyframes slideFromCenterToRight {
					0% {
						transform: translateX( 0 ) translateY( 8px )
							rotate( 0deg ) scale( 0.9 );
					}
					100% {
						transform: translateX( -40px ) translateY( 0 )
							rotate( -10deg ) scale( 1 );
					}
				}

				.animate-slide-center-left {
					animation: slideFromCenterToLeft 0.8s ease-out forwards;
				}

				.animate-slide-center-right {
					animation: slideFromCenterToRight 0.8s ease-out forwards;
				}

				.image-container {
					transition: all 0.5s ease-in-out;
				}

				.drag-cursor {
					cursor: grab;
				}

				.drag-cursor:active {
					cursor: grabbing;
				}
			` }</style>

			<section
				id={ sectionId || undefined }
				className="plugin-custom-block not-prose section-padding w-full static-background"
			>
				<div className="max-w-container mx-auto flex flex-col lg:grid grid-cols-2 xl:grid-cols-5 !items-center !justify-center gap-20 lg:gap-10">
					{ /* Images Section */ }
					<div className="relative w-full !flex !items-center !justify-center col-span-1 xl:col-span-3 !min-h-[280px] 400:!min-h-[500px]">
						{ reviews.map( ( review, i ) => (
							<div
								key={ i }
								className={ `!absolute !inset-0 !flex !items-center !justify-center ${
									i === currentIndex ? '!block' : '!hidden'
								}` }
							>
								{ review.image1?.url && (
									<div class="max-w-[400px] w-full rotate-5 relative left-8 shadow-lg">
										<img
											src={ review.image1.url }
											alt={
												review.image1.alt ||
												'Review image 1'
											}
											className="aspect-[3/4] w-full h-full object-cover rounded-lg overflow-hidden"
											loading="lazy"
										/>
									</div>
								) }

								{ review.image2?.url && (
									<div class="max-w-[400px] w-full -rotate-5 shadow-lg relative right-8">
										<img
											src={ review.image2.url }
											alt={
												review.image2.alt ||
												'Review image 2'
											}
											className="aspect-[3/4]  w-full h-full object-cover rounded-lg overflow-hidden"
											loading="lazy"
										/>
									</div>
								) }
							</div>
						) ) }
					</div>

					<div className="!flex !flex-col !gap-2 col-span-1 xl:col-span-2">
						{ heading && (
							<h2 class="heading-two text-center my-0">
								{ heading }
							</h2>
						) }

						<div
							className="!w-full !overflow-hidden !relative drag-cursor"
							onMouseDown={ onDragStart }
							onMouseUp={ onDragEnd }
							onTouchStart={ onDragStart }
							onTouchEnd={ onDragEnd }
							onMouseLeave={ ( e ) => dragging && onDragEnd( e ) }
							style={ { touchAction: 'pan-y' } }
						>
							{ /* Navigation Arrows */ }
							<div className="max-w-[85%] 400:max-w-none mx-auto !absolute !top-1/2 !left-1/2 !transform !-translate-x-1/2 !-translate-y-1/2 !w-full !flex !flex-row !items-center !justify-between !z-[100]">
								<div
									className="!p-2 !rounded-full !bg-white hover:!bg-brand-green hover:!text-white !duration-300 !transition-all !ease-in-out !shadow-lg !cursor-pointer"
									onClick={ () =>
										goToSlide( currentIndex - 1 )
									}
								>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										width="20"
										height="20"
										fill="currentColor"
										viewBox="0 0 256 256"
									>
										<path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z" />
									</svg>
								</div>
								<div
									className="!p-2 !rounded-full !bg-white hover:!bg-brand-green hover:!text-white !duration-300 !transition-all !ease-in-out !shadow-lg !cursor-pointer"
									onClick={ () =>
										goToSlide( currentIndex + 1 )
									}
								>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										width="20"
										height="20"
										fill="currentColor"
										viewBox="0 0 256 256"
									>
										<path d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z" />
									</svg>
								</div>
							</div>

							{ /* Slider */ }
							<div
								className="!flex !transition-transform !duration-500 !ease-in-out !select-none !items-center"
								style={ {
									transform: `translateX(-${
										currentIndex * 100
									}%)`,
								} }
							>
								{ reviews.map( ( review, i ) => (
									<div
										key={ i }
										className="!w-full !flex-shrink-0 !flex !flex-col !text-center !gap-6"
									>
										<p className="!text-lg 400:!text-xl lg:!text-2xl !leading-relaxed !max-w-[75%] 400:!max-w-[80%] !mx-auto !text-center !my-0">
											{ review.text || '' }
										</p>

										<div className="!flex !flex-col !items-center !gap-2">
											<div className="!flex !justify-center !gap-0">
												{ [ 1, 2, 3, 4, 5 ].map(
													( s ) => (
														<svg
															key={ s }
															className={ `${
																s <=
																( review.stars ||
																	0 )
																	? '!text-[#EDBB4F]'
																	: '!text-gray-300'
															}` }
															fill="currentColor"
															viewBox="0 0 20 20"
															width="22"
															height="22"
														>
															<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.067 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.955z" />
														</svg>
													)
												) }
											</div>
											<p className="!text-neutral-600 !capitalize !my-0">
												{ review.name || '' }
											</p>
										</div>
									</div>
								) ) }
							</div>
						</div>

						{ /* Pagination */ }
						<div className="!flex !flex-row !items-center !justify-center !gap-2">
							<div className="!flex !justify-center !gap-1">
								{ reviews.map( ( _, i ) => (
									<button
										key={ i }
										className={ `!h-3 !w-3 !rounded-full !transition-colors !cursor-pointer ${
											currentIndex === i
												? '!bg-brand-green-dark'
												: '!border !border-brand-green-dark'
										}` }
										onClick={ () => goToSlide( i ) }
										aria-label={ `Go to review ${ i + 1 }` }
									/>
								) ) }
							</div>
							<div className="!text-sm !font-medium">
								<span>{ currentIndex + 1 }</span> /{ ' ' }
								<span>{ totalSlides }</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</>
	);
}
