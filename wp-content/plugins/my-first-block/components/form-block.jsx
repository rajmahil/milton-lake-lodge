import { Input } from './ui/input';

const FormBlock = ( props ) => {
	const { heading, topHeading, fields } = props;

	return (
		<section className="section-padding">
			<div class="max-w-3xl mx-auto w-full flex flex-col gap-12">
				<div className="flex flex-col gap-2">
					<p className="decorative-text text-brand-yellow-dark !text-4xl !my-0 text-center">
						{ topHeading }
					</p>
					<h2 className="!text-4xl font-bold mb-4 text-center !my-0">
						{ heading }
					</h2>
				</div>
				<form className="grid grid-cols-2 gap-4">
					{ fields.map( ( field, index ) => {
						switch ( field.type ) {
							case 'text':
							case 'email':
							case 'tel':
								return (
									<Input
										type={ field.type }
										placeholder="Email"
									/>
								);
							case 'textarea':
								return <p>text area</p>;

							case 'select':
								return <p>select</p>;

							case 'checkbox':
								return <p>checkbox</p>;

							case 'radio':
								return <p>radio btn</p>;

							default:
								console.log( 'Unknown field type' );
								break;
						}
					} ) }
				</form>
			</div>
		</section>
	);
};

export default FormBlock;
