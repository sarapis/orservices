(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/bootstrap-datepicker', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginBootstrapDatepicker = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'datepicker'; // import $ from 'jquery';

  var Datepicker = function (_Plugin) {
    babelHelpers.inherits(Datepicker, _Plugin);

    function Datepicker() {
      babelHelpers.classCallCheck(this, Datepicker);
      return babelHelpers.possibleConstructorReturn(this, (Datepicker.__proto__ || Object.getPrototypeOf(Datepicker)).apply(this, arguments));
    }

    babelHelpers.createClass(Datepicker, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          autoclose: true
        };
      }
    }]);
    return Datepicker;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Datepicker);

  exports.default = Datepicker;
});
