// tailwind.config.js
module.exports = {
  darkMode: 'class', // Enable class-based dark mode
  content: [
    './index.html', // Make sure to include the entry HTML file
    './src/**/*.{vue,js,ts,jsx,tsx}', // Make sure to include your Vue components and any other files that might contain Tailwind classes
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
