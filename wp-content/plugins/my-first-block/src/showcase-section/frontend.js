import ReactDOM from 'react-dom/client';
import Showcase from '../../components/showcase';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-showcase-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <ShowcaseRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function ShowcaseRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<Showcase { ...props } />
		</div>
	);
}
