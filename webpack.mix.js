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
    .js('resources/assets/js/auth/reset.js', 'public/js/auth')

    /* app pref */
    .js('resources/assets/js/app/admin/app_pref/index.js', 'public/js/app/admin/app_pref')
    /* user activity log */
    .js('resources/assets/js/app/admin/user_activity_log/index.js', 'public/js/app/admin/user_activity_log')

    /* account settings */
    .js('resources/assets/js/app/account_settings/index.js', 'public/js/app/account_settings')

    /* dashboard */
    .js('resources/assets/js/app/dashboard/index.js', 'public/js/app/dashboard')

    /* product */
    .js('resources/assets/js/app/product/index.js', 'public/js/app/product')

    /* alert */
    .js('resources/assets/js/app/alert/index.js', 'public/js/app/alert')

    /* report */
    .js('resources/assets/js/app/report/index.js', 'public/js/app/report')

    /* domain */
    .js('resources/assets/js/app/url_management/domain/index.js', 'public/js/app/domain')
    .js('resources/assets/js/app/url_management/domain/create.js', 'public/js/app/domain')
    .js('resources/assets/js/app/url_management/domain/show.js', 'public/js/app/domain')
    .js('resources/assets/js/app/url_management/domain/edit.js', 'public/js/app/domain')

    /* domain meta */
    .js('resources/assets/js/app/url_management/domain_meta/edit.js', 'public/js/app/domain_meta')

    /* item */
    .js('resources/assets/js/app/url_management/item/index.js', 'public/js/app/item')

    /* item meta */
    .js('resources/assets/js/app/url_management/item_meta/index.js', 'public/js/app/item_meta')

    /* url */
    .js('resources/assets/js/app/url_management/url/index.js', 'public/js/app/url')
    .js('resources/assets/js/app/url_management/url/create.js', 'public/js/app/url')
    .js('resources/assets/js/app/url_management/url/edit.js', 'public/js/app/url')

    /* user management */
    /* user */
    .js('resources/assets/js/app/user_management/user/index.js', 'public/js/app/user')
    .js('resources/assets/js/app/user_management/user/create.js', 'public/js/app/user')
    .js('resources/assets/js/app/user_management/user/edit.js', 'public/js/app/user')
    .js('resources/assets/js/app/user_management/user/show.js', 'public/js/app/user')
    /* group */
    .js('resources/assets/js/app/user_management/group/index.js', 'public/js/app/group')
    .js('resources/assets/js/app/user_management/group/create.js', 'public/js/app/group')
    .js('resources/assets/js/app/user_management/group/edit.js', 'public/js/app/group')
    .js('resources/assets/js/app/user_management/group/show.js', 'public/js/app/group')
    /* role */
    .js('resources/assets/js/app/user_management/role/index.js', 'public/js/app/role')
    .js('resources/assets/js/app/user_management/role/create.js', 'public/js/app/role')
    .js('resources/assets/js/app/user_management/role/edit.js', 'public/js/app/role')
    .js('resources/assets/js/app/user_management/role/show.js', 'public/js/app/role')
    /* permission */
    .js('resources/assets/js/app/user_management/permission/index.js', 'public/js/app/permission')
    .js('resources/assets/js/app/user_management/permission/create.js', 'public/js/app/permission')
    .js('resources/assets/js/app/user_management/permission/edit.js', 'public/js/app/permission')
    .js('resources/assets/js/app/user_management/permission/show.js', 'public/js/app/permission')


    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/auth.scss', 'public/css')

    .copy('resources/assets/images', 'public/images')

    .sourceMaps()
    .version();
