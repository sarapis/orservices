(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/jquery-placeholder', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginJqueryPlaceholder = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'placeholder';

  var Placeholder = function (_Plugin) {
    babelHelpers.inherits(Placeholder, _Plugin);

    function Placeholder() {
      babelHelpers.classCallCheck(this, Placeholder);
      return babelHelpers.possibleConstructorReturn(this, (Placeholder.__proto__ || Object.getPrototypeOf(Placeholder)).apply(this, arguments));
    }

    babelHelpers.createClass(Placeholder, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.placeholder) {
          return;
        }

        var $el = this.$el;

        $el.placeholder();
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return Placeholder;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Placeholder);

  exports.default = Placeholder;
});
