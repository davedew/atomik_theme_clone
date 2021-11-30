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

## Use / Editing JS and CSS

When making changes to the JavaScript, make your changes in the build/assets/js/main.js file.  For SCSS make edits in the themes/atomik_clone/css/scss folder.  Rebuild the JS and CSS files in the package by using your terminal, in the build folder with **npx mix** or **npx mix --production** for minified versions. 

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

In the **build** folder do the following:

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



