const generateSizes = () => {
  return [
    { width: 320, suffix: "-320w" },
    { width: 480, suffix: "-480w" },
    { width: 640, suffix: "-640w" },
    { width: 768, suffix: "-768w" },
    { width: 1024, suffix: "-1024w" },
    { width: 1280, suffix: "-1280w" },
    { width: 1600, suffix: "-1600w" },
    { width: 1920, suffix: "-1920w" },
    { width: 2560, suffix: "-2560w" },
  ];
};

module.exports = { generateSizes };
