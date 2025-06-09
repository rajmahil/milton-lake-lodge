import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const {} = attributes;

	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	return <div { ...blockProps }>123 CAlendar goes here</div>;
}
