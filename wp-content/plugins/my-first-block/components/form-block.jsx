import { Input } from './ui/input';

const FormBlock = ( { heading, topHeading, fields = [], onSubmit } ) => {
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
					<form
						className="grid grid-cols-2 gap-4"
						onSubmit={ onSubmit }
					>
						{ fields.map( ( field, index ) => {
							const fullWidthClass = field.fullWidth
								? 'col-span-2'
								: '';
							const requiredMark = field.required ? ' *' : '';
							const baseProps = {
								key: index,
								required: field.required,
								name: field.name,
								className: `form-input ${ fullWidthClass }`,
								placeholder: `${
									field.placeholder || ''
								}${ requiredMark }`,
								defaultValue: field.value,
							};

							switch ( field.type ) {
								case 'text':
								case 'email':
								case 'tel':
									return (
										<Input
											type={ field.type }
											{ ...baseProps }
										/>
									);

								case 'textarea':
									return (
										<textarea
											{ ...baseProps }
											className={ `form-input min-h-24 pt-4 ${ fullWidthClass }` }
										/>
									);

								case 'select':
									return (
										<div
											key={ index }
											className={ `relative ${ fullWidthClass }` }
										>
											{ field.label && (
												<label className="block mb-3 text-medium text-center">
													{ field.label }
													{ field.required && (
														<span>*</span>
													) }
												</label>
											) }
											<select
												name={ field.name }
												className="form-input w-full cursor-pointer"
												defaultValue=""
												required={ field.required }
											>
												<option value="" disabled>
													{ field.placeholder }
												</option>
												{ field.options?.map(
													( opt, i ) => (
														<option
															key={ i }
															value={ opt }
														>
															{ opt }
														</option>
													)
												) }
											</select>
										</div>
									);

								case 'checkbox':
									return (
										<div
											key={ index }
											className="col-span-2"
										>
											{ field.label && (
												<label className="block mb-3 text-medium text-center">
													{ field.label }
													{ field.required && (
														<span>*</span>
													) }
												</label>
											) }
											<div className="grid grid-cols-2 gap-2">
												{ field.options?.map(
													( opt, i ) => (
														<label
															key={ i }
															className={ `flex items-center justify-start p-5 gap-2 bg-white border border-brand-grey rounded-md cursor-pointer ${ fullWidthClass }` }
														>
															<input
																type="checkbox"
																name={
																	field.name
																}
																value={ opt }
																className="accent-brand-dark-blue translate-y-px focus:ring-brand-dark-blue !h-4.5 !w-4.5"
															/>
															<span className="capitalize">
																{ opt }
															</span>
														</label>
													)
												) }
											</div>
										</div>
									);

								case 'radio':
									return (
										<div
											key={ index }
											className="col-span-2 my-2"
										>
											{ field.label && (
												<label className="block mb-3 text-medium text-center">
													{ field.label }
													{ field.required && (
														<span>*</span>
													) }
												</label>
											) }
											<div className="grid grid-cols-2 gap-2">
												{ field.options?.map(
													( opt, i ) => (
														<label
															key={ i }
															className={ `flex items-center p-5 gap-2 bg-white border border-brand-grey rounded-md cursor-pointer ${ fullWidthClass }` }
														>
															<input
																type="radio"
																name={
																	field.name
																}
																value={ opt }
																className="accent-brand-dark-blue translate-y-px focus:ring-brand-dark-blue"
															/>
															<span className="capitalize">
																{ opt }
															</span>
														</label>
													)
												) }
											</div>
										</div>
									);

								default:
									console.warn(
										'Unknown field type:',
										field.type
									);
									return null;
							}
						} ) }

						<button
							type="submit"
							className="btn btn-dark btn-xl col-span-2 h-14 !mt-4"
						>
							Submit
						</button>
					</form>
				</div>
			</div>
		</section>
	);
};

export default FormBlock;
