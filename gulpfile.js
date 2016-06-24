var elixir = require('laravel-elixir');

elixir(function(mix) {

    //Combine all SCSS into CSS
    mix.sass('app.scss', 'public/resources/app.css');

    //Copy bootstrap glyphicons into public
    mix.copy(
        'node_modules/bootstrap-sass/assets/fonts/bootstrap/**',
        'public/resources/fonts'
    );

    //Combine all scripts together
    mix.scripts([
        '../../../node_modules/jquery/dist/jquery.min.js',
        '../../../node_modules/moment/min/moment.min.js',
        '../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        '../../../node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
    ], 'public/resources/app.js');
});
