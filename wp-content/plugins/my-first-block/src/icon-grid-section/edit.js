import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	InspectorAdvancedControls,
	MediaUpload,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
} from '@wordpress/components';
import IconGrid from '../../components/icon-grid';

export default function Edit( { attributes, setAttributes } ) {
	const {
		heading,
		buttonText,
		buttonUrl,
		items = [],
		sectionId,
	} = attributes;

	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	const updateItem = ( index, field, value ) => {
		const updated = [ ...items ];
		updated[ index ][ field ] = value;
		setAttributes( { items: updated } );
	};

	const removeItem = ( index ) => {
		const updated = items.filter( ( _, i ) => i !== index );
		setAttributes( { items: updated } );
	};

	const slugPattern = /^[a-z0-9-]*$/;

	const onChangeSectionId = ( value ) => {
		const sanitized = value.toLowerCase().replace( /[^a-z0-9-]/g, '' );

		if ( slugPattern.test( sanitized ) ) {
			setAttributes( { sectionId: sanitized } );
		}
	};

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Content', 'your-text-domain' ) }
					initialOpen={ true }
				>
					<TextControl
						label={ __( 'Heading', 'your-text-domain' ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Items', 'your-text-domain' ) }
					initialOpen={ true }
				>
					{ items.map( ( item, index ) => (
						<div
							key={ index }
							style={ {
								marginBottom: '16px',
								borderBottom: '1px solid #ddd',
								paddingBottom: '12px',
							} }
						>
							<TextControl
								label={ `Item ${ index + 1 } Title` }
								value={ item.title || '' }
								onChange={ ( value ) =>
									updateItem( index, 'title', value )
								}
							/>
							<MediaUpload
								onSelect={ ( media ) => {
									updateItem( index, 'image', {
										id: media.id,
										url: media.url,
										alt: media.alt,
										width: media.width,
										height: media.height,
									} );
								} }
								allowedTypes={ [ 'image' ] }
								render={ ( { open } ) => (
									<div>
										{ item?.image?.url && (
											<div
												style={ { marginTop: '10px' } }
											>
												<img
													src={ item.image.url }
													alt={ item.image.alt || '' }
													style={ {
														maxWidth: '100px',
														height: 'auto',
														display: 'block',
														marginBottom: '8px',
													} }
												/>
											</div>
										) }

										<Button
											onClick={ open }
											variant="secondary"
										>
											{ item?.image?.url
												? 'Replace Image'
												: 'Select Image' }
										</Button>
									</div>
								) }
							/>

							<Button
								variant="secondary"
								isDestructive
								onClick={ () => removeItem( index ) }
								style={ { marginTop: '8px' } }
							>
								Remove Item
							</Button>
						</div>
					) ) }

					<Button
						variant="primary"
						onClick={ () =>
							setAttributes( {
								items: [ ...items, { title: '', image: {} } ],
							} )
						}
					>
						Add Item
					</Button>
				</PanelBody>

				<PanelBody
					title={ __( 'Button Settings', 'your-text-domain' ) }
				>
					<TextControl
						label={ __( 'Button Text', 'your-text-domain' ) }
						value={ buttonText }
						onChange={ ( value ) =>
							setAttributes( { buttonText: value } )
						}
					/>
					<TextControl
						label={ __( 'Button URL', 'your-text-domain' ) }
						value={ buttonUrl }
						onChange={ ( value ) =>
							setAttributes( { buttonUrl: value } )
						}
					/>
				</PanelBody>

				<InspectorAdvancedControls>
					<TextControl
						label="Section ID (slug, hyphens only)"
						value={ sectionId }
						onChange={ onChangeSectionId }
						help="Use lowercase letters, numbers, and hyphens only."
					/>
				</InspectorAdvancedControls>
			</InspectorControls>

			<IconGrid { ...attributes } />
		</div>
	);
}
