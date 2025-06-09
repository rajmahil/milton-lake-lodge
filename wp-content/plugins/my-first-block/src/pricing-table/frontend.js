import ReactDOM from 'react-dom/client';
import PriceTable from '../../components/price-table';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-price-table-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <PriceTableRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function PriceTableRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<PriceTable { ...props } />
		</div>
	);
}
