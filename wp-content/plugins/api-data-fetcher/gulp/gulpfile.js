const gulp = require("gulp");
const processSass = require("./gulp-tasks/sass");
const webpackScripts = require("./gulp-tasks/js");
const processAssets = require("./gulp-tasks/images");
const processFonts = require("./gulp-tasks/fonts");
const watchTask = require("./gulp-tasks/watch");

// Build tasks
const buildTask = gulp.parallel(processSass, webpackScripts, processAssets, processFonts);

// Default task (build + watch)
gulp.task("default", gulp.series(buildTask, watchTask));

// Build task (no watch)
gulp.task("build", buildTask);

// Watch task
gulp.task("watch", watchTask);
