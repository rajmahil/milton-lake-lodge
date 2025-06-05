import ReactDOM from 'react-dom/client';
import Cta from '../../components/cta';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-cta-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <CtaRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function CtaRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<Cta { ...props } />
		</div>
	);
}
