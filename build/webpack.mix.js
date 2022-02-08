let mix = require("laravel-mix");

mix.webpackConfig({
  externals: {
    jquery: "jQuery",
    bootstrap: true,
    vue: "Vue",
    moment: "moment",
  }
});
mix
  .setPublicPath("../themes/atomik_clone") // Change this if you change your theme naming / file structure.
  .sass("assets/scss/presets/default/main.scss", "css/skins/default.css")
  // Comment this next sass definition out if you don't need an extra color option.  It just adds to the compile time.
  .sass("assets/scss/presets/rustic-elegance/main.scss", "css/skins/rustic-elegance.css")
  .js("assets/js/main.js", "").vue();

// mix.browserSync({
//     proxy: 'c59.test' // You need to change this to your local dev URL for npm run watch or npx mix watch
// });
