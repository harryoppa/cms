let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'src/core/' + directory;
const dist = 'public/vendor/core/core/' + directory;

mix.js(source + '/resources/assets/js/dashboard.js', dist + '/js');

mix.sass(source + '/resources/assets/sass/dashboard.scss', dist + '/css')

    .copyDirectory(dist + '/js', source + '/public/js')
    .copyDirectory(dist + '/css', source + '/public/css');
