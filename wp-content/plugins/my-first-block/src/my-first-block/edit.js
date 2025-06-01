import { useState } from '@wordpress/element';
import { useBlockProps } from '@wordpress/block-editor';
import '../style.css';

export default function Edit(props) {
    const { attributes, setAttributes } = props;
    const [showDetails, setShowDetails] = useState(false);

    // This is crucial - it provides the block wrapper with proper WordPress functionality
    const blockProps = useBlockProps({
        className: 'my-unique-plugin-wrapper-class'
    });

    return (
        <div {...blockProps}>
            <div className="bg-green-100 border-2 border-green-800 p-6 my-3 rounded-lg shadow-md">
                <p className="text-teal-600 font-bold mb-4">
                    🎉 DYNAMIC BLOCK - Updates Instantly!
                </p>
                {attributes.heading && (
                    <h2 className="text-2xl font-bold mb-4 text-gray-900">
                        {attributes.heading}
                    </h2>
                )}
                {attributes.subheading && (
                    <p className="text-gray-600 mb-4">
                        {attributes.subheading}
                    </p>
                )}
                <button
                    className="rounded bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 mr-3 mb-3 inline-block transition-colors"
                    onClick={() => setShowDetails(prev => !prev)}
                >
                    Toggle Details123
                </button>
                {showDetails && (
                    <div className="mt-4 p-4 bg-white rounded border">
                        <p><strong>Heading:</strong> {attributes.heading || 'Not set'}</p>
                        <p><strong>Subheading:</strong> {attributes.subheading || 'Not set'}</p>
                        <p><strong>Button Text:</strong> {attributes.buttonText || 'Not set'}</p>
                        <p><strong>Button URL:</strong> {attributes.buttonUrl || 'Not set'}</p>
                    </div>
                )}
                {attributes.buttonText && (
                    <a
                        href={attributes.buttonUrl || '#'}
                        className="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded inline-block mt-4 transition-colors"
                    >
                        {attributes.buttonText}
                    </a>
                )}
                {attributes.imageUrl && (
                    <div className="mt-4">
                        <img
                            src={attributes.imageUrl || "/placeholder.svg"}
                            alt="Block Image"
                            className="max-w-full h-auto rounded"
                        />
                    </div>
                )}
            </div>
        </div>
    );
}
