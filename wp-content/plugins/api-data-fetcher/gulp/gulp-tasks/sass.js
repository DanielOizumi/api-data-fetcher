const gulp = require("gulp");
const sourcemaps = require("gulp-sourcemaps");
const rename = require("gulp-rename");
const sass = require("gulp-dart-sass");
const cfg = require("../assets.config.js");

// Error handling function
function handleError(err) {
  console.error(err.toString());
  this.emit("end");
}

async function processSass() {
  // Dynamic import of ESM modules
  const cleanCSS = (await import("gulp-clean-css")).default;
  const autoprefixer = (await import("gulp-autoprefixer")).default;

  return gulp
    .src(`${cfg.css.src}/${cfg.css.entryFilename}`)
    .pipe(sourcemaps.init())
    .pipe(sass().on("error", handleError))
    .pipe(autoprefixer({ cascade: false })) // Add vendor prefixes
    .pipe(cleanCSS()) // Minify CSS for better performance
    .pipe(
      rename((path) => {
        const [name, ext] = cfg.css.outputFilename.split(".");
        path.basename = name;
        path.extname = `.${ext}`;
      })
    )
    .pipe(sourcemaps.write(".")) // Generate sourcemaps for debugging
    .pipe(gulp.dest(`${cfg.css.dest}`));
}

module.exports = processSass;
