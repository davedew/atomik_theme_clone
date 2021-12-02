let mix = require('laravel-mix');

mix.webpackConfig({
    module: {
        rules: [
            {
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
    .sass('assets/scss/presets/default/main.scss', 'css/skins/default.css')
    .sass('assets/scss/presets/rustic-elegance/main.scss', 'css/skins/rustic-elegance.css')
    .js('assets/js/main.js', '').vue();

mix.options({
    processCssUrls: false
});

mix.setPublicPath('../themes/atomik_clone');