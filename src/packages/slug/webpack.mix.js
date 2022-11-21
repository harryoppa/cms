let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'src/packages/' + directory;
const dist = 'public/vendor/core/packages/' + directory;

mix
    .js(source + '/resources/assets/js/slug.js', dist + '/js')
    .sass(source + '/resources/assets/sass/slug.scss', dist + '/css')

    .copy(dist + '/js/slug.js', source + '/public/js')
    .copy(dist + '/css/slug.css', source + '/public/css');
