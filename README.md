## Description

This is an attempt to copy the Atomik theme provided with the Concrete CMS installation in to it's own installable package for designers to modify as they would like.  

---

## Installation

Using your terminal, navigate to your Concrete CMS packages folder then: 

```shell
git clone https://github.com/davedew/atomik_theme_clone.git
```

Proceed to install the package in Concrete's Dashboard > Extend Concrete page.

---

## Personalize for your project

- Change the icon.png (97px x 97px) in the packages/atomik_theme_clone directory.
- Change the thumbnail.png (360px x 270px) in the packages/atomik_theme_clone/themes/atomik_clone directory.
- To change the name and descriptions, for my example below I want my theme to be named "**Rock Solid**":
  -  **Directory names**
     - packages/theme_rock_solid
     - packages/theme_rock_solid/themes/rock_solid
   - **Namespaces & Use Statements**
     - **File:** packages/theme_rock_solid/controller.php
       - namespace Concrete\Package\ThemeRockSolid;
     - **File:** packages/theme_rock_solid/themes/rock_solid/page_theme.php
       - namespace Concrete\Package\ThemeRockSolid\Theme\RockSolid;
       - use Concrete\Package\AtomikThemeClone\AtomikCloneDocumentationProvider change to: 
         - use Concrete\Package\ThemeRockSolid\AtomikCloneDocumentationProvider;
     - **File:** packages/theme_rock_solid/src/Concrete/AtomikCloneDocumentationProvider.php
       - namespace Concrete\Package\ThemeRockSolid;
       - use Concrete\Package\AtomikThemeClone\Theme\AtomikClone\PageTheme change to: 
         - use Concrete\Package\ThemeRockSolid\Theme\RockSolid\PageTheme;
   - **Names and Descriptions**
     - **File:** packages/theme_rock_solid/controller.php
       - protected $pkgHandle = 'theme_rock_solid';
       - protected $themePath = 'themes/rock_solid/';
       - protected $themeName = 'Rock Solid';
       - protected $themeHandle = 'rock_solid';
       - public function getPackageDescription: 
       - public function getEntityManagerProvider > Change the Concrete\Package\AtomikTheme to Concrete\Package\RockSolid
     - **File:** packages/theme_rock_solid/themes/rock_solid/page_theme.php
       - public function getThemeName()
       - public function getThemeDescription()
     - **File:** packages/theme_rock_solid/build/webpack.mix.js
       - mix.setPublicPath('../themes/rock_solid');
   - **Other**
     - **File:** packages/theme_rock_solid/content.xml
       - Change the <theme handle="rock_solid">
       - Change all references for package to look like this: package="theme_rock_solid"
---

## Use / Editing JS and CSS

When making changes to the JavaScript, make your changes in the *[build/assets/js/main.js](build/assets/js/main.js)* file.  For SCSS make edits in the *[build/assets/scss](build/assets/scss)* folder.  The main scss file if found at *[build/assets/scss/presets/default/main.scss](build/assets/scss/presets/default/main.scss)*. Rebuild the JS and CSS files in the package by using your terminal, in the build folder with **npx mix** or **npx mix --production** for minified versions. These will generate the main.js and css files in your theme folder.

---

## Node / NPM / Laravel Mix Build CSS / JavaScript

Make sure to install your node modules to start in the [build](build/) directory:

```
npm install
```

If you do not have npm, you'll need to install [Node Js](https://nodejs.org/en/).

In [build](build/) you will see the Laravel Mix setup.  You should be able to use the following documentation from [laravel-mix/docs/cli.md](https://github.com/laravel-mix/laravel-mix/blob/master/docs/cli.md).

**Note:** The package.json was created from my environment.  You might want to start over from your own.  You can do so with the following:

Remove the **package.json**, **package-lock.json** (if exists), and the **node_modules** (if exists) folder and start over with the following:

I'm referencing Laravel Mix's docs here: [https://github.com/laravel-mix/laravel-mix/blob/master/docs/installation.md](https://github.com/laravel-mix/laravel-mix/blob/master/docs/installation.md)

In the **[build](build/)** folder do the following:

1. npm init -y
2. npm install laravel-mix --save-dev
3. npm install @concretecms/bedrock
4. Leave the webpack.mix.js and all other files alone, then run 
5. npx mix

---

## Laravel Mix CLI:

To build assets for development, reach for the `npx mix` command. Mix will then read your `webpack.mix.js` configuration file, and compile your assets.

```
npx mix
```

#### Watch Assets for Changes

Particularly for larger projects, compilation can take a bit of time. For this reason, it's highly recommended that you instead leverage webpack's ability to watch your filesystem for changes. The `npx mix watch` command will handle this for you. Now, each time you update a file, Mix will automatically recompile the file and rebuild your bundle. 

```
npx mix watch
```

#### Polling

In certain situations, webpack may not automatically detect changes. An example of this is when you're on an NFS volume inside virtualbox. If this is a problem, pass the `--watch-options-poll` option directly to webpack-cli to turn on manual polling. 
 
 ```
 npx mix watch -- --watch-options-poll=1000
```

Of course, you can add this to a build script within your `package.json` file.

#### Hot Module Replacement

Hot module replacement is a webpack featured that gives supporting modules the ability to "live update" in certain situations. A live-update is when your application refreshes without requiring a page reload. In fact, this is what powers Vue's live updates when developing. To turn this feature on, include the `--hot` flag. 

```
npx mix watch --hot
```

### Compiling for Production

When it comes time to build your assets for a production environment, Mix will set the appropriate webpack options, minify your source code, and optionally version your assets based on your Mix configuration file (`webpack.mix.js`). To build assets for production, include the `--production` flag - or the alias `-p` - to the Mix CLI. Mix will take care of the rest!

```
npx mix --production
```

#### Customize the Mix Configuration Path

You may customise the location of your `webpack.mix.js` file by using the `--mix-config` option. For example, if you wish to load your `webpack.mix.js` file from a nested `build` directory, here's how:
 
 ```
 npx mix --mix-config=build/webpack.mix.js --production
```

### Pass Options to Webpack-CLI

If you end any `mix` call with two dashes (`--`), anything after it will be passed through to webpack-cli. For example, you can pass environment variables using webpack-cli's `--env` option: 

```
npx mix -- --env foo=bar
```



