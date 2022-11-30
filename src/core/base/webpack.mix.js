let mix = require('laravel-mix');
let glob = require('glob');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'src/core/' + directory;
const dist = 'public/vendor/core/core/' + directory;

glob.sync(source + '/resources/assets/sass/base/themes/*.scss').forEach(item => {
    mix.sass(item, dist + '/css/themes');
});


mix
    .sass(source + '/resources/assets/sass/core.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/custom/system-info.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/custom/email.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/custom/error-pages.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/rtl.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/tree-category.scss', dist + '/css')

    .js(source + '/resources/assets/js/app.js', dist + '/js')
    .js(source + '/resources/assets/js/core.js', dist + '/js')
    .js(source + '/resources/assets/js/editor.js', dist + '/js')
    .js(source + '/resources/assets/js/cache.js', dist + '/js')
    .js(source + '/resources/assets/js/tags.js', dist + '/js')
    .js(source + '/resources/assets/js/form/phone-number-field.js', dist + '/js')
    .js(source + '/resources/assets/js/system-info.js', dist + '/js')
    .js(source + '/resources/assets/js/tree-category.js', dist + '/js')
    .js(source + '/resources/assets/js/cleanup.js', dist + '/js');

mix
    .js(source + '/resources/assets/js/vue-app.js', dist + '/js').vue()
    .js(source + '/resources/assets/js/repeater-field.js', dist + '/js').vue();

mix
    .copy(dist + '/css/core.css', source + '/public/css')
    .copy(dist + '/css/system-info.css', source + '/public/css')
    .copy(dist + '/css/email.css', source + '/public/css')
    .copy(dist + '/css/error-pages.css', source + '/public/css')
    .copy(dist + '/css/rtl.css', source + '/public/css')
    .copy(dist + '/css/tree-category.css', source + '/public/css')
    .copy(dist + '/js/app.js', source + '/public/js')
    .copy(dist + '/js/core.js', source + '/public/js')
    .copy(dist + '/js/vue-app.js', source + '/public/js')
    .copy(dist + '/js/editor.js', source + '/public/js')
    .copy(dist + '/js/cache.js', source + '/public/js')
    .copy(dist + '/js/tags.js', source + '/public/js')
    .copy(dist + '/js/phone-number-field.js', source + '/public/js')
    .copy(dist + '/js/system-info.js', source + '/public/js')
    .copy(dist + '/js/repeater-field.js', source + '/public/js')
    .copy(dist + '/js/tree-category.js', source + '/public/js')
    .copy(dist + '/js/cleanup.js', source + '/public/js')
