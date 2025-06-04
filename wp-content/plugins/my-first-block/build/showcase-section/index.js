( () => {
	'use strict';
	var e,
		t = {
			325: () => {
				const e = window.wp.blocks,
					t = window.wp.i18n,
					l = window.wp.blockEditor,
					a = window.wp.components,
					i = window.ReactJSXRuntime,
					n = ( {
						topHeading: e,
						heading: t,
						buttonText: l,
						buttonUrl: a,
						images: n = [],
					} ) =>
						( 0, i.jsxs )( 'section', {
							className:
								'flex flex-col gap-24 overflow-hidden relative not-prose section-padding w-full  static-background ',
							children: [
								( 0, i.jsxs )( 'div', {
									className:
										'relative z-[2] max-w-container flex flex-row flex-wrap gap-5 items-end justify-between',
									children: [
										( 0, i.jsxs )( 'div', {
											className:
												'flex flex-col gap-4 lg:max-w-[60%] w-full',
											children: [
												e &&
													( 0, i.jsx )( 'p', {
														className:
															'decorative-text text-brand-yellow !text-2xl',
														children: e,
													} ),
												t &&
													( 0, i.jsx )( 'h2', {
														className:
															'!my-0 !text-3xl md:!text-4xl lg:!text-5xl !font-[600]  text-left ',
														children: t,
													} ),
											],
										} ),
										( 0, i.jsx )( 'div', {
											children: ( 0, i.jsx )( 'a', {
												href: a || '#',
												children: ( 0, i.jsx )(
													'button',
													{
														className:
															'btn btn-outline btn-xl',
														children:
															l || 'Learn More',
													}
												),
											} ),
										} ),
									],
								} ),
								( 0, i.jsx )( 'div', {
									className:
										'group relative w-full h-full select-none ',
									children: ( 0, i.jsx )( 'div', {
										className:
											'flex w-max animate-slide gap-10 whitespace-nowrap',
										children: n
											.concat( n )
											.map( ( e, t ) =>
												( 0, i.jsx )(
													'div',
													{
														className: `px-1 py-1.5 bg-white rounded-lg overflow-hidden \n\t\t\t\t\t\t\t${
															t % 3 == 0
																? 'rotate-[-3deg]'
																: t % 3 == 1
																? 'rotate-[2deg]'
																: 'rotate-[-1deg]'
														} \n\t\t\t\t\t\t  `,
														children: ( 0, i.jsx )(
															'img',
															{
																src:
																	e?.sizes
																		?.large
																		?.url ||
																	e?.url,
																srcSet: ` \n          ${
																	e?.sizes
																		?.thumbnail
																		?.url ||
																	''
																} 150w, \n          ${
																	e?.sizes
																		?.medium
																		?.url ||
																	''
																} 300w, \n          ${
																	e?.sizes
																		?.large
																		?.url ||
																	''
																} 1024w, \n          ${
																	e?.sizes
																		?.full
																		?.url ||
																	e?.url ||
																	''
																} ${
																	e?.width ||
																	''
																}w \n        `,
																sizes: '(max-width: 768px) 100vw, 1024px',
																alt:
																	e?.alt ||
																	'',
																width: e?.width,
																height: e?.height,
																className:
																	'flex-shrink-0 h-full aspect-[3/4] w-full max-w-[200px] md:max-w-[300px] object-cover',
																loading:
																	'eager',
															}
														),
													},
													`showcase-${
														e.id || t
													}-${ Math.floor(
														t / n.length
													) }`
												)
											),
									} ),
								} ),
							],
						} ),
					r = JSON.parse(
						'{"UU":"brad-boilerplate/showcase-section"}'
					);
				( 0, e.registerBlockType )( r.UU, {
					edit: function ( { attributes: e, setAttributes: r } ) {
						const {
								topHeading: o,
								heading: s,
								buttonText: d,
								buttonUrl: x,
								images: c,
							} = e,
							u = ( 0, l.useBlockProps )( {
								className: 'my-unique-plugin-wrapper-class',
								style: { maxWidth: '100%', margin: '0 auto' },
							} );
						return ( 0, i.jsxs )( 'div', {
							...u,
							children: [
								( 0, i.jsxs )( l.InspectorControls, {
									children: [
										( 0, i.jsxs )( a.PanelBody, {
											title: ( 0, t.__ )(
												'Content',
												'your-text-domain'
											),
											children: [
												( 0, i.jsx )( a.TextControl, {
													label: ( 0, t.__ )(
														'Top Heading',
														'your-text-domain'
													),
													value: o,
													onChange: ( e ) =>
														r( { topHeading: e } ),
												} ),
												( 0, i.jsx )( a.TextControl, {
													label: ( 0, t.__ )(
														'Heading',
														'your-text-domain'
													),
													value: s,
													onChange: ( e ) =>
														r( { heading: e } ),
												} ),
											],
										} ),
										( 0, i.jsxs )( a.PanelBody, {
											title: ( 0, t.__ )(
												'Button Settings',
												'your-text-domain'
											),
											children: [
												( 0, i.jsx )( a.TextControl, {
													label: ( 0, t.__ )(
														'Button Text',
														'your-text-domain'
													),
													value: d,
													onChange: ( e ) =>
														r( { buttonText: e } ),
												} ),
												( 0, i.jsx )( a.TextControl, {
													label: ( 0, t.__ )(
														'Button URL',
														'your-text-domain'
													),
													value: x,
													onChange: ( e ) =>
														r( { buttonUrl: e } ),
												} ),
											],
										} ),
										( 0, i.jsxs )( a.PanelBody, {
											title: ( 0, t.__ )(
												'Image Gallery',
												'your-text-domain'
											),
											children: [
												( 0, i.jsx )( l.MediaUpload, {
													onSelect: ( e ) => {
														const t = Array.isArray(
															e
														)
															? e
															: [ e ];
														r( {
															images: t.map(
																( e ) => ( {
																	id: e.id,
																	url: e.url,
																	alt: e.alt,
																	width: e.width,
																	height: e.height,
																	sizes: e.sizes,
																} )
															),
														} );
													},
													allowedTypes: [ 'image' ],
													multiple: ! 0,
													gallery: ! 0,
													value: c?.map(
														( e ) => e.id
													),
													render: ( { open: e } ) =>
														( 0, i.jsx )(
															a.Button,
															{
																onClick: e,
																isPrimary: ! 0,
																children:
																	c?.length
																		? ( 0,
																		  t.__ )(
																				'Edit Gallery',
																				'your-text-domain'
																		  )
																		: ( 0,
																		  t.__ )(
																				'Add Images',
																				'your-text-domain'
																		  ),
															}
														),
												} ),
												c?.length > 0 &&
													( 0, i.jsx )( 'div', {
														style: {
															marginTop: '10px',
															display: 'flex',
															gap: '10px',
															flexWrap: 'wrap',
														},
														children: c.map(
															( e ) =>
																( 0, i.jsx )(
																	'img',
																	{
																		src: e.url,
																		alt:
																			e.alt ||
																			'',
																		width: 80,
																		style: {
																			height: 'auto',
																			borderRadius:
																				'4px',
																		},
																	},
																	e.id
																)
														),
													} ),
											],
										} ),
									],
								} ),
								( 0, i.jsx )( n, { ...e } ),
							],
						} );
					},
				} );
			},
		},
		l = {};
	function a( e ) {
		var i = l[ e ];
		if ( void 0 !== i ) return i.exports;
		var n = ( l[ e ] = { exports: {} } );
		return t[ e ]( n, n.exports, a ), n.exports;
	}
	( a.m = t ),
		( e = [] ),
		( a.O = ( t, l, i, n ) => {
			if ( ! l ) {
				var r = 1 / 0;
				for ( x = 0; x < e.length; x++ ) {
					for (
						var [ l, i, n ] = e[ x ], o = ! 0, s = 0;
						s < l.length;
						s++
					)
						( ! 1 & n || r >= n ) &&
						Object.keys( a.O ).every( ( e ) => a.O[ e ]( l[ s ] ) )
							? l.splice( s--, 1 )
							: ( ( o = ! 1 ), n < r && ( r = n ) );
					if ( o ) {
						e.splice( x--, 1 );
						var d = i();
						void 0 !== d && ( t = d );
					}
				}
				return t;
			}
			n = n || 0;
			for ( var x = e.length; x > 0 && e[ x - 1 ][ 2 ] > n; x-- )
				e[ x ] = e[ x - 1 ];
			e[ x ] = [ l, i, n ];
		} ),
		( a.o = ( e, t ) => Object.prototype.hasOwnProperty.call( e, t ) ),
		( () => {
			var e = { 151: 0, 904: 0 };
			a.O.j = ( t ) => 0 === e[ t ];
			var t = ( t, l ) => {
					var i,
						n,
						[ r, o, s ] = l,
						d = 0;
					if ( r.some( ( t ) => 0 !== e[ t ] ) ) {
						for ( i in o ) a.o( o, i ) && ( a.m[ i ] = o[ i ] );
						if ( s ) var x = s( a );
					}
					for ( t && t( l ); d < r.length; d++ )
						( n = r[ d ] ),
							a.o( e, n ) && e[ n ] && e[ n ][ 0 ](),
							( e[ n ] = 0 );
					return a.O( x );
				},
				l = ( globalThis.webpackChunkmy_first_block =
					globalThis.webpackChunkmy_first_block || [] );
			l.forEach( t.bind( null, 0 ) ),
				( l.push = t.bind( null, l.push.bind( l ) ) );
		} )();
	var i = a.O( void 0, [ 904 ], () => a( 325 ) );
	i = a.O( i );
} )();
