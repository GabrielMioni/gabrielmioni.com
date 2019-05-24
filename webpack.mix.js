const mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .babel('public/js/app.js', 'public/js/app.js')
   .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/frontend/app.js', 'public/js/frontend/')
  .babel('public/js/frontend/app.js', 'public/js/frontend/app.js');
