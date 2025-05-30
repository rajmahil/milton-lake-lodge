import React, { useState } from "react"
import ReactDOM from "react-dom/client"

const divsToUpdate = document.querySelectorAll(".my-block-update-me")

divsToUpdate.forEach(div => {
	const data = JSON.parse(div.querySelector("pre").innerText)
	const root = ReactDOM.createRoot(div)
	root.render(<MyBlockComponent {...data} />)
	div.classList.remove("my-block-update-me")
})

export default function MyBlockComponent(props) {
	const [showDetails, setShowDetails] = useState(false)

	return (
		<div className="my-unique-plugin-wrapper-class">
			<div className="bg-green-100 border-2 border-green-300 p-6 my-3 rounded-lg shadow-md">
				<p className="text-red-600 font-bold mb-4">
					🎉 DYNAMIC BLOCK - Updates Instantly!
				</p>

				{props.heading && (
					<h2 className="text-2xl font-bold mb-4 text-gray-900">
						{props.heading}
					</h2>
				)}

				{props.subheading && (
					<p className="text-gray-600 mb-4">
						{props.subheading}
					</p>
				)}

				<button
					className="rounded bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 mr-3 mb-3 inline-block transition-colors"
					onClick={() => setShowDetails(prev => !prev)}
				>
					Toggle Details
				</button>

				{showDetails && (
					<div className="mt-4 p-4 bg-white rounded border">
						<p><strong>Heading:</strong> {props.heading || 'Not set'}</p>
						<p><strong>Subheading:</strong> {props.subheading || 'Not set'}</p>
						<p><strong>Button Text:</strong> {props.buttonText || 'Not set'}</p>
						<p><strong>Button URL:</strong> {props.buttonUrl || 'Not set'}</p>
					</div>
				)}

				{props.buttonText && (
					<a
						href={props.buttonUrl || '#'}
						className="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded inline-block mt-4 transition-colors"
					>
						{props.buttonText}
					</a>
				)}

				{props.imageUrl && (
					<div className="mt-4">
						<img
							src={props.imageUrl || "/placeholder.svg"}
							alt="Block Image"
							className="max-w-full h-auto rounded"
						/>
					</div>
				)}
			</div>
		</div>
	)
}
