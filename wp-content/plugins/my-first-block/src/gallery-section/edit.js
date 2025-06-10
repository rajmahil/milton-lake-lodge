import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import GallerySection from '../../components/gallery-section';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, images = [] } = attributes;

	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: {
			maxWidth: '100%',
			margin: '0 auto',
		},
	} );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				{ /* Heading Control */ }
				<PanelBody title={ __( 'Content', 'your-text-domain' ) }>
					<TextControl
						label={ __( 'Heading', 'your-text-domain' ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
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
			</InspectorControls>
			<GallerySection { ...attributes } />
		</div>
	);
}
