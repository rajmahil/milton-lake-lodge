import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const {
		heading,
		subheading,
		buttonText,
		buttonUrl,
		imageUrl
	} = attributes;

	// Add console log to verify this code is running
	console.log('🔄 Save function running with attributes:', attributes);

	return (
		<div {...useBlockProps.save()} className="my-unique-plugin-wrapper-class">
			<div className="p-6 bg-green-200 border-2 border-green-400">
				<p className="text-red-600 font-bold mb-4">SAVE FUNCTION UPDATED - NEW VERSION 999</p>
				{heading && <RichText.Content tagName="h2" value={heading} className="text-2xl font-bold mb-4" />}
				{subheading && <RichText.Content tagName="p" value={subheading} className="text-gray-600 mb-4" />}
				<p className="text-purple-600 font-bold">This text should appear if save.js is working - VERSION 123</p>
				{buttonText && (
					<a href={buttonUrl} className="bg-red-500 text-white px-4 py-2 rounded inline-block mt-4">
						<RichText.Content value={buttonText} />
					</a>
				)}
				{imageUrl && <img src={imageUrl || "/placeholder.svg"} alt="Hero" className="mt-4 max-w-full h-auto" />}
			</div>
		</div>
	);
}
