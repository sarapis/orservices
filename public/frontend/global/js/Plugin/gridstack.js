(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/gridstack', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginGridstack = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'gridstack';

  var Gridstack = function (_Plugin) {
    babelHelpers.inherits(Gridstack, _Plugin);

    function Gridstack() {
      babelHelpers.classCallCheck(this, Gridstack);
      return babelHelpers.possibleConstructorReturn(this, (Gridstack.__proto__ || Object.getPrototypeOf(Gridstack)).apply(this, arguments));
    }

    babelHelpers.createClass(Gridstack, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.gridstack) {
          return;
        }

        var $el = this.$el;

        $el.gridstack(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          cellHeight: 80,
          verticalMargin: 20
        };
      }
    }]);
    return Gridstack;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Gridstack);

  exports.default = Gridstack;
});
