import { useState, useEffect } from 'react';

export default function ReviewsSection( { topHeading, reviews = [] } ) {
	const [ currentIndex, setCurrentIndex ] = useState( 0 );
	const [ animationKey, setAnimationKey ] = useState( 0 );

	// Trigger animation when currentIndex changes
	useEffect( () => {
		setAnimationKey( ( prev ) => prev + 1 );
	}, [ currentIndex ] );

	const currentReview = reviews[ currentIndex ];

	return (
		<section className="not-prose section-padding w-full static-background">
			{ /* Add custom CSS for animations */ }
			<style jsx>{ `
				@keyframes slideInTiltLeft {
					0% {
						transform: translateX( 10px ) translateY( 10px )
							rotate( 0deg ) scale( 0.95 );
					}
					100% {
						transform: translateX( -5px ) translateY( 0 )
							rotate( -7deg ) scale( 1 );
					}
				}

				@keyframes slideInTiltRight {
					0% {
						transform: translateX( -10px ) translateY( 10px )
							rotate( 0deg ) scale( 0.95 );
					}
					100% {
						transform: translateX( 5px ) translateY( 0 )
							rotate( 4deg ) scale( 1 );
					}
				}

				.animate-slide-tilt-left {
					animation: slideInTiltLeft 0.8s ease-out forwards;
					transform: translateX( -5px ) rotate( -7deg );
				}

				.animate-slide-tilt-right {
					animation: slideInTiltRight 0.8s ease-out forwards;
					transform: translateX( 5px ) rotate( 4deg );
				}
			` }</style>
			<div className="max-w-container mx-auto grid lg:grid-cols-2  items-center">
				{ /* Images Section */ }
				<div className="relative">
					{ currentReview && (
						<div className="flex justify-center items-center relative ">
							{ /* First Image */ }
							{ currentReview.image1?.url && (
								<div
									key={ `left-${ animationKey }` }
									className="w-full aspect-[3/4] max-w-[170px] sm:max-w-[200px] md:max-w-[300px]  rounded-lg shadow-lg animate-slide-tilt-left bg-white p-1"
								>
									<img
										src={ currentReview.image1.url }
										alt={
											currentReview.image1.alt ||
											`Review image 1 by ${ currentReview.name }`
										}
										className="w-full h-full aspect-[3/4] max-w-[170px] sm:max-w-[200px] md:max-w-[300px] object-cover transition-transform duration-300 "
									/>
								</div>
							) }

							{ /* Second Image */ }
							{ currentReview.image2?.url && (
								<div
									key={ `right-${ animationKey }` }
									className="w-full  aspect-[3/4] max-w-[170px] sm:max-w-[200px] md:max-w-[300px]  rounded-lg shadow-lg animate-slide-tilt-right -ml-16 sm:-ml-20 lg:-ml-24 z-10 bg-white p-1"
								>
									<img
										src={ currentReview.image2.url }
										alt={
											currentReview.image2.alt ||
											`Review image 2 by ${ currentReview.name }`
										}
										className="w-full h-full aspect-[3/4] max-w-[170px] sm:max-w-[200px] md:max-w-[300px] object-cover transition-transform duration-300 "
									/>
								</div>
							) }
						</div>
					) }

					{ /* Fallback for missing images */ }
					{ ( ! currentReview?.image1?.url ||
						! currentReview?.image2?.url ) && (
						<div className="w-full max-w-md mx-auto h-64 sm:h-48 lg:h-64 bg-gray-200 rounded-lg flex items-center justify-center">
							<p className="text-gray-500 text-sm">
								Images not available
							</p>
						</div>
					) }
				</div>

				{ /* Reviews Content Section */ }
				<div className="relative w-full overflow-hidden flex flex-col">
					{ topHeading && (
						<p className="decorative-text !text-brand-yellow-dark text-center text-3xl lg:!text-4xl !my-0">
							{ topHeading }
						</p>
					) }

					<div className="relative overflow-hidden">
						<div
							className="flex transition-transform duration-500 ease-in-out"
							style={ {
								transform: `translateX(-${
									currentIndex * 100
								}%)`,
							} }
						>
							{ reviews.map( ( review, index ) => {
								const stars = review.stars || 0;
								return (
									<div
										key={ index }
										className="w-full shrink-0 px-4"
										tabIndex={ 0 }
										role="region"
										aria-labelledby={ `review-title-${ index }` }
										aria-describedby={ `review-text-${ index }` }
									>
										<div className="flex flex-col items-center gap-3">
											<p
												id={ `review-text-${ index }` }
												className="!text-2xl leading-relaxed text-brand-dark-blue !my-0 text-center"
											>
												{ review.text }
											</p>

											<div className="stars flex">
												{ [ 1, 2, 3, 4, 5 ].map(
													( star ) => (
														<svg
															key={ star }
															className={ `w-6 h-6 ${
																star <= stars
																	? 'text-[#EDBB4F]'
																	: 'text-gray-300'
															}` }
															fill="currentColor"
															viewBox="0 0 20 20"
															xmlns="http://www.w3.org/2000/svg"
															aria-hidden="true"
														>
															<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.067 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.955z" />
														</svg>
													)
												) }
											</div>

											<p
												id={ `review-title-${ index }` }
												className="text-sm text-gray-600 font-medium !my-0 capitalize"
											>
												{ review.name }
											</p>
										</div>
									</div>
								);
							} ) }
						</div>

						<div className="flex justify-center gap-2 mt-6">
							{ reviews.map( ( _, i ) => (
								<button
									key={ i }
									className={ `h-2.5 w-2.5 rounded-full transition-colors ${
										i === currentIndex
											? 'bg-black'
											: 'bg-white border border-black'
									}` }
									onClick={ () => setCurrentIndex( i ) }
									aria-label={ `Go to review ${ i + 1 }` }
								/>
							) ) }
						</div>
					</div>
				</div>
			</div>
		</section>
	);
}
