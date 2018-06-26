(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/selectable', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginSelectable = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'selectable';

  var Selectable = function (_Plugin) {
    babelHelpers.inherits(Selectable, _Plugin);

    function Selectable() {
      babelHelpers.classCallCheck(this, Selectable);
      return babelHelpers.possibleConstructorReturn(this, (Selectable.__proto__ || Object.getPrototypeOf(Selectable)).apply(this, arguments));
    }

    babelHelpers.createClass(Selectable, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.asSelectable) {
          return;
        }

        var $el = this.$el;

        $el.asSelectable(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          allSelector: '.selectable-all',
          itemSelector: '.selectable-item',
          rowSelector: 'tr',
          rowSelectable: false,
          rowActiveClass: 'active',
          onChange: null
        };
      }
    }]);
    return Selectable;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Selectable);

  exports.default = Selectable;
});
