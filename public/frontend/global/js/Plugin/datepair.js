(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/datepair', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginDatepair = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'datepair'; // import $ from 'jquery';

  var Datepair = function (_Plugin) {
    babelHelpers.inherits(Datepair, _Plugin);

    function Datepair() {
      babelHelpers.classCallCheck(this, Datepair);
      return babelHelpers.possibleConstructorReturn(this, (Datepair.__proto__ || Object.getPrototypeOf(Datepair)).apply(this, arguments));
    }

    babelHelpers.createClass(Datepair, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          startClass: 'datepair-start',
          endClass: 'datepair-end',
          timeClass: 'datepair-time',
          dateClass: 'datepair-date'
        };
      }
    }]);
    return Datepair;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Datepair);

  exports.default = Datepair;
});
