let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

/*
   mix.styles([
   		'resources/assets/css/libs/bootstrap.min.css',
   		'resources/assets/css/libs/flexslider.css',
   		'resources/assets/css/libs/jquery.fancybox.css',
   		'resources/assets/css/libs/main.css',
   		'resources/assets/css/libs/responsive.css,',
   		'resources/assets/css/libs/font-icon.css',
   		'resources/assets/css/libs/animate.min.css'
   	], 'public/css/libs.css');


   mix.scripts([
   		'resources/assets/js/libs/bootstrap.min.js',
   		'resources/assets/js/libs/jquery.flexslider-min.js',
   		'resources/assets/js/libs/jquery.fancybox.pack.js',
   		'resources/assets/js/libs/retina.min.js',
   		'resources/assets/js/libs/modernizr.js',
   		'resources/assets/js/libs/main.js',
   		'resources/assets/js/libs/jquery.contact.js'
   	], 'public/js/libs.js');
*/