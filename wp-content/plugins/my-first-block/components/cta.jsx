const Cta = ( {
	topHeading,
	heading,
	buttonText,
	buttonUrl,
	image,
	image2,
	backgroundImage,
} ) => {
	// Default background image fallback

	return (
		<section className="plugin-custom-block section-padding w-full">
			<div
				className="relative max-w-container bg-brand-green bg-blend-hard-light grid grid-cols-5 !items-center gap-5 text-white w-full rounded-2xl z-[0] bg-repeat"
				style={ {
					backgroundImage: backgroundImage?.url
						? `url('${ backgroundImage.url }')`
						: "url('http://milton-lodge.local/wp-content/uploads/effects/green-topo.png')",
				} }
			>
				<div className="flex flex-col gap-4 w-full relative z-[1] items-start justify-center section-padding col-span-5 900:col-span-2">
					<h2 className="heading-two text-center 900:text-left text-white">
						{ heading }
					</h2>
					<a href={ buttonUrl || '#' } className="mx-auto 900:mx-0">
						<button className="btn btn-outline btn-xl">
							{ buttonText }
						</button>
					</a>
				</div>

				{ ( image?.url || image2?.url ) && (
					<div className="flex justify-center items-center relative col-span-5 900:col-span-3">
						{ image?.url && (
							<div className="max-w-[400px] w-full rotate-5 relative left-10">
								<img
									src={ image.url }
									alt={ image.alt || '' }
									className="aspect-[3/4] w-full object-cover"
									loading="lazy"
									decoding="async"
								/>
							</div>
						) }
						{ image2?.url && (
							<div className="max-w-[400px] w-full rotate-[-10deg] relative right-10">
								<img
									src={ image2.url }
									alt={ image2.alt || '' }
									className="aspect-[3/4] w-full object-cover"
									loading="lazy"
									decoding="async"
								/>
							</div>
						) }
					</div>
				) }
			</div>
		</section>
	);
};

export default Cta;
