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
	TextareaControl,
	Button,
} from '@wordpress/components';
import Cta from '../../components/cta';

export default function Edit( { attributes, setAttributes } ) {
	const {
		heading,
		subheading,
		buttonText,
		buttonUrl,
		backgroundImage,
		sectionId,
	} = attributes;

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

				<PanelBody
					title={ __( 'Background Settings', 'your-text-domain' ) }
				>
					<MediaUpload
						onSelect={ ( media ) =>
							setAttributes( {
								backgroundImage: {
									id: media.id,
									url: media.url,
									alt: media.alt,
								},
							} )
						}
						allowedTypes={ [ 'image' ] }
						value={ backgroundImage?.id }
						render={ ( { open } ) => (
							<>
								<Button onClick={ open } isSecondary>
									{ backgroundImage?.url
										? __(
												'Replace Background',
												'your-text-domain'
										  )
										: __(
												'Add Custom Background',
												'your-text-domain'
										  ) }
								</Button>
								{ backgroundImage?.url && (
									<div style={ { marginTop: '10px' } }>
										<img
											src={ backgroundImage.url }
											alt={ backgroundImage.alt || '' }
											style={ {
												maxWidth: '100%',
												height: 'auto',
											} }
										/>
										<Button
											onClick={ () =>
												setAttributes( {
													backgroundImage: {},
												} )
											}
											isDestructive
											style={ { marginTop: '5px' } }
										>
											{ __(
												'Remove Custom Background',
												'your-text-domain'
											) }
										</Button>
									</div>
								) }
							</>
						) }
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

			<Cta { ...attributes } />
		</div>
	);
}
