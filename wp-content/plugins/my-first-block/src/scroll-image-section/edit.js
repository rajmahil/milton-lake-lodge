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
} from '@wordpress/components';
import ScrollImage from '../../components/scroll-image';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, subheading, buttonText, buttonUrl, image, sectionId } =
		attributes;
	// This is crucial - it provides the block wrapper with proper WordPress functionality
	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	const slugPattern = /^[a-z0-9-]*$/;

	const onChangeSectionId = ( value ) => {
		const sanitized = value.toLowerCase().replace( /[^a-z0-9-]/g, '' );

		if ( slugPattern.test( sanitized ) ) {
			setAttributes( { sectionId: sanitized } );
		}
	};

	return (
		<div { ...blockProps }>
			{ /* Sidebar settings */ }
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
						label={ __( 'Subheading', 'your-text-domain' ) }
						value={ subheading }
						onChange={ ( value ) =>
							setAttributes( { subheading: value } )
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
					<MediaUpload
						onSelect={ ( media ) =>
							setAttributes( {
								image: {
									id: media.id,
									url: media.url,
									alt: media.alt,
									width: media.width,
									height: media.height,
									sizes: media.sizes,
									srcSet: media.sizes?.full?.source_url
										? undefined
										: undefined, // example
								},
							} )
						}
						allowedTypes={ [ 'image' ] }
						value={ image?.id }
						render={ ( { open } ) => (
							<Button onClick={ open } isPrimary>
								{ image?.url
									? __( 'Replace Image', 'your-text-domain' )
									: __( 'Upload Image', 'your-text-domain' ) }
							</Button>
						) }
					/>

					{ image?.url && (
						<div style={ { marginTop: '10px' } }>
							<img
								src={ image.url }
								alt={ image.alt || '' }
								width={ image.width }
								height={ image.height }
								style={ { maxWidth: '100%' } }
							/>
						</div>
					) }
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
			<ScrollImage { ...attributes } />
		</div>
	);
}
