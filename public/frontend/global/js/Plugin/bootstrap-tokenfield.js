(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/bootstrap-tokenfield', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginBootstrapTokenfield = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'tokenfield'; // import $ from 'jquery';

  var Tokenfield = function (_Plugin) {
    babelHelpers.inherits(Tokenfield, _Plugin);

    function Tokenfield() {
      babelHelpers.classCallCheck(this, Tokenfield);
      return babelHelpers.possibleConstructorReturn(this, (Tokenfield.__proto__ || Object.getPrototypeOf(Tokenfield)).apply(this, arguments));
    }

    babelHelpers.createClass(Tokenfield, [{
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
    return Tokenfield;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Tokenfield);

  exports.default = Tokenfield;
});
