/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./**/*.html",
    "./**/*.js",
    "./src/**/*.{js,jsx,ts,tsx}",
    "./assets/**/*.js",
    "*.{js,ts,jsx,tsx,mdx}",
  ],

  theme: {
    extend: {
      // Your @theme variables will be automatically picked up from CSS
    },
  },

  safelist: [
    "wp-block-*",
    "has-*-color",
    "has-*-background-color",
    "has-*-font-size",
    "alignwide",
    "alignfull",
    "aligncenter",
    "alignleft",
    "alignright",
    "current-menu-item",
    "current-menu-parent",
    "menu-item-has-children",
    "sub-menu",
    "sticky",
    "bypostauthor",
    "wp-caption",
    "gallery-caption",
    "screen-reader-text",
  ],

  plugins: [],
};
