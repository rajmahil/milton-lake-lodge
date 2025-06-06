/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		// Plugin PHP files (block registration, server-side rendering)
		'./*.php',
		'./includes/**/*.php',

		// React/JS components and blocks
		'./src/**/*.{js,jsx,ts,tsx}',
		'./components/**/*.{js,jsx,ts,tsx}',

		// Block JSON files (block.json contains attributes that might reference classes)
		'./src/**/block.json',

		// Any HTML templates or fragments
		'./src/**/*.html',
		'./templates/**/*.html',

		// Utility files that might contain class references
		'./utils/**/*.{js,ts}',

		// Assets that might contain class references
		'./assets/**/*.{js,css}',

		// Build output (if you're scanning compiled files)
		'./build/**/*.{js,asset.php}',
	],

	theme: {
		extend: {},
	},

	safelist: [
		'wp-block-*',
		'has-*-color',
		'has-*-background-color',
		'has-*-font-size',
		'alignwide',
		'alignfull',
		'aligncenter',
		'alignleft',
		'alignright',
		'current-menu-item',
		'current-menu-parent',
		'menu-item-has-children',
		'sub-menu',
		'sticky',
		'bypostauthor',
		'wp-caption',
		'gallery-caption',
		'screen-reader-text',
	],

	plugins: [],
};
