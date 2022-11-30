let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'src/packages/' + directory;
const dist = 'public/vendor/core/packages/' + directory;

mix
    .js(source + '/resources/assets/js/menu.js', dist + '/js')
    .sass(source + '/resources/assets/sass/menu.scss', dist + '/css')

    .copy(dist + '/js/menu.js', source + '/public/js')
    .copy(dist + '/css/menu.css', source + '/public/css');
