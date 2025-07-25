import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	MediaUpload,
	InspectorControls,
	InspectorAdvancedControls,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	Button,
	TextareaControl,
	ToggleControl,
} from '@wordpress/components';
import TwoCol from '../../components/two-col';

export default function Edit( { attributes, setAttributes } ) {
	const { sectionId, heading, buttonText, buttonUrl, text } = attributes;

	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	const renderImageUploader = ( key, label ) => {
		const currentImage = attributes[ key ];

		return (
			<>
				<MediaUpload
					onSelect={ ( media ) =>
						setAttributes( {
							[ key ]: {
								id: media.id,
								url: media.url,
								alt: media.alt,
								width: media.width,
								height: media.height,
								sizes: media.sizes,
							},
						} )
					}
					allowedTypes={ [ 'image' ] }
					render={ ( { open } ) => (
						<Button
							onClick={ open }
							isPrimary
							style={ { marginBottom: '10px' } }
						>
							{ currentImage?.url
								? __(
										label.replace( 'Upload', 'Replace' ),
										'your-text-domain'
								  )
								: __( label, 'your-text-domain' ) }
						</Button>
					) }
				/>

				{ currentImage?.url && (
					<div style={ { marginTop: '10px' } }>
						<img
							src={ currentImage.url }
							alt={ currentImage.alt || '' }
							width={ currentImage.width }
							height={ currentImage.height }
							style={ { maxWidth: '100%' } }
						/>
						<Button
							onClick={ () => setAttributes( { [ key ]: {} } ) }
							isDestructive
							isSmall
							style={ { marginTop: '5px' } }
						>
							{ __( 'Remove Image', 'your-text-domain' ) }
						</Button>
					</div>
				) }
			</>
		);
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
				<PanelBody title={ __( 'Content', 'your-text-domain' ) }>
					<TextControl
						label={ __( 'Heading', 'your-text-domain' ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
					<TextareaControl
						label={ __( 'Text', 'your-text-domain' ) }
						value={ text }
						onChange={ ( value ) =>
							setAttributes( { text: value } )
						}
					/>
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

				<PanelBody title={ __( 'Image Settings', 'your-text-domain' ) }>
					{ renderImageUploader( 'image', 'Upload Image' ) }
				</PanelBody>
				<PanelBody title="Inverted" initialOpen={ true }>
					<ToggleControl
						label="Invert Orientation"
						checked={ attributes.inverted }
						onChange={ ( val ) =>
							setAttributes( { inverted: val } )
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

			<TwoCol { ...attributes } />
		</div>
	);
}
