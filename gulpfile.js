var elixir = require('laravel-elixir');
require('laravel-elixir-vueify');

var paths = {
    "assets": "./resources/assets/bower_components/",
    "js": "./resources/assets/js/",
    "base": "./resources/assets/"
}

elixir(function(mix) {
     mix
     .sass(
        'app.scss',
        'public/css/main.css',
        { includePaths:
            [
                paths.assets + 'bourbon/',
                paths.assets + 'base/',
                paths.assets + 'font-awesome/scss/',
                paths.assets + 'neat/',
                paths.base + 'custom/',
            ]
        })

    .copy (
            paths.assets + 'font-awesome/fonts', 'public/fonts'
        )   

    .styles([
            paths.assets + 'normalize-css/normalize.css',
            paths.assets + 'animate.css/animate.css',
            'public/css/main.css'
            ], 'public/css/app.css', './')

    .version('public/css/app.css')

    .scripts ([
            paths.assets + 'jquery/dist/jquery.min.js',
            paths.js + 'app.js'
        ], "public/js/app.js", "./")

    .browserify('main.js')
    .copy(
        paths.js + 'map.js',
        'public/js/map.js'
        )

});
