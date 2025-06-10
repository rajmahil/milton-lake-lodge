import ReactDOM from 'react-dom/client';
import GallerySection from '../../components/gallery-section-section';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-gallery-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <GalleryRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function GalleryRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<GallerySection { ...props } />
		</div>
	);
}
