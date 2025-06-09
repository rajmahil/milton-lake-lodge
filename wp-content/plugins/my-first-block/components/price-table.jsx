import React, { useState, useEffect, useRef } from 'react';

const PriceTable = ( { heading, tabs } ) => {
	const [ activeTab, setActiveTab ] = useState( 1 );
	const [ slideDirection, setSlideDirection ] = useState( 'right' );
	const [ currency, setCurrency ] = useState( 'CAD' );

	const tabButtonsRef = useRef( [] );

	const handleTabChange = ( newTab ) => {
		setSlideDirection( newTab > activeTab ? 'right' : 'left' );
		setActiveTab( newTab );
	};

	useEffect( () => {
		if ( tabs.length > 0 ) {
			setActiveTab( 1 );
		}
	}, [ tabs ] );

	return (
		<section className="plugin-custom-block not-prose section-padding w-full static-background">
			<div className=" max-w-container mx-auto flex flex-col gap-8">
				<h2 className="!my-0 text-center !text-4xl md:!text-5xl font-extrabold uppercase text-[#123C2A]">
					{ heading }
				</h2>

				{ tabs.length > 0 ? (
					<div className="flex flex-col gap-8">
						<div className="bg-white rounded-full w-fit mx-auto p-1">
							<div className="flex justify-center">
								{ tabs.map( ( tab, index ) => (
									<button
										key={ index }
										ref={ ( el ) =>
											( tabButtonsRef.current[ index ] =
												el )
										}
										onClick={ () =>
											handleTabChange( index + 1 )
										}
										className={ `px-6 py-2 text-lg rounded-full transition-all duration-300 cursor-pointer whitespace-nowrap ${
											activeTab === index + 1
												? 'bg-[#123C2A] text-white font-bold'
												: 'text-gray-500 hover:text-gray-800'
										}` }
									>
										{ tab.title }
									</button>
								) ) }
							</div>
						</div>

						<div
							className="relative overflow-hidden"
							style={ { height: 'auto', minHeight: '300px' } }
						>
							{ tabs.map( ( tab, index ) => (
								<div
									key={ index }
									className={ `transition-all duration-500 ease-in-out ${
										activeTab === index + 1
											? 'block translate-x-0 opacity-100'
											: slideDirection === 'right'
											? 'hidden translate-x-full opacity-0'
											: 'hidden -translate-x-full opacity-0'
									}` }
								>
									<div className="bg-white rounded-2xl  overflow-hidden">
										<div class="!hidden 750:!grid 750:!grid-cols-2 py-6 px-8 !text-base !text-gray-800  1000:gap-10">
											<p className="!hidden 750:!block">
												Package Type
											</p>
											<div className="flex items-center gap-10">
												<p className="!hidden 750:!block">
													Per Person
												</p>
												<div className="bg-stone-200 rounded-full w-fit p-1">
													<div className="flex justify-center !text-sm">
														{ [ 'USD', 'CAD' ].map(
															( curr ) => (
																<button
																	key={ curr }
																	onClick={ () =>
																		setCurrency(
																			curr
																		)
																	}
																	className={ `px-3 py-1 text-lg rounded-full transition-all duration-300 cursor-pointer whitespace-nowrap ${
																		currency ===
																		curr
																			? 'bg-[#123C2A] text-white font-bold'
																			: 'text-black'
																	}` }
																>
																	{ curr }
																</button>
															)
														) }
													</div>
												</div>
											</div>
										</div>
										<div class="pt-8 750:pt-0">
											{ tab.features &&
											tab.features.length > 0 ? (
												tab.features.map(
													( feature, i ) => (
														<div
															key={ i }
															className="grid md:grid-cols-2  1000:gap-10 pb-6 !pt-0 px-8"
														>
															<div className="mb-2 md:mb-0">
																<p className="!my-0 text-lg md:text-xl font-bold !capitalize">
																	{
																		feature.title
																	}
																</p>
																{ feature.description && (
																	<p className="!text-sm !my-0 750:!max-w-[70%]">
																		{
																			feature.description
																		}
																	</p>
																) }
															</div>
															<div className="mt-2 md:mt-0">
																<p>
																	{ feature.priceType ===
																	'currency' ? (
																		<>
																			<span className="!text-xl !font-medium">
																				$
																				{ currency ===
																				'USD'
																					? feature.price
																					: feature.price }
																			</span>{ ' ' }
																			<span className="!text-sm">
																				{
																					currency
																				}
																			</span>
																		</>
																	) : (
																		feature.price
																	) }
																</p>
															</div>
														</div>
													)
												)
											) : (
												<p className="text-gray-800 px-8 !py-0 !pb-5 !text-base">
													No features avaialble for
													this package.
												</p>
											) }
										</div>

										{ tab.note && (
											<div className="border-t border-brand-grey !p-8 text-gray-800">
												<p className="!text-sm ">
													{ tab.note }
												</p>
											</div>
										) }
									</div>
								</div>
							) ) }
						</div>
					</div>
				) : (
					<div className="text-center px-8 !py-0 !pb-5 text-gray-500">
						No packages added yet. Please add packages in the
						editor.
					</div>
				) }
			</div>
		</section>
	);
};

export default PriceTable;
