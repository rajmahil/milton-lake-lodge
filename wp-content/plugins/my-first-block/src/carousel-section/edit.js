import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
} from '@wordpress/components';
import Carousel from '../../components/carousel';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, subheading, items = [] } = attributes;

	const blockProps = useBlockProps( {
		className: 'carousel-section-wrapper',
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

	const addNewItem = () => {
		setAttributes( {
			items: [
				...items,
				{
					title: '',
					text: '',
					image: { id: null, url: '', alt: '' },
				},
			],
		} );
	};

	const updateItemImage = ( index, media ) => {
		const updated = [ ...items ];
		updated[ index ].image = {
			id: media.id,
			url: media.url,
			alt: media.alt || '',
		};
		setAttributes( { items: updated } );
	};

	const removeItemImage = ( index ) => {
		const updated = [ ...items ];
		updated[ index ].image = { id: null, url: '', alt: '' };
		setAttributes( { items: updated } );
	};

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Section Settings', 'carousel-section-block' ) }
					initialOpen={ true }
				>
					<TextControl
						label={ __( 'Heading', 'carousel-section-block' ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
					<TextareaControl
						label={ __( 'Subheading', 'carousel-section-block' ) }
						value={ subheading }
						onChange={ ( value ) =>
							setAttributes( { subheading: value } )
						}
					/>
				</PanelBody>

				{ items.map( ( item, index ) => (
					<PanelBody
						key={ index }
						title={ `${ __( 'Item', 'carousel-section-block' ) } ${
							index + 1
						}` }
						initialOpen={ false }
					>
						<TextControl
							label={ __( 'Title', 'carousel-section-block' ) }
							value={ item.title }
							onChange={ ( value ) =>
								updateItem( index, 'title', value )
							}
						/>
						<TextareaControl
							label={ __( 'Text', 'carousel-section-block' ) }
							value={ item.text }
							onChange={ ( value ) =>
								updateItem( index, 'text', value )
							}
						/>

						<div style={ { marginTop: '15px' } }>
							<label
								style={ {
									display: 'block',
									marginBottom: '5px',
									fontWeight: 'bold',
								} }
							>
								{ __(
									'Image (Required)',
									'carousel-section-block'
								) }
							</label>
							<MediaUploadCheck>
								<MediaUpload
									onSelect={ ( media ) =>
										updateItemImage( index, media )
									}
									allowedTypes={ [ 'image' ] }
									value={ item.image?.id }
									render={ ( { open } ) => (
										<div>
											{ item.image?.url ? (
												<div>
													<img
														src={ item.image.url }
														alt={ item.image.alt }
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
															'carousel-section-block'
														) }
													</Button>
													<Button
														isDestructive
														onClick={ () =>
															removeItemImage(
																index
															)
														}
														style={ {
															marginLeft: '10px',
														} }
													>
														{ __(
															'Remove',
															'carousel-section-block'
														) }
													</Button>
												</div>
											) : (
												<Button
													isPrimary
													onClick={ open }
												>
													{ __(
														'Select Image',
														'carousel-section-block'
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
							onClick={ () => removeItem( index ) }
							style={ { marginTop: '20px' } }
						>
							{ __( 'Remove Item', 'carousel-section-block' ) }
						</Button>
					</PanelBody>
				) ) }

				<div className="p-5 pt-0">
					<Button
						isSecondary
						onClick={ addNewItem }
						style={ { marginTop: '20px' } }
					>
						{ __( 'Add Item', 'carousel-section-block' ) }
					</Button>
				</div>
			</InspectorControls>

			<Carousel
				heading={ heading }
				subheading={ subheading }
				items={ items }
			/>
		</div>
	);
}
