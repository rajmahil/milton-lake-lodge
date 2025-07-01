import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { sortBy } = attributes;

	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title="Sorting Options">
					<SelectControl
						label="Sort Posts By"
						value={ attributes.sortBy }
						options={ [
							{ label: 'Newest First', value: 'newest' },
							{ label: 'Oldest First', value: 'oldest' },
							{ label: 'A–Z (Title)', value: 'title' },
						] }
						onChange={ ( value ) =>
							setAttributes( { sortBy: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			{ 'news section' }
		</div>
	);
}
