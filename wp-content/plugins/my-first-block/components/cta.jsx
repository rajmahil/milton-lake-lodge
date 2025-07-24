import React from 'react';

const CtaSection = ( {
	heading = 'Main Heading',
	subheading = 'Subheading text goes here.',
	buttonText = 'Learn More',
	buttonUrl = '#',
	sectionId = '',
	backgroundImage = {},
} ) => {
	const bgImageUrl =
		backgroundImage?.url || '/wp-content/uploads/effects/green-topo.png'; // fallback

	return (
		<section
			id={ sectionId }
			className="plugin-custom-block section-padding w-full"
		>
			<div
				className="relative max-w-container bg-brand-green bg-blend-hard-light !items-center text-white w-full rounded-2xl z-[0] bg-repeat bg-size-[450px]"
				style={ { backgroundImage: `url('${ bgImageUrl }')` } }
			>
				<div className="flex flex-col gap-6 w-full relative z-[1] !items-center !justify-center section-padding ">
					<div>
						<h2 className="heading-two !text-center text-white">
							{ heading }
						</h2>
						<p className="text-lg !text-center text-white">
							{ subheading }
						</p>
					</div>
					<a href={ buttonUrl }>
						<button className="btn btn-outline btn-xl">
							{ buttonText }
						</button>
					</a>
				</div>
			</div>
		</section>
	);
};

export default CtaSection;
