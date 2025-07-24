export default function PageHeader( {
	heading = '',
	breadcrumbs = [],
	navigation = [],
	image = {},
	sectionId,
} ) {
	console.log( 'Navigation:', navigation );
	return (
		<section id={ sectionId || undefined } class="!plugin-custom-block">
			<section
				className="relative  h-[350px] md:h-[500px] lg:h-[600px] flex items-center not-prose justify-center bg-cover bg-center"
				style={ {
					backgroundImage: `url(${ image.url })`,
				} }
			>
				<div className="absolute inset-0 bg-brand-green-dark/60" />

				<div className="relative z-10 text-center px-4 flex items-center justify-center w-full">
					<div className="flex flex-col items-center gap-4 max-w-screen-xl mx-auto !pt-14 lg:!pt-5">
						{ heading && (
							<h1 className="text-5xl sm:!text-6xl md:!text-7xl lg:!text-8xl !font-[600] text-center !text-white !uppercase !my-0 !py-0">
								{ heading }
							</h1>
						) }

						{ navigation?.length > 0 && (
							<nav aria-label="page-navigation">
								<ol className="flex flex-col sm:flex-row items-center justify-center w-fit bg-brand-light-grey/60 backdrop-blur-md !p-1 rounded-2xl sm:rounded-full">
									{ navigation.map( ( item, i ) => (
										<li
											key={ i }
											className="flex items-center gap-1 w-full sm:w-fit"
										>
											{ item.link ? (
												<a
													href={ item.link }
													className="!text-black w-full sm:w-fit !text-base btn btn-light btn-sm hover:!bg-brand-green-dark hover:!text-white transition-all duration-200 ease-in-out"
												>
													{ item.text }
												</a>
											) : (
												<span className="!text-base">
													{ item.text }
												</span>
											) }
										</li>
									) ) }
								</ol>
							</nav>
						) }

						{ breadcrumbs.length > 0 && (
							<nav aria-label="Breadcrumb">
								<ol className="!p-0 !my-0 !flex justify-center flex-wrap  items-center text-sm text-white gap-2">
									{ breadcrumbs.map( ( crumb, index ) => (
										<li
											key={ index }
											className="flex items-center gap-1"
										>
											{ crumb.link ? (
												<a
													href={ crumb.link }
													className="!text-white !text-base"
												>
													{ crumb.text }
												</a>
											) : (
												<span className="!text-white !text-base">
													{ crumb.text }
												</span>
											) }
											{ index <
												breadcrumbs.length - 1 && (
												<span className="!text-white !text-base">
													/
												</span>
											) }
										</li>
									) ) }
								</ol>
							</nav>
						) }
					</div>
				</div>
			</section>
		</section>
	);
}
