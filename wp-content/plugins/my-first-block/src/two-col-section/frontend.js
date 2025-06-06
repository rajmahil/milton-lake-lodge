import ReactDOM from 'react-dom/client';
import TwoCol from '../../components/two-col';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-two-col-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <TwoColRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function TwoColRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<TwoCol { ...props } />
		</div>
	);
}
