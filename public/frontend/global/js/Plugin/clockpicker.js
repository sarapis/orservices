(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/clockpicker', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginClockpicker = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'clockpicker'; // import $ from 'jquery';

  var Clockpicker = function (_Plugin) {
    babelHelpers.inherits(Clockpicker, _Plugin);

    function Clockpicker() {
      babelHelpers.classCallCheck(this, Clockpicker);
      return babelHelpers.possibleConstructorReturn(this, (Clockpicker.__proto__ || Object.getPrototypeOf(Clockpicker)).apply(this, arguments));
    }

    babelHelpers.createClass(Clockpicker, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          donetext: 'Done'
        };
      }
    }]);
    return Clockpicker;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Clockpicker);

  exports.default = Clockpicker;
});
