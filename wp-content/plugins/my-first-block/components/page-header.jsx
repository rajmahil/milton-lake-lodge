export default function PageHeader( {
	heading = '',
	breadcrumbs = [],
	image = {},
	sectionId,
} ) {
	return (
		<section id={ sectionId || undefined } class="!plugin-custom-block">
			<section
				className="relative  !h-[350px] md:!h-[500px] lg:!h-[600px] !flex items-center not-prose justify-center !bg-cover !bg-center"
				style={ {
					backgroundImage: `url(${ image.url })`,
				} }
			>
				<div className="absolute inset-0 bg-black/40" />

				<div className="relative z-10 text-center px-4 flex items-center justify-center">
					<div className="flex flex-col items-center gap-3 !pt-14 lg:!pt-5">
						{ heading && (
							<h1 className="!my-0 !text-5xl md:!text-6xl lg:!text-7xl !font-[600]  text-center !uppercase !text-white">
								{ heading }
							</h1>
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
