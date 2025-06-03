import ReactDOM from 'react-dom/client';
import Hero from '../../components/hero';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-hero-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <HeroRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function HeroRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<Hero { ...props } />
		</div>
	);
}
