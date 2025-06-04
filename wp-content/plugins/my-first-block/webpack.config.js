const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const cleanedDefaultPlugins = defaultConfig.plugins.filter( ( plugin ) => {
	if ( [ 'RtlCssPlugin' ].includes( plugin.constructor.name ) ) {
		return false;
	}
	return true;
} );

module.exports = {
	...defaultConfig,
	plugins: [ ...cleanedDefaultPlugins ],
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
