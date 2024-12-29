const fs = require("fs");
const path = require("path");
const { ensureDirectoryExistence } = require("./ensureDirectoryExistence");

const generateSrcsetHtml = (file, outputBasePath, sizes) => {
  const filenameWithoutExt = path.basename(file.path, path.extname(file.path));
  const srcset = sizes
    .map((size) => `../../../gulp/${outputBasePath}${size.suffix}.webp ${size.width}w`)
    .join(", ");
  const srcsetHtml = `<img src="../../../gulp/${outputBasePath}.webp" srcset="${srcset}" alt="${filenameWithoutExt}" loading="lazy">`;

  const htmlPath = path.join(
    __dirname,
    "../../../partials/atoms/images",
    `${filenameWithoutExt}.php`
  );
  ensureDirectoryExistence(path.dirname(htmlPath));
  fs.writeFileSync(htmlPath, srcsetHtml);
};

module.exports = { generateSrcsetHtml };
