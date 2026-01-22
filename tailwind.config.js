/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', 'Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
  // Ignore Livewire attributes
  safelist: [],
  // CSS processing options
  corePlugins: {
    // Disable plugins that cause warnings
    preflight: true,
  },
}