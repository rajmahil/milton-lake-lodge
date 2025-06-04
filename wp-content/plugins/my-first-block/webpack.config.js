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
		'form-block/frontend': path.resolve(
			process.cwd(),
			'src/form-block',
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
