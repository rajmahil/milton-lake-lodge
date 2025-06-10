const Hero = ( {
	topHeading,
	heading,
	subheading,
	buttonText,
	buttonUrl,
	button2Text,
	button2Url,
	image,
} ) => {
	return (
		<section className="plugin-custom-block  h-screen flex items-end  overflow-hidden relative not-prose section-padding w-full">
			<div className="absolute top-0 left-0 w-full h-full z-[0] pointer-events-none select-none">
				<img
					src={
						image?.sizes?.large?.url ||
						image?.url ||
						'/placeholder.svg'
					}
					srcSet={ `
    ${ image?.sizes?.thumbnail?.url } 150w,
    ${ image?.sizes?.medium?.url } 300w,
    ${ image?.sizes?.large?.url } 1024w,
    ${ image?.sizes?.full?.url } ${ image?.width }w
  ` }
					sizes="(max-width: 768px) 100vw, 1024px"
					alt={ image?.alt || '' }
					width={ image?.width }
					height={ image?.height }
					className="object-cover object-center w-full h-full"
					loading="eager" // key point for Hero!
				/>
			</div>
			<div className="absolute bottom-0 left-0 w-full h-full bg-linear-to-t  from-brand-green-dark to-black/0"></div>
			<div className="relative z-[2] max-w-container flex flex-row gap-5 flex-wrap items-end justify-between ">
				<div className="flex flex-col gap-4 max-w-[650px] w-full">
					<p className="decorative-text text-brand-yellow !text-4xl lg:text-5xl !my-0">
						{ topHeading }
					</p>
					<h1 className="!my-0 !text-4xl md:!text-5xl lg:!text-6xl !font-[600]  text-left  !text-white">
						{ heading }
					</h1>
					<p className="!my-0 text-xl  text-left !leading-normal">
						{ subheading }
					</p>
				</div>

				<div className="flex flex-row gap-2">
					<a href={ buttonUrl || '#' }>
						<button className="btn btn-outline btn-xl">
							{ buttonText || 'Learn More' }
						</button>
					</a>
					<a href={ button2Url || '#' }>
						<button className="btn btn-primary btn-xl">
							{ button2Text || 'Learn More' }
						</button>
					</a>
				</div>
			</div>
		</section>
	);
};

export default Hero;
