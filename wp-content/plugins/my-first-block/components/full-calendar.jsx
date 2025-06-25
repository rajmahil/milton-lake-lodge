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

const FullCalendarComp = ( props ) => {
	const { heading, subheading, selectedPostId, sectionId } = props;

	console.log( 'FullCalendarComp Props:', props );

	const [ selected, setSelected ] = useState( null );
	const [ slots, setSlots ] = useState( [] );
	const [ defMonth, setDefMonth ] = useState( null );

	useEffect( () => {
		if ( ! myCalendarData?.posts?.length ) return;

		const selectedPost = myCalendarData?.posts?.find(
			( post ) => post.id === selectedPostId
		);

		if ( selectedPost ) {
			console.log( 'Selected Post:', selectedPost );

			const tripSlots = selectedPost?.trips.map( ( slot ) => {
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
			} );

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
				<div className="grid grid-cols-1 xl:grid-cols-4 gap-6">
					<div className="p-6 xl:col-span-3 bg-white rounded-lg my-calendar-wrapper">
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
										arg.event.extendedProps.textColor ||
										'#1f2937';
									const status =
										arg.event.extendedProps.status.label;

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
												end.setDate( end.getDate() - 1 )
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

									console.log( start, end, 'Event Dates' );

									return (
										<div
											style={ {
												backgroundImage: `linear-gradient(135deg, ${ bgColorFrom } 0%, ${ bgColorTo } 100%)`,
												color: textColor,
											} }
											className="p-2 rounded-md h-22 font-medium  text-wrap flex flex-col gap-2 items-start justify-between"
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
		</section>
	);
};

export default FullCalendarComp;
