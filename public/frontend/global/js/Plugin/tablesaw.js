(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/tablesaw', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginTablesaw = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'tablesaw';

  var Tablesaw = function (_Plugin) {
    babelHelpers.inherits(Tablesaw, _Plugin);

    function Tablesaw() {
      babelHelpers.classCallCheck(this, Tablesaw);
      return babelHelpers.possibleConstructorReturn(this, (Tablesaw.__proto__ || Object.getPrototypeOf(Tablesaw)).apply(this, arguments));
    }

    babelHelpers.createClass(Tablesaw, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }, {
      key: 'api',
      value: function api() {
        return function () {
          if (typeof _jquery2.default.fn.tablesaw === 'undefined') {
            return;
          }

          (0, _jquery2.default)(document).trigger("enhance.tablesaw");
        };
      }
    }]);
    return Tablesaw;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Tablesaw);

  exports.default = Tablesaw;
});
