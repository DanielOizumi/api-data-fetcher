const gulp = require("gulp");
const processSass = require("./sass");
const processJS = require("./js");
const processAssets = require("./images");
const cfg = require("../assets.config.js");

function watchSass() {
  gulp.watch(
    [`${cfg.css.src}/**/*.scss`, `${cfg.assets.src}/**/*.scss`],
    processSass
  );
}

function watchJS() {
  gulp.watch([`${cfg.js.src}/**/*.js`, `${cfg.assets.src}/**/*.js`], processJS);
}

function watchAssets() {
  gulp.watch(`${cfg.assets.src}/**/*`, processAssets);
}

const watchTask = gulp.parallel(watchSass, watchJS, watchAssets);

module.exports = watchTask;
