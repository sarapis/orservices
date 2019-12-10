(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/bootstrap-select', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginBootstrapSelect = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'selectpicker'; // import $ from 'jquery';

  var Selectpicker = function (_Plugin) {
    babelHelpers.inherits(Selectpicker, _Plugin);

    function Selectpicker() {
      babelHelpers.classCallCheck(this, Selectpicker);
      return babelHelpers.possibleConstructorReturn(this, (Selectpicker.__proto__ || Object.getPrototypeOf(Selectpicker)).apply(this, arguments));
    }

    babelHelpers.createClass(Selectpicker, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          style: 'btn-select',
          iconBase: 'icon',
          tickIcon: 'wb-check'
        };
      }
    }]);
    return Selectpicker;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Selectpicker);

  exports.default = Selectpicker;
});
