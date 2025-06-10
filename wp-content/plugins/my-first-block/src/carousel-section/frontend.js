import ReactDOM from 'react-dom/client';
import Carousel from '../../components/carousel';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-carousel-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <CarouselRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function CarouselRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<Carousel { ...props } />
		</div>
	);
}
