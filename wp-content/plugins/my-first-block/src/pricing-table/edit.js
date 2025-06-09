import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	ToggleControl,
	Button,
} from '@wordpress/components';
import '../style.css';
import PriceTable from '../../components/price-table';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, tabs } = attributes;
	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	const addPackageTab = () => {
		const newTabs = [
			...tabs,
			{
				title: '',
				note: '',
				features: [],
			},
		];
		setAttributes( { tabs: newTabs } );
	};

	const updatePackageTab = ( index, field, value ) => {
		const updatedTabs = [ ...tabs ];
		updatedTabs[ index ][ field ] = value;
		setAttributes( { tabs: updatedTabs } );
	};

	const removePackageTab = ( index ) => {
		const updatedTabs = tabs.filter( ( _, i ) => i !== index );
		setAttributes( { tabs: updatedTabs } );
	};

	const addFeature = ( tabIndex ) => {
		const updatedTabs = [ ...tabs ];
		if ( ! updatedTabs[ tabIndex ].features ) {
			updatedTabs[ tabIndex ].features = [];
		}
		updatedTabs[ tabIndex ].features.push( {
			title: '',
			description: '',
			priceType: 'currency',
			price: '',
		} );
		setAttributes( { tabs: updatedTabs } );
	};

	const updateFeature = ( tabIndex, featureIndex, field, value ) => {
		const updatedTabs = [ ...tabs ];
		updatedTabs[ tabIndex ].features[ featureIndex ][ field ] = value;
		setAttributes( { tabs: updatedTabs } );
	};

	const removeFeature = ( tabIndex, featureIndex ) => {
		const updatedTabs = [ ...tabs ];
		updatedTabs[ tabIndex ].features.splice( featureIndex, 1 );
		setAttributes( { tabs: updatedTabs } );
	};

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Content', 'your-text-domain' ) }
					initialOpen={ true }
				>
					<TextControl
						label={ __( 'Heading', 'your-text-domain' ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
				</PanelBody>

				{ tabs.map( ( tab, index ) => (
					<PanelBody
						key={ index }
						title={ `${ __( 'Package', 'your-text-domain' ) } ${
							index + 1
						}` }
						initialOpen={ false }
					>
						<TextControl
							label={ __( 'Package Title', 'your-text-domain' ) }
							value={ tab.title }
							onChange={ ( value ) =>
								updatePackageTab( index, 'title', value )
							}
						/>

						<TextareaControl
							label={ __( 'Package Note', 'your-text-domain' ) }
							value={ tab.note }
							onChange={ ( value ) =>
								updatePackageTab( index, 'note', value )
							}
						/>

						<hr />
						<strong>
							{ __( 'Features', 'your-text-domain' ) }
						</strong>

						<div
							style={ {
								marginBottom: '20px',
								marginTop: '10px',
							} }
						>
							{ tab.features?.map( ( feature, fIndex ) => (
								<PanelBody
									key={ fIndex }
									title={
										feature.title ||
										__(
											'Untitled Feature',
											'your-text-domain'
										)
									}
									initialOpen={ false }
								>
									<TextControl
										label="Feature Title"
										value={ feature.title }
										onChange={ ( value ) =>
											updateFeature(
												index,
												fIndex,
												'title',
												value
											)
										}
									/>

									<TextareaControl
										label="Feature Description"
										value={ feature.description }
										onChange={ ( value ) =>
											updateFeature(
												index,
												fIndex,
												'description',
												value
											)
										}
									/>

									<ToggleControl
										label={
											feature.priceType === 'note'
												? 'Note Mode Enabled'
												: 'Currency Mode Enabled'
										}
										checked={ feature.priceType === 'note' }
										onChange={ ( checked ) =>
											updateFeature(
												index,
												fIndex,
												'priceType',
												checked ? 'note' : 'currency'
											)
										}
									/>

									<TextControl
										label={
											feature.priceType === 'currency'
												? 'Price'
												: 'Note'
										}
										value={ feature.price }
										onChange={ ( value ) =>
											updateFeature(
												index,
												fIndex,
												'price',
												value
											)
										}
									/>

									<Button
										isDestructive
										variant="link"
										onClick={ () =>
											removeFeature( index, fIndex )
										}
										style={ { marginTop: '10px' } }
									>
										{ __(
											'Remove Feature',
											'your-text-domain'
										) }
									</Button>
								</PanelBody>
							) ) }
						</div>

						<div style={ { marginTop: '20px' } }>
							<Button
								variant="secondary"
								onClick={ () => addFeature( index ) }
							>
								{ __( 'Add Feature', 'your-text-domain' ) }
							</Button>
						</div>

						<Button
							isDestructive
							variant="link"
							onClick={ () => removePackageTab( index ) }
							style={ { marginTop: '20px', display: 'block' } }
						>
							{ __( 'Remove Package', 'your-text-domain' ) }
						</Button>
					</PanelBody>
				) ) }

				<div className="!p-5">
					<Button variant="primary" onClick={ addPackageTab }>
						{ __( 'Add Package', 'your-text-domain' ) }
					</Button>
				</div>
			</InspectorControls>

			<PriceTable { ...attributes } />
		</div>
	);
}
