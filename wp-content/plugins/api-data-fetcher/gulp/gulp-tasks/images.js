const gulp = require("gulp");
const gulpif = require("gulp-if");
const { processImages, isProcessableImage } = require("./utils/processImages");
const cfg = require("../assets.config.js");

function processAssets() {
  return gulp
    .src(`${cfg.assets.src}/**/*`)
    .pipe(
      // Processing webp, png, jpg and jpeg files
      gulpif((file) => isProcessableImage(file), processImages())
    )
    .pipe(
      // Copy other files without processing (eg. svg, gif...)
      gulpif((file) => !isProcessableImage(file), gulp.dest(cfg.assets.dest))
    );
}

module.exports = processAssets;
