import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
} from '@wordpress/components';
import Accordion from '../../components/accordion';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, subheading, items = [] } = attributes;

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
							label={ __( 'Text', 'your-text-domain' ) }
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
			</InspectorControls>

			<Accordion { ...attributes } />
		</div>
	);
}
