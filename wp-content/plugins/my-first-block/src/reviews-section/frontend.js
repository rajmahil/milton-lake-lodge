import ReactDOM from 'react-dom/client';
import Review from '../../components/review';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-reviews-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <ReviewRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function ReviewRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<Review { ...props } />
		</div>
	);
}
