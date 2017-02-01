const {mix} = require('laravel-mix');
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

mix
    .js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/auth/login.js', 'public/js')
    .js('resources/assets/js/auth/register.js', 'public/js')
    .js('resources/assets/js/auth/forgot.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/auth.scss', 'public/css')

    /* copy fonts */
    .copy('node_modules/font-awesome/fonts', 'public/fonts')
    .copy('vendor/almasaeed2010/adminlte/bootstrap/fonts', 'public/fonts')
    .copy('resources/assets/fonts', 'public/fonts')

    /* copy images */
    .copy('resources/assets/images', 'public/images')

    .version();
