/**
 * Scripts
 * configuration
 * object
 *
 */
// config
var config = require('../../config.json');
var pkg = require('../../package.json');
var banner = '/*!\n' +
          ' * ' + pkg.name + ' (' + pkg.homepage + ')\n' +
          ' * Copyright ' + new Date().getFullYear() + ' ' + pkg.author.name + '\n' +
          ' * Licensed under the ' + pkg.license + '\n' +
          ' */\n';

module.exports = {
  banner: banner,
  babel: {
    sourceMap: false,
    presets: ["es2015"],
    sourceRoot: config.source.es,
    moduleRoot: '',
    moduleIds: true,
    presets: ["es2015"],
    plugins: [["transform-es2015-modules-umd", {
      "globals": {
        "jquery": "jQuery",
        "GridMenu": "SectionGridMenu",
        "Menubar": "SectionMenubar",
        "PageAside": "SectionPageAside",
        "Sidebar": "SectionSidebar"
      }
    }], "external-helpers"]
  }
};
