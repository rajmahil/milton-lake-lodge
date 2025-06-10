import ReactDOM from 'react-dom/client';
import PageHeader from '../../components/page-header';

const divsToUpdate = document.querySelectorAll(
	'.tailwind-update-page-header-section'
);

divsToUpdate.forEach( ( div ) => {
	const data = JSON.parse( div.querySelector( 'pre' ).innerText );
	const root = ReactDOM.createRoot( div );
	root.render( <PageHeaderRenderComponent { ...data } /> );
	div.classList.remove( 'tailwind-update-me' );
} );

function PageHeaderRenderComponent( props ) {
	return (
		<div className="my-unique-plugin-wrapper-class">
			<PageHeader { ...props } />
		</div>
	);
}
