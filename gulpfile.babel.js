import { src, dest, watch, series, parallel } from 'gulp'

import sass from 'gulp-sass'
import sassGlob from 'gulp-sass-glob'
import cleanCss from 'gulp-clean-css'
import gulpif from 'gulp-if'
import postcss from 'gulp-postcss'
import sourcemaps from 'gulp-sourcemaps'
import imagemin from 'gulp-imagemin'
import gulpStylelint from 'gulp-stylelint'
import cssImport from 'gulp-cssimport'
import zip from "gulp-zip";

import yargs from 'yargs'
import named from 'vinyl-named'
import autoprefixer from 'autoprefixer'
import del from 'del'
import webpack from 'webpack-stream'
import browserSync from "browser-sync";

import webpackConfig from './src/build/webpack.config'
import config from './src/config'
import info from "./package.json";

const env = yargs.argv.env
const publicPath = (folder = '') => `${config.publicPath}/${folder}`

/*
 * Server Live
 * */
const server = browserSync.create();
export const serve = done => {
  server.init({
    proxy: config.proxy
  });
  done();
};
export const reload = done => {
  server.reload();
  done();
};

/*
 * Styles
 * */
sass.compiler = require('node-sass')

export const styles = () => {
  const entryStyles = config.entry.styles;
  const options = {
    includePaths: ['node_modules']
  }

  return src(entryStyles)
    .pipe(gulpif(env === 'development', sourcemaps.init()))
    .pipe(sassGlob())
    .pipe(sass({includePaths: ['node_modules'], importCss: true}).on('error', sass.logError))
    .pipe(gulpif(env === 'production', postcss([autoprefixer])))
    .pipe(gulpif(env === 'production', cleanCss({inline: ['none'], compatibility:'ie8'})))
    .pipe(gulpif(env === 'development', sourcemaps.write()))
    .pipe(cssImport(options))
    .pipe(dest(publicPath('css')))
    .pipe(server.stream());
}

export const lintCss = () => {
  return src(config.globalResources.styles)
    .pipe(gulpStylelint({
      failAfterError: true,
      reporters: [
        {formatter: 'verbose', console: true}
      ]
    }))
}

/*
 * Images
 * */
export const images = () => {
  return src(config.globalResources.images)
    .pipe(gulpif(env === 'production', imagemin()))
    .pipe(dest(publicPath('images')));
}

/*
 * Javascript
 * */
export const scripts = () => {
  return src(config.entry.js)
    .pipe(named())
    .pipe(webpack({
      config: webpackConfig
    }))
    .pipe(dest(publicPath('js')))
}

/*
 * Copy
 * */
export const copy = () => {
  const ignoreFolders = env === 'production' ? config.ignoreFoldersProduction : config.ignoreFoldersDevelopment ;

  return src(ignoreFolders)
    .pipe(dest(publicPath()));
}

/*
 * Clean
 * */
export const clean = () => del([publicPath()])
export const clean_images = () => del([publicPath('images')])

/*
 * Clean
 * */
export const compress = () => {
  return src(config.compressFolder)
    .pipe(zip(`${info.name}.zip`))
    .pipe(dest('./bundled'));
};

/*
 * Watch
 * */
export const watchForChanges = () => {
  watch([config.globalResources.styles], parallel(styles, lintCss))
  watch([config.globalResources.images], series(clean_images, images, reload))
  watch(config.ignoreFoldersDevelopment, series(copy, reload))
  watch([config.globalResources.js], series(scripts, reload))
  watch([config.globalResources.php], reload);
}

/*
 * Compilation
 * */
export const dev = series(clean, parallel(styles, images, copy, scripts), lintCss, serve, watchForChanges)
export const build = series(clean, parallel(styles, images, copy, scripts))
export const compressTheme = series(clean, compress)

export default dev
