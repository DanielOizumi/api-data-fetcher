const gulp = require("gulp");
const webpack = require("webpack");
const webpackConfig = require("../webpack.config.js");

function webpackScripts() {
  webpackConfig.mode = "development";
  return new Promise((resolve, reject) => {
    webpack(webpackConfig, (err, stats) => {
      if (err) {
        console.error("Webpack error:", err);
        return reject(err);
      }
      console.log(
        stats.toString({
          colors: true, // Improved readability with color
        })
      );
      resolve();
    });
  });
}

module.exports = webpackScripts;
