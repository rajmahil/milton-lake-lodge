import { useState, useRef, useEffect } from 'react';

const Accordion = ( { heading, subheading, items = [] } ) => {
	const [ activeAccordion, setActiveAccordion ] = useState( null );
	const contentRefs = useRef( {} ); // store refs to content divs

	const toggleAccordion = ( id ) => {
		setActiveAccordion( ( prev ) => ( prev === id ? null : id ) );
	};

	return (
		<section className="not-prose section-padding w-full">
			<div className="max-w-container mx-auto !grid lg:!grid-cols-2 gap-10 items-start">
				<div className="flex flex-col !gap-5">
					{ heading && (
						<h2 className="heading-two text-left text-brand-dark-blue">
							{ heading }
						</h2>
					) }
					{ subheading && (
						<p className="!text-lg text-left max-w-2xl !mt-0 !pt-0">
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
								className="!cursor-pointer group !bg-white rounded-xl px-4"
								onClick={ () => toggleAccordion( id ) }
							>
								<button
									type="button"
									className="w-full text-left flex items-center justify-between text-xl font-medium select-none group-hover:cursor-pointer"
								>
									<p className="!text-lg font-normal">
										{ item.title }
									</p>
									<svg
										className={ `w-5 h-5 transition-transform duration-250 ${
											isActive ? 'rotate-45' : ''
										}` }
										viewBox="0 0 24 24"
										fill="none"
										stroke="currentColor"
										strokeWidth="2"
										strokeLinecap="round"
										strokeLinejoin="round"
									>
										<line x1="12" y1="5" x2="12" y2="19" />
										<line x1="5" y1="12" x2="19" y2="12" />
									</svg>
								</button>

								<div
									ref={ ( el ) =>
										( contentRefs.current[ id ] = el )
									}
									style={ {
										maxHeight: isActive
											? contentRefs.current[ id ]
													?.scrollHeight + 'px'
											: '0px',
										overflow: 'hidden',
										transition: 'max-height 0.25s ease',
									} }
								>
									<p className="text-sm text-muted-foreground !pt-0 !mt-0">
										{ item.text }
									</p>
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
