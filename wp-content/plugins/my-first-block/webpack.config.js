const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

const cleanedDefaultPlugins = defaultConfig.plugins.filter( ( plugin ) => {
	if ( [ 'RtlCssPlugin' ].includes( plugin.constructor.name ) ) {
		return false;
	}
	return true;
} );

module.exports = {
	...defaultConfig,
	plugins: [ ...cleanedDefaultPlugins ],
	entry: {
		// Block entry points (JS only - no CSS imports)
		'hero-section/index': path.resolve(
			process.cwd(),
			'src/hero-section',
			'index.js'
		),
		'features-section/index': path.resolve(
			process.cwd(),
			'src/features-section',
			'index.js'
		),
		'showcase-section/index': path.resolve(
			process.cwd(),
			'src/showcase-section',
			'index.js'
		),
		'form-block/index': path.resolve(
			process.cwd(),
			'src/form-block',
			'index.js'
		),
		'accordion-section/index': path.resolve(
			process.cwd(),
			'src/accordion-section',
			'index.js'
		),
		'reviews-section/index': path.resolve(
			process.cwd(),
			'src/reviews-section',
			'index.js'
		),
		'scroll-image-section/index': path.resolve(
			process.cwd(),
			'src/scroll-image-section',
			'index.js'
		),
		'cta-section/index': path.resolve(
			process.cwd(),
			'src/cta-section',
			'index.js'
		),
		'two-col-section/index': path.resolve(
			process.cwd(),
			'src/two-col-section',
			'index.js'
		),
		'pricing-table/index': path.resolve(
			process.cwd(),
			'src/pricing-table',
			'index.js'
		),
		'page-header-section/index': path.resolve(
			process.cwd(),
			'src/page-header-section',
			'index.js'
		),
		'gallery-section/index': path.resolve(
			process.cwd(),
			'src/gallery-section',
			'index.js'
		),
		// Component entry points (JS only - no CSS imports), two entry points for client component (frontend.js)
		'calendar-section/index': path.resolve(
			process.cwd(),
			'src/calendar-section',
			'index.js'
		),
		'calendar-section/frontend': path.resolve(
			process.cwd(),
			'src/calendar-section',
			'frontend.js'
		),
	},
	optimization: {
		...defaultConfig.optimization,
		splitChunks: {
			cacheGroups: {
				styles: {
					name: 'style',
					type: 'css/mini-extract',
					chunks: 'all',
					enforce: true,
				},
			},
		},
	},
};
