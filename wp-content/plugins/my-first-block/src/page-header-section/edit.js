import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import PageHeader from '../../components/page-header';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, breadcrumbs = [], image = {} } = attributes;

	const blockProps = useBlockProps( {
		className: 'page-header-section-wrapper',
		style: { maxWidth: '100%', margin: '0 auto' },
	} );

	const updateBreadcrumb = ( index, field, value ) => {
		const updated = [ ...breadcrumbs ];
		updated[ index ][ field ] = value;
		setAttributes( { breadcrumbs: updated } );
	};

	const removeBreadcrumb = ( index ) => {
		const updated = breadcrumbs.filter( ( _, i ) => i !== index );
		setAttributes( { breadcrumbs: updated } );
	};

	const addBreadcrumb = () => {
		setAttributes( {
			breadcrumbs: [ ...breadcrumbs, { text: '', link: '' } ],
		} );
	};

	return (
		<div { ...blockProps } className="plugin-custom-block">
			<InspectorControls>
				{ /* Heading */ }
				<PanelBody
					title={ __(
						'Header Settings',
						'page-header-section-block'
					) }
					initialOpen={ true }
				>
					<TextControl
						label={ __( 'Heading', 'page-header-section-block' ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
				</PanelBody>

				{ /* Breadcrumbs */ }
				<PanelBody
					title={ __( 'Breadcrumbs', 'page-header-section-block' ) }
					initialOpen={ false }
				>
					{ breadcrumbs.map( ( breadcrumb, index ) => (
						<PanelBody
							key={ index }
							title={ `${ __(
								'Breadcrumb',
								'page-header-section'
							) } ${ index + 1 }` }
							initialOpen={ false }
						>
							<TextControl
								label={ __(
									'Text',
									'page-header-section-block'
								) }
								value={ breadcrumb.text }
								onChange={ ( value ) =>
									updateBreadcrumb( index, 'text', value )
								}
							/>
							<TextControl
								label={ __(
									'Link',
									'page-header-section-block'
								) }
								value={ breadcrumb.link }
								onChange={ ( value ) =>
									updateBreadcrumb( index, 'link', value )
								}
							/>
							<Button
								isDestructive
								onClick={ () => removeBreadcrumb( index ) }
								style={ { marginBottom: '10px' } }
							>
								{ __(
									'Remove Breadcrumb',
									'page-header-section-block'
								) }
							</Button>
						</PanelBody>
					) ) }
					<div className="!py-3">
						<Button isSecondary onClick={ addBreadcrumb }>
							{ __(
								'Add Breadcrumb',
								'page-header-section-block'
							) }
						</Button>
					</div>
				</PanelBody>

				{ /* Image Upload */ }
				<PanelBody
					title={ __(
						'Image Settings',
						'page-header-section-block'
					) }
					initialOpen={ false }
				>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) =>
								setAttributes( {
									image: {
										id: media.id,
										url: media.url,
										alt: media.alt,
										width: media.width,
										height: media.height,
										sizes: media.sizes,
										srcSet: media.sizes?.full?.source_url
											? undefined
											: undefined,
									},
								} )
							}
							allowedTypes={ [ 'image' ] }
							value={ image?.id }
							render={ ( { open } ) => (
								<Button onClick={ open } isPrimary>
									{ image?.url
										? __(
												'Replace Image',
												'page-header-section-block'
										  )
										: __(
												'Upload Image',
												'page-header-section-block'
										  ) }
								</Button>
							) }
						/>
					</MediaUploadCheck>

					{ image?.url && (
						<div style={ { marginTop: '10px' } }>
							<img
								src={ image.url }
								alt={ image.alt || '' }
								width={ image.width }
								height={ image.height }
								style={ { maxWidth: '100%' } }
							/>
						</div>
					) }
				</PanelBody>
			</InspectorControls>
			<PageHeader { ...attributes } />
		</div>
	);
}
