import { LazyLoadImage } from 'react-lazy-load-image-component';

const ScrollImage = ( {
	topHeading,
	heading,
	buttonText,
	buttonUrl,
	image,
} ) => {
	return (
		<section className="plugin-custom-block  section-padding static-background">
			<div className="relative w-full overflow-hidden rounded-lg max-w-container mx-auto h-screen flex items-start justify-start not-prose p-10">
				<div
					className="absolute inset-0 z-0 transform origin-center transition-transform duration-300 ease-out"
					style={ { willChange: 'transform' } }
				>
					<LazyLoadImage
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
						className="object-cover w-full h-full"
						loading="eager"
					/>
					<div className="absolute inset-0 bg-black/30"></div>
				</div>

				<div className="relative z-10 text-white flex flex-col gap-4">
					<div className="flex flex-col items-center sm:items-start gap-3">
						{ topHeading && (
							<p className="decorative-text !text-brand-yellow-dark text-3xl lg:!text-4xl !my-0 text-center sm:text-left">
								{ topHeading }
							</p>
						) }
						{ heading && (
							<h2 className="!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600] max-w-none md:max-w-[60%] text-center sm:text-left">
								{ heading }
							</h2>
						) }
					</div>
					{ buttonText && (
						<a
							href={ buttonUrl || '#' }
							className="inline-block mt-4 mx-auto sm:mx-0"
						>
							<button className="btn btn-xl btn-outline ">
								{ buttonText }
							</button>
						</a>
					) }
				</div>
			</div>
		</section>
	);
};

export default ScrollImage;
