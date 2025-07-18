const Cta = ( {
	heading,
	subheading,
	buttonText,
	buttonUrl,
	image,
	image2,
	backgroundImage,
	sectionId,
} ) => {
	// Default background image fallback

	return (
		<section
			id={ sectionId || undefined }
			className="plugin-custom-block section-padding w-full static-background"
		>
			<div
				className="relative max-w-container bg-brand-green bg-blend-hard-light grid grid-cols-5 !items-center  text-white w-full rounded-2xl z-[0] bg-repeat bg-size-[450px]"
				style={ {
					backgroundImage: backgroundImage?.url
						? `url('${ backgroundImage.url }')`
						: "url('http://milton-lodge.local/wp-content/uploads/effects/green-topo.png')",
				} }
			>
				<div className="flex flex-col gap-6 w-full relative z-[1] items-start justify-center section-padding py-16 col-span-5 900:col-span-2 ">
					<div>
						<h2 className="heading-two !text-left text-white !my-0">
							{ heading }
						</h2>
						{ subheading && (
							<p class="text-lg !text-left text-white !leading-tight">
								{ subheading }
							</p>
						) }
					</div>
					<a href={ buttonUrl || '#' }>
						<button className="btn btn-outline btn-xl">
							{ buttonText }
						</button>
					</a>
				</div>

				{ ( image?.url || image2?.url ) && (
					<div className="flex justify-center items-center relative col-span-5 900:col-span-3 pb-20 900:pb-0">
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
