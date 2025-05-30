import React, { useState } from "react"
import ReactDOM from "react-dom/client"

const divsToUpdate = document.querySelectorAll(".tailwind-update-me")

divsToUpdate.forEach(div => {
  const data = JSON.parse(div.querySelector("pre").innerText)
  const root = ReactDOM.createRoot(div)
  root.render(<OurComponent {...data} />)
  div.classList.remove("tailwind-update-me")
})

function OurComponent(props) {


console.log("propos", props)

  return (
    <div className="my-unique-plugin-wrapper-class">
     hellow
    </div>
  )
}
