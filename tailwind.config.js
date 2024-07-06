/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          300: '#5fb9e5',
          500: '#36a7df',
          900: '#1d2241',
        },
      },
    },
  },
  plugins: [],
}
