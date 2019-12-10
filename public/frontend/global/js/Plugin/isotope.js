(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/isotope', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginIsotope = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'isotope';

  var Isotope = function (_Plugin) {
    babelHelpers.inherits(Isotope, _Plugin);

    function Isotope() {
      babelHelpers.classCallCheck(this, Isotope);
      return babelHelpers.possibleConstructorReturn(this, (Isotope.__proto__ || Object.getPrototypeOf(Isotope)).apply(this, arguments));
    }

    babelHelpers.createClass(Isotope, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        var _this2 = this;

        if (typeof _jquery2.default.fn.isotope === 'undefined') {
          return;
        }

        var callback = function callback() {
          var $el = _this2.$el;

          $el.isotope(_this2.options);
        };
        if (this !== document) {
          callback();
        } else {
          (0, _jquery2.default)(window).on('load', function () {
            callback();
          });
        }
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return Isotope;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Isotope);

  exports.default = Isotope;
});
