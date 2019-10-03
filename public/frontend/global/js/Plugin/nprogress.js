(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/nprogress', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginNprogress = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'nprogress'; // import $ from 'jquery';

  var Nprogress = function (_Plugin) {
    babelHelpers.inherits(Nprogress, _Plugin);

    function Nprogress() {
      babelHelpers.classCallCheck(this, Nprogress);
      return babelHelpers.possibleConstructorReturn(this, (Nprogress.__proto__ || Object.getPrototypeOf(Nprogress)).apply(this, arguments));
    }

    babelHelpers.createClass(Nprogress, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (typeof NProgress === 'undefined') {
          return;
        }

        NProgress.configure(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          minimum: 0.15,
          trickleRate: 0.07,
          trickleSpeed: 360,
          showSpinner: false,
          template: '<div class="bar" role="bar"></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'
        };
      }
    }]);
    return Nprogress;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Nprogress);

  exports.default = Nprogress;
});
