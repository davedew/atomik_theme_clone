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
    // Comment this next line out if you don't need an extra color option.  It just adds to the compile time.
    .sass('assets/scss/presets/rustic-elegance/main.scss', 'css/skins/rustic-elegance.css')
    .js('assets/js/main.js', '').vue();

mix.options({
    processCssUrls: false
});

mix.setPublicPath('../themes/atomik_clone'); // Change this if you change your theme naming / file structure.

mix.browserSync({
    proxy: 'atomik-clone.test' // You need to change this to your local dev URL for npm run watch or npx mix watch
});