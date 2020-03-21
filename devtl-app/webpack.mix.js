const mix = require('laravel-mix');
let LaravelMixFilenameVersioning = require('laravel-mix-filename-versioning');

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
    .styles([
        'resources/vendor/open-iconic/css/open-iconic-bootstrap.min.css',
        'resources/css/custom.css'
    ], 'public/css/all.css')
    .version();

if (mix.inProduction()) {
  mix.webpackConfig({
    plugins: [
      new LaravelMixFilenameVersioning
    ]
  });
}
