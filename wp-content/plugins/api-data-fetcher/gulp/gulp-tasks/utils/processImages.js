const path = require("path");
const sharp = require("sharp");
const through2 = require("through2");
const { generateSizes } = require("./generateSizes");
const { generateSrcsetHtml } = require("./generateSrcsetHtml");
const cfg = require("../../assets.config.js");

const VALID_IMAGE_EXTENSIONS = [".png", ".jpg", ".jpeg", ".webp"];

const isProcessableImage = (file) => {
  const ext = path.extname(file.path).toLowerCase();
  return VALID_IMAGE_EXTENSIONS.includes(ext);
};

const getOutputBasePath = (file) => {
  const relativePath = path.relative(cfg.assets.src, file.path);
  return path.join(cfg.assets.dest, relativePath.replace(path.extname(file.path), ""));
};

const processImageSizes = async (file, outputBasePath, sizes) => {
  const promises = sizes.map(async (size) => {
    const outputPath = `${outputBasePath}${size.suffix}.webp`;
    await sharp(file.path)
      .resize(size.width)
      .toFormat("webp", { quality: 90 })
      .toFile(outputPath);
  });

  await Promise.all(promises);
  generateSrcsetHtml(file, outputBasePath, sizes);
};

// New function to process a single image to WebP
const processSingleImageToWebP = async (file, outputBasePath) => {
  const outputPath = `${outputBasePath}.webp`;
  await sharp(file.path)
    .toFormat("webp", { quality: 75 })
    .toFile(outputPath);
  console.log("Image processed to WebP:", file.relative);
};


// Function to process image files and convert them to WebP format
const processImages = () => {
  const sizes = generateSizes(); // Get the defined sizes
  
  return through2.obj(async (file, _, cb) => {
    try {
      // Check if the file is processable
      if (!isProcessableImage(file)) {
        cb(null, file); // Skip unsupported files
        return;
      }

      const outputBasePath = getOutputBasePath(file);

      // Check if the file is inside the images folder
      if (file.path.includes("/assets/images/")) {
        await processImageSizes(file, outputBasePath, sizes); // Process each size
        await processSingleImageToWebP(file, outputBasePath);
        cb(null, file); // Call cb with the original file
      } else {
        // If not inside the images folder, create a single WebP file using the new function
        await processSingleImageToWebP(file, outputBasePath);
        cb(null, file); // Call cb with the original file
      }
    } catch (err) {
      console.error(`Error processing ${file.relative}:`, err);
      cb(err); // Call cb with the error
    }
  });
};

module.exports = {
  processImages,
  isProcessableImage,
};
