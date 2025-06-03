import ReactDOM from 'react-dom/client';

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
			<div class="h-screen bg-red-200 w-full"></div>
		</div>
	);
}
