/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./includes/**/*.php",
    "./templates/**/*.php",
    "./assets/js/**/*.js",
    // Add other paths that contain Tailwind classes
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
