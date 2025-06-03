import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import '../style.css';

export default function Edit( { attributes, setAttributes } ) {
	// This is crucial - it provides the block wrapper with proper WordPress functionality
	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	return <div { ...blockProps }>form component</div>;
}
