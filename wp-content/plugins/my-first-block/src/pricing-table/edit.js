import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	InspectorAdvancedControls,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	ToggleControl,
	Button,
	Notice,
} from '@wordpress/components';
import '../style.css';
import PriceTable from '../../components/price-table';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, tabs, sectionId } = attributes;
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
				whatsIncluded: [],
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
		if (
			field === 'price' &&
			updatedTabs[ tabIndex ].features[ featureIndex ].priceType ===
				'currency'
		) {
			const numericRegex = /^\d*\.?\d*$/;
			if ( value === '' || numericRegex.test( value ) ) {
				updatedTabs[ tabIndex ].features[ featureIndex ][ field ] =
					value;
			}
		} else {
			updatedTabs[ tabIndex ].features[ featureIndex ][ field ] = value;
		}
		setAttributes( { tabs: updatedTabs } );
	};

	const removeFeature = ( tabIndex, featureIndex ) => {
		const updatedTabs = [ ...tabs ];
		updatedTabs[ tabIndex ].features.splice( featureIndex, 1 );
		setAttributes( { tabs: updatedTabs } );
	};

	const addWhatsIncludedItem = ( tabIndex ) => {
		const updatedTabs = [ ...tabs ];
		updatedTabs[ tabIndex ].whatsIncluded =
			updatedTabs[ tabIndex ].whatsIncluded || [];
		updatedTabs[ tabIndex ].whatsIncluded.push( '' );
		setAttributes( { tabs: updatedTabs } );
	};

	const updateWhatsIncludedItem = ( tabIndex, itemIndex, value ) => {
		const updatedTabs = [ ...tabs ];
		updatedTabs[ tabIndex ].whatsIncluded[ itemIndex ] = value;
		setAttributes( { tabs: updatedTabs } );
	};

	const removeWhatsIncludedItem = ( tabIndex, itemIndex ) => {
		const updatedTabs = [ ...tabs ];
		updatedTabs[ tabIndex ].whatsIncluded.splice( itemIndex, 1 );
		setAttributes( { tabs: updatedTabs } );
	};

	const isValidPrice = ( price, priceType ) => {
		if ( priceType !== 'currency' ) return true;
		if ( price === '' ) return true;
		return /^\d+(\.\d{0,2})?$/.test( price );
	};

	const getPriceValidationMessage = ( price, priceType ) => {
		if (
			priceType === 'currency' &&
			price &&
			! isValidPrice( price, priceType )
		) {
			return __(
				'Please enter a valid number (e.g., 100 or 99.99)',
				'your-text-domain'
			);
		}
		return null;
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

						<div className="flex flex-col items-start gap-2 w-full">
							<strong>
								{ __( 'What’s Included', 'your-text-domain' ) }
							</strong>
							{ ( tab.whatsIncluded || [] ).map(
								( item, iIndex ) => (
									<div
										key={ iIndex }
										style={ { marginBottom: '10px' } }
										className="w-full"
									>
										<TextControl
											label={ `Item ${ iIndex + 1 }` }
											value={ item }
											onChange={ ( value ) =>
												updateWhatsIncludedItem(
													index,
													iIndex,
													value
												)
											}
										/>
										<Button
											isDestructive
											onClick={ () =>
												removeWhatsIncludedItem(
													index,
													iIndex
												)
											}
										>
											Remove
										</Button>
									</div>
								)
							) }
							<Button
								variant="secondary"
								onClick={ () => addWhatsIncludedItem( index ) }
							>
								{ __( 'Add Item', 'your-text-domain' ) }
							</Button>
						</div>
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
							{ tab.features?.map( ( feature, fIndex ) => {
								const validationMessage =
									getPriceValidationMessage(
										feature.price,
										feature.priceType
									);

								return (
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
											checked={
												feature.priceType === 'note'
											}
											onChange={ ( checked ) => {
												const newPriceType = checked
													? 'note'
													: 'currency';
												updateFeature(
													index,
													fIndex,
													'priceType',
													newPriceType
												);
												if (
													newPriceType ===
														'currency' &&
													feature.price &&
													! /^\d*\.?\d*$/.test(
														feature.price
													)
												) {
													updateFeature(
														index,
														fIndex,
														'price',
														''
													);
												}
											} }
											help={
												feature.priceType === 'currency'
													? __(
															'Currency mode: Only numbers allowed',
															'your-text-domain'
													  )
													: __(
															'Note mode: Any text allowed',
															'your-text-domain'
													  )
											}
										/>

										<TextControl
											label={
												feature.priceType === 'currency'
													? 'Price (USD)'
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
											type={
												feature.priceType === 'currency'
													? 'number'
													: 'text'
											}
											step={
												feature.priceType === 'currency'
													? '0.01'
													: undefined
											}
											min={
												feature.priceType === 'currency'
													? '0'
													: undefined
											}
											placeholder={
												feature.priceType === 'currency'
													? __(
															'Enter price (e.g., 100 or 99.99)',
															'your-text-domain'
													  )
													: __(
															'Enter note text',
															'your-text-domain'
													  )
											}
											help={
												feature.priceType === 'currency'
													? __(
															'Enter numeric value only. Will be converted to CAD automatically.',
															'your-text-domain'
													  )
													: __(
															'Any text can be entered here.',
															'your-text-domain'
													  )
											}
										/>

										{ validationMessage && (
											<Notice
												status="error"
												isDismissible={ false }
												style={ { marginTop: '10px' } }
											>
												{ validationMessage }
											</Notice>
										) }

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
								);
							} ) }
						</div>

						<Button
							variant="secondary"
							onClick={ () => addFeature( index ) }
						>
							{ __( 'Add Feature', 'your-text-domain' ) }
						</Button>

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

				<InspectorAdvancedControls>
					<TextControl
						label="Section ID (slug, hyphens only)"
						value={ sectionId }
						onChange={ onChangeSectionId }
						help="Use lowercase letters, numbers, and hyphens only."
					/>
				</InspectorAdvancedControls>
			</InspectorControls>

			<PriceTable { ...attributes } />
		</div>
	);
}
