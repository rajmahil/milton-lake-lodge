import ReactDOM from 'react-dom/client';
import Features from '../../components/features';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-features-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <FeaturesRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function FeaturesRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<Features { ...props } />
		</div>
	);
}
