import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	InspectorAdvancedControls,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
	RangeControl,
} from '@wordpress/components';
import Reviews from '../../components/reviews';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, reviews = [], sectionId } = attributes;

	const blockProps = useBlockProps( {
		className: 'reviews-section-wrapper',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	const updateReview = ( index, field, value ) => {
		const updated = [ ...reviews ];
		updated[ index ][ field ] = value;
		setAttributes( { reviews: updated } );
	};

	const removeReview = ( index ) => {
		const updated = reviews.filter( ( _, i ) => i !== index );
		setAttributes( { reviews: updated } );
	};

	const addNewReview = () => {
		setAttributes( {
			reviews: [
				...reviews,
				{
					name: '',
					text: '',
					stars: 5,
					image1: { id: null, url: '' },
					image2: { id: null, url: '' },
				},
			],
		} );
	};

	const updateReviewImage = ( index, imageField, media ) => {
		const updated = [ ...reviews ];
		updated[ index ][ imageField ] = {
			id: media.id,
			url: media.url,
			alt: media.alt || '',
		};
		setAttributes( { reviews: updated } );
	};

	const removeReviewImage = ( index, imageField ) => {
		const updated = [ ...reviews ];
		updated[ index ][ imageField ] = { id: null, url: '', alt: '' };
		setAttributes( { reviews: updated } );
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
					title={ __( 'Section Settings', 'reviews-section-block' ) }
					initialOpen={ true }
				>
					<TextControl
						label={ __( 'Heading', 'reviews-section-block' ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
				</PanelBody>

				{ reviews.map( ( review, index ) => (
					<PanelBody
						key={ index }
						title={ `${ __( 'Review', 'reviews-section-block' ) } ${
							index + 1
						}` }
						initialOpen={ false }
					>
						<TextControl
							label={ __( 'Name', 'reviews-section-block' ) }
							value={ review.name }
							onChange={ ( value ) =>
								updateReview( index, 'name', value )
							}
						/>
						<TextareaControl
							label={ __(
								'Review Text',
								'reviews-section-block'
							) }
							value={ review.text }
							onChange={ ( value ) =>
								updateReview( index, 'text', value )
							}
						/>
						<RangeControl
							label={ __( 'Stars', 'reviews-section-block' ) }
							value={ review.stars }
							onChange={ ( value ) =>
								updateReview( index, 'stars', value )
							}
							min={ 1 }
							max={ 5 }
							step={ 1 }
						/>

						{ /* First Image */ }
						<div style={ { marginTop: '15px' } }>
							<label
								style={ {
									display: 'block',
									marginBottom: '5px',
									fontWeight: 'bold',
								} }
							>
								{ __(
									'First Image (Required)',
									'reviews-section-block'
								) }
							</label>
							<MediaUploadCheck>
								<MediaUpload
									onSelect={ ( media ) =>
										updateReviewImage(
											index,
											'image1',
											media
										)
									}
									allowedTypes={ [ 'image' ] }
									value={ review.image1?.id }
									render={ ( { open } ) => (
										<div>
											{ review.image1?.url ? (
												<div>
													<img
														src={
															review.image1.url
														}
														alt={
															review.image1.alt
														}
														style={ {
															maxWidth: '100px',
															height: 'auto',
															display: 'block',
															marginBottom:
																'10px',
														} }
													/>
													<Button
														isSecondary
														onClick={ open }
													>
														{ __(
															'Replace Image',
															'reviews-section-block'
														) }
													</Button>
													<Button
														isDestructive
														onClick={ () =>
															removeReviewImage(
																index,
																'image1'
															)
														}
														style={ {
															marginLeft: '10px',
														} }
													>
														{ __(
															'Remove',
															'reviews-section-block'
														) }
													</Button>
												</div>
											) : (
												<Button
													isPrimary
													onClick={ open }
												>
													{ __(
														'Select First Image',
														'reviews-section-block'
													) }
												</Button>
											) }
										</div>
									) }
								/>
							</MediaUploadCheck>
						</div>

						{ /* Second Image */ }
						<div style={ { marginTop: '15px' } }>
							<label
								style={ {
									display: 'block',
									marginBottom: '5px',
									fontWeight: 'bold',
								} }
							>
								{ __(
									'Second Image (Required)',
									'reviews-section-block'
								) }
							</label>
							<MediaUploadCheck>
								<MediaUpload
									onSelect={ ( media ) =>
										updateReviewImage(
											index,
											'image2',
											media
										)
									}
									allowedTypes={ [ 'image' ] }
									value={ review.image2?.id }
									render={ ( { open } ) => (
										<div>
											{ review.image2?.url ? (
												<div>
													<img
														src={
															review.image2.url
														}
														alt={
															review.image2.alt
														}
														style={ {
															maxWidth: '100px',
															height: 'auto',
															display: 'block',
															marginBottom:
																'10px',
														} }
													/>
													<Button
														isSecondary
														onClick={ open }
													>
														{ __(
															'Replace Image',
															'reviews-section-block'
														) }
													</Button>
													<Button
														isDestructive
														onClick={ () =>
															removeReviewImage(
																index,
																'image2'
															)
														}
														style={ {
															marginLeft: '10px',
														} }
													>
														{ __(
															'Remove',
															'reviews-section-block'
														) }
													</Button>
												</div>
											) : (
												<Button
													isPrimary
													onClick={ open }
												>
													{ __(
														'Select Second Image',
														'reviews-section-block'
													) }
												</Button>
											) }
										</div>
									) }
								/>
							</MediaUploadCheck>
						</div>

						<Button
							isDestructive
							onClick={ () => removeReview( index ) }
							style={ { marginTop: '20px' } }
						>
							{ __( 'Remove Review', 'reviews-section-block' ) }
						</Button>
					</PanelBody>
				) ) }

				<div className="p-5 pt-0">
					<Button
						isSecondary
						onClick={ addNewReview }
						style={ { marginTop: '20px' } }
					>
						{ __( 'Add Review', 'reviews-section-block' ) }
					</Button>
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

			<Reviews heading={ heading } reviews={ reviews } />
		</div>
	);
}
