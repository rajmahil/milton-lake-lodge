import { LazyLoadImage } from 'react-lazy-load-image-component';

const Hero = ( { heading, subheading, buttonText, buttonUrl, image } ) => {
	console.log( image );

	return (
		<section className="h-screen flex items-end  overflow-hidden relative not-prose section-padding w-full">
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
			<div className="absolute bottom-0 left-0 w-full h-full bg-linear-to-t  from-black/50 to-black/0"></div>
			<div className="relative z-[2] max-w-container flex flex-row items-center justify-between ">
				<div className="flex flex-col gap-4">
					<h1 className="!my-0 !text-5xl !font-semibold !text-white text-left">
						{ heading }
					</h1>
					<p className="!my-0 !text-white text-left">
						{ subheading }
					</p>
				</div>
				<a href="#">
					<button className="btn-primary">
						{ buttonText || 'Learn More' }
					</button>
				</a>
			</div>
		</section>
	);
};

export default Hero;
