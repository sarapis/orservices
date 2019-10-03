module.exports = function () {
  "use strict";

  return {
    options: {
      precision: 6,
      sourcemap: 'auto',
      style: 'expanded',
      trace: true,
      bundleExec: true,
      includePaths: [
        '<%= config.source.sass %>',
        '<%= config.bootstrap.sass %>',
        '<%= config.bootstrap.mixins %>'
      ]
    },
    compileBootstrap: {
      src: '<%= config.source.sass %>/bootstrap.scss',
      dest: '<%= config.destination.css %>/bootstrap.css'
    },
    compileExtend: {
      src: '<%= config.source.sass %>/bootstrap-extend.scss',
      dest: '<%= config.destination.css %>/bootstrap-extend.css'
    },
    fonts: {
      expand: true,
      cwd: '<%= config.source.fonts %>',
      src: ['*/*.scss', '!*/_*.scss'],
      dest: '<%= config.destination.fonts %>',
      ext: '.css',
      extDot: 'last'
    },
    vendor: {
      expand: true,
      cwd: '<%= config.source.vendor %>',
      src: ['*/*.scss', '!*/settings.scss'],
      dest: '<%= config.destination.vendor %>',
      ext: '.css',
      extDot: 'last'
    }
  };
};
