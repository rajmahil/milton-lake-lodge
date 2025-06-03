import ReactDOM from 'react-dom/client';
import FormBlock from '../../components/form-block';

const divsToUpdate = document.querySelectorAll( '.tailwind-update-form-block' );

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <FormComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function FormComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<FormBlock { ...props } />
		</div>
	);
}
