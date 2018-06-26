(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/jt-timepicker', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginJtTimepicker = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'timepicker'; // import $ from 'jquery';

  var Timepicker = function (_Plugin) {
    babelHelpers.inherits(Timepicker, _Plugin);

    function Timepicker() {
      babelHelpers.classCallCheck(this, Timepicker);
      return babelHelpers.possibleConstructorReturn(this, (Timepicker.__proto__ || Object.getPrototypeOf(Timepicker)).apply(this, arguments));
    }

    babelHelpers.createClass(Timepicker, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return Timepicker;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Timepicker);

  exports.default = Timepicker;
});
