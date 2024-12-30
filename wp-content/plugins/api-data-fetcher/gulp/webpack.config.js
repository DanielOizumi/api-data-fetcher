const path = require("path");
const assetsConfig = require("./assets.config");
const TerserPlugin = require("terser-webpack-plugin");

module.exports = {
  // Entry point for webpack, typically your main JavaScript file
  entry: `${assetsConfig.js.src}/main.js`,

  // Output configuration
  output: {
    filename: "./main.js", // Output filename
    path: path.resolve(__dirname, `${assetsConfig.js.dest}`), // Output directory
  },

  // Set the context for resolving entry point and loaders
  context: path.resolve(__dirname, "."),

  // Module rules for loaders
  module: {
    rules: [
      {
        test: /\.m?js$/, // Match .js and .mjs files
        exclude: /(node_modules|bower_components)/, // Exclude node_modules and bower_components
        use: {
          loader: "babel-loader", // Use babel-loader for transpiling JavaScript
          options: {
            presets: ["@babel/preset-env"], // Babel preset for compiling ES6+ to ES5
          },
        },
      },
      {
        test: /\.css$/, // Match .css files
        use: ["style-loader", "css-loader"], // Use style-loader and css-loader for handling CSS
      },
    ],
  },

  // Optimization configuration
  optimization: {
    minimize: true, // Enable minimization
    minimizer: [new TerserPlugin()], // Use TerserPlugin for minification
    splitChunks: {
      chunks: "all", // Code splitting
    },
  },
};
