(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/ladda', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginLadda = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'ladda'; // import $ from 'jquery';

  var LaddaPlugin = function (_Plugin) {
    babelHelpers.inherits(LaddaPlugin, _Plugin);

    function LaddaPlugin() {
      babelHelpers.classCallCheck(this, LaddaPlugin);
      return babelHelpers.possibleConstructorReturn(this, (LaddaPlugin.__proto__ || Object.getPrototypeOf(LaddaPlugin)).apply(this, arguments));
    }

    babelHelpers.createClass(LaddaPlugin, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (typeof Ladda === 'undefined') {
          return;
        }

        if (this.options.type === 'progress') {
          this.options.callback = function (instance) {
            var progress = 0;
            var interval = setInterval(function () {
              progress = Math.min(progress + Math.random() * 0.1, 1);
              instance.setProgress(progress);

              if (progress === 1) {
                instance.stop();
                clearInterval(interval);
              }
            }, 200);
          };
        }
        Ladda.bind(this.$el[0], this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          type: 'normal',
          timeout: 2000
        };
      }
    }]);
    return LaddaPlugin;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, LaddaPlugin);

  exports.default = LaddaPlugin;
});
