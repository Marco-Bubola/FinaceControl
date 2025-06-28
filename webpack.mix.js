const mix = require('laravel-mix')

mix
  .js('resources/js/app.js', 'public/js')
 .postCss('resources/css/app.css', 'public/css', [
  require('tailwindcss'),
  require('autoprefixer'),
])


 .minify('public/assets/js/soft-ui-dashboard.js');
mix.sass('public/assets/scss/soft-ui-dashboard.scss', 'public/assets/css');