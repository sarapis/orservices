(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/asspinner', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginAsspinner = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'asSpinner'; // import $ from 'jquery';

  var AsSpinner = function (_Plugin) {
    babelHelpers.inherits(AsSpinner, _Plugin);

    function AsSpinner() {
      babelHelpers.classCallCheck(this, AsSpinner);
      return babelHelpers.possibleConstructorReturn(this, (AsSpinner.__proto__ || Object.getPrototypeOf(AsSpinner)).apply(this, arguments));
    }

    babelHelpers.createClass(AsSpinner, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          namespace: 'spinnerUi',
          skin: null,
          min: '-10',
          max: 100,
          mousewheel: true
        };
      }
    }]);
    return AsSpinner;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, AsSpinner);

  exports.default = AsSpinner;
});
