(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/ionrangeslider', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginIonrangeslider = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'ionRangeSlider'; // import $ from 'jquery';

  var IonRangeSlider = function (_Plugin) {
    babelHelpers.inherits(IonRangeSlider, _Plugin);

    function IonRangeSlider() {
      babelHelpers.classCallCheck(this, IonRangeSlider);
      return babelHelpers.possibleConstructorReturn(this, (IonRangeSlider.__proto__ || Object.getPrototypeOf(IonRangeSlider)).apply(this, arguments));
    }

    babelHelpers.createClass(IonRangeSlider, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }]);
    return IonRangeSlider;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, IonRangeSlider);

  exports.default = IonRangeSlider;
});
