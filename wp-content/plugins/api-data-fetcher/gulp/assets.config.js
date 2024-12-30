/*
  Project Configuration
  --------------------
  This file defines configuration settings for a web project, specifying source
  and destination paths for various assets such as CSS, JavaScript, SVGs, and more.
  These settings are used throughout the project's build process to ensure
  consistency and easy maintenance.

  Paths:
  - `srcPath` defines the root source directory path.
  - `destPath` defines the root destination directory path.

  Asset Categories:
  - `assets`: Configuration for general project assets.
  - `svgs`: Configuration for SVG files.
  - `css`: Configuration for CSS and Sass files.
  - `js`: Configuration for JavaScript files.
  - `jsLibs`: Configuration for JavaScript libraries.

  Note:
  - Source and destination paths are constructed based on `srcPath` and `destPath`.
*/

// Configuration Paths
const srcPath = "./src";
const destPath = "./dist";

// Export configuration settings
module.exports = {
  assets: {
    src: `${srcPath}/assets`,
    dest: `${destPath}/assets`,
  },
  css: {
    src: `${srcPath}/css`,
    entryFilename: "main.scss",
    dest: `${destPath}/css`,
    outputFilename: "style.css",
  },
  fonts: {
    src: `${srcPath}/fonts`,
    dest: `${destPath}/fonts`,
  },
  js: {
    src: `${srcPath}/js`,
    dest: `${destPath}/js`,
  },
  jsLibs: {
    src: `${srcPath}/lib`,
  },
};
