import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ComboboxControl, TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import FullCalendar from '../../components/full-calendar';

export default function Edit( { attributes, setAttributes } ) {
	const { selectedPostId, heading, subheading } = attributes;

	const blockProps = useBlockProps( {
		className: 'my-unique-plugin-wrapper-class',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	// Load posts from CPT 'calendar'
	const posts = useSelect(
		( select ) =>
			select( 'core' ).getEntityRecords( 'postType', 'season_calendar', {
				per_page: -1,
			} ),
		[]
	);

	// Convert to options array for ComboboxControl
	const postOptions = posts
		? posts.map( ( post ) => ( {
				value: post.id,
				label: post.title.rendered,
		  } ) )
		: [];

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Content', 'your-text-domain' ) }>
					<TextControl
						label={ __( 'Heading', 'your-text-domain' ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
					<TextControl
						label={ __( 'Subheading', 'your-text-domain' ) }
						value={ subheading }
						onChange={ ( value ) =>
							setAttributes( { subheading: value } )
						}
					/>
				</PanelBody>
				<PanelBody title={ __( 'Select Calendar Post', 'my-plugin' ) }>
					<ComboboxControl
						label={ __( 'Calendar Post', 'my-plugin' ) }
						options={ postOptions }
						value={ selectedPostId }
						onChange={ ( value ) =>
							setAttributes( { selectedPostId: value } )
						}
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>
			{ /* <FullCalendar { ...attributes } /> */ }
			{ selectedPostId }
		</div>
	);
}
