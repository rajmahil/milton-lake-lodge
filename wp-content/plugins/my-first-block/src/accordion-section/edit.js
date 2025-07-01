import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	InspectorAdvancedControls,
	RichText,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
} from '@wordpress/components';
import Accordion from '../../components/accordion';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, subheading, items = [], sectionId } = attributes;

	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
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
					<TextareaControl
						label={ __( 'Subheading', 'your-text-domain' ) }
						value={ subheading }
						onChange={ ( value ) =>
							setAttributes( { subheading: value } )
						}
					/>
				</PanelBody>

				{ items.map( ( item, index ) => (
					<PanelBody
						key={ index }
						title={ `${ __( 'Item', 'your-text-domain' ) } ${
							index + 1
						}` }
						initialOpen={ false }
					>
						<TextControl
							label={ __( 'Title', 'your-text-domain' ) }
							value={ item.title }
							onChange={ ( value ) =>
								updateItem( index, 'title', value )
							}
						/>

						<TextareaControl
							label="Answer (HTML allowed)"
							help="Use simple HTML like <a href=''>link</a> for inline links."
							value={ item.text }
							onChange={ ( value ) =>
								updateItem( index, 'text', value )
							}
						/>
						<Button
							isDestructive
							onClick={ () => removeItem( index ) }
							style={ { marginTop: '10px' } }
						>
							{ __( 'Remove Item', 'your-text-domain' ) }
						</Button>
					</PanelBody>
				) ) }

				<div className="p-5 pt-0">
					<Button
						isSecondary
						onClick={ () =>
							setAttributes( {
								items: [ ...items, { title: '', text: '' } ],
							} )
						}
						style={ { marginTop: '20px' } }
					>
						{ __( 'Add Item', 'your-text-domain' ) }
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

			<Accordion { ...attributes } />
		</div>
	);
}
