const { mix } = require('laravel-mix');

mix.js('resources/assets/js/laravel-mentions.js', 'dist/js')
   .sass('resources/assets/sass/laravel-mentions.scss', 'dist/css');
