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
import Features from '../../components/features';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, features, sectionId } = attributes;
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
				</PanelBody>

				<div>
					{ features.map( ( feature, index ) => (
						<PanelBody
							key={ index }
							title={ `${ __( 'Feature', 'your-text-domain' ) } ${
								index + 1
							}` }
							initialOpen={ false }
						>
							<TextControl
								label={ __( 'Heading', 'your-text-domain' ) }
								value={ feature.heading }
								onChange={ ( value ) => {
									const updated = [ ...features ];
									updated[ index ].heading = value;
									setAttributes( { features: updated } );
								} }
							/>
							<TextareaControl
								label={ __( 'Text', 'your-text-domain' ) }
								value={ feature.text }
								onChange={ ( value ) => {
									const updated = [ ...features ];
									updated[ index ].text = value;
									setAttributes( { features: updated } );
								} }
							/>
							<MediaUpload
								onSelect={ ( media ) => {
									const updated = [ ...features ];
									updated[ index ].image = {
										id: media.id,
										url: media.url,
										alt: media.alt,
										width: media.width,
										height: media.height,
										sizes: media.sizes,
									};
									setAttributes( { features: updated } );
								} }
								allowedTypes={ [ 'image' ] }
								value={ feature.image?.id }
								render={ ( { open } ) => (
									<Button onClick={ open } isPrimary>
										{ feature.image?.url
											? __(
													'Replace Image',
													'your-text-domain'
											  )
											: __(
													'Upload Image',
													'your-text-domain'
											  ) }
									</Button>
								) }
							/>
							{ feature.image?.url && (
								<div style={ { marginTop: '10px' } }>
									<img
										src={ feature.image.url }
										alt={ feature.image.alt || '' }
										style={ { maxWidth: '100%' } }
									/>
								</div>
							) }
							<Button
								isDestructive
								onClick={ () => {
									const updated = features.filter(
										( _, i ) => i !== index
									);
									setAttributes( { features: updated } );
								} }
								style={ { marginTop: '10px' } }
							>
								{ __( 'Remove Feature', 'your-text-domain' ) }
							</Button>
						</PanelBody>
					) ) }
					<div class="p-5 pt-0">
						<Button
							isSecondary
							onClick={ () =>
								setAttributes( {
									features: [
										...features,
										{
											heading: '',
											text: '',
											image: {},
										},
									],
								} )
							}
							style={ { marginTop: '20px' } }
						>
							{ __( 'Add Feature', 'your-text-domain' ) }
						</Button>
					</div>
				</div>
				<InspectorAdvancedControls>
					<TextControl
						label="Section ID (slug, hyphens only)"
						value={ sectionId }
						onChange={ onChangeSectionId }
						help="Use lowercase letters, numbers, and hyphens only."
					/>
				</InspectorAdvancedControls>
			</InspectorControls>
			<Features { ...attributes } />
		</div>
	);
}
