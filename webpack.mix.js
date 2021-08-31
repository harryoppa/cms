let glob = require('glob');
let mix = require('laravel-mix');
let arg = process.argv[process.argv.length - 1];
let configs = [
    // './src/*/webpack.mix.js',
    './src/**/*/webpack.mix.js'
];


mix.options({
    processCssUrls: false,
    clearConsole: true,
    terser: {
        extractComments: false,
    }
});

/**
 * When Arguments has module pathname
 * Only build for that module
 *
 * eg. npm run production -- --file=plugins/blog
 */
if (arg.match(/file=/)) {
    arg = arg.split('=')[1];

    require('./src/' + arg + '/webpack.mix.js');

} else {

    configs.forEach(config => glob.sync(config).forEach(item => require(item)));
}
