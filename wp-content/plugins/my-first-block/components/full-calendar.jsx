import { useState, useEffect } from '@wordpress/element';

function formatDate( yyyymmdd ) {
	const year = yyyymmdd.slice( 0, 4 );
	const month = yyyymmdd.slice( 4, 6 ) - 1; // zero-indexed
	const day = yyyymmdd.slice( 6, 8 );
	return new Date( year, month, day ).toLocaleDateString( undefined, {
		month: 'short',
		day: 'numeric',
		year: 'numeric',
	} );
}

const FullCalendarComp = ( props ) => {
	const { heading, subheading, downloadableFile, selectedPostId, sectionId } =
		props;

	const [ selected, setSelected ] = useState( null );
	const [ slots, setSlots ] = useState( [] );

	useEffect( () => {
		if ( ! myCalendarData?.posts?.length ) return;

		const selectedPost = myCalendarData?.posts?.find(
			( post ) => post.id === selectedPostId
		);

		if ( selectedPost ) {
			console.log( 'Selected Post:', selectedPost );

			const tripSlots = selectedPost?.trips
				.map( ( slot ) => {
					const slotType = selectedPost?.slot_types.find(
						( type ) => type.label_ === slot.trip_type
					);

					return {
						title: slot.trip_type,
						start: slot.start_date,
						end: slot.end_date,
						description: slot.description,
						extendedProps: {
							backgroundColor:
								slotType?.background_color || '#fef3c7',
							textColor: slotType?.text_color || '#1f2937',
							status: slot.status,
							startDate: slot.start_date,
							endDate: slot.end_date,
						},
					};
				} )
				?.sort( ( a, b ) => {
					const formatDate = ( str ) => {
						// Convert "20250613" -> "2025-06-13"
						return new Date(
							`${ str.slice( 0, 4 ) }-${ str.slice(
								4,
								6
							) }-${ str.slice( 6, 8 ) }`
						);
					};

					return formatDate( a.start ) - formatDate( b.start );
				} );

			setSlots( tripSlots );
			setSelected( selectedPost );
		} else {
			setSelected( myCalendarData.posts[ 0 ] );
		}
	}, [ myCalendarData, selectedPostId ] );

	if ( ! myCalendarData?.posts?.length ) {
		return (
			<div className="text-center text-gray-500">
				No calendar posts found.
			</div>
		);
	}

	return (
		<section
			id={ sectionId }
			className="plugin-custom-block  section-padding"
		>
			<div className="max-w-container w-full mx-auto flex flex-col gap-12">
				<div>
					{ heading && (
						<h2 className="heading-two text-center">{ heading }</h2>
					) }
					{ subheading && (
						<p className="text-center text-neutral-600 text-xl">
							{ subheading }
						</p>
					) }
				</div>

				<div className="flex flex-col items-center gap-4">
					<div className="grid grid-cols-1 xl:grid-cols-4 gap-6">
						<div className="p-0 lg:p-6 xl:col-span-3 rounded-lg my-calendar-wrapper">
							<div className="flex flex-col gap-10">
								{ Object.entries(
									slots?.reduce( ( groups, slot ) => {
										const date = new Date(
											slot.start.slice( 0, 4 ),
											slot.start.slice( 4, 6 ) - 1
										);
										const key = date.toLocaleString(
											'default',
											{
												month: 'long',
												year: 'numeric',
											}
										);

										if ( ! groups[ key ] )
											groups[ key ] = [];
										groups[ key ].push( slot );
										return groups;
									}, {} )
								)?.map( ( [ month, groupedSlots ], index ) => (
									<div key={ month } className="">
										<h2 className="text-3xl  mb-2">
											{ month }
										</h2>

										<div className="flex flex-col gap-2">
											{ groupedSlots.map( ( slot, i ) => {
												const status =
													slot.extendedProps.status
														.label;

												const statusClasses = {
													Open: 'bg-green-100 border border-green-7000 text-green-700  py-0.5 px-2 rounded-full',
													Pending:
														'bg-yellow-100 border border-yellow-700  text-yellow-700  py-0.5 px-2 rounded-full',
													Booked: 'bg-red-100 border border-red-700 text-red-700 py-0.5 px-2 rounded-full',
													Past: 'bg-gray-100 border border-gray-700 text-gray-700 py-0.5 px-2 rounded-full',
												};

												const isPast =
													new Date(
														slot.start.slice(
															0,
															4
														),
														slot.start.slice(
															4,
															6
														) - 1,
														slot.start.slice( 6, 8 )
													) < new Date();

												return (
													<div
														key={ i }
														className="p-4 bg-white rounded-lg relative flex flex-col gap-4"
													>
														<div className="absolute top-2 right-2 text-xs py-0.5 px-2 rounded-full">
															<div
																className={
																	statusClasses[
																		! isPast
																			? status
																			: 'Past'
																	] ||
																	'bg-gray-300 text-black'
																}
															>
																{ ! isPast
																	? status
																	: 'Completed' }
															</div>
														</div>

														<div className="flex flex-row items-center gap-4 ">
															<div
																style={ {
																	backgroundColor:
																		slot
																			.extendedProps
																			.backgroundColor,
																	color: slot
																		.extendedProps
																		.textColor,
																} }
																className="aspect-square w-[60px] h-[60px] rounded-md flex items-center justify-center"
															>
																<svg
																	xmlns="http://www.w3.org/2000/svg"
																	width="25"
																	height="25"
																	fill="currentColor"
																	viewBox="0 0 256 256"
																	className="opacity-60"
																>
																	<path d="M168,76a12,12,0,1,1-12-12A12,12,0,0,1,168,76Zm48.72,67.64c-19.37,34.9-55.44,53.76-107.24,56.1l-22,51.41A8,8,0,0,1,80.1,256l-.51,0a8,8,0,0,1-7.19-5.78L57.6,198.39,5.8,183.56a8,8,0,0,1-1-15.05l51.41-22c2.35-51.78,21.21-87.84,56.09-107.22,24.75-13.74,52.74-15.84,71.88-15.18,18.64.64,36,4.27,38.86,6a8,8,0,0,1,2.83,2.83c1.69,2.85,5.33,20.21,6,38.85C232.55,90.89,230.46,118.89,216.72,143.64Zm-55.18,29a52.11,52.11,0,0,1-33.4-44.78A52.09,52.09,0,0,1,83.37,94.47q-10.45,23.79-11.3,57.59a8,8,0,0,1-4.85,7.17L31.83,174.37l34.45,9.86a8,8,0,0,1,5.49,5.5l9.84,34.44,15.16-35.4a8,8,0,0,1,7.17-4.84Q137.71,183.12,161.54,172.64ZM212.42,43.57c-14.15-3-64.1-11-100.3,14.75a81.21,81.21,0,0,0-16,15.07,36,36,0,0,0,39.35,38.44,8,8,0,0,1,8.73,8.73,36,36,0,0,0,38.47,39.34,80.81,80.81,0,0,0,15-16C223.42,107.73,215.42,57.74,212.42,43.57Z"></path>
																</svg>
															</div>
															<div>
																<p className="font-medium text-lg ">
																	{
																		slot.title
																	}
																</p>
																<p className="text-base text-neutral-500 font-medium">
																	{ formatDate(
																		slot.start
																	) }{ ' ' }
																	→{ ' ' }
																	{ formatDate(
																		slot.end
																	) }
																</p>
															</div>
														</div>

														{ slot?.description && (
															<p className="text-neutral-600">
																{
																	slot?.description
																}
															</p>
														) }
													</div>
												);
											} ) }
										</div>
									</div>
								) ) }
							</div>
						</div>

						<div className="h-full ">
							<div className="flex flex-col gap-4  h-fit sticky top-[100px]">
								<div className="p-4 rounded-lg bg-white flex flex-col gap-2 w-full">
									<h3 className="text-2xl">Legend</h3>
									<div className="flex flex-col gap-2">
										{ (
											selected?.calendar_legend || []
										).map( ( item, index ) => (
											<div
												key={ index }
												className="flex items-center gap-2"
											>
												<div
													style={ {
														backgroundColor:
															item.background_color,
													} }
													className="w-6 h-6 rounded-lg"
												></div>
												<p className="text-lg">
													{ item.label }
												</p>
											</div>
										) ) }
									</div>
								</div>

								{ selected?.additional_notes && (
									<div className="p-4 rounded-lg bg-white flex flex-col gap-2 w-full">
										<h3 className="text-2xl">Details</h3>
										<p>{ selected?.additional_notes }</p>
									</div>
								) }

								{ downloadableFile?.url && (
									<a
										href={ downloadableFile?.url }
										target="_blank"
										rel="noopener noreferrer"
										download={ downloadableFile?.title }
										aria-label="Download PDF"
										class="hero-btn-link"
									>
										<button
											class="btn btn-white hover:bg-brand-yellow bg-white btn-lg w-full"
											type="button"
										>
											Download Trip Calendar
										</button>
									</a>
								) }
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	);
};

export default FullCalendarComp;
