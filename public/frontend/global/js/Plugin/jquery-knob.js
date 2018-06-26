(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/jquery-knob', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginJqueryKnob = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'knob'; // import $ from 'jquery';

  var Knob = function (_Plugin) {
    babelHelpers.inherits(Knob, _Plugin);

    function Knob() {
      babelHelpers.classCallCheck(this, Knob);
      return babelHelpers.possibleConstructorReturn(this, (Knob.__proto__ || Object.getPrototypeOf(Knob)).apply(this, arguments));
    }

    babelHelpers.createClass(Knob, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          min: -50,
          max: 50,
          width: 120,
          height: 120,
          thickness: '.1'
        };
      }
    }]);
    return Knob;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Knob);

  exports.default = Knob;
});
