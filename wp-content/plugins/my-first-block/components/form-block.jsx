import React, { useState } from 'react';

const FormBlock = ( props ) => {
	const {
		heading = 'Your Adventure Awaits',
		// topHeading = 'Your Adventure Awaits',
		fields = [],
		formTitle,
		sectionId,
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
						className={ `form-input !border !border-brand-grey placeholder:!text-muted-foreground/60 ${ fieldClasses }` }
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
						className={ `form-input !pt-4 min-h-24 !border !border-brand-grey placeholder:!text-muted-foreground/60  !text-base ${ fieldClasses }` }
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
							className="w-full h-14 px-4 py-2 text-left bg-white rounded-md border border-brand-grey flex items-center justify-between"
							onClick={ () => toggleSelect( index ) }
						>
							<span
								className={ `truncate ${
									! selectedValue
										? 'text-muted-foreground/60'
										: ''
								}` }
							>
								{ selectedValue ||
									field.placeholder ||
									'Select an option' }
							</span>

							<span className="ml-2">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									width="20"
									height="20"
									viewBox="0 0 24 24"
									fill="none"
									stroke="currentColor"
									strokeWidth="2"
									strokeLinecap="round"
									strokeLinejoin="round"
									className="lucide lucide-chevrons-up-down text-muted-foreground/60"
								>
									<path d="m7 15 5 5 5-5" />
									<path d="m7 9 5-5 5 5" />
								</svg>
							</span>
						</button>

						<div
							className={ `absolute left-0 w-full top-25 bg-white border border-brand-grey rounded-md shadow-md z-10 list-none overflow-auto transition-all duration-100 ease-out transform origin-center  ${
								isOpen
									? 'opacity-100 scale-100 pointer-events-auto max-h-50'
									: 'opacity-0 scale-95 pointer-events-none max-h-50'
							}` }
						>
							{ ( field.options || [] ).map(
								( option, optionIndex ) => (
									<p
										key={ optionIndex }
										className="px-4 py-2 hover:bg-brand-light-grey cursor-pointer capitalize !my-0"
										onClick={ () =>
											selectOption(
												index,
												option,
												field.name
											)
										}
									>
										{ option }
									</p>
								)
							) }
						</div>
					</div>
				);

			case 'checkbox':
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
								( option, optionIndex ) => {
									const isChecked = (
										formData[ field.name ] || []
									).includes( option );

									const toggleCheckbox = () => {
										setFormData( ( prev ) => {
											const currentValues =
												prev[ field.name ] || [];
											if ( isChecked ) {
												return {
													...prev,
													[ field.name ]:
														currentValues.filter(
															( v ) =>
																v !== option
														),
												};
											} else {
												return {
													...prev,
													[ field.name ]: [
														...currentValues,
														option,
													],
												};
											}
										} );
									};

									return (
										<label
											key={ optionIndex }
											className={ `flex items-center p-5 gap-2 bg-white border border-brand-grey rounded-sm cursor-pointer ${
												field.fullWidth
													? 'col-span-2'
													: ''
											}` }
											onClick={ toggleCheckbox }
										>
											<input
												type="checkbox"
												name={
													field.name ||
													'checkbox-group'
												}
												value={ option }
												checked={ isChecked }
												readOnly
												className=" translate-y-px  !h-4.5 !w-4.5 !bg-white"
											/>
											<span className="relative flex flex-col text-left space-y-1.5 ">
												<span className="font-normal text-left capitalize leading-tight ">
													{ option }
												</span>
											</span>
										</label>
									);
								}
							) }
						</div>
					</div>
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
										className={ `flex items-center p-5 gap-2 bg-white border border-brand-grey rounded-md cursor-pointer ${
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
											className="accent-black translate-y-px focus:ring-black"
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
		<section
			id={ sectionId || undefined }
			className="my-unique-plugin-wrapper-class plugin-custom-block "
		>
			<div className="section-padding static-background flex flex-col gap-12">
				<div class="max-w-3xl w-full mx-auto flex flex-col gap-4">
					<h2 class="heading-two text-center text-brand-green-dark">
						{ heading }
					</h2>
				</div>

				<div className="max-w-2xl w-full mx-auto">
					<form className="grid grid-cols-2 gap-4">
						<input
							type="hidden"
							name="form_template"
							value={ 'main_form' }
						/>
						<input
							type="hidden"
							name="formTitle"
							value={ formTitle }
						/>
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
