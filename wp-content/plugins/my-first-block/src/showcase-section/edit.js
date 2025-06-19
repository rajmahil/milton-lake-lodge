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
	RadioControl,
} from '@wordpress/components';
import Showcase from '../../components/showcase';

export default function Edit( { attributes, setAttributes } ) {
	const {
		heading,
		buttonText,
		buttonUrl,
		images,
		backgroundImage,
		imagesSpeed,
		sectionId,
	} = attributes;
	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: {
			maxWidth: '100%',
			margin: '0 auto',
		},
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
				<PanelBody title={ __( 'Image Gallery', 'your-text-domain' ) }>
					<MediaUpload
						onSelect={ ( media ) => {
							const selectedImages = Array.isArray( media )
								? media
								: [ media ];

							setAttributes( {
								images: selectedImages.map( ( img ) => ( {
									id: img.id,
									url: img.url,
									alt: img.alt,
									width: img.width,
									height: img.height,
									sizes: img.sizes,
								} ) ),
							} );
						} }
						allowedTypes={ [ 'image' ] }
						multiple
						gallery
						value={ images?.map( ( img ) => img.id ) }
						render={ ( { open } ) => (
							<Button onClick={ open } isPrimary>
								{ images?.length
									? __( 'Edit Gallery', 'your-text-domain' )
									: __( 'Add Images', 'your-text-domain' ) }
							</Button>
						) }
					/>

					{ images?.length > 0 && (
						<div
							style={ {
								marginTop: '10px',
								display: 'flex',
								gap: '10px',
								flexWrap: 'wrap',
							} }
						>
							{ images.map( ( img ) => (
								<img
									key={ img.id }
									src={ img.url }
									alt={ img.alt || '' }
									width={ 80 }
									style={ {
										height: 'auto',
										borderRadius: '4px',
									} }
								/>
							) ) }
						</div>
					) }
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
				<PanelBody title={ __( 'Slider Speed', 'your-text-domain' ) }>
					<RadioControl
						label={ __( 'Image Slide Speed', 'your-text-domain' ) }
						selected={ imagesSpeed }
						options={ [
							{ label: 'Slow', value: 'slow' },
							{ label: 'Medium', value: 'medium' },
							{ label: 'Fast', value: 'fast' },
						] }
						onChange={ ( value ) =>
							setAttributes( { imagesSpeed: value } )
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
			<Showcase { ...attributes } />
		</div>
	);
}
