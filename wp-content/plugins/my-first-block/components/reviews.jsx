import { useState, useEffect } from 'react';

export default function ReviewsSection( { topHeading, reviews = [] } ) {
	const [ currentIndex, setCurrentIndex ] = useState( 0 );
	const [ animationKey, setAnimationKey ] = useState( 0 ); // To re-trigger animations

	// Effect to re-trigger animations when currentIndex changes
	useEffect( () => {
		setAnimationKey( ( prev ) => prev + 1 );
	}, [ currentIndex ] );

	const totalSlides = reviews.length;

	return (
		<section className="not-prose section-padding w-full static-background">
			<style jsx>{ `
				.review-carousel-container {
					overflow: hidden;
					position: relative;
				}

				.review-slides-wrapper {
					display: flex;
					transition: transform 0.4s ease-in-out;
					width: ${ totalSlides * 100 }%;
				}

				.review-slide {
					flex: 0 0 ${ 100 / totalSlides }%;
					opacity: 1;
				}

				.review-images {
					opacity: 1;
					display: none; /* Hidden by default */
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
				}

				.review-images.active {
					display: block; /* Only active image container is displayed */
				}

				#review-images-container {
					position: relative;
					min-height: 300px; /* Ensure there's space for images */
				}

				.review-images .flex {
					height: 100%;
					align-items: center;
					justify-content: center;
				}

				/* Improved image animation keyframes - both start from center */
				@keyframes slideFromCenterToLeft {
					0% {
						transform: translateX( 0 ) translateY( 10px )
							rotate( 0deg ) scale( 0.9 );
						opacity: 1;
					}
					100% {
						transform: translateX( -60px ) translateY( 0 )
							rotate( -7deg ) scale( 1 );
						opacity: 1;
					}
				}

				@keyframes slideFromCenterToRight {
					0% {
						transform: translateX( 0 ) translateY( 10px )
							rotate( 0deg ) scale( 0.9 );
						opacity: 1;
					}
					100% {
						transform: translateX( 60px ) translateY( 0 )
							rotate( 4deg ) scale( 1 );
						opacity: 1;
					}
				}

				.animate-slide-center-left {
					animation: slideFromCenterToLeft 0.8s ease-out forwards;
				}

				.animate-slide-center-right {
					animation: slideFromCenterToRight 0.8s ease-out forwards;
				}

				/* Reset animation class - both images start at center */
				.animate-reset {
					animation: none;
					transform: translateX( 0 ) translateY( 10px ) rotate( 0deg )
						scale( 0.9 );
					opacity: 1;
				}

				/* Initial positioning for overlapped center start */
				.image-left,
				.image-right {
					transform: translateX( 0 ) translateY( 10px ) rotate( 0deg )
						scale( 0.9 );
					opacity: 1;
				}

				/* Adjust the static positioning for the final state */
				.image-left.final-position {
					transform: translateX( -60px ) translateY( 0 )
						rotate( -7deg ) scale( 1 );
				}

				.image-right.final-position {
					transform: translateX( 60px ) translateY( 0 ) rotate( 4deg )
						scale( 1 );
				}

				/* Initial fade in for first load */
				@keyframes fadeInUp {
					0% {
						opacity: 0;
						transform: translateY( 20px );
					}
					100% {
						opacity: 1;
						transform: translateY( 0 );
					}
				}

				.review-slide {
					animation: fadeInUp 0.4s ease-out forwards;
				}
			` }</style>

			<div className="max-w-container mx-auto grid lg:grid-cols-2  items-center justify-center">
				{ /* Images that change with review */ }
				<div className="relative" id="review-images-container">
					{ reviews.map( ( review, i ) => (
						<div
							key={ i }
							className={ `review-images ${
								i === currentIndex ? 'active' : ''
							}` }
							data-index={ i }
						>
							<div className="flex justify-center items-center relative h-full">
								{ review.image1?.url && (
									<div
										key={ `image1-${ i }-${ animationKey }` } // Key to re-trigger animation
										className={ `image-left w-full aspect-[3/4] max-w-[170px] sm:max-w-[200px] md:max-w-[350px] rounded-lg shadow-lg bg-white p-1 absolute ${
											i === currentIndex
												? 'animate-slide-center-left'
												: 'final-position'
										}` }
									>
										<img
											src={ review.image1.url }
											alt={
												review.image1.alt ||
												'Review image 1'
											}
											className="w-full h-full object-cover"
										/>
									</div>
								) }

								{ review.image2?.url && (
									<div
										key={ `image2-${ i }-${ animationKey }` } // Key to re-trigger animation
										className={ `image-right w-full aspect-[3/4] max-w-[170px] sm:max-w-[200px] md:max-w-[350px] rounded-lg shadow-lg bg-white p-1 absolute z-10 ${
											i === currentIndex
												? 'animate-slide-center-right'
												: 'final-position'
										}` }
									>
										<img
											src={ review.image2.url }
											alt={
												review.image2.alt ||
												'Review image 2'
											}
											className="w-full h-full object-cover"
										/>
									</div>
								) }
							</div>
						</div>
					) ) }
				</div>

				{ /* Review text content - slides */ }
				<div className="w-full flex flex-col items-center">
					{ topHeading && (
						<p className="decorative-text !text-brand-yellow-dark text-center text-3xl lg:!text-4xl !mt-0 sm:!mt-[160px] lg:!mt-0 !mb-4">
							{ topHeading }
						</p>
					) }

					<div className="review-carousel-container relative w-full">
						<div
							className="review-slides-wrapper"
							style={ {
								transform: `translateX(-${
									currentIndex * ( 100 / totalSlides )
								}%)`,
							} }
						>
							{ reviews.map( ( review, i ) => (
								<div
									key={ i }
									className="review-slide"
									data-index={ i }
								>
									<div className="text-center px-4">
										<p className="!text-2xl leading-relaxed text-brand-dark-blue !mt-0 !mb-4">
											{ review.text || '' }
										</p>
										<div className="flex justify-center gap-1 mb-2">
											{ [ 1, 2, 3, 4, 5 ].map( ( s ) => (
												<svg
													key={ s }
													className={ `w-6 h-6 ${
														s <=
														( review.stars || 0 )
															? 'text-[#EDBB4F]'
															: 'text-gray-300'
													}` }
													fill="currentColor"
													viewBox="0 0 20 20"
													xmlns="http://www.w3.org/2000/svg"
												>
													<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.067 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.955z" />
												</svg>
											) ) }
										</div>
										<p className="text-sm text-gray-600 font-medium capitalize !my-0">
											{ review.name || '' }
										</p>
									</div>
								</div>
							) ) }
						</div>
					</div>

					{ /* Pagination Dots */ }
					<div className="flex justify-center gap-2 mt-6">
						{ reviews.map( ( _, i ) => (
							<button
								key={ i }
								className={ `h-2.5 w-2.5 rounded-full transition-colors dot ${
									i === currentIndex
										? 'bg-black'
										: 'bg-white border border-black'
								}` }
								data-dot={ i }
								onClick={ () => setCurrentIndex( i ) }
								aria-label={ `Go to review ${ i + 1 }` }
							></button>
						) ) }
					</div>
				</div>
			</div>
		</section>
	);
}
