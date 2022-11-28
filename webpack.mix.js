let mix = require('laravel-mix');
let glob = require('glob');

mix.options({
    processCssUrls: false,
    clearConsole: true,
    terser: {
        extractComments: false,
    }
});

// Run all webpack.mix.js in app
// glob.sync('./src/**/**/webpack.mix.js').forEach(item => require(item));

// require('./src/packages/[package]/webpack.mix.js');

require('./src/core/media/webpack.mix');

// require('./src/core/media/webpack.mix');