module.exports = {
  darkMode: 'class',
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/css/**/*.css',
    './resources/sass/**/*.scss',
    './node_modules/flowbite', // Adicione esta linha
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('flowbite/plugin') // Adicione esta linha
  ],
}