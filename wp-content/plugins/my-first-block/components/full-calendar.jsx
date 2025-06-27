import { useState, useEffect } from '@wordpress/element';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';

function addDayToYYYYMMDD( dateStr ) {
	const year = parseInt( dateStr.slice( 0, 4 ) );
	const month = parseInt( dateStr.slice( 4, 6 ) ) - 1;
	const day = parseInt( dateStr.slice( 6, 8 ) );

	const date = new Date( year, month, day );
	date.setDate( date.getDate() + 1 );

	const yyyy = date.getFullYear();
	const mm = String( date.getMonth() + 1 ).padStart( 2, '0' );
	const dd = String( date.getDate() ).padStart( 2, '0' );

	return `${ yyyy }${ mm }${ dd }`;
}

function hexToRgba( hex, opacity ) {
	const parsed = hex.replace( '#', '' );
	const bigint = parseInt( parsed, 16 );
	const r = ( bigint >> 16 ) & 255;
	const g = ( bigint >> 8 ) & 255;
	const b = bigint & 255;
	return `rgba(${ r }, ${ g }, ${ b }, ${ opacity })`;
}

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

function isMobile() {
	return window.innerWidth < 768; // md breakpoint in Tailwind
}

const FullCalendarComp = ( props ) => {
	const { heading, subheading, selectedPostId, sectionId } = props;

	const [ selected, setSelected ] = useState( null );
	const [ slots, setSlots ] = useState( [] );
	const [ defMonth, setDefMonth ] = useState( null );

	const getInitialView = () => {
		return isMobile() ? 'list' : 'calendar';
	};

	const [ defaultView, setDefaultView ] = useState( getInitialView() );

	const handleViewChange = ( view ) => {
		setDefaultView( view );
	};

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
						end: addDayToYYYYMMDD( slot.end_date ),
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
				?.sort( ( a, b ) => new Date( a.start ) - new Date( b.start ) ); // sort by start date

			if ( ! selectedPost?.default_month_view ) {
				const activeMonths = [ 5, 6, 7 ];
				const currentMonth = new Date().getMonth();
				const currentYear = new Date().getFullYear();

				if ( activeMonths.includes( currentMonth ) ) {
					setDefMonth(
						`${ currentYear }-${ selectedPost?.default_month_view?.value }`
					);
				}
			}

			setSlots( tripSlots );
			setSelected( selectedPost );
		} else {
			setSelected( myCalendarData.posts[ 0 ] );
		}
	}, [ myCalendarData, selectedPostId ] );

	// Handle resize to update view on screen size change
	useEffect( () => {
		const handleResize = () => {
			const newInitialView = getInitialView();
			setDefaultView( newInitialView );
		};

		window.addEventListener( 'resize', handleResize );

		// Set initial view on component mount
		setDefaultView( getInitialView() );

		return () => {
			window.removeEventListener( 'resize', handleResize );
		};
	}, [] );

	if ( ! myCalendarData?.posts?.length ) {
		return (
			<div className="text-center text-gray-500">
				No calendar posts found.
			</div>
		);
	}

	console.log( slots, 'Slots Data' );

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
					<div className="w-fit bg-white p-1 rounded-full grid grid-cols-2 gap-0 mb-6">
						<button
							className={ `text-lg btn btn-lg ${
								defaultView === 'calendar' ? 'btn-dark' : ''
							}` }
							onClick={ () => handleViewChange( 'calendar' ) }
						>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="22"
								height="22"
								fill="currentColor"
								viewBox="0 0 256 256"
								className="mr-1"
							>
								<path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM72,48v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80H48V48ZM208,208H48V96H208V208Zm-68-76a12,12,0,1,1-12-12A12,12,0,0,1,140,132Zm44,0a12,12,0,1,1-12-12A12,12,0,0,1,184,132ZM96,172a12,12,0,1,1-12-12A12,12,0,0,1,96,172Zm44,0a12,12,0,1,1-12-12A12,12,0,0,1,140,172Zm44,0a12,12,0,1,1-12-12A12,12,0,0,1,184,172Z"></path>
							</svg>
							Calendar
						</button>
						<button
							className={ `text-lg btn btn-lg ${
								defaultView === 'list' ? 'btn-dark' : ''
							}` }
							onClick={ () => handleViewChange( 'list' ) }
						>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="22"
								height="22"
								fill="currentColor"
								viewBox="0 0 256 256"
								className="mr-1"
							>
								<path d="M88,64a8,8,0,0,1,8-8H216a8,8,0,0,1,0,16H96A8,8,0,0,1,88,64Zm128,56H96a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16Zm0,64H96a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16ZM56,56H40a8,8,0,0,0,0,16H56a8,8,0,0,0,0-16Zm0,64H40a8,8,0,0,0,0,16H56a8,8,0,0,0,0-16Zm0,64H40a8,8,0,0,0,0,16H56a8,8,0,0,0,0-16Z"></path>
							</svg>
							List
						</button>
					</div>

					<div className="grid grid-cols-1 xl:grid-cols-4 gap-6">
						<div className="p-3 sm:p-6 xl:col-span-3 bg-white rounded-lg my-calendar-wrapper">
							{ defaultView === 'calendar' ? (
								<div className="overflow-x-scroll  w-full">
									<FullCalendar
										key={ defMonth || 'default' }
										aspectRatio={ 1.35 }
										height="auto"
										initialDate={ defMonth || undefined }
										headerToolbar={ {
											left: 'title',
											right: 'prev,today,next',
										} }
										plugins={ [ dayGridPlugin ] }
										initialView="dayGridMonth"
										events={ slots }
										eventContent={ ( arg ) => {
											const bgColorFrom =
												hexToRgba(
													arg.event.extendedProps
														.backgroundColor,
													0.1
												) || '#fef3c7';

											const bgColorTo =
												hexToRgba(
													arg.event.extendedProps
														.backgroundColor,
													0.2
												) || '#fef3c7';
											const textColor =
												arg.event.extendedProps
													.textColor || '#1f2937';
											const status =
												arg.event.extendedProps.status
													.label;

											const start = arg.event.start;
											const end = arg.event.end;

											const options = {
												year: 'numeric',
												month: 'short',
												day: 'numeric',
											};

											const startFormatted =
												start.toLocaleDateString(
													undefined,
													options
												);
											const endFormatted = end
												? new Date(
														end.setDate(
															end.getDate() - 1
														)
												  ).toLocaleDateString(
														undefined,
														options
												  )
												: startFormatted;

											const rangeText =
												startFormatted === endFormatted
													? startFormatted
													: `${ startFormatted } → ${ endFormatted }`;

											const statusClasses = {
												Open: 'bg-green-700 text-white text-xs py-0.5 px-2 rounded-full',
												Pending:
													'bg-yellow-300 text-black text-xs py-0.5 px-2 rounded-full',
												Booked: 'bg-red-600 text-white text-xs py-0.5 px-2 rounded-full',
											};

											console.log(
												start,
												end,
												'Event Dates'
											);

											return (
												<div
													style={ {
														backgroundImage: `linear-gradient(135deg, ${ bgColorFrom } 0%, ${ bgColorTo } 100%)`,
														color: textColor,
													} }
													className="p-2 rounded-md h-22 font-medium  text-wrap flex flex-col gap-2 items-start justify-between pattern-diagonal-lines pattern-blue-500 pattern-bg-white
 pattern-size-6"
												>
													<p className="text-sm !leading-[1.1] ">
														{ arg.event.title }{ ' ' }
													</p>
													<p className="text-sm leading-[1.1]">
														{ rangeText }
													</p>
												</div>
											);
										} }
									/>
								</div>
							) : (
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
									).map(
										( [ month, groupedSlots ], index ) => (
											<div key={ month } className="">
												<h2 className="text-2xl  mb-2">
													{ month }
												</h2>

												<div className="flex flex-col gap-2">
													{ groupedSlots.map(
														( slot, i ) => {
															const bgColorFrom =
																hexToRgba(
																	slot
																		.extendedProps
																		.backgroundColor,
																	0.1
																) || '#fef3c7';

															const bgColorTo =
																hexToRgba(
																	slot
																		.extendedProps
																		.backgroundColor,
																	0.2
																) || '#fef3c7';

															const status =
																slot
																	.extendedProps
																	.status
																	.label;

															const statusClasses =
																{
																	Open: 'bg-green-700 text-white text-xs py-0.5 px-2 rounded-full',
																	Pending:
																		'bg-yellow-300 text-black text-xs py-0.5 px-2 rounded-full',
																	Booked: 'bg-red-600 text-white text-xs py-0.5 px-2 rounded-full',
																};

															return (
																<div
																	key={ i }
																	className="p-2 sm:p-3 border border-neutral-200 rounded-lg flex flex-row items-center gap-2 sm:gap-4 relative"
																>
																	<div className="absolute top-2 right-2 text-xs py-0.5 px-2 rounded-full">
																		<div
																			className={
																				statusClasses[
																					status
																				] ||
																				'bg-gray-300 text-black'
																			}
																		>
																			{
																				status
																			}
																		</div>
																	</div>
																	<div
																		style={ {
																			backgroundImage: `linear-gradient(135deg, ${ bgColorFrom } 0%, ${ bgColorTo } 100%)`,
																		} }
																		className="aspect-square w-[60px] sm:w-[70px] h-[60px] sm:h-[70px] rounded-md"
																	></div>
																	wd
																	<div>
																		<p className="font-medium text-base sm:text-lg">
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
															);
														}
													) }
												</div>
											</div>
										)
									) }
								</div>
							) }
						</div>
						<div className="flex flex-col gap-4 ">
							<div className="p-4 rounded-lg bg-white flex flex-col gap-2 w-full">
								<h3 className="text-2xl">Legend</h3>
								<div className="flex flex-col gap-2">
									{ ( selected?.calendar_legend || [] ).map(
										( item, index ) => (
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
										)
									) }
								</div>
							</div>

							{ selected?.additional_notes && (
								<div className="p-4 rounded-lg bg-white flex flex-col gap-2 w-full">
									<h3 className="text-2xl">Details</h3>
									<p>{ selected?.additional_notes }</p>
								</div>
							) }
						</div>
					</div>
				</div>
			</div>
		</section>
	);
};

export default FullCalendarComp;
