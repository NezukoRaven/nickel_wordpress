const mix = require('laravel-mix');

var LiveReloadWebpackPlugin = require('@kooneko/livereload-webpack-plugin');

mix.webpackConfig({
    plugins: [new LiveReloadWebpackPlugin()]
});

// mix.sass('assets/scss/app.scss', 'assets/css/app.css');
mix.js('assets/js/components/form_settings.js', 'assets/js/components/form_settings.min.js');

mix.js('assets/js/components/quote_list/quote_list.js', 'assets/js/components/quote_list/quote_list.min.js');
mix.js('assets/js/components/mini_quote_list/render_mini_quote_list.js', 'assets/js/components/mini_quote_list/render_mini_quote_list.min.js');

mix.js('assets/js/front_script.js', 'assets/js/front_script.min.js');
mix.js('assets/js/components/quote_list/add_to_quote.js', 'assets/js/components/quote_list/add_to_quote.min.js');
mix.js('assets/js/components/quote_list/quote_items.js', 'assets/js/components/quote_list/quote_items.min.js');
mix.sass('assets/scss/app.scss', 'assets/css/app.css');



