import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	InspectorAdvancedControls,
	MediaUpload,
} from '@wordpress/block-editor';
import {
	PanelBody,
	ComboboxControl,
	TextControl,
	Button,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import FullCalendar from '../../components/full-calendar';

export default function Edit( { attributes, setAttributes } ) {
	const { selectedPostId, heading, subheading, sectionId } = attributes;

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

					<MediaUpload
						label={ __( 'Select Downloadable File', 'my-plugin' ) }
						onSelect={ ( file ) => {
							setAttributes( {
								downloadableFile: {
									id: file.id,
									url: file.url,
									title: file.title,
									mime: file.mime,
								},
							} );
						} }
						allowedTypes={ [
							'application/pdf',
							'application/msword',
							'application/zip',
						] } // or just ['*']
						render={ ( { open } ) => (
							<div className="mt-4">
								<Button onClick={ open } variant="secondary">
									{ attributes.downloadableFile?.url
										? 'Replace Downloadable File'
										: 'Add Downloadable File' }
								</Button>
								{ attributes.downloadableFile?.url && (
									<p style={ { marginTop: '10px' } }>
										Selected:{ ' ' }
										<a
											href={
												attributes.downloadableFile.url
											}
											target="_blank"
											rel="noreferrer"
										>
											{
												attributes.downloadableFile
													.title
											}
										</a>
									</p>
								) }
							</div>
						) }
					/>
				</PanelBody>

				<InspectorAdvancedControls>
					<TextControl
						label="Section ID (slug, hyphens only)"
						value={ sectionId }
						onChange={ onChangeSectionId }
						help="Use lowercase letters, numbers, and hyphens only."
					/>
				</InspectorAdvancedControls>
			</InspectorControls>

			<div className="w-full flex items-center justify-center px-4 py-10">
				Calendar Embedd: { selectedPostId }
			</div>
		</div>
	);
}
