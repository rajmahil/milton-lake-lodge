import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	ToggleControl,
	Button,
	SelectControl,
} from '@wordpress/components';
import FormBlock from '../../components/form-block';

export default function Edit( { attributes, setAttributes } ) {
	const { topHeading, heading, formTitle, submitButtonText, fields } =
		attributes;

	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	// Handlers
	const updateField = ( index, key, value ) => {
		const newFields = [ ...fields ];
		newFields[ index ][ key ] = value;
		setAttributes( { fields: newFields } );
	};

	const addField = () => {
		const newFields = [
			...fields,
			{
				type: 'text',
				label: '',
				name: '',
				required: false,
				fullWidth: false,
				options: [],
			},
		];
		setAttributes( { fields: newFields } );
	};

	const removeField = ( index ) => {
		const newFields = [ ...fields ];
		newFields.splice( index, 1 );
		setAttributes( { fields: newFields } );
	};

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Content', 'form-section-block' ) }
					initialOpen={ true }
				>
					<TextControl
						label="Top Heading"
						value={ topHeading }
						onChange={ ( value ) =>
							setAttributes( { topHeading: value } )
						}
					/>
					<TextControl
						label="Heading"
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Form Settings', 'form-section-block' ) }
					initialOpen={ true }
				>
					<TextControl
						label="Form Title"
						value={ formTitle }
						onChange={ ( value ) =>
							setAttributes( { formTitle: value } )
						}
					/>
					<TextControl
						label="Submit Button Text"
						value={ submitButtonText }
						onChange={ ( value ) =>
							setAttributes( { submitButtonText: value } )
						}
					/>
				</PanelBody>
				<PanelBody
					title={ __( 'Form Fields', 'form-section-block' ) }
					initialOpen={ true }
				>
					{ fields.map( ( field, index ) => (
						<div
							key={ index }
							style={ {
								borderBottom: '1px solid #ddd',
								marginBottom: '1em',
								paddingBottom: '1em',
							} }
						>
							<SelectControl
								label="Field Type"
								value={ field.type }
								options={ [
									{ label: 'Text', value: 'text' },
									{ label: 'Textarea', value: 'textarea' },
									{ label: 'Email', value: 'email' },
									{ label: 'Phone', value: 'tel' },
									{ label: 'Select', value: 'select' },
									{ label: 'Radio', value: 'radio' },
									{ label: 'Checkbox', value: 'checkbox' },
								] }
								onChange={ ( value ) =>
									updateField( index, 'type', value )
								}
							/>
							<TextControl
								label="Field Label"
								value={ field.label }
								onChange={ ( value ) =>
									updateField( index, 'label', value )
								}
							/>
							<TextControl
								label="Placeholder"
								value={ field.placeholder }
								onChange={ ( value ) =>
									updateField( index, 'placeholder', value )
								}
							/>
							<TextControl
								label="Field Id (HTML id)"
								value={ field.name }
								onChange={ ( value ) =>
									updateField( index, 'name', value )
								}
							/>
							<ToggleControl
								label="Required"
								checked={ field.required }
								onChange={ ( value ) =>
									updateField( index, 'required', value )
								}
							/>
							<ToggleControl
								label="Full Width"
								checked={ field.fullWidth }
								onChange={ ( value ) =>
									updateField( index, 'fullWidth', value )
								}
							/>

							{ [ 'select', 'radio', 'checkbox' ].includes(
								field.type
							) && (
								<TextareaControl
									label="Options (comma separated)"
									defaultValue={ ( field.options || [] ).join(
										', '
									) }
									onChange={ ( value ) =>
										updateField(
											index,
											'options',
											value
												.split( ',' )
												.map( ( opt ) => opt.trim() )
												.filter( Boolean )
										)
									}
								/>
							) }

							<Button
								isDestructive
								variant="secondary"
								onClick={ () => removeField( index ) }
								style={ { marginTop: '10px' } }
							>
								Remove Field
							</Button>
						</div>
					) ) }

					<Button variant="primary" onClick={ addField }>
						Add New Field
					</Button>
				</PanelBody>
			</InspectorControls>
			<FormBlock { ...attributes } />
		</div>
	);
}
