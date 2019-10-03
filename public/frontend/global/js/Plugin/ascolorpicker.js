(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/ascolorpicker', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginAscolorpicker = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'asColorPicker'; // import $ from 'jquery';

  var AsColorPicker = function (_Plugin) {
    babelHelpers.inherits(AsColorPicker, _Plugin);

    function AsColorPicker() {
      babelHelpers.classCallCheck(this, AsColorPicker);
      return babelHelpers.possibleConstructorReturn(this, (AsColorPicker.__proto__ || Object.getPrototypeOf(AsColorPicker)).apply(this, arguments));
    }

    babelHelpers.createClass(AsColorPicker, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          namespace: 'colorInputUi'
        };
      }
    }]);
    return AsColorPicker;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, AsColorPicker);

  exports.default = AsColorPicker;
});
