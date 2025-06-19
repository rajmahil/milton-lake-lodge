import { useState } from 'react';

const Accordion = ( { heading, subheading, items = [], sectionId } ) => {
	const [ activeAccordion, setActiveAccordion ] = useState( null );

	const toggleAccordion = ( id ) => {
		setActiveAccordion( ( prev ) => ( prev === id ? null : id ) );
	};

	return (
		<section
			id={ sectionId || undefined }
			className="plugin-custom-block not-prose section-padding w-full static-background"
		>
			<div className="max-w-container mx-auto grid lg:grid-cols-2 gap-8 items-start">
				<div className="flex flex-col gap-2">
					{ heading && (
						<h2 className="!my-0 heading-two text-left">
							{ heading }
						</h2>
					) }

					{ subheading && (
						<p className="!my-0 text-lg text-left max-w-2xl">
							{ subheading }
						</p>
					) }
				</div>

				<div className="relative w-full overflow-hidden flex flex-col gap-5">
					{ items.map( ( item, index ) => {
						const id = `accordion-item-${ index }`;
						const isActive = activeAccordion === id;

						return (
							<div
								key={ id }
								className={ `group bg-white rounded-md border border-brand-grey p-6 transition-all duration-200 ease-in-out !cursor-pointer w-full` }
								onClick={ () => toggleAccordion( id ) }
							>
								<button
									type="button"
									className="!w-full text-left text-xl font-medium select-none  !cursor-pointer"
								>
									<div className="!flex !flex-row !w-full !items-center !justify-between !cursor-pointer">
										<h3 class="!my-0 text-lg !cursor-pointer font-normal !uppercase">
											{ item.title }
										</h3>
										<svg
											className={ `w-5 h-5 transition-transform duration-300 ease-out flex-shrink-0 ${
												isActive ? 'rotate-45' : ''
											}` }
											viewBox="0 0 24 24"
											fill="none"
											stroke="currentColor"
											strokeWidth="2"
											strokeLinecap="round"
											strokeLinejoin="round"
										>
											<line
												x1="12"
												y1="5"
												x2="12"
												y2="19"
											/>
											<line
												x1="5"
												y1="12"
												x2="19"
												y2="12"
											/>
										</svg>
									</div>
								</button>

								<div
									className={ `overflow-hidden transition-all duration-300 ease-in-out ${
										isActive
											? 'max-h-96 opacity-100'
											: 'max-h-0 opacity-0'
									}` }
								>
									<div className="pt-4 text-base text-neutral-500 cursor-default">
										{ item.text }
									</div>
								</div>
							</div>
						);
					} ) }
				</div>
			</div>
		</section>
	);
};

export default Accordion;
