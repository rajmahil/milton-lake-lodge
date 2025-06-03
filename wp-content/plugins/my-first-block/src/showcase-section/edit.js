import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	MediaUpload,
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import '../style.css';
import Showcase from '../../components/showcase';

export default function Edit( { attributes, setAttributes } ) {
	const { topHeading, heading, buttonText, buttonUrl, images } = attributes;
	// This is crucial - it provides the block wrapper with proper WordPress functionality
	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	return (
		<div { ...blockProps }>
			{ /* Sidebar settings */ }
			<InspectorControls>
				<PanelBody title={ __( 'Content', 'your-text-domain' ) }>
					<TextControl
						label={ __( 'Top Heading', 'your-text-domain' ) }
						value={ topHeading }
						onChange={ ( value ) =>
							setAttributes( { topHeading: value } )
						}
					/>
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
			</InspectorControls>
			<Showcase { ...attributes } />
		</div>
	);
}
