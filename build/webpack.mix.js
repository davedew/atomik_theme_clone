let mix = require('laravel-mix');

mix.webpackConfig({
    // resolve: {
    //     symlinks: false
    // },
    // externals: {
    //     jquery: 'jQuery',
    //     bootstrap: true,
    //     vue: 'Vue',
    //     moment: 'moment'
    // },
    module: {
        rules: [
            {
                // test: /\.jsx?$/,
                // exclude: /(bower_components|node_modules\/v-calendar)/,
                // use: [
                //     {
                //         loader: 'babel-loader',
                //         options: Config.babel()
                //     }
                // ],
                resolve: {
                    alias: {
                        "concretecms-bedrock": "@concretecms/bedrock"
                    }
                }
            }
        ],
    },
})

mix
    // .sass('../themes/atomik_clone/css/presets/default/main.scss', 'css/skins/default.css')
    // .sass('../themes/atomik_clone/css/presets/rustic-elegance/main.scss', 'css/skins/rustic-elegance.css')
    .sass('assets/scss/presets/default/main.scss', 'css/skins/default.css')
    .sass('assets/scss/presets/rustic-elegance/main.scss', 'css/skins/rustic-elegance.css')
    .js('assets/js/main.js', '').vue();

mix.options({
    processCssUrls: false
});

mix.setPublicPath('../themes/atomik_clone');