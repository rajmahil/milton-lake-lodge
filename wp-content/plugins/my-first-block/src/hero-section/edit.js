import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	MediaUpload,
	InspectorControls,
	MediaUploadCheck,
	InspectorAdvancedControls,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	Button,
	TextareaControl,
	RangeControl,
} from '@wordpress/components';
import Hero from '../../components/hero';

export default function Edit( { attributes, setAttributes } ) {
	const {
		heading,
		subheading,
		buttonText,
		buttonUrl,
		button2Text,
		button2Url,
		image,
		tripAdvisorStars,
		tripAdvisorReviews,
		sectionId,
	} = attributes;
	// This is crucial - it provides the block wrapper with proper WordPress functionality
	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	const addReview = () => {
		const newReviews = [ ...tripAdvisorReviews, { image: '', text: '' } ];
		setAttributes( { tripAdvisorReviews: newReviews } );
	};

	const updateReview = ( index, field, value ) => {
		const newReviews = tripAdvisorReviews.map( ( review, i ) => {
			if ( i === index ) {
				return { ...review, [ field ]: value };
			}
			return review;
		} );
		setAttributes( { tripAdvisorReviews: newReviews } );
	};

	const removeReview = ( index ) => {
		const newReviews = tripAdvisorReviews.filter( ( _, i ) => i !== index );
		setAttributes( { tripAdvisorReviews: newReviews } );
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
					<TextControl
						label={ __( 'Button 2 Text', 'your-text-domain' ) }
						value={ button2Text }
						onChange={ ( value ) =>
							setAttributes( { button2Text: value } )
						}
					/>
					<TextControl
						label={ __( 'Button 2 URL', 'your-text-domain' ) }
						value={ button2Url }
						onChange={ ( value ) =>
							setAttributes( { button2Url: value } )
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

				<PanelBody title={ __( 'TripAdvisor', 'your-text-domain' ) }>
					<RangeControl
						label="TripAdvisor Stars"
						value={ tripAdvisorStars }
						onChange={ ( value ) =>
							setAttributes( { tripAdvisorStars: value } )
						}
						min={ 1 }
						max={ 5 }
						step={ 0.1 }
					/>
					<h4>{ __( 'Reviews', 'your-text-domain' ) }</h4>
					{ tripAdvisorReviews.map( ( review, index ) => (
						<div
							key={ index }
							style={ {
								border: '1px solid #ddd',
								padding: '10px',
								marginBottom: '10px',
								borderRadius: '4px',
							} }
						>
							<MediaUploadCheck>
								<MediaUpload
									onSelect={ ( media ) =>
										updateReview(
											index,
											'image',
											media.url
										)
									}
									allowedTypes={ [ 'image' ] }
									render={ ( { open } ) => (
										<Button
											onClick={ open }
											variant="secondary"
										>
											{ review.image
												? __(
														'Change Image',
														'your-text-domain'
												  )
												: __(
														'Select Image',
														'your-text-domain'
												  ) }
										</Button>
									) }
								/>
							</MediaUploadCheck>
							{ review.image && (
								<img
									src={ review.image }
									alt=""
									style={ {
										maxWidth: '100%',
										marginTop: '10px',
									} }
								/>
							) }

							<TextareaControl
								label="Review Text"
								value={ review.text }
								onChange={ ( value ) =>
									updateReview( index, 'text', value )
								}
							/>

							<Button
								variant="link"
								isDestructive
								onClick={ () => removeReview( index ) }
							>
								{ __( 'Remove Review', 'your-text-domain' ) }
							</Button>
						</div>
					) ) }

					<Button variant="primary" onClick={ addReview }>
						{ __( 'Add Review', 'your-text-domain' ) }
					</Button>
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
			<Hero { ...attributes } />
		</div>
	);
}
