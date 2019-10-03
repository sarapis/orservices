(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/formatter', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginFormatter = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'formatter';

  var Formatter = function (_Plugin) {
    babelHelpers.inherits(Formatter, _Plugin);

    function Formatter() {
      babelHelpers.classCallCheck(this, Formatter);
      return babelHelpers.possibleConstructorReturn(this, (Formatter.__proto__ || Object.getPrototypeOf(Formatter)).apply(this, arguments));
    }

    babelHelpers.createClass(Formatter, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.formatter) {
          return;
        }

        var browserName = navigator.userAgent.toLowerCase(),
            ieOptions = void 0;

        if (/msie/i.test(browserName) && !/opera/.test(browserName)) {
          ieOptions = {
            persistent: false
          };
        } else {
          ieOptions = {};
        }

        var $el = this.$el,
            options = this.options;

        if (options.pattern) {
          options.pattern = options.pattern.replace(/\[\[/g, '{{').replace(/\]\]/g, '}}');
        }
        $el.formatter(options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          persistent: true
        };
      }
    }]);
    return Formatter;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Formatter);

  exports.default = Formatter;
});
