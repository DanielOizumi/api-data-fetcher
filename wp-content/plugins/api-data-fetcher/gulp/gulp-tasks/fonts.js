const gulp = require("gulp");
const cfg = require("../assets.config.js");

function processFonts() {
  return gulp
    .src(`${cfg.fonts.src}/**/*`)
    .pipe(gulp.dest(cfg.fonts.dest));
}

module.exports = processFonts;
