(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/bootstrap-touchspin', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginBootstrapTouchspin = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'TouchSpin'; // import $ from 'jquery';

  var TouchSpin = function (_Plugin) {
    babelHelpers.inherits(TouchSpin, _Plugin);

    function TouchSpin() {
      babelHelpers.classCallCheck(this, TouchSpin);
      return babelHelpers.possibleConstructorReturn(this, (TouchSpin.__proto__ || Object.getPrototypeOf(TouchSpin)).apply(this, arguments));
    }

    babelHelpers.createClass(TouchSpin, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          verticalupclass: 'md-plus',
          verticaldownclass: 'md-minus',
          buttondown_class: 'btn btn-default',
          buttonup_class: 'btn btn-default'
        };
      }
    }]);
    return TouchSpin;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, TouchSpin);

  exports.default = TouchSpin;
});
