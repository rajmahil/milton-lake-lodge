import { useState, useEffect, useRef } from 'react';

function convertPrice( price, fromCAD = true ) {
	const currency = 'CAD';
	const exchangeRate = 1.25;
	if ( ! price || isNaN( parseFloat( price ) ) ) return price;
	const numPrice = parseFloat( price );
	if ( currency === 'CAD' ) {
		return fromCAD ? numPrice : numPrice * exchangeRate;
	} else {
		return fromCAD ? numPrice / exchangeRate : numPrice;
	}
}

function formatPrice( price, priceType ) {
	if ( priceType !== 'currency' ) return price;
	if ( ! price || isNaN( parseFloat( price ) ) ) return 'N/A';
	const converted = convertPrice( price, true );
	return new Intl.NumberFormat( 'en-US', {
		minimumFractionDigits: 0,
		maximumFractionDigits: 0,
	} ).format( Math.round( converted ) );
}

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
				<h2 className="!my-0 text-center heading-two font-extrabold uppercase text-brand-green">
					{ heading }
				</h2>

				{ tabs.length > 0 ? (
					<div className="flex flex-col gap-8">
						<div className="bg-white rounded-lg sm:rounded-full w-full sm:w-fit mx-auto p-1">
							<div className="flex flex-col sm:flex-row justify-center">
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
												? 'bg-brand-green text-white font-bold'
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
										<div className="!grid !grid-cols-3 md:!grid-cols-2 py-6 px-4 sm:px-8 !text-base !text-gray-800 gap-5 lg:gap-10">
											<p className="!col-span-2 md:!col-span-1 !my-0">
												Package Type
											</p>
											<div className="!flex !justify-end md:!justify-start items-center gap-4 lg:gap-10 flex-wrap">
												<p className="!my-0">
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
																			? 'bg-brand-green text-white font-bold'
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
										<div className="pt-8 md:pt-0">
											{ tab.features &&
											tab.features.length > 0 ? (
												tab.features.map(
													( feature, i ) => (
														<div
															key={ i }
															className="grid !grid-cols-3 md:!grid-cols-2 gap-5 lg:gap-10 pb-6 !pt-0 px-4 sm:px-8"
														>
															<div className="mb-2 md:mb-0  !col-span-2 md:!col-span-1">
																<h3
																	className="!my-0 !text-lg md:!text-xl !capitalize"
																	style={ {
																		fontFamily:
																			'"Overused Grotesk"',
																		fontWeight: 500,
																	} }
																>
																	{
																		feature.title
																	}
																</h3>
																{ feature.description && (
																	<p className="!text-sm !my-0 md:!max-w-[70%]">
																		{
																			feature.description
																		}
																	</p>
																) }
															</div>
															<div className="!flex !justify-end md:!justify-start items-start ">
																<p className="!my-0 !text-right">
																	{ feature.priceType ===
																	'currency' ? (
																		<>
																			<span
																				className="!text-lg md:!text-xl"
																				style={ {
																					fontWeight: 500,
																				} }
																			>
																				$
																				{ formatPrice(
																					feature.price,
																					'currency'
																				) }
																			</span>{ ' ' }
																			<span className="!text-sm">
																				{
																					currency
																				}
																			</span>
																		</>
																	) : (
																		<span className="!text-base sm:!text-lg break-words whitespace-normal block max-w-full !text-right">
																			{
																				feature.price
																			}
																		</span>
																	) }
																</p>
															</div>
														</div>
													)
												)
											) : (
												<p className="text-gray-800 px-4 sm:px-8 !py-0 !pb-5 !text-base">
													No features avaialble for
													this package.
												</p>
											) }
										</div>

										{ tab.note && (
											<div className="border-t border-brand-grey !p-4 sm:!p-8 text-gray-800">
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
					<div className="text-center px-4 sm:px-8 !py-0 !pb-5 text-gray-500">
						No packages added yet. Please add packages in the
						editor.
					</div>
				) }
			</div>
		</section>
	);
};

export default PriceTable;
