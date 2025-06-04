import React, { useState } from 'react';

const FormBlock = ( props ) => {
	const {
		heading = 'Your Adventure Awaits',
		topHeading = 'Your Adventure Awaits',
		fields = [],
	} = props;

	// State for form interactions
	const [ formData, setFormData ] = useState( {} );
	const [ openSelects, setOpenSelects ] = useState( {} );

	const handleInputChange = ( fieldName, value ) => {
		setFormData( ( prev ) => ( {
			...prev,
			[ fieldName ]: value,
		} ) );
	};

	const toggleSelect = ( index ) => {
		setOpenSelects( ( prev ) => ( {
			...prev,
			[ index ]: ! prev[ index ],
		} ) );
	};

	const selectOption = ( index, option, fieldName ) => {
		handleInputChange( fieldName, option );
		setOpenSelects( ( prev ) => ( {
			...prev,
			[ index ]: false,
		} ) );
	};

	const renderField = ( field, index ) => {
		const fieldClasses = `${ field.fullWidth ? 'col-span-2' : '' }`;

		switch ( field.type ) {
			case 'text':
			case 'email':
			case 'tel':
				return (
					<input
						key={ index }
						type={ field.type || 'text' }
						name={ field.name || '' }
						value={ formData[ field.name ] || field.value || '' }
						placeholder={ `${ field.placeholder || '' }${
							field.required ? ' *' : ''
						}` }
						className={ `form-input ${ fieldClasses }` }
						required={ field.required }
						onChange={ ( e ) =>
							handleInputChange( field.name, e.target.value )
						}
					/>
				);

			case 'textarea':
				return (
					<textarea
						key={ index }
						className={ `form-input min-h-24 pt-4 ${ fieldClasses }` }
						name={ field.name || '' }
						placeholder={ `${ field.placeholder || '' }${
							field.required ? ' *' : ''
						}` }
						value={ formData[ field.name ] || field.value || '' }
						onChange={ ( e ) =>
							handleInputChange( field.name, e.target.value )
						}
					/>
				);

			case 'select':
				const isOpen = openSelects[ index ] || false;
				const selectedValue = formData[ field.name ] || '';

				return (
					<div
						key={ index }
						className={ `relative ${ fieldClasses }` }
					>
						{ field.label && (
							<label className="block mb-3 text-medium text-center">
								{ field.label }
								{ field.required && (
									<span className="text-red-500">*</span>
								) }
							</label>
						) }

						<button
							type="button"
							className="w-full h-14 px-4 py-2 text-left bg-white rounded-md border border-brand-grey"
							onClick={ () => toggleSelect( index ) }
						>
							<span>
								{ selectedValue ||
									field.placeholder ||
									'Select an option' }
							</span>
						</button>

						{ isOpen && (
							<ul className="absolute left-0 w-full mt-1 bg-white border rounded shadow max-h-40 overflow-auto z-10">
								{ ( field.options || [] ).map(
									( option, optionIndex ) => (
										<li
											key={ optionIndex }
											className="px-4 py-2 hover:bg-gray-100 cursor-pointer"
											onClick={ () =>
												selectOption(
													index,
													option,
													field.name
												)
											}
										>
											{ option }
										</li>
									)
								) }
							</ul>
						) }
					</div>
				);

			case 'checkbox':
				return (
					<label
						key={ index }
						className={ `flex items-center space-x-2 ${ fieldClasses }` }
					>
						<input
							type="checkbox"
							name={ field.name || '' }
							className="form-checkbox"
							checked={
								formData[ field.name ] || field.checked || false
							}
							onChange={ ( e ) =>
								handleInputChange(
									field.name,
									e.target.checked
								)
							}
						/>
						<span>{ field.label || '' }</span>
					</label>
				);

			case 'radio':
				const radioValue = formData[ field.name ] || null;

				return (
					<div key={ index } className="col-span-2 my-2">
						{ field.label && (
							<label className="block mb-3 text-medium text-center">
								{ field.label }
								{ field.required && (
									<span className="text-red-500">*</span>
								) }
							</label>
						) }

						<div className="grid grid-cols-2 gap-2">
							{ ( field.options || [] ).map(
								( option, optionIndex ) => (
									<label
										key={ optionIndex }
										className={ `flex items-start p-5 space-x-3 bg-white border border-brand-grey rounded-md cursor-pointer ${
											field.fullWidth ? 'col-span-2' : ''
										}` }
										onClick={ () =>
											handleInputChange(
												field.name,
												option
											)
										}
									>
										<input
											type="radio"
											name={ field.name || 'radio-group' }
											value={ option }
											checked={ radioValue === option }
											className="accent-brand-dark-blue translate-y-px focus:ring-brand-dark-blue"
											onChange={ () =>
												handleInputChange(
													field.name,
													option
												)
											}
										/>
										<span className="relative flex flex-col text-left space-y-1.5 leading-none">
											<span className="font-normal text-left">
												{ option }
											</span>
										</span>
									</label>
								)
							) }
						</div>
					</div>
				);

			default:
				console.log( 'Unknown field type:', field.type );
				return null;
		}
	};

	return (
		<section className="my-unique-plugin-wrapper-class">
			<div className="section-padding static-background flex flex-col gap-12">
				<div className="max-w-3xl w-full mx-auto">
					<p className="decorative-text text-brand-yellow-dark !text-4xl !my-0 text-center">
						{ topHeading }
					</p>
					<h2 className="!text-4xl font-bold mb-4 text-center !my-0 text-brand-dark-blue">
						{ heading }
					</h2>
				</div>

				<div className="max-w-2xl w-full mx-auto">
					<form className="grid grid-cols-2 gap-4">
						{ fields.map( ( field, index ) =>
							renderField( field, index )
						) }
					</form>
				</div>
			</div>
		</section>
	);
};

export default FormBlock;
