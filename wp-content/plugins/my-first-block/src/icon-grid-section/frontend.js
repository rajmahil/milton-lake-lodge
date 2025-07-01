import ReactDOM from 'react-dom/client';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-accordion-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <AccordionRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function AccordionRenderComponent( props ) {
	return <div className="my-unique-plugin-wrapper-class"></div>;
}
