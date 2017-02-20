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

    /* auth */
    .js('resources/assets/js/auth/login.js', 'public/js/auth')
    .js('resources/assets/js/auth/register.js', 'public/js/auth')
    .js('resources/assets/js/auth/forgot.js', 'public/js/auth')

    /* product */
    .js('resources/assets/js/app/product/index.js', 'public/js/app/product')

    /* alert */
    .js('resources/assets/js/app/alert/index.js', 'public/js/app/alert')

    /* report */
    .js('resources/assets/js/app/report/index.js', 'public/js/app/report')

    /* user management */
    .js('resources/assets/js/app/user_management/user/index.js', 'public/js/app/user')
    .js('resources/assets/js/app/user_management/user/create.js', 'public/js/app/user')
    .js('resources/assets/js/app/user_management/user/edit.js', 'public/js/app/user')
    .js('resources/assets/js/app/user_management/user/show.js', 'public/js/app/user')


    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/auth.scss', 'public/css')

    .copy('resources/assets/images', 'public/images')

    .sourceMaps()
    .version();
