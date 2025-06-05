import ReactDOM from 'react-dom/client';
import ScrollImage from '../../components/scroll-image';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-scroll-image-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <ScrollImageRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function ScrollImageRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<ScrollImage { ...props } />
		</div>
	);
}
